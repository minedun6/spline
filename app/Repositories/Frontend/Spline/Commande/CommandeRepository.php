<?php 

namespace App\Repositories\Frontend\Spline\Commande;

use App\Models\Commande\Commande;
use App\Models\Log\Log;
use App\Models\Planification\Planification;
use App\Repositories\Frontend\Spline\File\FileRepository;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommandeRepository extends Repository  {
	/**
	 * Associated Repository Model
	 */
	const MODEL = Commande::class;

	protected $files;

    public function __construct(
        FileRepository $files
    )
    {
        $this->files = $files;
    }
    
    public function findWithTrashed($id)
    {
    	return Commande::withTrashed()->where('id', $id)->first();
    }
    
    public function getLatestPosedOrder($client_id)
    {
    	return Commande::where('client_id', $client_id)
    		->where('status', 'done')
    		->orderBy('extras->history->done', 'desc')
    		->take(10);
    }
    public function getByClient($client_id)
    {
    	return Commande::withTrashed()->where('client_id', $client_id);
    }

    public function getByStatus($status)
    {
    	return Commande::where('status', $status);
    }

    public function keepTrackOf($id, $state)
    {
    	$commande = $this->find($id);
    	$extras = json_decode($commande->extras, true);

        if(isset($extras['history'])){
            $history = $extras['history'];
        }

        $history[$state] = \Carbon\Carbon::now()->format('Y-m-d H:i');

        $extras['history'] = $history;
        $commande->extras = json_encode($extras);
        $commande->save();

        return $commande;
    }
	public function getAllCommandes()
    {
    	$commandes = Commande::withTrashed()
    		->get();
		return $commandes;
    }
    public function getAllCommandesForPose()
    {
    	$commandes = Commande::where('status', '!=', 'done')
    		->where('status', '!=', 'brouillon')
    		->get();

    	foreach ($commandes as $key => $c) {
    		if(Planification::where('commande_id', $c->id)->where('extras->done', true)->first()){
    			unset($commandes[$key]);
    		}
    	}

    	return $commandes;
    }
    public function changeStatus($status, $id)
    {
    	$commande = $this->find($id);

    	$commande->status = $status;

		$this->log('<strong>[Commande #'.$id.']</strong> Status: "'.strtoupper($status).'"', 'status', $id);

    	return $this->save($commande);
    }
	public function store($data)
	{
		$commande = $this->createCommandeStub($data);

		DB::transaction(function () use ($commande, $data) {
			if(parent::save($commande) and isset($data['fichiers']))
                $this->files->storeMultiple($data['fichiers'], $commande->id, 'images/commandes', 'attachment');

            $commande->ref = \Carbon\Carbon::now()->format('ymd-').$commande->id;
            $commande->save();
		});
	
		$commande = $this->keepTrackOf($commande->id, 'created_at');

		$this->log('Ajout d\'une commande #'.$commande->id.'.', 'add', $commande->id);

		return $commande;
	}

	public function update1($model, $data)
	{
		$commande = $this->createCommandeStub($data, $model);
        $commande = $this->save($commande);
        if($commande->status != 'brouillon'){
			$commande = $this->keepTrackOf($commande->id, 'updated_at');
        }

		DB::transaction(function () use ($commande, $data) {
			if(isset($data['fichiers']))
                $this->files->storeMultiple($data['fichiers'], $commande->id, 'images/commandes', 'attachment');
		});
		$this->log('Mis à jour de la commande #'.$commande->id.'.', 'update', $commande->id);

        return $commande;
	}

	public function deleteOrder($id)
	{
		DB::transaction(function () use ($id) {
			$commande = $this->find($id);

			$commande->deleted_by = Auth::user()->id;
			$commande->save();
            $this->delete($commande);        
    		$this->log('Suppression de la commande #'.$id.'.', 'delete',$id);
		});
	}
	public function createCommandeStub($data, $commande = null)
	{
		if (!$commande) {
			$commande = new Commande;
		}

		$commande->description = $data['description'];
		if($data['brouillon'] == "true"){
			$status = 'brouillon';
		}else{
			$status = 'en traitement';
		}
		$commande->status = $status;
		
		$commande->is_vitrine = ($data['commande_type'] == "vitrine") ? true : false;
		$commande->is_presentoir = ($data['commande_type'] == "presentoir") ? true : false;
		$commande->is_merchandising = ($data['commande_type'] == "lineaire-merchandising") ? true : false;
		$commande->owner_id = Auth::user()->id;
		$commande->pharmacie_id = $data['pharmacie_id'];

		if(Auth::user()->collaborateur){
			$commande->client_id = Auth::user()->collaborateur->client->id;
		}else{
			$commande->client_id = $data['client_id'];
		}
		
		if ($commande->is_vitrine) {
			$data['vitrines'] = (isset($data['vitrines'])) ? [intval($data['vitrines'])] : [];
			$commande->type_support = $data['type_support'];
			$commande->vitrine = (isset($data['vitrines'])) ? json_encode($data['vitrines']) : json_encode([]);

			$commande->product_id = $data['product_id'];
		}else{
			$commande->type_support = null;
			$commande->vitrine = json_encode([]);
			$commande->product_id = null;
		}
		$extras = json_decode($commande->extras, true);
		$extras['choosen_vitrine'] = (isset($data['choosen_vitrine'])) ? $data['choosen_vitrine'] : 0;
		$commande->extras = json_encode($extras);
		if($data['date_pose'] != ''){
			$commande->date_pose = Carbon::parse($data['date_pose']);
		}else{
			$commande->date_pose = null;
		}

		return $commande;
	}

	public function log($content, $action, $id)
	{
		$log = new Log;
		$log->related_to = 'commande';
		$log->related_to_id = $id;
		$log->action_by = Auth::user()->id;
		$log->content = $content;
		$log->action = $action;
		$log->save();

		return 'success';
	}
}