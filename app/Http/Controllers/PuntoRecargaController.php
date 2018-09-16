<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PuntoRecarga;
use App\Usuario;
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
    
    /*Metodo que calcula los puntos de recarga mas cercanos al usuario*/
    public function obtenerPuntosCercanos($idUsuario){
        //Busco al usuario para trabajar con su ultima posicion
        $usuario=Usuario::find($idUsuario);
        
        //Obtengo todas las paradas
        $puntos=PuntoRecarga::all();

        //Creo una variable donde se va a contener todos los id's,de las paradas mas cercanas al usuario
        $ids_puntos="";
        
        //Recorro todas las paradas
        foreach ($puntos as $punto) {
            
            //Obtengo el radio entre la posicion del usuario y una parada
            $radio=\DB::select("SELECT ST_DWithin(usuario.ultima_posicion,punto.geom,900) FROM usuarios usuario,puntos_recarga punto WHERE
            usuario.id=$usuario->id AND punto.id=$punto->id");
            
            //Si el radio es menor a 900 metros se agrega el id de la parada para mostrar
            if($radio[0]->st_dwithin==true){
                //Agrego el id de la parada 
                $ids_puntos=$ids_puntos.$punto->id.",";
            }
        }
        
        //Si se encontro alguna parada cercana al usuario
        if($ids_puntos!=''){
            //Quito la ultima coma para evitar error al momento de ejecutar la consulta
            $ids_puntos=trim($ids_puntos,',');
            
            //Obtengo todas las paradas
            $puntosCercanos = \DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM puntos_recarga WHERE id IN($ids_puntos) ORDER BY id DESC");
           
            //Las retorno al cliente
            return response()->json([
                'puntos' => $puntosCercanos,
            ], 200);
        }else{
            return response()->json([
                'message' => "No hay Puntos de recarga cercanos a tu posicion"
            ], 200);
        }
    } 

}
