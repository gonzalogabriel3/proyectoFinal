<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Geometry;

class Recorrido extends Model
{
    use PostgisTrait;
    
    protected $table="recorridos";

    public $timestamps = false;

    protected $fillable=[
        'nombre'
    ];

    protected $postgisFields = [
        'geom',
    ];
    protected $postgisTypes = [
        'geom' => [
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];

    public function paradas(){
        
        return $this->belongsToMany('App\Parada','parada_recorrido');
    }


}
