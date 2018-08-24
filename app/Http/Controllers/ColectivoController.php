<?php

namespace App\Http\Controllers;

use App\Colectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function obtenerPosicion()
    {
        $usuarios = \DB::select("SELECT id, ultima_posicion::geometry ,st_x(ultima_posicion::geometry) as longitud , st_y(ultima_posicion::geometry) as latitud FROM usuarios");
        
        for($i = 0 ; $i < count($usuarios); $i++){
            $usu1 = $usuarios[$i];
            for($j = 0 ; $j < count($usuarios); $j++){
                if(!($i != $j)){                    
                    $usu2 = $usuarios[$j];
                    if(\DB::select("SELECT ST_Equals(pos1.ultima_posicion::geometry, pos2.ultima_posicion::geometry) 
                                    FROM usuarios pos1, usuarios pos2 
                                    WHERE pos1.id=$usu1->id AND pos2.id=$usu2->id;")){
                        $colectivos = array(); 
                        array_push($colectivos,$usuarios[$i]);
                    }
                } 
            } 
        }
        if(count($colectivos) > 2){
            $coincidencia = self::obtenerCoincidencia($colectivos);
            return response()->json([
                'colectivo'    => $coincidencia,
                'message' => 'La posicion de Colectivo se encontro correctamente'
            ], 200);
        } else {
            $coincidencia = self::obtenerCoincidencia($colectivos);
            return response()->json([
                'colectivo'    => $coincidencia,
                'message' => 'La posicion de Colectivo se encontro correctamente'
            ], 200);
        }
        return response()->json([
            'message' => 'La posicion de Colectivo no se encontro'
        ], 200);

    }
    /*
        Esta funcion recibe un arreglo de posicion que coinciden y las compara con el recorrido para saber si estan 
        dentro del mismo
    */
    public function obtenerCoincidencia(array $colectivos)
    {
        for($i=0;$i < count($colectivos);$i++){
            $colectivo = $colectivos[$i];
            if(\DB::select("SELECT ST_Intersects(pos1.ultima_posicion::geometry, pos2.geom::geometry) 
                            FROM usuarios pos1, recorridos pos2 
                            WHERE pos1.id=$colectivo->id AND pos2.id=1;")){
                $coincidenciap = $colectivo; 
            }
        }
        return $coincidenciap;
    }
}