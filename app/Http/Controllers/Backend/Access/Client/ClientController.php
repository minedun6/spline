<?php

namespace App\Http\Controllers\Backend\Access\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Repositories\Backend\Access\User\UserRepository;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

class ClientController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @param UserRepository $users
     * @param RoleRepository $roles
     */
    public function __construct(UserRepository $users, RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.access.clients.index');
    }

    public function getClients(Request $request)
    {
        return Datatables::of($this->users->getForClientsDataTable($request->get('status'), $request->get('trashed')))
            ->addColumn('nom_client', function($user) {
                return $user->collaborateur->client->name;
            })
            ->addColumn('actions', function($user) {
                return $user->action_buttons;
            })
            ->withTrashed()
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.access.clients.create')
            ->withRoles($this->roles->getAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client_id = 0;
        $this->users->create([
            'data' => $request->except('assignees_roles'), 
            'roles' => [ 
                'assignees_roles' => [ 4 => "4"]
            ],
            'collaborateur' => [
                'client' => $request->only('client')
            ]
        ]);
        // TODO: Add Client specific collaborators list
        return redirect()->route('admin.access.client.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
