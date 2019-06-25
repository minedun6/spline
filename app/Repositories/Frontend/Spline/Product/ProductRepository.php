<?php 

namespace App\Repositories\Frontend\Spline\Product;

use App\Models\Product\Product;
use App\Repositories\Repository;

class ProductRepository extends Repository  {
	/**
	 * Associated Repository Model
	 */
	const MODEL = Product::class;

	public function getByClient($id)
	{
		return $this->query()->where('client_id', $id)->get();
	}
	
	public function store($data)
	{
		$product = new Product;

		$product->nom = $data['nom'];
		$product->secteur = $data['secteur'];
		$product->adresse = $data['adresse'];
		$product->telephone = $data['telephone'];
		$product->nbre_vitrines = $data['nbre_vitrines'];
		$product->note = $data['note'];
		$product->lat = $data['lat'];
		$product->long = $data['long'];

		return $this->save($product);
	}

	
}