<?php

namespace App\Http\Controllers\Frontend\Spline\Collaborateur;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\User\CollaborateurRepository;
use App\Repositories\Backend\Access\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class CollaborateurController extends Controller
{   
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param UserRepository $users
     * @param RoleRepository $roles
     */
    public function __construct(
        UserRepository $users,
        CollaborateurRepository $collaborateurs
    )
    {
        $this->users = $users;
        $this->collaborateurs = $collaborateurs;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.spline.collaborateurs.index');
    }

    public function getCollaborateurs(Request $request)
    {
        return Datatables::of($this->collaborateurs->getCollaborateursByClient(Auth::user()->collaborateur->client_id))
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend.spline.collaborateurs.create');
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
                'client_id' => intval(Auth::user()->collaborateur->client_id)
            ]
        ]);
        // TODO: Add Client specific collaborators list
        return view('frontend.spline.collaborateurs.index');
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
