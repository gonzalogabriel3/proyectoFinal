<?php

namespace App\Http\Controllers;

use App\Tramo;
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

        $tramos = \DB::select("SELECT id,nombre,recorrido_id, st_x(inicio::geometry) as iniciola, st_y(inicio::geometry) as iniciolo , st_x(fin::geometry) as finla, st_y(fin::geometry) as finlo FROM tramos ORDER BY id DESC");
        
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

        $tramo = new Tramo();
        
        $tramo->nombre = $request->nombre;

        $tramo->recorrido_id = $request->recorrido_id;
        
        $ini = \DB::select("SELECT st_x(geom::geometry) as longitud, st_y(geom::geometry) as latitud FROM paradas WHERE id = $request->inicio");
        $tramo->inicio = new Point( $ini[0]->latitud , $ini[0]->longitud );
        
        $fin = \DB::select("SELECT st_x(geom::geometry) as longitud, st_y(geom::geometry) as latitud FROM paradas WHERE id = $request->fin");
        $tramo->fin = new Point ( $fin[0]->latitud , $fin[0]->longitud );
        
        $tramo->save();

        $tramo->colectivos()->attach($request->colectivos);
        
        return response()->json([
            'tramo'    => $tramo,
            'message' => 'Colectivo Creado Correctamente'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tramo $tramo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {                
        //
        $colectivos = \DB::select("SELECT colectivos.empresa,colectivos.num_coche FROM colectivos 
        INNER JOIN colectivo_tramo ON colectivo_tramo.colectivo_id = colectivos.id 
        INNER JOIN tramos ON colectivo_tramo.tramo_id = tramos.id 
        WHERE tramos.id = $id ");                
        
        return response()->json([
            'colectivos'    => $colectivos,
        ], 200);
            
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tramo $tramo
     * @return \Illuminate\Http\Response
     */
    public function edit(Tramo $tramo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tramo $tramo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tramo $tramo)
    {
        //
        $this->validate($request, [
            'nombre' => 'required|min:3|max:100',
            'inicio' => 'required',
            'fin' => 'required',
            'recorrido_id' => 'required',
            'colectivos' => 'required'
        ]);

        $tramo->nombre = $request->nombre;

        $tramo->recorrido_id = $request->recorrido_id;
        
        $ini = \DB::select("SELECT st_x(geom::geometry) as longitud, st_y(geom::geometry) as latitud FROM paradas WHERE id = $request->inicio");
        $tramo->inicio = new Point( $ini[0]->latitud , $ini[0]->longitud );
        
        $fin = \DB::select("SELECT st_x(geom::geometry) as longitud, st_y(geom::geometry) as latitud FROM paradas WHERE id = $request->fin");
        $tramo->fin = new Point ( $fin[0]->latitud , $fin[0]->longitud );
        
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
     * @param  \App\Tramo $tramo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tramo $tramo)
    {
        //
        if($tramo->delete()){
            $tramo->colectivos()->detach();
        }

        return response()->json([
            'message' => 'Colectivo eliminado Correctamente'
        ], 200);
    }
}
