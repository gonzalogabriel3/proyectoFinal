<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    //
    protected $table="horarios";

    public $timestamps = false;
    protected $fillable=[
        'salida','llegada'
    ];
    public function colectivos (){
        return $this->belongsToMany("App\Colectivo","colectivo_horario");
    }

}
