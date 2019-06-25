<?php

namespace App\Models\Planification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planification extends Model
{
    use SoftDeletes;

	protected $dates = ['date_pose_finale', 'created_at'];

    public function commande()
    {
    	return $this->belongsTo('App\Models\Commande\Commande');;
    }

    public function owner()
    {
    	return $this->belongsTo('App\Models\Access\User\User', 'poseur_id');
    }
}
