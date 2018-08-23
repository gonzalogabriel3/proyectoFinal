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
        si al encontrarse dos o mas usuarios en el mismo lugar, se considere que esa es la posicion
        del colectivo

    */
    public function obtenerPosicion()
    {
        $usuarios = \DB::select("SELECT id, ultima_posicion::geometry ,st_x(ultima_posicion::geometry) as longitud , st_y(ultima_posicion::geometry) as latitud FROM usuarios ORDER BY id DESC");
        for($i = 0 ; $i <= count($usuarios); $i++){
            $usu1 = $usuarios[$i];
            for($j = 0 ; $j <= count($usuarios); $j++){
                if(!($i != $j)){
                                        
                    $usu2 = $usuarios[$j];
                    if(\DB::select("SELECT ST_Equals(pos1.ultima_posicion::geometry, pos2.ultima_posicion::geometry) 
                                    FROM usuarios pos1, usuarios pos2 
                                    WHERE pos1.id=$usu1->id AND pos2.id=$usu2->id;")){
                        $colectivo = $usuarios[$i];
                        return response()->json([
                            'colectivo'    => $colectivo,
                            'message' => 'La posicion de Colectivo se encontro correctamente'
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'message' => 'La posicion de Colectivo no se encontro'
                    ], 200);
                }
            } 
        }
    }
}