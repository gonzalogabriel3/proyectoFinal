<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colectivo extends Model
{
    //
    protected $table="colectivos";

    public $timestamps = false;
    protected $fillable=[
        'empresa','num_coche'
    ];


    public function tramos (){
    
        return $this->belongsToMany("App\Tramos","colectivo_tramo");
    
    }
}
