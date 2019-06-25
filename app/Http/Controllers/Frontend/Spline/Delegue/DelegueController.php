<?php

namespace App\Http\Controllers\Frontend\Spline\Delegue;

use App\Http\Controllers\Controller;
use App\Models\Delegue\DgPhaCl;
use App\Repositories\Backend\Spline\Client\ClientRepository;
use App\Repositories\Frontend\Spline\Delegue\DelegueRepository;
use App\Repositories\Frontend\Spline\Pharmacie\PharmacieRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class DelegueController extends Controller
{
    public function __construct(
        PharmacieRepository $pharmacies,
        DelegueRepository $delegues,
        ClientRepository $clients
    )
    {
        $this->pharmacies = $pharmacies;
        $this->delegues = $delegues;
        $this->clients = $clients;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.spline.delegues.index', [
            'clients' => $this->clients->getAll()
        ]);
    }

    public function getAllDelegues()
    {   
        if (Auth::user()->hasRole('Administrator')) {
            $delegues = $this->delegues->getAll();
        }else{
            $delegues = $this->delegues->getByClient(Auth::user()->collaborateur->client_id);
        }

        return Datatables::of($delegues)
            ->editColumn('created_at', function($delegue){
                return $delegue->created_at->format('d-m-Y');
            })
            ->addColumn('actions', function($delegue) {
                $edit_btn = '<a class="btn btn-xs btn-success" data-toggle="modal" href="'. route('frontend.delegues.edit', $delegue->id).'" data-target="#edit-delegue-modal"><i class="fa fa-edit"></i> </a>';
                return $edit_btn.'<a class="btn btn-xs btn-danger delete-delegue"  rel="tooltip"  title="Supprimer produit" data-toggle="modal" data-id="'.$delegue->id.'" data-produit=\''.json_encode($delegue).'\' href="#delete-modal"><i class="glyphicon glyphicon-trash"></i></a>';
        })
            ->make(true);;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->delegues->store($data);

        return back();
    }

    public function assign()
    {
        $clients = $this->clients->getAll();
        $pharmacies = $this->pharmacies->getActives();
        $delegues = $this->delegues->getAll();

        return view('frontend.spline.delegues.partials.assign', [
            'clients'    => $clients,
            'pharmacies' => $pharmacies,
            'delegues'   => $delegues
        ]);
    }

    public function storeAssign(Request $request)
    {
        $data = $request->all();
        $delegue = $this->delegues->assign($data);

        if(!$delegue->wasRecentlyCreated){
            $errors = '<div class="alert alert-warning">';
            $errors .= '<i class="fa fa-alert"></i> Le délégué <b>'.$delegue->delegue->firstname.' - '.$delegue->delegue->phone.'</b> est déjà affecté au client <b>'.$delegue->client->name.'</b> et à la pharmacie <b>'.$delegue->pharmacie->nom.'</b>.';
            $errors .= '</div>';
        }else{
            $errors = '<div class="alert alert-success">';
            $errors .= '<i class="fa fa-check"></i> Le délégué <b>'.$delegue->delegue->firstname.' - '.$delegue->delegue->phone.'</b> a été affecté au client <b>'.$delegue->client->name.'</b> et à la pharmacie <b>'.$delegue->pharmacie->nom.'</b>.';
            $errors .= '</div>';
        }

        return redirect()->back()->with('err', $errors);
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
        $delegue = $this->delegues->find($id);

         return view('frontend.spline.delegues.partials.edit', [
            'delegue'   => $delegue
        ]);
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
        $delegue = $this->delegues->find($id);
        $req = $request->all();
        $data = ['firstname' => $req['firstname'], 'phone' => $req['phone']]; 
        $this->delegues->update($delegue, $data);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!DgPhaCl::where('delegue_id', $id)->first()){
                    $this->delegues->delete($this->delegues->find($id)); 
            return "success";
        }{
            return "fail";
        }
    }

    public function remove($id)
    {
        DgPhaCl::find($id)->delete();

        return back();
    }
}
