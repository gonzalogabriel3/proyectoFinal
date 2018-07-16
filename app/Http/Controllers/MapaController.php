<?php

namespace App\Http\Controllers;

use App\Recorrido;
use Illuminate\Http\Request;

class MapaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $recorridos = Recorrido::find(1);
        //dd($recorridos);
        $original_data = json_decode($recorridos, true);

        $features = array();

            $features[] = array(
                    'id' => $recorridos->nombre,
                    'type' => 'line',
                    'source' => [
                        'type' => 'geojson',
                        'data' => [
                        'type' => 'feature',
                        'properties' => [],
                        'geometry' => array('type' => 'LineString', 'coordinates' => $recorridos->geom),
                        
                    ]
                    ]
                    );
                    dd(json_encode($features, JSON_PRETTY_PRINT));
        return response()->json([
            'recorridos' => json_encode($features, JSON_PRETTY_PRINT)
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
