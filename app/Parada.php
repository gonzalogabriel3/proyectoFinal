<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Geometry;

class Parada extends Model
{
    use PostgisTrait;
    
    protected $table="paradas";

    public $timestamps = false;
    
    protected $fillable=[
        'nombre','cubierta','iluminada'
    ];
    protected $postgisFields = [
        'geom',
    ];
    protected $postgisTypes = [
        'geom' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
    ];

    public function recorridos(){

        return $this->belongsToMany('App\Recorrido','parada_recorrido');
    }
    
}
