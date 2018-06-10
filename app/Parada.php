<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    
    protected $table="paradas";

    public $timestamps = false;
    protected $fillable=[
        'geom','nombre'
    ];

    
}
