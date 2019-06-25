<?php

namespace App\Models\Delegue;

use Illuminate\Database\Eloquent\Model;

class Delegue extends Model
{
    protected $fillable = ['firstname', 'phone'];

	protected $dates = ['created_at'];
}
