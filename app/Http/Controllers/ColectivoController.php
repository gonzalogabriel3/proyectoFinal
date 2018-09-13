<?php

namespace App\Http\Controllers;

use App\Colectivo;
use App\Tramo;
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
        Esta funcion es la que permite comparar las posiciones de los usuarios para definir 
        si al encontrarse dos o mas usuarios en el mismo lugar se tiene en cuenta como posicion del colectivo

    */
    public function obtenerPosicion($idTramo)
    {

        $usuarios = \DB::select("SELECT id,st_x(ultima_posicion::geometry) as longitud , st_y(ultima_posicion::geometry) as latitud FROM usuarios");
        $colectivot = \DB::select("SELECT colectivos.id 
                                   FROM tramos 
                                   INNER JOIN colectivo_tramo ON colectivo_tramo.tramo_id = tramos.id 
                                   INNER JOIN colectivos ON colectivo_tramo.colectivo_id = colectivos.id 
                                   WHERE tramos.id = $idTramo");
        $colectivos = array(); 
        
        for($i = 0 ; $i < count($usuarios); $i++){
            $usu1 = $usuarios[$i];
            
            for($j = 0 ; $j < count($usuarios); $j++){
                if($i != $j){                    
                    $usu2 = $usuarios[$j];

                    //hago la consulta que comprueba si dos geometrias o posicion son espacialmente iguales
                    $validador = \DB::select("SELECT ST_Equals(pos1.ultima_posicion::geometry, pos2.ultima_posicion::geometry) FROM usuarios pos1, usuarios pos2 WHERE pos1.id=$usu1->id AND pos2.id=$usu2->id;");
                    
                    if($validador[0]->st_equals == true){
                        array_push($colectivos,$usuarios[$i]);
                    }
                } 
            } 
        }
        
        if(count($colectivos) > 1){
            $coincidencia = self::obtenerCoincidencia($colectivos,$idTramo);
            if($coincidencia != ""){
            $colectivo = Colectivo::find($colectivot[0]->id);
            $colectivo->ultima_posicion = new Point($coincidencia->longitud,$coincidencia->latitud);
            $colectivo->save(); 
            
            return response()->json([
                'colectivo'    => $coincidencia,
                'message' => 'La posicion de Colectivo se encontro correctamente'
            ], 200);
            }
        } 
        return response()->json([
            'message' => 'La posicion de Colectivo no se encontro'
        ], 200);

    }
    /*
        Esta funcion recibe el id del tramo buscado y un arreglo de posiciones que coinciden y las compara con el recorrido para saber si estan 
        dentro del mismo
    */
    public function obtenerCoincidencia(array $colectivos,$idTramo)
    {
        $tramo = Tramo::find($idTramo)->first();
        $coincidenciap = "";
        for($i=0;$i < count($colectivos);$i++){
            $colectivo = $colectivos[$i];
            $validador=\DB::select("SELECT ST_Intersects(pos1.ultima_posicion::geometry, pos2.geom::geometry) 
            FROM usuarios pos1, recorridos pos2 
            WHERE pos1.id=$colectivo->id AND pos2.id=$tramo->recorrido_id;");
            if($validador == true){
                $coincidenciap = $colectivo; 
            } 
        }
        return $coincidenciap;
    }

    
}