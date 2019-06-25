<?php

namespace App\Models\Commande;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commande extends Model
{
    use SoftDeletes;

    protected $dates = ['date_pose', 'create_at', 'deleted_at'];
    
    public function client()
    {
    	return $this->belongsTo('App\Models\Client\Client');
    }

    public function pharmacie()
    {
    	return $this->belongsTo('App\Models\Pharmacie\Pharmacie');
    }

    public function product()
    {
    	return $this->belongsTo('App\Models\Product\Product');
    }
    
    public function owner()
    {
        return $this->belongsTo('App\Models\Access\User\User', 'owner_id');
    }

    public function planification()
    {
        return $this->belongsTo('App\Models\Planification\Planification');
    }

    public function _deleted_by()
    {
        return $this->belongsTo('App\Models\Access\User\User', 'deleted_by');
    }
}
