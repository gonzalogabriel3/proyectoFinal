<?php

namespace App\Http\Controllers;

use App\ViajeUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;

class ViajeUsuarioController extends Controller
{
    /*
     * Metodo que Filtra las Funciones segun si se esta Logueado con el Rol  de Admin
    
    */
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
        $viajes = \DB::select("SELECT *,st_x(punto_inicio::geometry) as longinicio , st_y(punto_inicio::geometry) as latinicio,
                               st_x(punto_fin::geometry) as longfin , st_y(punto_fin::geometry) as latfin FROM viajes ORDER BY id DESC");

        return response()->json([
            'viajes' => $viajes,
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
        /*
         * Se validan los datos del request
         * Se traen todos los vertices de las calles
         * si el punto inicio esta en el request se settea ese punto
         * si el punto fin esta en el request se guarda ese punto
         * se hace un for comparando los puntos con los vertices para buscar el mas cercano
         * se cambia la posicion de los puntos por el de los vertices y se procede a guardar en la bd 
        */
        $viajeUsuario = new ViajeUsuario();
        $viajeUsuario->id_usuario = $request->id_usuario;
        $vertices = \DB::select("SELECT *,st_x(the_geom::geometry) as longitud , st_y(the_geom::geometry) as latitud FROM calles_rawson_vertices_pgr ORDER BY id DESC");
        $distmascercana = 1000;
        $puntmascerca = null;
        if (isset($request->latinicio)) {
            $punto = new Point($request->latinicio,$request->longinicio);
            for ($i=0; $i < count($vertices); $i++) {
                $vertice = new Point($vertices[$i]->latitud,$vertices[$i]->longitud);
                $calculo =  \DB::select("SELECT ST_Distance('POINT($punto)','POINT($vertice)') as distancia");
                if($calculo[0]->distancia < $distmascercana){
                    $distmascercana = $calculo;
                    $puntmascerca = $vertice;
                }
            }
            $viajeUsuario->punto_inicio = new Point($puntmascerca->getLat(),$puntmascerca->getLng());
        }
       if (isset($request->latfin)){
            $punto = new Point($request->latfin,$request->longfin);
            for ($i=0; $i < count($vertices); $i++) {
                $vertice = new Point($vertices[$i]->latitud,$vertices[$i]->longitud);
                $calculo =  \DB::select("SELECT ST_Distance('POINT($punto)','POINT($vertice)') as distancia");
                if($calculo[0]->distancia < $distmascercana){
                    $distmascercana = $calculo;
                    $puntmascerca = $vertice;
                }
            }
            $viajeUsuario->punto_fin = new Point($puntmascerca->getLat(),$puntmascerca->getLng());        
        }
        
        $viajeUsuario->save();
        
        return response()->json([
            'message' => 'Viaje de Usuario Creado Correctamente'
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ViajeUsuario  $viajeUsuario
     * @return \Illuminate\Http\Response
     */
    public function show(ViajeUsuario $viajeUsuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ViajeUsuario  $viajeUsuario
     * @return \Illuminate\Http\Response
     */
    public function edit(ViajeUsuario $viajeUsuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ViajeUsuario  $viajeUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ViajeUsuario $viajeUsuario)
    {
        //
        $vertices = \DB::select("SELECT *,st_x(the_geom::geometry) as longitud , st_y(the_geom::geometry) as latitud FROM calles_rawson_vertices_pgr ORDER BY id DESC");
        $distmascercana = 1000;
        $puntmascerca = null;
        if (isset($request->punto_fin)){
            $punto = new Point($request->latitud,$request->longitud);
            foreach ($vertices as $vertice) {
                $calculo =  \DB::select("SELECT ST_Distance('POINT($punto)', 'POINT($vertice->the_geom)')as distancia");
                if($calculo < $distancia){
                    $distancia = $calculo;
                    $puntmascerca = $vertice;
                }
            }
            $viajeUsuario->punto_fin = new Point($puntmascerca->getLat(),$puntmascerca->getLng());        
        }
        $viajeUsuario->save();
        return response()->json([
            'message' => 'Viaje de Usuario Editado Correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ViajeUsuario  $viajeUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $viaje = ViajeUsuario::find($id);
        $viaje->delete();
        
        return response()->json([
            'message' => 'Viaje de Usuario eliminado Correctamente'
        ], 200);
    }
}
