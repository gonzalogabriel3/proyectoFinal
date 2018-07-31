<?php

namespace App\Http\Controllers;

use DB;
use App\Recorrido;
use Illuminate\Http\Request;
use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;

class MapaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Obtengo el recorrido(linestring)
        $recorrido=Recorrido::find(1);
        
        //Creo un array donde se va a contener todos los punto del linestring
        $puntos=array();
        
        foreach ($recorrido->geom as $geom) {
            //Obtengo la latitud y longitud de un punto
            $latitud=$geom->getLat();
            $longitud=$geom->getLng();
            
            //Creo un nuevo arreglo y le inserto los valores de la latitud y longitud
            $punto=array();
            array_push($punto,$latitud);
            array_push($punto,$longitud);

            //Al arreglo creado,lo agrego al arreglo de puntos
            array_push($puntos,$punto);
        }
       
    
        return response()->json([
            'puntos' => $puntos
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Obtengo el recorrido(linestring)
        $recorrido=Recorrido::find($id);
        
        //Creo un array donde se va a contener todos los punto del linestring
        $puntos=array();
        
        foreach ($recorrido->geom as $geom) {
            //Obtengo la latitud y longitud de un punto
            $latitud=$geom->getLat();
            $longitud=$geom->getLng();
            
            //Creo un nuevo arreglo y le inserto los valores de la latitud y longitud
            $punto=array();
            array_push($punto,$latitud);
            array_push($punto,$longitud);

            //Al arreglo creado,lo agrego al arreglo de puntos
            array_push($puntos,$punto);
        }
       
        return response()->json([
            'puntos' => $puntos
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
