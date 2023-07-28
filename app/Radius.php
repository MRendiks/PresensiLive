<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Radius extends Model
{
	protected $table = 'radius';
	public $fillable =['lat','long','radius'];
}
