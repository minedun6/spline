<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function commandes()
    {
    	return $this->hasMany('App\Models\Commande\Commande');
    }

    public function products()
    {
    	return $this->hasMany('App\Models\Product\Product');
    }
}
