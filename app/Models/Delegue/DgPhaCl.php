<?php

namespace App\Models\Delegue;

use Illuminate\Database\Eloquent\Model;

class DgPhaCl extends Model
{
    protected $table = 'client_pharmacie_delegue';

    public function client()
    {
    	return $this->belongsTo('App\Models\Client\Client');
    }

    public function pharmacie()
    {
    	return $this->belongsTo('App\Models\Pharmacie\Pharmacie');
    }

    public function delegue()
    {
    	return $this->belongsTo('App\Models\Delegue\Delegue');
    }
}
