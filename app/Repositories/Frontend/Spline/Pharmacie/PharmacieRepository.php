<?php 

namespace App\Repositories\Frontend\Spline\Pharmacie;

use App\Models\Pharmacie\Pharmacie;
use App\Repositories\Frontend\Spline\Delegue\DelegueRepository;
use App\Repositories\Frontend\Spline\File\FileRepository;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Storage;

class PharmacieRepository extends Repository  {
	/**
	 * Associated Repository Model
	 */
	const MODEL = Pharmacie::class;

	protected $files;

    public function __construct(
        FileRepository $files,
        DelegueRepository $delegues
    )
    {
        $this->files = $files;
        $this->delegues = $delegues;
    }

    public function getActives()
    {
    	return $this->query()->where('is_active', true)->get();
    }

	public function store($data)
	{
		$pharmacie = $this->createPharmacieStub($data);

		$photos = $data['photos'];

		\DB::transaction(function () use ($photos, $pharmacie) {

		    $pharmacie = $this->save($pharmacie);

		    foreach ($photos as $key => $value) {
	            foreach ($value as $k => $v) {
	            	$file = [];
	                if($k == 0){
	                    $with_sizes = true;
	                }else{
                		$with_sizes = false;
	                }
					
					$file_path = Storage::putFile('images/vitrines', $v);

	                $file['path'] = $file_path;
	                $file['type'] = 'image';
	                $file['related_to'] = [
	                	'id' => $pharmacie->id, 
	                	'vitrines' => $key + 1, 
	                	'avec_mesure' => $with_sizes, 
	                	'type' => 'pharmacie'
	                ];

	                $this->files->store($file);
	            }
	        }
		});

		return $pharmacie;
	}

	public function update1($id, $input)
	{
		$pharmacie = $this->createPharmacieStub($input, $this->find(intval($id)));
		$photos = $input['photos'];

		\DB::transaction(function () use ($photos, $pharmacie) {
			$pharmacie->is_active = true;
		    $pharmacie = $this->save($pharmacie);

		    if($photos){
			    foreach ($photos as $key => $value) {
		            foreach ($value as $k => $v) {
		                if($k == 0){
		                    $with_sizes = true;
		                }else{
	                		$with_sizes = false;
		                }
						
						$file_path = Storage::putFile('images/vitrines', $v);

						$count_vitrine = count($this->files->searchByPharmacie($pharmacie->id));

		                $file['path'] = $file_path;
		                $file['type'] = 'image';
		                $file['related_to'] = [
		                	'id' => $pharmacie->id, 
		                	'vitrines' => $count_vitrine + 1, 
		                	'avec_mesure' => $with_sizes, 
		                	'type' => 'pharmacie'
		                ];

		                $this->files->store($file);
		            }
		        }
		    }
		});
		return $pharmacie;
	}

 	function createPharmacieStub($data, $model = null)
	{
		if (!$model) {
			$pharmacie = new Pharmacie;
		}else{
			$pharmacie = $model;
		}

		$pharmacie->nom = $data['nom'];
		$pharmacie->secteur = $data['secteur'];
		$pharmacie->adresse = $data['adresse'];
		$pharmacie->telephone = $data['telephone'];
		$pharmacie->nbre_vitrines = $data['nbre_vitrines'];
		$pharmacie->note = $data['note'];
		$pharmacie->lat = $data['lat'];
		$pharmacie->long = $data['long'];
        
        $delegue = [];

        if(isset($data['firstName'])){
            $delegue['firstName'] = $data['firstName'];
        }
        if(isset($data['lastName'])){
            $delegue['lastName'] = $data['lastName'];
        }
        if(isset($data['phone'])){
            $delegue['phone'] = $data['phone'];
        }

		$pharmacie->extras = json_encode(['delegue' => $delegue, 'mesures' => $data['mesures']]);

		return $pharmacie;
	}
}