<?php

namespace App\Http\Controllers;

use App\Tramo;
use App\Parada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;


class TramoController extends Controller
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

        $tramos = \DB::select("SELECT nombre,recorrido_id, st_x(inicio::geometry) as iniciola, st_y(inicio::geometry) as iniciolo , st_x(fin::geometry) as finla, st_y(fin::geometry) as finlo FROM tramos ORDER BY id DESC");
       
        return response()->json([
            'tramos' => $tramos,
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
            'nombre' => 'required|min:3|max:100',
            'inicio' => 'required',
            'fin' => 'required',
            'recorrido_id' => 'required',
            'colectivos' => 'required'
        ]);

        $tramo = new tramo;
        
        $tramo->nombre = $request->nombre;
        
        $ini = Parada::find($request->inicio)->first();
        $tramo->inicio = $ini->geom;
        
        $fin = Parada::find($request->fin)->first();
        $tramo->fin = $fin->geom;
        
        $tramo->recorrido_id = $request->recorrido_id;
        
        if($tramo->save()){
        $tramo->colectivos()->attach($request->colectivos);
        }
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
    public function update(Request $request, Tramo $tramo)
    {
        //
        $this->validate($request, [
            'empresa' => 'required|min:3|max:100',
            'numero' => 'required'
        ]);

        $tramo->nombre = $request->nombre;
        $ini = \Parada::find($request->inicio)->first();
        $tramo->inicio = $ini->geom;
        $fin = \Parada::find($request->fin)->first();
        $tramo->recorrido_id = $request->recorrido_id;
        
        if($tramo->save()){
        $tramo->colectivos()->sync($request->colectivos);
        }
    
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
    public function destroy(Tramo $tramo)
    {
        //
        if($tramo->delete()){
            $tramo->colectivos()->dettach();
        }

        return response()->json([
            'message' => 'Colectivo eliminado Correctamente'
        ], 200);
    }
}
