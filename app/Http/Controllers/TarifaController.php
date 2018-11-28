<?php

namespace App\Http\Controllers;

use App\Tarifa;
use Illuminate\Http\Request;

class TarifaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tarifas = \DB::select("SELECT tarifas.id, tarifas.tramo_id, tramos.nombre as nombre ,recorridos.nombre as recorrido,tarifas.monto as monto FROM tarifas INNER JOIN tramos ON tarifas.tramo_id=tramos.id
        INNER JOIN recorridos ON tramos.recorrido_id=recorridos.id");

        return response()->json([
            'tarifas'    => $tarifas,
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
        $this->validate($request, [
            'monto' => 'required',
        ]);
        
        $tarifa=new Tarifa;
        $tarifa->monto=$request->monto;
        $tarifa->tramo_id=$request->tramo;
        $tarifa->save();


        return response()->json([
            'tarifa'    => $tarifa,
            'message' => 'Tarifa creada correctamente'
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarifa $tarifa)
    {
        $this->validate($request, [
            'monto' => 'required|max:255',
        ]);
        $tarifa->monto = $request->monto;
        $tarifa->tramo_id=$request->tramo;
        $tarifa->save();
 
        return response()->json([
            'message' => 'Tarifa Actualizado Correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarifa $tarifa)
    {
        $tarifa->delete();
        return response()->json([
            'message' => 'Tarifa eliminada Correctamente'
        ], 200);
    }
}
