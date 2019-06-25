<?php

namespace App\Models\Commande;

use App\Models\Client\Client;
use App\Repositories\Frontend\Spline\Commande\CommandeRepository;
use App\Repositories\Frontend\Spline\File\FileRepository;
use App\Repositories\Frontend\Spline\Pharmacie\PharmacieRepository;
use App\Repositories\Frontend\Spline\Product\ProductRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CommandeEdit
{

	protected $pharmacies;
    protected $products;
    protected $commandes;

    public function __construct(
        PharmacieRepository $pharmacies,
        ProductRepository $products,
        FileRepository $files,
        CommandeRepository $commandes
    )
    {
        $this->files = $files;
        $this->commandes = $commandes;
        $this->products = $products;
        $this->pharmacies = $pharmacies;
    }

    public function viewModel($id)
    {
    	$commande = $this->commandes->find($id);

    	$commande_type = 'Autres';

    	if ($commande->is_vitrine) {
    		$commande_type = 'vitrine';
    	} else if ($commande->is_merchandising) {
    		$commande_type = 'lineaire-merchandising';
    	} else if ($commande->is_presentoir) {
    		$commande_type = 'presentoir';
    	} else{
    		$commande_type = 'autre';
    	}
        $pharmacie_vitrines = [];
    	
    	if(!$commande->is_vitrine){
    		$pharmacie = null;
    		$product = null;
    	}else{
	    	$pharmacie = $this->pharmacies->find($commande->pharmacie_id);
	    	$product = $this->products->find($commande->product_id);

	    	$pv = $this->files->searchByPharmacie($commande->pharmacie_id);


	    	foreach ($pv as $key => $vitrine) {
	    		$pharmacie_vitrines[$key]['filled'] = (in_array(($key+1), json_decode($commande->vitrine))) ? true : false;
	    		$pharmacie_vitrines[$key]['vitrine'] = $vitrine->{'vitrine'};
	    		$pharmacie_vitrines[$key]['path'] = $vitrine->{'path'};
	    	}	
    	}
    	$choosen_vitrine_image = substr($commande->vitrine, 1, strlen($commande->vitrine)-2);
        $clients = null;
        if (Auth::user()->hasRole('Administrator')) {
            $clients = Client::all();
        }

    	$editModel = new \ArrayObject([
    		'commande_type'         => $commande_type,
    		'pharmacie'             => $pharmacie,
    		'commande'              => $commande,
    		'product'               => $product,
    		'pharmacies'            => $this->pharmacies->getAll(),
    		'products'              => $this->products->getByClient($commande->client_id),
    		'pharmacie_vitrines'    => json_encode($pharmacie_vitrines),
    		'pharmacie_id'          => (!$commande->pharmacie_id) ? 0 : $commande->pharmacie_id,
    		'product_id'            => (!$commande->product_id) ? 0 : $commande->product_id,
            'choosen_vitrine_image' => ($choosen_vitrine_image == '') ? 0 : $choosen_vitrine_image,
            'clients'               => $clients,
            'client_id'             => $commande->client_id
    	]);
        $editModel->setFlags(\ArrayObject::ARRAY_AS_PROPS);

        return $editModel;

    }
}