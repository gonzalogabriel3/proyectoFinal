<?php

namespace App\Http\Controllers;

use App\Recorrido;
use Illuminate\Http\Request;
use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;


class RecorridoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function index()
    {
       $recorridos=Recorrido::orderBy('id','DESC')->get();

        return response()->json([
            'recorridos' => $recorridos
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
        $puntos=array();

       $cantElementos = sizeof($request->puntos);
       
       for($i = 0 ; $i <$cantElementos; $i++) {
           //Añado al final del arreglo $puntos,el nuevo punto   
          array_push($puntos,new Point( $request->input("puntos.".$i.".latitud") , $request->input("puntos.".$i.".longitud")));
            
        }     
            
        $recorrido->geom=new Linestring($puntos);
               
        $recorrido->save();

        //Inserto el registro en la tabla intermedia
        $recorrido->paradas()->attach($request->paradas);
        
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
        $paradas = \DB::select("SELECT paradas.nombre,paradas.id FROM paradas 
        INNER JOIN parada_recorrido ON parada_recorrido.parada_id = paradas.id 
        INNER JOIN recorridos ON parada_recorrido.recorrido_id = recorridos.id 
        WHERE recorridos.id = $id ");                
        
        return response()->json([
        'paradas'    => $paradas,
        ], 200);
    
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
    public function update(Request $request, Recorrido $recorrido)
    {
        $this->validate($request, [
            'nombre' => 'required|min:3|max:100',
            'puntos' => 'required'
        ]);
        

        $recorrido->nombre=$request->nombre;
        if($request->puntos) {
        $puntos=array();

        $cantElementos = sizeof($request->puntos);
       
        for($i = 0 ; $i <$cantElementos; $i++) {
            //Añado al final del arreglo $puntos,el nuevo punto   
            array_push($puntos,new Point( $request->input("puntos.".$i.".latitud") , $request->input("puntos.".$i.".longitud")));
                
        }     
            
        $recorrido->geom=new Linestring($puntos);        
        }
        $recorrido->save();
        $recorrido->paradas()->sync($request->paradas);

        
        return response()->json([
            'recorrido' => $recorrido,
            'message' => 'Recorrido Creada Correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recorrido $recorrido)
    {
        $recorrido->delete();
        $recorrido->paradas()->detach();
        return response()->json([
            'message' => 'Recorrido eliminado Correctamente'
        ], 200);
    }

}
