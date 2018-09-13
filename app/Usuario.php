<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Geometry;


class Usuario extends Model
{
    use PostgisTrait;

    protected $table="usuarios";

    public $timestamps = false;

    protected $fillable=[
        'id','nombre','password','email'
    ];
    protected $postgisFields = [
        'ultima_posicion','posicion_normalizada'
    ];
    protected $postgisTypes = [
        'ultima_posicion' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
        'posicion_normalizada' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ]
    ];
}
