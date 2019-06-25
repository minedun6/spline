<?php

namespace App\Http\Controllers\Frontend\Spline\Commande;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Repositories\Frontend\Spline\Commande\CommandeRepository;
use App\Repositories\Frontend\Spline\File\FileRepository;
use App\Repositories\Frontend\Spline\Pharmacie\PharmacieRepository;
use App\Repositories\Frontend\Spline\Planification\PlanificationRepository;
use App\Repositories\Frontend\Spline\Product\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class PlanificationController extends Controller
{
    protected $pharmacies;
    protected $products;
    protected $commandes;

    public function __construct(
        PharmacieRepository $pharmacies,
        ProductRepository $products,
        FileRepository $files,
        CommandeRepository $commandes,
        PlanificationRepository $planifications
    )
    {
        $this->files = $files;
        $this->planifications = $planifications;
        $this->commandes = $commandes;
        $this->products = $products;
        $this->pharmacies = $pharmacies;
    }
    public function getAllCommandesForPose()
    {   
        $commandes = $this->commandes->getAllCommandesForPose();

        return Datatables::of($commandes)
            ->addColumn('client_name', function($commande){
                return $commande->client->name;
            })
            ->addColumn('checkbox', function ($commande)
            {   $input = '<label>
                <div class="checker">
                <span class="">
                <input type="checkbox" name="commandes_chk[]" value="'.$commande->id.'">
                </span>
                </div> 
                </label>';
                return $input;
            })
            ->addColumn('commande', function($commande){
                $c = '<strong>['.$commande->client->name.']</strong> '.
                    $commande->pharmacie->nom.'/ '.
                    $commande->pharmacie->secteur.(($commande->product) ? '/ '.
                    $commande->product->name : '').'/ ';
                if($commande->is_vitrine)
                    $c .= 'Vitrine';
                elseif($commande->is_merchandising)
                    $c .= 'Linéaire/ Merchandising'; 
                elseif($commande->is_presentoire)
                    $c .= 'Présentoire'; 
                else $c .= 'Autre';

                return $c;
            })
            ->addColumn('date_souhaite', function ($commande)
            {
                return \Carbon\Carbon::parse($commande->date_pose)->format('Y-m-d') ;
            })
            ->addColumn('date_ajout', function ($commande)
            {
                return json_decode($commande->extras)->{'history'}->{'created_at'};
            })
            ->addColumn('date_pose_finale', function ($commande)
            {
                return ($commande->planification)?\Carbon\Carbon::parse($commande->planification->date_pose_finale)->format('Y-m-d') : "Pas encore planifiée" ;
            })
            ->addColumn('dates', function ($commande)
            {
                if($commande->planification and isset(json_decode($commande->planification->extras, true)['report'])){
                    $date_report = (isset(json_decode($commande->planification->extras, true)['report']['date_report']))? json_decode($commande->planification->extras, true)['report']['date_report'] : null;
                    $date_refus = (isset(json_decode($commande->planification->extras, true)['refus']) and isset(json_decode($commande->planification->extras, true)['refus']))? json_decode($commande->planification->extras, true)['refus']['date_refus'] : null;
                    $date_planifie = (($date_report)?'<span class="font-yellow"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Cette commande a été reportée pour le <strong>'.$date_report.'</strong></span>':'').(($date_refus)?'<br><span class="font-red"><i class="fa fa-times-circle" aria-hidden="true"></i>  La commande a été annulée le <strong>'.$date_refus.'</strong></span>':'');
                }else{
                    $date_planifie = ($commande->planification)?\Carbon\Carbon::parse($commande->planification->date_pose_finale)->format('Y-m-d') : "Pas encore planifiée";
                }

                $date_souhaite = ($commande->date_pose)?\Carbon\Carbon::parse($commande->date_pose)->format('Y-m-d'): "Aucune";
                $date_ajout = \Carbon\Carbon::parse(json_decode($commande->extras)->{'history'}->{'created_at'})->format('Y-m-d');

                return '<strong>Date de la commande: </strong><br>'.$date_ajout.'<br>'.
                        '<strong>Date de pose souhaitée: </strong><br>'.$date_souhaite.'<br>'.
                        '<strong>Date de pose planifiée: </strong><br>'.$date_planifie.'<br>';
            })
            ->addColumn('actions', function($commande) {
                $btn = '<a href="'.route('frontend.commandes.show', ['id'=>$commande->id]).'" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> Voir</a>';
            return $btn;
        })
        ->make(true);
    }

    public function planificationsIndex()
    {   
        $poseurs = User::whereHas('roles', function ($query)
        {
            $query->where('roles.name', '=', 'Poseur');
        })->get();

        return view('frontend.spline.commandes.planifications.index', [
            'poseurs' => $poseurs,
        ]);
    }

    public function getAllPlanifications()
    {
        $planifications = $this->planifications->getAll();

        return Datatables::of($planifications)
            ->addColumn('poseur', function($planification){
                return $planification->owner->name.' '.$planification->owner->lastname;
            })
            ->addColumn('commande', function($p){
                $commande = '<strong>['.$p->commande->client->name.']</strong> '.$p->commande->pharmacie->nom.'/ '.$p->commande->pharmacie->secteur.(($p->commande->product) ? '/ '.$p->commande->product->name : '').'/ ';
                if($p->commande->is_vitrine)
                    $commande .= 'Vitrine';
                elseif($p->commande->is_merchandising)
                    $commande .= 'Linéaire/ Merchandising'; 
                elseif($p->commande->is_presentoire)
                    $commande .= 'Présentoire'; 
                else $commande .= 'Autre';

                return $commande;
            })
            ->addColumn('date_souhaite', function ($p)
            {
                return \Carbon\Carbon::parse($p->commande->date_pose)->format('Y-m-d') ;
            })
            ->addColumn('date_ajout', function ($p)
            {
                return json_decode($p->commande->extras)->{'history'}->{'created_at'};
            })
            ->addColumn('date_pose_finale', function ($p)
            {
                return \Carbon\Carbon::parse($p->date_pose_finale)->format('Y-m-d') ;
            })
            ->addColumn('dates', function ($p)
            {
                $date_souhaite = \Carbon\Carbon::parse($p->commande->date_pose)->format('Y-m-d');
                $date_ajout = \Carbon\Carbon::parse(json_decode($p->commande->extras)->{'history'}->{'created_at'})->format('Y-m-d');
                $date_planifie = \Carbon\Carbon::parse($p->date_pose_finale)->format('Y-m-d');
                if(isset(json_decode($p->extras, true)['report'])){
                    $date_report = (isset(json_decode($p->extras, true)['report']['date_report']))? json_decode($p->extras, true)['report']['date_report'] : null;
                    $date_refus = (isset(json_decode($p->extras, true)['refus']) and isset(json_decode($p->extras, true)['refus']))? json_decode($p->extras, true)['refus']['date_refus'] : null;
                    $date_planifie = (($date_report)?'<span class="font-yellow"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Cette commande a été reportée pour le <strong>'.$date_report.'</strong></span>':'').(($date_refus)?'<br><span class="font-red"><i class="fa fa-times-circle" aria-hidden="true"></i>  La commande a été annulée le <strong>'.$date_refus.'</strong></span>':'');
                }
                
                return '<strong>Date de la commande: </strong><br>'.$date_ajout.'<br>'.
                        '<strong>Date de pose souhaitée: </strong><br>'.$date_souhaite.'<br>'.
                        '<strong>Date de pose planifiée: </strong><br>'.$date_planifie.'<br>';
            })
            ->addColumn('actions', function($p) {
                $btn = '<a href="'.route('frontend.commandes.show', ['id'=>$p->commande_id]).'" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> Voir</a>';
                $btn .= '<a href="/planifications/annuler/'.$p->id.'" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Annuler</a>';
            return $btn;
        })
        ->make(true);
    }

    public function planifierCreate()
    {
        $commandes = $this->commandes->getByStatus('en pose');
        
        $poseurs = User::whereHas('roles', function ($query)
        {
            $query->where('roles.name', '=', 'Poseur');
        })->get();

        return view('frontend.spline.commandes.planifications.create', [
            'poseurs' => $poseurs,
            'commandes' => $commandes
        ]);
    }

    public function getPlanificationForFullCallendar()
    {
        return $this->planifications->getPlanificationForFullCallendar();
    }

    public function planifierStore(Request $request)
    {
        $req = $request->all();

        $planifications = $this->planifications->storeMultiple($req);

        return redirect()->route('frontend.commandes.planifications');
    }

    public function posesIndex()
    {
        $poses = $this->planifications->getByEquipe(Auth::user()->id);

        $mes_poses['completed'] = [];
        $mes_poses['pending'] = [];
        foreach ($poses as $key => $p) {
            if (json_decode($p->extras) && json_decode($p->extras)->{'done'}) {
                $mes_poses['completed'][] = $p;
            }else{
                $mes_poses['pending'][] = $p;
            }
        }

        return view('frontend.spline.commandes.poses.index', [
            'mes_poses' => $mes_poses
        ]);
    }

    public function posesValidate($id, Request $request)
    {   
        $this->planifications->pose([
            'poseur_id' => Auth::user()->id, 
            'commande_id' => intval($id),
            'data' => $request->all()]);
        
        $commande = $this->commandes->keepTrackOf($id, 'done');

        return redirect()->route('frontend.commandes.show', intval($id));
    }

    public function posesReporter($id, Request $request)
    {   
        $this->planifications->poseReport([
            'poseur_id' => Auth::user()->id, 
            'commande_id' => intval($id),
            'data' => $request->all()]);
        

        return redirect()->route('frontend.commandes.show', intval($id));
    }

    public function posesRefuser($id, Request $request)
    {   
        $this->planifications->poseRefuser([
            'poseur_id' => Auth::user()->id, 
            'commande_id' => intval($id),
            'data' => $request->all()]);
        

        return redirect()->route('frontend.commandes.show', intval($id));
    }

    public function annulerPlanification($id)
    {
        $this->planifications->annuler($id);
        return back();
    }
}
