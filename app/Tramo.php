<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tramo extends Model
{
    //
    protected $table="tramos";

    public $timestamps = false;
    protected $fillable=[
        'nombre','inicio','fin','recorrido_id'
    ];


    public function colectivos (){
    
        return $this->belongsToMany("App\Colectivo","colectivo_tramo");
    
    }
}
