<?php

namespace App\Http\Controllers;

use App\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HorarioController extends Controller
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

        $horarios = \DB::select("SELECT *, salida || '/' || llegada as horas from horarios ORDER BY id ASC");
        
        return response()->json([
            'horarios' => $horarios,
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
            'salida' => 'required|min:3',
            'llegada' => 'required|min:3',
        ]);

        $horario = new Horario;
        $horario->salida = $request->salida;
        $horario->llegada = $request->llegada;
        $horario->save();


        return response()->json([
            'horario'    => $horario,
            'message' => 'Horario Creado Correctamente'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Horario $horario)
    {
        //
        $this->validate($request, [
            'salida' => 'required|max:255|min:3',
            'llegada' => 'required|max:255|min:3'
        ]);
 
        $horario->salida = $request->salida;
        $horario->llegada = $request->llegada;
        $horario->save();
 
        return response()->json([
            'message' => 'Horario Actualizado Correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horario $horario)
    {
        //
        $horario->delete();
        return response()->json([
            'message' => 'Horario eliminado Correctamente'
        ], 200);
    }
}
