<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Geometry;

class Colectivo extends Model
{
    //
    use PostgisTrait;

    protected $table="colectivos";

    public $timestamps = false;
    protected $fillable=[
        'empresa','num_coche'
    ];
    protected $postgisFields = [
        'ultima_posicion',
    ];
    protected $postgisTypes = [
        'ultima_posicion' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
    ];


    public function tramos (){
    
        return $this->belongsToMany("App\Tramo","colectivo_tramo");
    
    }
}
