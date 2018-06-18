<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tarifa extends Model
{
    protected $table="tarifas";

    public $timestamps = false;
    protected $fillable=[
        'monto'
    ];
}

