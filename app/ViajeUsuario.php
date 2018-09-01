<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Geometry;

class ViajeUsuario extends Model
{
    //
    use PostgisTrait;

    protected $table="viajes";

    public $timestamps = false;
    
    protected $fillable=[
        'id_usuario',
    ];
    protected $postgisFields = [
        'punto_inicio','punto_fin',
    ];
    protected $postgisTypes = [
        'punto_inicio' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
        'punto_fin' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
    ];








}
