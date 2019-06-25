<?php

namespace App\Http\Controllers\Frontend\Spline\Pharmacie;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Commande\Commande;
use App\Models\Pharmacie\Pharmacie;
use App\Repositories\Frontend\Spline\Delegue\DelegueRepository;
use App\Repositories\Frontend\Spline\File\FileRepository;
use App\Repositories\Frontend\Spline\Pharmacie\PharmacieRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;

class PharmacieController extends Controller
{
    protected $pharmacies;

    public function __construct(
        PharmacieRepository $pharmacies,
        FileRepository $files,
        DelegueRepository $delegues
    )
    {
        $this->pharmacies = $pharmacies;
        $this->files = $files;
        $this->delegues = $delegues;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.spline.pharmacies.index');
    }

    public function getAllPharmacies()
    {   
        $pharmacies = $this->pharmacies->getAll();
        return Datatables::of($pharmacies)
            ->addColumn('actions', function($pharmacie) {
            return '<a href="'.route('frontend.pharmacies.show', $pharmacie->id).'" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>'.'<a id="editProduitBtn" rel="tooltip" title="Editer produit" href="'.route('frontend.pharmacies.edit' ,$pharmacie->id).'" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit" ></i></a>'.                   '<a class="btn btn-xs btn-danger delete-pharmacie"  rel="tooltip"  title="Supprimer produit" data-toggle="modal" data-id="'.$pharmacie->id.'" data-produit=\''.json_encode($pharmacie).'\' href="#delete-modal"><i class="glyphicon glyphicon-trash"></i></a>';
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
        return view('frontend.spline.pharmacies.create');
    }
    public function createPartial()
    {
        return view('frontend.spline.pharmacies.partials.create_1');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datas = $request->all();
        // dd($request->only('delegues'));
        if(Auth::user()->hasRole('Administrator') or Auth::user()->hasRole('Crea')){
            $datas['photos'] = $request->file('photos');
            $datas['extras'] = json_encode([]);
            $this->pharmacies->store($datas);
        }else{
            $pharmacie = new Pharmacie;
            if(isset($datas['nom_pharmacie']) and isset($datas['secteur'])){
                $pharmacie->nom = $datas['nom_pharmacie'];
                $pharmacie->secteur = $datas['secteur'];
                $pharmacie->is_active = false;
                $pharmacie->extras = json_encode([]);
                $pharmacie->save();

                return json_encode($pharmacie);
            }else{
                return 'fail';
            }

        }
        
        return view('frontend.spline.pharmacies.index');
    }

    public function getPharmacieVitrines($id)
    {   $pharmacie = $this->pharmacies->find($id);

        return \Response::json([$this->files->searchByPharmacie(intval($id)), $pharmacie]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pharmacie = $this->pharmacies->find($id);
        $vitrines = [];
        $vitrines = $this->files->searchByPharmacie(intval($id));
        $delegues = $this->delegues->getActiveByPharmacie($id);

        return view('frontend.spline.pharmacies.show', [
            'pharmacie' => $pharmacie,
            'vitrines'  => $vitrines,
            'clients'   => Client::all(),
            'delegues'  => $delegues
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pharmacie = $this->pharmacies->find($id);
        $vitrine_mesures = [];

        if(isset(json_decode($pharmacie->extras, true)['mesures'])){
            $vitrine_mesures = json_decode($pharmacie->extras, true)['mesures'];
        }

        if (count($vitrine_mesures) == 0) {
            $vitrine_mesures = json_decode('{"1":{"width": "0", "height": "0"}}');
        }

        $m = '';
        foreach ($vitrine_mesures as $k => $v) {
            $m .= ','.json_encode($v);
        }
        if($m[0] == ','){
            $m[0] = ' ';
        }

        $m = '['.$m.']';

        return view('frontend.spline.pharmacies.edit', [
            'pharmacie' =>$pharmacie,
            'vitrine_mesures' => $m
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
        $datas = $request->all();
        if(Auth::user()->hasRole('Administrator') or Auth::user()->hasRole('Crea')){
            $datas['photos'] = $request->file('photos');
            $datas['extras'] = json_encode([]);
            $this->pharmacies->update1($id, $datas);
        }

        return redirect()->route('frontend.pharmacies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       if(count(Commande::where('pharmacie_id', $id)->get()) == 0 && $pharmacie = $this->pharmacies->find($id)){
            $this->pharmacies->delete($pharmacie);     
            return "success";       
        }else{
            return "fail";
        }
    }

    public function getDelegues(Request $request)
    {
        $req = $request->all();
        $client_id = intval($req['client_id']);
        $delegues = DB::table('delegues')
                        ->select('delegues.id', 'delegues.firstname','delegues.lastname', 'delegues.phone')
                        ->where('client_id', $client_id)
                        ->get();

        return $delegues;
    }

    public function addDelegueToPharmacie(Request $request)
    {
        $data = $request->all();
        $this->delegues->chooseTheOne(intval($data['client_id']), intval($data['pharmacie_id']), intval($data['delegue_id']));

        return back();
    }

    
    public function addDelegue(Request $request)
    {
        $data = $request->all();
        $data['client_id'] = intval($data['client_id']);
        $data['pharmacie_id'] = intval($data['pharmacie_id']);
        $this->delegues->store($data);

        return back();
    }

}
