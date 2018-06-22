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

    public function tarifa(){
       
        return $this->belongsTo('App\Tarifa','tarifa_id');
    }
    public function horarios (){
        return $this->belongsToMany("App\Horario","colectivo_horario");
    }
}
