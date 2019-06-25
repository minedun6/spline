<?php

namespace App\Repositories\Backend\Access\User;

use App\Models\Collaborateur\Collaborateur;

use App\Repositories\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class CollaborateurRepository
 * @package App\Repositories\Backend\Access\User
 */
class CollaborateurRepository extends Repository
{
	/**
	 * Associated Repository Model
	 */
	const MODEL = Collaborateur::class;

    public function getCollaborateursByClient($client_id)
    {
        return $this->query()
                ->where('client_id', $client_id)
                ->join('users as u', 'u.id', '=', 'user_id')->get();
    }
	/**
	 * @param Model $input
	 */
	public function create($input)
    {

        $collaborateur = $this->createCollaborateurStub($input);

		return $collaborateur->save();
    }

    /**
     * @param  $input
     * @return mixed
     */
    protected function createCollaborateurStub($input)
    {
    	$collaborateur					  = self::MODEL;
        $collaborateur                    = new $collaborateur;
        $collaborateur->user_id           = $input['user_id'];
        $collaborateur->client_id         = $input['client_id'];
        $collaborateur->is_manager         = $input['is_manager'];
        
        return $collaborateur;
    }
}
