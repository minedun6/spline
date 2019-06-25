<?php 

namespace App\Repositories\Frontend\Spline\Delegue;

use App\Models\Delegue\Delegue;
use App\Models\Delegue\DgPhaCl;
use App\Repositories\Repository;

class DelegueRepository extends Repository  {
	/**
	 * Associated Repository Model
	 */
	const MODEL = Delegue::class;

	public function getByClient($id)
	{
		return $this->query()->where('client_id', $id)->get();
	}
	
	public function getByPharmacie($id)
	{
		return $this->query()->where('pharmacie_id', $id)->get();
	}

	public function getActiveByPharmacie($id)
	{
		return DgPhaCl::where('pharmacie_id', $id)->get();
	}

	public function getByPharmacieAndClient($id_pharmacie, $id_client)
	{
		return DgPhaCl::where('pharmacie_id', $id_pharmacie)->where('client_id', $id_client)->first();
	}
	
	public function store($data)
	{
		$delegue = new Delegue;

		$delegue->firstname = $data['firstname'];
		$delegue->phone = $data['phone'];

		return $this->save($delegue);
	}

	public function assign($data)
	{
		if ($d = DgPhaCl::where('pharmacie_id', $data['pharmacie_id'])
			->where('client_id', $data['client_id'])->first()) {
			return $d;
		}else{
			$dgPhaCl = new DgPhaCl;

			$dgPhaCl->client_id = $data['client_id'];
			$dgPhaCl->delegue_id = $data['delegue_id'];
			$dgPhaCl->pharmacie_id = $data['pharmacie_id'];
			$dgPhaCl->save();

			return $dgPhaCl;			
		}
	}
	
	public function reset($client_id, $pharmacie_id)
	{
		foreach ($this->query()->where('client_id', $client_id)->where('pharmacie_id', $pharmacie_id)->get() as $key => $value) {
			$value->is_the_one = false;
			$value->save();
		}
	}

	
}