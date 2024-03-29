<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;

/**
 * Class UserStatusController
 */
class UserStatusController extends Controller
{
	/**
	 * @var UserRepository
	 */
	protected $users;

	/**
	 * @param UserRepository $users
	 */
	public function __construct(UserRepository $users)
	{
		$this->users = $users;
	}

	/**
	 * @param ManageUserRequest $request
	 * @return mixed
	 */
	public function getDeactivated(ManageUserRequest $request)
	{
		return view('backend.access.deactivated');
	}

	/**
	 * @param ManageUserRequest $request
	 * @return mixed
	 */
	public function getDeleted(ManageUserRequest $request)
	{
		return view('backend.access.deleted');
	}

	/**
	 * @param User $user
	 * @param $status
	 * @param ManageUserRequest $request
	 * @return mixed
	 */
	public function mark(User $user, $status, ManageUserRequest $request)
	{
		$this->users->mark($user, $status);
		return back();
	}

	/**
	 * @param User $deletedUser
	 * @param ManageUserRequest $request
	 * @return mixed
	 */
	public function delete(User $deletedUser, ManageUserRequest $request)
	{
		$this->users->forceDelete($deletedUser);
		return back();
	}

	/**
	 * @param User $deletedUser
	 * @param ManageUserRequest $request
	 * @return mixed
	 */
	public function restore(User $deletedUser, ManageUserRequest $request)
	{
		$this->users->restore($deletedUser);
		return back();
	}
}