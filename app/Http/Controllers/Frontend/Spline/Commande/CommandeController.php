<?php

namespace App\Http\Controllers\Frontend\Spline\Commande;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Client\Client;
use App\Models\Commande\Commande;
use App\Models\Commande\CommandeEdit;
use App\Models\Log\Log;
use App\Models\Pharmacie\Pharmacie;
use App\Models\Planification\Planification;
use App\Repositories\Frontend\Spline\Commande\CommandeRepository;
use App\Repositories\Frontend\Spline\Delegue\DelegueRepository;
use App\Repositories\Frontend\Spline\File\FileRepository;
use App\Repositories\Frontend\Spline\Pharmacie\PharmacieRepository;
use App\Repositories\Frontend\Spline\Planification\PlanificationRepository;
use App\Repositories\Frontend\Spline\Product\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class CommandeController extends Controller
{
    protected $pharmacies;
    protected $products;
    protected $commandes;

    public function __construct(
        PharmacieRepository $pharmacies,
        ProductRepository $products,
        FileRepository $files,
        CommandeRepository $commandes,
        PlanificationRepository $planifications,
        DelegueRepository $delegues,
        CommandeEdit $commandeEditModel
    )
    {
        $this->files = $files;
        $this->planifications = $planifications;
        $this->commandes = $commandes;
        $this->products = $products;
        $this->pharmacies = $pharmacies;
        $this->delegues = $delegues;
        $this->commandeEditModel = $commandeEditModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.spline.commandes.index');
    }

    public function getAllCommandes()
    {   
        if(!($collaborateur = Auth::user()->collaborateur))
            $commandes = $this->commandes->getAllCommandes();
        else{
            $commandes = $this->commandes->getByClient($collaborateur->client_id);
        }   

        return Datatables::of($commandes)
            ->addColumn('client_name', function($commande){
                return $commande->client->name;
            })
            ->addColumn('pharmacie', function($commande){
                if($commande->pharmacie_id == 0 || !Pharmacie::find($commande->pharmacie_id))
                    return null;
                return $commande->pharmacie->nom.'/ '.$commande->pharmacie->secteur;
            })
            ->addColumn('type', function($commande){
                if ($commande->is_vitrine) {
                    return "Vitrine";
                }elseif ($commande->is_presentoir) {
                    return "Présentoir";
                }elseif ($commande->is_merchandising) {
                    return "Linéaire/ Merchandising";
                }else{
                    return "Autre";
                }
            })
            ->editColumn('status', function($commande){
                switch (strtolower($commande->status)) {
                    case 'en traitement':
                        $classname = "warning";
                        $status = "En traitement";
                        break;
                    case 'en validation':
                        $classname = "warning";
                        $status = "Validée";
                        break;
                    case 'en impression':
                        $classname = "primary";
                        $status = "En impression";
                        break;
                    case 'brouillon':
                        $classname = "default";
                        $status = "Brouillon";
                        break;
                    case 'en pose':
                        $classname = "info";
                        $status = "En pose";
                        break;
                    case 'annulee':
                        $classname = "danger";
                        $status = "Annulée";
                        break;
                    case 'done':
                        $classname = "success";
                        $status = "Terminée";
                        break;

                    default:
                        $classname = "info";
                        $status = "Unknown";
                        break;
                }
                if($commande->deleted_at != null){
                    return '<span class="label label-sm label-danger"  data-toggle="popover" title="Supprimée par:" data-trigger="hover" data-placement="bottom" data-content="'.$commande->_deleted_by->name.' '.$commande->_deleted_by->lastname.'"> Supprimée </span>';
                }

                return '<span class="label label-sm label-'.$classname.'"> '.$status.' </span>';
            })
            ->addColumn('date_ajout', function ($commande)
            {
                return json_decode($commande->extras)->{'history'}->{'created_at'};
            })
            ->editColumn('created_at', function ($commande)
            {
                return \Carbon\Carbon::parse($commande->created_at)->format('Y-m-d');
            })
            ->editColumn('deleted_by', function ($commande)
            {
                return User::find($commande->deleted_by);
            })
            ->addColumn('actions', function($commande) {
                if($commande->deleted_at){
                    $btn_show = '<a href="'.route('frontend.commandes.show', ['id'=>$commande->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i></a>';
                    $btn = $btn_show;

                    $user = Auth::user();
                    if(is_admin() or $commande->deleted_by == $user->id){
                        $btn_restore = '<a href="'.route('frontend.commandes.restore', ['id'=>$commande->id]).'" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-refresh"></i></a>';
                        $btn .= $btn_restore;
                    }
                    return $btn;
                }
                if($commande->status != 'brouillon'){
                    $btn_show = '<a href="'.route('frontend.commandes.show', ['id'=>$commande->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i></a>';
                    $btn = $btn_show;
                }else{
                    $btn = '';
                }
                if(Auth::user()->hasRole('Collaborateur')){
                    $btn_delete = '<a class="btn btn-xs btn-danger delete-commande"  rel="tooltip"  title="Supprimer commande" data-toggle="modal" data-id="'.$commande->id.'" data-commande=\''.json_encode($commande).'\' href="#delete-modal"><i class="glyphicon glyphicon-trash"></i></a>';

                    if(in_array($this->commandes->find($commande->id)->status,['en traitement', 'brouillon'])) {
                        $btn_edit = '<a href="'.route('frontend.commandes.edit', ['id'=>$commande->id]).'" rel="tooltip" title="Editer commande" data-id="'.$commande->id.'" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit" ></i></a>';
                        $btn .= $btn_edit;
                    }
                    $btn .= $btn_delete;
                }
                return $btn;
            })
            ->make(true);
    }

    public function changeStatus($id, $status)
    {
        $commande = $this->commandes->changeStatus('en validation', intval($id));
        return back();
    }
    
    public function validateCommande($id)
    {   
        $commande = $this->commandes->keepTrackOf($id, 'validated');

        $commande = $this->commandes->changeStatus('en impression', intval($id));
        
        return back();
    }

    public function finishCommande($id)
    {   
        $commande = $this->commandes->keepTrackOf($id, 'done');

        $commande = $this->commandes->changeStatus('done', intval($id));

        return back();
    }

    public function printCommande($id)
    {   
        $commande = $this->commandes->keepTrackOf($id, 'printed');

        $commande = $this->commandes->changeStatus('en pose', intval($id));

        return back();
    }

    public function cancelCommande($id)
    {   
        $commande = $this->commandes->keepTrackOf($id, 'canceled');
        
        $commande = $this->commandes->changeStatus('annulee', intval($id));
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = null;
        if (Auth::user()->hasRole('Administrator')) {
            $clients = Client::all();
            $products = $this->products->getAll();
        }else{
            $products = $this->products->getByClient(Auth::user()->collaborateur->client_id);
        }



        return view('frontend.spline.commandes.create',[
            'pharmacies' => $this->pharmacies->getAll(),
            'products'   => $products,
            'clients'    => $clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        
        $commande = $this->commandes->store($data);
        
        return redirect('/commandes');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commande = $this->commandes->findWithTrashed($id);

        $vitrines = [];
        if ($commande->is_vitrine) {
            $vitrines = $this->files->searchByPharmacie($commande->pharmacie_id);
        }

        $files = $this->files->getAttachments($id, 'attachment');
        $crea_files = $this->files->getAttachments($id, 'crea_attachment');

        $planifications = $this->planifications->getPoseImages($id);
        $paths = ($planifications) ? json_decode($planifications->path) : null;
        $delegue = $this->delegues->getByPharmacieAndClient($commande->pharmacie_id, $commande->client_id);
        $report_paths = ($a = $this->planifications->getReportImages($id)) ? json_decode($a->path) : null;
        $refus_paths = ($b = $this->planifications->getRefusImages($id)) ? json_decode($b->path) : null;

        $logs = Log::where('related_to', 'commande')->where('related_to_id', $id)->get();

        return view('frontend.spline.commandes.show',  [
            'commande'        => $commande,
            'vitrines'        => $vitrines,
            'paths'           => $paths,
            'report_paths'    => $report_paths,
            'refus_paths'     => $refus_paths,
            'files'           => $files,
            'crea_files'      => $crea_files,
            'delegue'         => ($delegue)? $delegue->delegue : null,
            'choosen_vitrine' => intval(json_decode($commande->extras, true)['choosen_vitrine']),
            'logs'            => $logs
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
        if(!in_array($this->commandes->find($id)->status,['en traitement', 'brouillon'] ))
            return redirect()->route('frontend.commandes.index');

        if(!(is_admin() or is_client())){
            return redirect()->route('frontend.commandes.index');
        }

        $editModel = $this->commandeEditModel->viewModel($id);

        return view('frontend.spline.commandes.edit', [
            'editModel' => $editModel
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
        $data = $request->except('_token');
        
        $old_commande = $this->commandes->find($id);

        $commande = $this->commandes->update1($old_commande, $data);
        
        return redirect('/commandes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($commande = $this->commandes->find($id)){
            if($commande->planification_id){
                $this->planifications->annuler($commande->planification_id);
            }

            $this->commandes->deleteOrder($id);            
            return 'success';
        }else{
            return 'fail';
        }
    }

    public function restore($id)
    {
        $commande = $this->commandes->findWithTrashed($id);

        if($commande){
            $commande->deleted_by = null;
            $commande->save();
            $commande->restore();
            $this->commandes->log('Restauration de la commande #'.$id, 'restore', $id);
        }

        return back();
    }
    public function upload(Request $request, $id)
    {
        $files = $this->files->storeMultiple($request->only('fichiers')['fichiers'], intval($id), 'images/commandes', 'crea_attachment');

        return back();
    }
}
