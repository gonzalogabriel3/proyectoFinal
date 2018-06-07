<?php

namespace App\Http\Controllers;

use App\Parada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParadaController extends Controller
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
        $paradas = Parada::all();

        return response()->json([
            'paradas'    => $paradas,
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

        $parada = new Parada;
        $parada->nombre = $request->nombre;
        $posicion = "";
        $parada->geom = $posicion;
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
        $posicion = "";
        $parada->geom = $posicion;
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
}
