<?php

namespace App\Http\Controllers;

use App\Parada;
use App\Usuario;
use App\Colectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;

class ParadaController extends Controller
{
    
    public function __construct()
    {
        //Aplico el middleware a todos los metodos del controlador menos al index
        $this->middleware('auth')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paradas = \DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM paradas ORDER BY id DESC");
        return response()->json([
            'paradas' => $paradas,
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
            'nombre' => 'required',
            'latitud' => 'required',
            'longitud' => 'required'
        ]);

        $parada = new Parada();
        $parada->nombre = $request->nombre;
        $parada->cubierta=$request->cubierta;
        $parada->iluminada=$request->iluminada;

        $parada->geom = new Point( $request->latitud , $request ->longitud );

        $parada->save();
        

        return response()->json([
            'parada'    => $parada,
            'message' => 'Parada Creada Correctamente'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Parada  $parada
     * @return \Illuminate\Http\Response
     */
    public function show(Parada $parada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Parada  $parada
     * @return \Illuminate\Http\Response
     */
    public function edit(Parada $parada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Parada  $parada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parada $parada)
    {
        //
        $this->validate($request, [
            'nombre' => 'required',
            'latitud' => 'required',
            'longitud' => 'required'
        ]);


        $parada->nombre = $request->nombre;
        $parada->cubierta=$request->cubierta;
        $parada->iluminada=$request->iluminada;
        $parada->geom = new Point( $request->latitud , $request ->longitud );


        $parada->save();
    
        return response()->json([
            'message' => 'Parada Actualizada Correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Parada  $parada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parada $parada)
    {
        //
        $parada->delete();
        return response()->json([
            'message' => 'Parada eliminada Correctamente'
        ], 200);
    }

    /*Metodo que calcula las paradas mas cercanas al usuario*/
    public function obtenerParadasCercanas($idUsuario,$idColectivo){
        //Busco al usuario para trabajar con su ultima posicion
        $usuario=Usuario::find($idUsuario);
        
        //Busco el colectivo para trabajar con su ultima posicion
        $colectivo=Usuario::find($idColectivo);
        
        //Obtengo la distancia entre esos dos puntos(el usuario y el colectivo)
        $distanciaColectivo=\DB::select("SELECT ST_Distance('POINT($usuario->ultima_posicion)','POINT($colectivo->ultima_posicion)') as distancia");
        $distanciaColectivo=(float)$distanciaColectivo[0]->distancia;
        
        //Obtengo todas las paradas
        $paradas=Parada::all();

        //Creo una variable donde se va a contener todos los id's,de las paradas mas cercanas al usuario
        $ids_paradas="";
        
        //Recorro todas las paradas
        foreach ($paradas as $parada) {
            $distanciaParada=\DB::select("SELECT ST_Distance('POINT($usuario->ultima_posicion)', 'POINT($parada->geom)')as distancia");
            $distanciaParada=(float)$distanciaParada[0]->distancia;
            //Si la distancia entre el usuario y el colectivo,NO es mayor que la distancia entre el usuario y la parada
            if($distanciaColectivo>$distanciaParada){
                //Agrego el id de la parada 
                $ids_paradas=$ids_paradas.$parada->id.",";
            }
        }
       
        
        //Si el colectivo esta por detras de la posicion del usuario,retorno las paradas cercanas
        if($ids_paradas!=''){
            //Quito la ultima coma para evitar error al momento de ejecutar la consulta
            $ids_paradas=trim($ids_paradas,',');
            
            //Obtengo todas las paradas
            $paradasCercanas = \DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM paradas WHERE id IN($ids_paradas) ORDER BY id DESC");
           
        }
        //Si el usuario esta por detras de la posicion del colectivo(que lo perdio),se le mostraran todas las paradas
        else{
            $paradasCercanas=\DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM paradas ORDER BY id DESC");
        }
        //Las retorno al cliente
        return response()->json([
            'paradas' => $paradasCercanas,
        ], 200);
        

    }
}
