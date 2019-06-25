<?php

namespace App\Repositories\Backend\Access\User;

use App\Events\Backend\Access\User\UserCreated;
use App\Events\Backend\Access\User\UserDeactivated;
use App\Events\Backend\Access\User\UserDeleted;
use App\Events\Backend\Access\User\UserPasswordChanged;
use App\Events\Backend\Access\User\UserPermanentlyDeleted;
use App\Events\Backend\Access\User\UserReactivated;
use App\Events\Backend\Access\User\UserRestored;
use App\Events\Backend\Access\User\UserUpdated;
use App\Exceptions\GeneralException;
use App\Models\Access\User\User;
use App\Models\Collaborateur\Collaborateur;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Repositories\Backend\Access\User\CollaborateurRepository;
use App\Repositories\Backend\Spline\Client\ClientRepository;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository extends Repository
{
	/**
	 * Associated Repository Model
	 */
	const MODEL = User::class;

    /**
     * @var RoleRepository
     */
    protected $role;

    protected $collaborateurs;
    protected $clients;

    /**
     * @param RoleRepository $role
     */
    public function __construct(
        RoleRepository $role,
        CollaborateurRepository $collaborateurs,
        ClientRepository $clients
    )
    {
        $this->role = $role;
        $this->collaborateurs = $collaborateurs;
        $this->clients = $clients;
    }

    /**
     * @param int $status
     * @param bool $trashed
     * @return mixed
     */
    public function getForDataTable($status = 1, $trashed = false)
    {
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        $dataTableQuery = $this->query()
            ->with('roles')
            ->whereDoesntHave('roles', function ($query)
            {
                $query->where('roles.id', '=', 4);
            })
            ->select([
                config('access.users_table') . '.id',
                config('access.users_table') . '.lastname',
                config('access.users_table') . '.name',
                config('access.users_table') . '.email',
                config('access.users_table') . '.status',
                config('access.users_table') . '.confirmed',
                config('access.users_table') . '.created_at',
                config('access.users_table') . '.updated_at',
                config('access.users_table') . '.deleted_at',
            ]);

        if ($trashed == "true") {
            return $dataTableQuery->onlyTrashed();
        }

        // active() is a scope on the UserScope trait
        return $dataTableQuery->active($status);
    }

        /**
     * @param int $status
     * @param bool $trashed
     * @return mixed
     */
    public function getForClientsDataTable($status = 1, $trashed = false)
    {
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        $dataTableQuery = $this->query()
            ->with('roles')
            ->whereHas('roles', function ($query)
            {
                $query->where('roles.id', '=', 4);
            })
            ->with('collaborateur')
            ->select([
                config('access.users_table') . '.id',
                config('access.users_table') . '.lastname',
                config('access.users_table') . '.name',
                config('access.users_table') . '.email',
                config('access.users_table') . '.status',
                config('access.users_table') . '.confirmed',
                config('access.users_table') . '.created_at',
                config('access.users_table') . '.updated_at',
                config('access.users_table') . '.deleted_at',
            ]);

        if ($trashed == "true") {
            return $dataTableQuery->onlyTrashed();
        }

        // active() is a scope on the UserScope trait
        return $dataTableQuery->withTrashed();
    }

	/**
	 * @param Model $input
	 */
	public function create($input)
    {
		$data = $input['data'];
        if(isset($input['data']['color'])){
            $data['color'] = $input['data']['color'];
        }else{
            $data['color'] = '';
        }
		$roles = $input['roles'];
        $collaborateur = isset($input['collaborateur']) ? $input['collaborateur'] : '';
        
        $user = $this->createUserStub($data);
        $colRepo = $this->collaborateurs;
        $clientsRepo = $this->clients;

		DB::transaction(function() use ($user, $data, $roles, $collaborateur, $colRepo, $clientsRepo) {
			if (parent::save($user)) {
                
                if(isset($collaborateur['client'])){
                    $client = $clientsRepo->create($collaborateur['client']);
                    $colRepo->create([
                        'client_id' => $client->id, 
                        'user_id' => $user->id,
                        'is_manager' => true
                    ]);
                }elseif (isset($collaborateur['client_id'])) {
                    $colRepo->create([
                        'client_id' => $collaborateur['client_id'], 
                        'user_id' => $user->id,
                        'is_manager' => false
                    ]);
                }

				//User Created, Validate Roles
				if (! count($roles['assignees_roles'])) {
					throw new GeneralException(trans('exceptions.backend.access.users.role_needed_create'));
				}

				//Attach new roles
				$user->attachRoles($roles['assignees_roles']);

				//Send confirmation email if requested
				if (isset($data['confirmation_email']) && $user->confirmed == 0) {
					$user->notify(new UserNeedsConfirmation($user->confirmation_code));
				}

				event(new UserCreated($user));
				return true;
			}

        	throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
		});
    }

	/**
	 * @param Model $user
	 * @param array $input
	 */
	public function update(Model $user, array $input)
    {
    	$data = $input['data'];
        if(isset($input['data']['color'])){
            $data['color'] = $input['data']['color'];
        }else{
            $data['color'] = '';
        }
		$roles = $input['roles'];
        $this->checkUserByEmail($data, $user);
        $clientsRepo = $this->clients;

		DB::transaction(function() use ($user, $data, $roles, $clientsRepo) {
            $extras = json_decode($user->extras, true);
            $extras['color'] = $data['color'];

            $user->extras = json_encode($extras);
			if (parent::update($user, $data)) {
				//For whatever reason this just wont work in the above call, so a second is needed for now
				$user->status = isset($data['status']) ? 1 : 0;
				$user->confirmed = isset($data['confirmed']) ? 1 : 0;
				parent::save($user);

				$this->checkUserRolesCount($roles);
				$this->flushRoles($roles, $user);
                if($user->collaborateur){
                    $clientsRepo->update1($data['client'], $user->collaborateur->client_id);
                }
				event(new UserUpdated($user));
				return true;
			}

        	throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
		});
    }

	/**
	 * @param Model $user
	 * @param $input
	 * @return bool
	 * @throws GeneralException
	 */
	public function updatePassword(Model $user, $input)
    {
        $user->password = bcrypt($input['password']);

        if (parent::save($user)) {
            event(new UserPasswordChanged($user));
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.update_password_error'));
    }

	/**
	 * @param Model $user
	 * @return bool
	 * @throws GeneralException
	 */
	public function delete(Model $user)
    {
        if (access()->id() == $user->id) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_self'));
        }

        if (parent::delete($user)) {
            event(new UserDeleted($user));
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
    }

	/**
	 * @param Model $user
	 * @throws GeneralException
	 */
	public function forceDelete(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.delete_first'));
        }

		DB::transaction(function() use ($user) {
			if (parent::forceDelete($user)) {
				event(new UserPermanentlyDeleted($user));
				return true;
			}

			throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
		});
    }

	/**
	 * @param Model $user
	 * @return bool
	 * @throws GeneralException
	 */
	public function restore(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_restore'));
        }

        if (parent::restore(($user))) {
            event(new UserRestored($user));
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.restore_error'));
    }

	/**
	 * @param Model $user
	 * @param $status
	 * @return bool
	 * @throws GeneralException
	 */
	public function mark(Model $user, $status)
    {
        if (access()->id() == $user->id && $status == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_self'));
        }

        $user->status = $status;

        switch ($status) {
            case 0:
                event(new UserDeactivated($user));
            break;

            case 1:
                event(new UserReactivated($user));
            break;
        }

        if (parent::save($user)) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.mark_error'));
    }

    /**
     * @param  $input
     * @param  $user
     * @throws GeneralException
     */
    protected function checkUserByEmail($input, $user)
    {
        //Figure out if email is not the same
        if ($user->email != $input['email']) {
            //Check to see if email exists
            if ($this->query()->where('email', '=', $input['email'])->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
            }
        }
    }

    /**
     * @param $roles
     * @param $user
     */
    protected function flushRoles($roles, $user)
    {
        //Flush roles out, then add array of new ones
        $user->detachRoles($user->roles);
        $user->attachRoles($roles['assignees_roles']);
    }

    /**
     * @param  $roles
     * @throws GeneralException
     */
    protected function checkUserRolesCount($roles)
    {
        //User Updated, Update Roles
        //Validate that there's at least one role chosen
        if (count($roles['assignees_roles']) == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.role_needed'));
        }
    }

    /**
     * @param  $input
     * @return mixed
     */
    protected function createUserStub($input)
    {
    	$user					 = self::MODEL;
        $user                    = new $user;
        $user->name              = $input['name'];
        $user->lastname          = $input['lastname'];
        $user->email             = $input['email'];
        $user->password          = bcrypt($input['password']);
        $user->status            = isset($input['status']) ? 1 : 0;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed         = isset($input['confirmed']) ? 1 : 0;
        $user->api_token         = str_random(60);
        $user->extras            = json_encode(['color' => $input['color']]);

        return $user;
    }
}
