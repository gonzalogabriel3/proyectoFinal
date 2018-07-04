<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Geometry;

class Tramo extends Model
{
    //
    protected $table="tramos";

    public $timestamps = false;
    protected $fillable=[
        'nombre','recorrido_id'
    ];
    protected $postgisFields = [
        'inicio','fin'
    ];
    protected $postgisTypes = [
        'inicio' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
        'fin' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
    ];



    public function colectivos (){
    
        return $this->belongsToMany("App\Colectivo","colectivo_tramo");
    
    }
}
