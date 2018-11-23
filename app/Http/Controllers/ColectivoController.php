<?php

namespace App\Http\Controllers;

use App\Colectivo;
use App\Tramo;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;

class ColectivoController extends Controller
{
    public function __construct()
    {
         //Aplico el middleware a todos los metodos del controlador menos al index
         $this->middleware('auth')->except(['index','obtenerPosicion']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $colectivos = Colectivo::all();
       
        return response()->json([
            'colectivos' => $colectivos,
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
        $this->validate($request, [
            'empresa' => 'required|min:3|max:100',
            'numero' => 'required'
        ]);

        $colectivo = new Colectivo;
        $colectivo->empresa = $request->empresa;
        $colectivo->num_coche = $request->numero;
        $colectivo->save();

        return response()->json([
            'colectivo'    => $colectivo,
            'message' => 'Colectivo Creado Correctamente'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Colectivo  $colectivo
     * @return \Illuminate\Http\Response
     */
    public function show(Colectivo $colectivo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Colectivo  $colectivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Colectivo $colectivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Colectivo  $colectivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Colectivo $colectivo)
    {
        //
        $this->validate($request, [
            'empresa' => 'required|min:3|max:100',
            'numero' => 'required'
        ]);

        $colectivo->empresa = $request->empresa;
        $colectivo->num_coche = $request->numero;
        $colectivo->save();

    
        return response()->json([
            'message' => 'Colectivo Actualizado Correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Colectivo  $colectivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Colectivo $colectivo)
    {
        //
        $colectivo->delete();

        return response()->json([
            'message' => 'Colectivo eliminado Correctamente'
        ], 200);
    }

    /*
        Esta funcion obtiene todos los usuarios que esten marcados como pasajeros en un tramo y se toma su posicion como la del colectivo,
        siempre que mantengan su posicion dentro de un recorrido

    */
    public function obtenerPosicion($idTramo)
    {
        //Obtengo el Colectivo perteneciente a ese Tramo
        $colectivot = \DB::select("SELECT colectivos.id
                                   FROM tramos 
                                   INNER JOIN colectivo_tramo ON colectivo_tramo.tramo_id = tramos.id 
                                   INNER JOIN colectivos ON colectivo_tramo.colectivo_id = colectivos.id 
                                   WHERE tramos.id = $idTramo");
        $idcol = $colectivot[0]->id;
     
        //Obtengo el usuario que marco que subio al colectivo
        $usuarios = \DB::select("SELECT *,st_x(ultima_posicion::geometry) as longitud , st_y(ultima_posicion::geometry) as latitud FROM usuarios WHERE pasajero = true AND tramo_id=$idTramo ORDER BY id");   

        if(count($usuarios) > 1){
            
            $colectivo = Colectivo::find($idcol);

            $colectivo->ultima_posicion = new Point($usuarios[0]->latitud,$usuarios[0]->longitud);        
            
            $colectivo->save();

            $colectivo = \DB::select("SELECT st_x(ultima_posicion::geometry) as longitud , st_y(ultima_posicion::geometry) as latitud
                                   FROM colectivos 
                                   WHERE id = $idcol");

            return response()->json([
                'colectivo' => $colectivo,
                'message' => 'La posicion de Colectivo se encontro correctamente'
            ], 200);
            
        } else {
            
            return response()->json([
                'error' => 'La posicion de Colectivo no se encontro'
            ], 200);
        }
    }

    /*
        Esta funcion recibe el id del tramo buscado y un arreglo de posiciones que coinciden y las compara con el recorrido para saber si estan 
        dentro del mismo
    */
    
}