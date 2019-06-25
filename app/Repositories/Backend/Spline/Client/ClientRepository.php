<?php

namespace App\Repositories\Backend\Spline\Client;

use App\Models\Client\Client;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


/**
 * Class UserRepository
 * @package App\Repositories\Frontend\User
 */
class ClientRepository extends Repository
{
	/**
	 * Associated Repository Model
	 */
	const MODEL = Client::class;


	public function create($data)
	{
		$logo = $data['client']['logo'];
        
		$client = $this->createClientStub($data['client']);

		DB::transaction(function() use ($client, $logo) {
			if (parent::save($client)) {

				$file_path = Storage::put('images/client_logos', $logo);

				$client->logo_path = $file_path;

				parent::save($client);
			}
		});
        
        return $client;
	}

	public function update1($data, $id)
	{
		if(isset($data['logo'])){
			$logo = $data['logo'];
		}else{
			$logo = false;
		}
        
		$client = $this->find($id);


		DB::transaction(function() use ($data, $client, $logo) {
			$client->name = $data['name'];
			if($logo){
				$file_path = Storage::put('images/client_logos', $logo);
				$client->logo_path = $file_path;
			}

			parent::save($client);
		});
        
        return $client;
	}


	protected function createClientStub($input)
    {
    	$client					   = self::MODEL;
        $client                    = new $client;
        $client->name              = $input['name'];
        $client->logo_path         = $input['logo'];
        
        return $client;
    }

}