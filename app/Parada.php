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
        'nombre',
    ];
    protected $postgisFields = [
        'geom' => Geometry::class,
    ];
    protected $postgisTypes = [
        'geom' => [
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];
}
