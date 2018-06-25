<?php

namespace App\Http\Controllers;

use App\Recorrido;
use Illuminate\Http\Request;
use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;


class RecorridoController extends Controller
{
   
    public function index()
    {
       $recorridos=Recorrido::all();
        
        return response()->json([
            'recorridos' => $recorridos,
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
            'puntos' => 'required'
        ]);
        

        $recorrido=new Recorrido;
        $recorrido->nombre=$request->nombre;
        
       
       $max = sizeof($request->puntos);
       for($i = 0 ; $i <= $max; $i++) {
        dd($request->input('puntos.latitud'));    
        $puntos = new Point( $request->input('puntos.$i.latitud') , $request->input('puntos.$i.longitud') );
            
        }     

        $recorrido->geom=new Linestring([$request->puntos->latitud,$request]);
        
        $recorrido->save();
        
        
        return response()->json([
            'recorrido'    => $recorrido,
            'message' => 'Recorrido Creada Correctamente'
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
