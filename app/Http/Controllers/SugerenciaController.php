<?php

namespace App\Http\Controllers;

use App\Sugerencia;
use Illuminate\Http\Request;

class SugerenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sugerencias = Sugerencia::all();

        return response()->json([
            'sugerencias'    => $sugerencias
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
            'sugerencia' => 'required|min:4|max:255',
            'id' => 'required'
        ]);
        
        $sugerencia =  new Sugerencia();
        $sugerencia->sugerencia = $request->sugerencia;
        $sugerencia->usuario_id = $request->id;
        $sugerencia->save();

        return response()->json([
            'message' => 'Sugerencia Registrada Correctamente'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sugerencia  $sugerencia
     * @return \Illuminate\Http\Response
     */
    public function show(Sugerencia $sugerencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sugerencia  $sugerencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Sugerencia $sugerencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sugerencia  $sugerencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if($request->estado){

            $this->validate($request, [
                'estado' => 'required',
            ]);
            
            $sugerencia = Sugerencia::find($id)->first();
            $sugerencia->estado = $request->estado;
            $sugerencia->save();
            return response()->json([
                'message' => 'Estado Actualizado Correctamente'
            ], 200);
        } else {

            $this->validate($request, [
                'sugerencia' => 'required',
            ]);
            
            $sugerencia = Sugerencia::find($id)->first();
            $sugerencia->sugerencia = $request->sugerencia;
            $sugerencia->save();
    
            return response()->json([
                'message' => 'Estado Actualizado Correctamente'
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sugerencia  $sugerencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sugerencia $sugerencia)
    {
        //
        
        $sugerencia->delete();
        
        return response()->json([
            'message' => 'Sugerencia eliminada Correctamente'
        ], 200);
    }
}
