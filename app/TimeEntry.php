<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    protected $fillable = [
        'clock_in',
        'clock_out',
        'clock_in_dispensation',
    ];
}