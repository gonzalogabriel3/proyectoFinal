<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Geometry;

class PuntoRecarga extends Model
{
    use PostgisTrait;
    
    protected $table="puntos_recarga";

    public $timestamps = false;
    
    protected $fillable=[
        'nombre'
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
}
