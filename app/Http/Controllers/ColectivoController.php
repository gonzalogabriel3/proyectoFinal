<?php

namespace App\Http\Controllers;

use App\Colectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColectivoController extends Controller
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
        $colectivos = Colectivo::all();

        return response()->json([
            'colectivos'    => $colectivos,
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
            'tramo' => 'required',
        ]);

        $colectivo = new Colectivo;
        $colectivo->tramo = $request->tramo;
        $colectivo->tarifa_id = 1;
        $colectivo->horario_id = 1;
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
        //
        $this->validate($request, [
            'tramo' => 'required|max:255',
        ]);
 
        $colectivo->tramo = $request->get('tramo');
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
}
