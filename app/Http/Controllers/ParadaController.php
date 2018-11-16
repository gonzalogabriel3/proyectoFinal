<?php

namespace App\Http\Controllers;

use App\Parada;
use App\Usuario;
use App\Colectivo;
use App\Recorrido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;

class ParadaController extends Controller
{
    
    public function __construct()
    {
        //Aplico el middleware a todos los metodos del controlador menos al index
        $this->middleware('auth')->except(['index','show','obtenerParadasCercanas']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paradas = \DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM paradas ORDER BY id DESC");
        return response()->json([
            'paradas' => $paradas,
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

        $parada = new Parada();
        $parada->nombre = $request->nombre;
        $parada->cubierta=$request->cubierta;
        $parada->iluminada=$request->iluminada;

        $parada->geom = new Point( $request->latitud , $request ->longitud );

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
        $parada = \DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM paradas WHERE id = $parada->id");
        return response()->json([
            'parada' => $parada,
        ], 200);
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
        $parada->cubierta=$request->cubierta;
        $parada->iluminada=$request->iluminada;
        $parada->geom = new Point( $request->latitud , $request ->longitud );


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

    /*Metodo que calcula las paradas mas cercanas al usuario*/
    public function obtenerParadasCercanas($idUsuario,$idRecorrido){
        //Busco al usuario para trabajar con su ultima posicion
        $usuario=Usuario::find($idUsuario);
        
        $recorrido = Recorrido::find($idRecorrido); 
                
        $paradas= array();

        //Obtengo todas las paradas
        foreach($recorrido->paradas as $p)
        {
            array_push($paradas,$p->id);
        }        
        
        
        //Creo una variable donde se va a contener todos los id's,de las paradas mas cercanas al usuario
        $ids_paradas="";
        
        //Recorro todas las paradas
        foreach ($paradas as $parada) {
            //Obtengo el radio entre la posicion del usuario y una parada
            $radio=\DB::select("SELECT ST_DWithin(usuario.ultima_posicion,parada.geom,900) FROM usuarios usuario,paradas parada WHERE
            usuario.id=$usuario->id AND parada.id=$parada");
            
            //Si el radio es menor a 900 metros se agrega el id de la parada para mostrar
            if($radio[0]->st_dwithin==true){
                //Agrego el id de la parada 
                $ids_paradas=$ids_paradas.$parada.",";
            }
        }
        
        //Si se encontro alguna parada cercana al usuario
        if($ids_paradas!=''){
            //Quito la ultima coma para evitar error al momento de ejecutar la consulta
            $ids_paradas=trim($ids_paradas,',');
            
            //Obtengo todas las paradas
            $paradasCercanas = \DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM paradas WHERE id IN($ids_paradas) ORDER BY id DESC");
           
            //Las retorno al cliente
            return response()->json([
                'paradas' => $paradasCercanas,
            ], 200);
        }else{
            return response()->json([
                'message' => "No hay paradas cercanas a tu posicion"
            ], 200);
        }
        
        

    } 
}
