<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colectivo extends Model
{
    //
    protected $table="colectivos";

    protected $dateFormat = 'Y-m-d H:i';

    protected $fillable=[
        'tramo','tarifa_id','horario_id','created_at','updated_at'
    ];
}
