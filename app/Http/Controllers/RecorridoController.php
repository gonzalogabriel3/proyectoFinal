<?php

namespace App\Http\Controllers;

use App\Recorrido;
use Illuminate\Http\Request;


class RecorridoController extends Controller
{
   
    //Funcion que quita el texto "LINESTRING",y los "{},()" devueltos en la consulta,se le pasa como parametro un linestring
    public function obtenerPuntos(array $recorrido){
        $puntos = explode(", [ ]", $recorrido);
        /*Quito el 'LineString' y los '{()}' devuelto en la consulta, para que unicamente queden los puntos*/
        //$puntos[0]=substr($puntos[0],23);
        //$puntos[count($puntos)-1]=substr($puntos[count($puntos)-1],0,-4);

        return $puntos;
    }
    
    public function index()
    {
       //$recorridos=\DB::select("SELECT *,ST_AsText(geom) as puntos FROM recorridos");
       $recorridos=Recorrido::all();
      
       /*
      foreach ($recorridos as $recorrido) {
            $recorrido->geom->coordinates=obtenerPuntos($recorrido->geom->coordinates);
        }
*/
        
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
        //
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
