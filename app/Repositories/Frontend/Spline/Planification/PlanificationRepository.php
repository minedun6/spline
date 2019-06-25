<?php 

namespace App\Repositories\Frontend\Spline\Planification;

use App\Models\Planification\Planification;
use App\Repositories\Frontend\Spline\Commande\CommandeRepository;
use App\Repositories\Frontend\Spline\File\FileRepository;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlanificationRepository extends Repository  {
	/**
	 * Associated Repository Model
	 */
	const MODEL = Planification::class;

	protected $commandes;

    public function __construct(
        CommandeRepository $commandes,
        FileRepository $files
    )
    {
        $this->files = $files;
        $this->commandes = $commandes;
    }

    public function storeMultiple($input)
    {   
        $data['poseur_id'] = $input['equipe_pose'];
        $data['date_pose_finale'] = \Carbon\Carbon::parse($input['date_pose_finale']);

        $checked_commandes_list = json_decode($input['checked_commandes_list']);
        if(count($checked_commandes_list) != 0){
            $planifications = [];
            foreach ($checked_commandes_list as $commande_id) {
                $data['commande_id'] = $commande_id;
                $planifications[] = $this->store($data);
            }
            return $planifications;
        }else{
            return [];
        }
    }

    public function store($data)
    {
        $p = $this->query()
            ->where('commande_id', $data['commande_id'])
            ->first();
        $planification = ($p) ? $p : new Planification;
        $re = '';
        if ($planification->date_pose_finale) {
            $re = 're-';
        }
        $planification->poseur_id = $data['poseur_id'];
        $planification->commande_id = $data['commande_id'];
        $planification->date_pose_finale = $data['date_pose_finale'];
        $planification->extras = json_encode(['done'=>false, 'images_poses' => []]);
        $cmds = $this->commandes;
        \DB::transaction(function () use ($planification, $cmds) {
            parent::save($planification);

            $commande = $cmds->find($planification->commande_id);
            $commande->planification_id = $planification->id;
            $commande->save();
        });

        $this->commandes->log('La commande #'.$data['commande_id'].' a été '.$re.'planifiée au '.\Carbon\Carbon::parse($data['date_pose_finale'])->format('Y-m-d').'.', 'planification',intval($data['commande_id']));

        return $planification;
    }


    public function getPlanificationForFullCallendar()
    {
        if(is_admin()){
            $planifications = $this->getAll();
        }else if(Auth::user()->hasRole('Poseur')){
            $planifications = $this->query()->where('poseur_id', Auth::user()->id)->get();
        }
        $events = [];
        foreach ($planifications as $p) {
            $data['title']  = substr($p->commande->client->name, 0, 10).((strlen($p->commande->client->name)>10)?'...':'').'</br>'.substr($p->commande->pharmacie->nom, 0, 10).((strlen($p->commande->pharmacie->nom)>10)?'...':'') ;
            $data['start']  = \Carbon\Carbon::parse($p->date_pose_finale)->format('Y-m-d H:i');
            $data['equipe'] = $p->poseur_id.'';
            $data['url']    = route('frontend.commandes.show', $p->commande_id);
            $data['color']  = '#'.json_decode($p->owner->extras, true)['color'];
            $events[] = $data;
        }

        return $events;

    }

    public function getByEquipe($id)
    {
        return $this->query()->where('poseur_id', $id)->get();
    }

    public function pose($data)
    {
        $planification = $this->query()
                    ->where('commande_id', $data['commande_id'])
                    ->first();

        $cmds = $this->commandes;
        // dd($data);
        \DB::transaction(function () use ($planification, $data, $cmds) {
            $paths['images_poses'] = (json_decode($planification->extras)) ? json_decode($planification->extras)->{'images_poses'} : [];
            if(isset($data['data']['photos'])){
                foreach ($data['data']['photos'] as $k => $v) {
                    $file_name  = $data['commande_id'].'-pose_'.($k + 1);
                    $file_name .= '.'.$v->extension();
                    
                    $file_path = Storage::putFile('images/commandes/poses', $v);

                    $file['path'] = $file_path;
                    $file['type'] = 'image';
                    $file['related_to'] = [
                        'id' => $planification->id, 
                        'type' => 'pose'
                    ];

                    $this->files->store($file);
                    $paths['images_poses'][] = $file_path;
                }
            }
            $paths['done'] = true;

            $paths['reclamation'] = ['content' => $data['data']['reclamation'], 'date_reclamation' => \Carbon\Carbon::now()->format('Y-m-d')];
            $planification->extras = json_encode($paths);

            $planification->save();
            $cmds->changeStatus('done', intval($data['commande_id']));
        });

        return true;
    }

    public function poseReport($data)
    {
        $planification = $this->query()
                    ->where('commande_id', $data['commande_id'])
                    ->first();
        $cmds = $this->commandes;

        \DB::transaction(function () use ($planification, $data, $cmds) {
            $paths['images_report'] = (json_decode($planification->extras) and isset(json_decode($planification->extras,true)['report']) and isset(json_decode($planification->extras,true)['report']['images_report'])) ? json_decode($planification->extras,true)['report']['images_report'] : [];
            if(isset($data['data']['photos'])){
                foreach ($data['data']['photos'] as $k => $v) {
                    $file_name = $v->getClientOriginalName();
                    
                    $file_path = Storage::putFile('images/commandes/poses/report', $v);

                    $file['name'] = $file_name;
                    $file['path'] = $file_path;
                    $file['type'] = 'image';
                    $file['related_to'] = [
                        'id' => $planification->id, 
                        'type' => 'report'
                    ];

                    $this->files->store($file);
                    $paths['images_report'][] = $file_path;

                }
            }

            $extras = json_decode($planification->extras, true);

            $extras['report'] = ['content' => $data['data']['report'], 'date_report' => \Carbon\Carbon::parse($data['data']['date_report'])->format('Y-m-d'), 'images_report' => $paths['images_report']];

            $planification->extras = json_encode($extras);
            $planification->save();
            $cmds->log('La commandes #'.$data['commande_id'].' a été planifiée le '.$planification->date_pose_finale->format('Y-m-d').' et a été reportée au '.$data['data']['date_report'].'.', 'planification', intval($data['commande_id']));
        });
    }


    public function poseRefuser($data)
    {
        $planification = $this->query()
                    ->where('commande_id', $data['commande_id'])
                    ->first();
        $cmds = $this->commandes;

        \DB::transaction(function () use ($planification, $data, $cmds) {
            $paths['images_refus'] = (json_decode($planification->extras) and isset(json_decode($planification->extras,true)['refus']) and isset(json_decode($planification->extras,true)['refus']['images_refus'])) ? json_decode($planification->extras,true)['refus']['images_refus'] : [];
            if(isset($data['data']['photos'])){
                foreach ($data['data']['photos'] as $k => $v) {
                    $file_name = $v->getClientOriginalName();
                    
                    $file_path = Storage::putFile('images/commandes/poses/refus', $v);

                    $file['name'] = $file_name;
                    $file['path'] = $file_path;
                    $file['type'] = 'image';
                    $file['related_to'] = [
                        'id' => $planification->id, 
                        'type' => 'refus'
                    ];

                    $this->files->store($file);
                    $paths['images_refus'][] = $file_path;

                }
            }

            $extras = json_decode($planification->extras, true);

            $extras['refus'] = ['content' => $data['data']['refus'], 'date_refus' => \Carbon\Carbon::now()->format('Y-m-d'), 'images_refus' => $paths['images_refus']];

            $planification->extras = json_encode($extras);
            $planification->save();
            $cmds->log('La commandes #'.$data['commande_id'].' a été refusée le '.\Carbon\Carbon::now()->format('Y-m-d').'.', 'planification', intval($data['commande_id']));

        });
    }

    public function getPoseImages($id)
    {   $images = $this->query()
                    ->select('extras->images_poses as path')
                    ->where('commande_id', $id)->first();
        return (count($images) == 0) ? null : $images;
    }

    public function getReportImages($id)
    {   $images = $this->query()
                    ->select('extras->report->images_report as path')
                    ->where('commande_id', $id)->first();
        return (count($images) == 0) ? null : $images;
    }

    public function getRefusImages($id)
    {   $images = $this->query()
                    ->select('extras->refus->images_refus as path')
                    ->where('commande_id', $id)->first();
        return (count($images) == 0) ? null : $images;
    }

    public function annuler($id)
    {
        $planification = $this->find($id);

        \DB::transaction(function () use ($planification) {
            $commande = $this->commandes->find($planification->commande_id);
            $commande->planification_id = null;
            $commande->save();

            parent::delete($planification);
        });

        return true;
    }

    public function getFinishedPoseImages()
    {   
        $images = $this->query()
                    ->select('planifications.extras->images_poses as path')
                    ->where('planifications.extras->done', true);
        if(is_client()){
            $images = $images->leftjoin('commandes as c','c.id', '=', 'planifications.commande_id')->where('c.client_id', Auth::user()->collaborateur->client_id);
        }
        $images = $images->get();

        return (count($images) == 0) ? null : $images;
    }
}