<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tarifa extends Model
{
    protected $table="tarifas";

    public $timestamps = false;
    protected $fillable=[
        'monto','tramo_id'
    ];

    public function tramos(){
        
        return $this->hasMany('App\Tramo','tramo_id');
    }
}

