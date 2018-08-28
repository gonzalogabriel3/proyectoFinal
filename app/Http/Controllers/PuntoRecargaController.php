<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PuntoRecarga;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;


class PuntoRecargaController extends Controller
{
    public function __construct()
    {
        //Aplico el middleware a todos los metodos del controlador menos al index
        $this->middleware('auth')->except(['index']);
    }
    
    public function index()
    {
        $puntos = \DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM puntos_recarga ORDER BY id DESC");
        return response()->json([
            'puntos' => $puntos,
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
            'nombre' => 'required',
            'latitud' => 'required',
            'longitud' => 'required'
        ]);

        $punto = new PuntoRecarga();
        $punto->nombre = $request->nombre;
        
        $punto->geom = new Point( $request->latitud , $request ->longitud );

        $punto->save();
        
        return response()->json([
            'punto'    => $punto,
            'message' => 'Punto de recarga Creado Correctamente'
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
    public function update(Request $request, PuntoRecarga $punto)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'latitud' => 'required',
            'longitud' => 'required'
        ]);

        $punto->nombre = $request->nombre;
        $punto->geom = new Point( $request->latitud , $request ->longitud );

        $punto->save();
    
        return response()->json([
            'message' => 'Punto de recarga actualizado Correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PuntoRecarga $punto )
    {
        $punto->delete();
        return response()->json([
            'message' => 'Punto eliminado Correctamente'
        ], 200);
    }


}
