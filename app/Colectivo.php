<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colectivo extends Model
{
    //
    protected $table="colectivos";

    public $timestamps = false;
    protected $fillable=[
        'tramo','tarifa_id','horario_id'
    ];
}
