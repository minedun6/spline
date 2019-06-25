<?php

namespace App\Models\Collaborateur;

use Illuminate\Database\Eloquent\Model;

class Collaborateur extends Model
{
    public function client()
    {
    	return $this->belongsTo('App\Models\Client\Client');;
    }
}
