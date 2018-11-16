<?php

namespace App\Http\Controllers;

use App\Colectivo;
use App\Tramo;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;

class ColectivoController extends Controller
{
    public function __construct()
    {
         //Aplico el middleware a todos los metodos del controlador menos al index
         $this->middleware('auth')->except(['index','obtenerPosicion']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $colectivos = Colectivo::all();
       
        return response()->json([
            'colectivos' => $colectivos,
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
            'empresa' => 'required|min:3|max:100',
            'numero' => 'required'
        ]);

        $colectivo = new Colectivo;
        $colectivo->empresa = $request->empresa;
        $colectivo->num_coche = $request->numero;
        $colectivo->save();

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
    public function update(Request $request, Colectivo $colectivo)
    {
        //
        $this->validate($request, [
            'empresa' => 'required|min:3|max:100',
            'numero' => 'required'
        ]);

        $colectivo->empresa = $request->empresa;
        $colectivo->num_coche = $request->numero;
        $colectivo->save();

    
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
    public function destroy(Colectivo $colectivo)
    {
        //
        $colectivo->delete();

        return response()->json([
            'message' => 'Colectivo eliminado Correctamente'
        ], 200);
    }
    /*
        Esta funcion es la que permite comparar las posiciones de los usuarios para definir 
        si al encontrarse dos o mas usuarios en el mismo lugar se tiene en cuenta como posicion del colectivo

    */
    public function obtenerPosicion($idTramo)
    {

        $usuarios = \DB::select("SELECT *,st_x(ultima_posicion::geometry) as longitud , st_y(ultima_posicion::geometry) as latitud FROM usuarios");
        $colectivot = \DB::select("SELECT colectivos.id 
                                   FROM tramos 
                                   INNER JOIN colectivo_tramo ON colectivo_tramo.tramo_id = tramos.id 
                                   INNER JOIN colectivos ON colectivo_tramo.colectivo_id = colectivos.id 
                                   WHERE tramos.id = $idTramo");
        $colectivosf = array(); 
        
        for($i = 0 ; $i < count($usuarios); $i++){
            $usu1 = $usuarios[$i];
            
            for($j = 0 ; $j < count($usuarios); $j++){
                if($i != $j){                    
                    $usu2 = $usuarios[$j];
                    
                    //hago la consulta que comprueba si dos geometrias o posicion son espacialmente iguales
                    $validador = \DB::select("SELECT ST_DWithin(pos1.ultima_posicion::geometry, pos2.ultima_posicion::geometry,3) FROM usuarios pos1, usuarios pos2 WHERE pos1.id=$usu1->id AND pos2.id=$usu2->id;");
                    if($validador[0]->st_dwithin == true){
                        $colectivos=array();
                        array_push($colectivos,$usuarios[$i]);
                        array_push($colectivos,$usuarios[$j]);
                        
                        array_push($colectivosf,$colectivos);
                    }
                } 
            } 
        }
        if(count($colectivos) > 1){
            $colectivo = Colectivo::find($colectivot[0]->id);
            
            $coincidencia = self::obtenerCoincidencia($colectivosf,$idTramo,$colectivo);

            if($coincidencia != ""){
                $consulta = \DB::select("SELECT st_x(ultima_posicion::geometry) as longitud , st_y(ultima_posicion::geometry) as latitud FROM colectivos WHERE id = $coincidencia->id");
                return response()->json([
                    'colectivo'    => $consulta[0],
                    'message' => 'La posicion de Colectivo se encontro correctamente'
                ], 200);
            }
        } 
        return response()->json([
            'message' => 'La posicion de Colectivo no se encontro'
        ], 200);

    }
    /*
        Esta funcion recibe el id del tramo buscado y un arreglo de posiciones que coinciden y las compara con el recorrido para saber si estan 
        dentro del mismo
    */
    public function obtenerCoincidencia(array $colectivos,$idTramo,$colectivo)
    {
        $tramo = Tramo::find($idTramo)->first();
        
        $coincidenciap = "";
        $distanciamascerca=1000;
        $coincidenciamascercana=null;
        $contador=0;
        $tiempo=null;
        $tiempo1=null;
        $tiempo2=null;

        for($i=0;$i < count($colectivos);$i++){
            
            $colectivov = $colectivos[$i];
            $usuario1 = $colectivov[0];
            $usuario2 = $colectivov[1];

            $validador=\DB::select("SELECT ST_DWithin(pos1.ultima_posicion::geometry, pos2.geom::geometry,10) FROM usuarios pos1, recorridos pos2 WHERE pos1.id=$usuario1->id AND pos2.id=$tramo->recorrido_id;");
            if($validador[0]->st_dwithin == true){
                //si la posicion del colectivo es desconocida se toma la coincidencia mas cercana al punto de inicio como la posicion del mismo
                if ($colectivo->ultima_posicion == null) {
                    for ($j=0; $j <count($colectivos) ; $j++) { 
                        
                        $dist = $colectivos[$j];
                        $distan = $dist[0];
                        $pdistan = new Point($distan->latitud,$distan->longitud);

                        $distancia = \DB::select("SELECT ST_Distance('POINT($tramo->inicio)','POINT($pdistan)') as distancia");
                        
                        if($distancia[0]->distancia < $distanciamascerca){
                        
                            $distanciamascerca = (float)$distancia[0]->distancia;
                            
                            $colectivo->ultima_posicion=new Point ($usuario1->latitud,$usuario1->longitud);
                            $colectivo->save();
                            
                        }

                    }
                    return $coincidenciap = $colectivo;
                }
                //Si existen usuarios marcados como pasajeros se toma su posicion como la del colectivo
                if ($colectivo->ultima_posicion !=null) {
                    switch ($contador){
                        case 0:
                            dd($usuario1);
                            $validador=\DB::select("SELECT ST_DWithin(pos1.ultima_posicion::geometry, pos2.ultima_posicion::geometry,10) FROM usuarios pos1, usuarios pos2 WHERE pos1.id=$usuario1->id AND pos2.id=$usuario2->id;");
                            if($validador[0]->st_dwithin == true){
                                $colectivo->ultima_posicion=new Point ($usuario1->latitud,$usuario1->longitud);
                                $colectivo->save();
                                $tiempo = new \DateTime();
                                $contador+=1;
                            }
                        break;
                        case 1:
                       
                            $validador=\DB::select("SELECT ST_DWithin(pos1.ultima_posicion::geometry, pos2.ultima_posicion::geometry,10) FROM usuarios pos1, usuarios pos2 WHERE pos1.id=$usuario1->id AND pos2.id=$usuario2->id;");
                            $tiempo1=new \DateTime();
                            $validarPosicion=\DB::select("SELECT ST_Equals(punto1.ultima_posicion::geometry, punto2.ultima_posicion::geometry) FROM usuarios punto1, colectivos punto2 WHERE punto1.id=$usuario1->id AND punto2.id=$colectivo->id;");
                            if($validador[0]->st_dwithin == true && $tiempo1>$tiempo && $validarPosicion[0]->st_equals!=true){
                                $colectivo->ultima_posicion=new Point ($usuario1->latitud,$usuario1->longitud);
                                $colectivo->save();
                                $contador+=1;
                            }
                        break;
                        case 2:
                        
                            $validador=\DB::select("SELECT ST_DWithin(pos1.ultima_posicion::geometry, pos2.ultima_posicion::geometry,10) FROM usuarios pos1, usuarios pos2 WHERE pos1.id=$usuario1->id AND pos2.id=$usuario2->id;");    
                            $tiempo2=new \DateTime();
                            $validarPosicion=\DB::select("SELECT ST_Equals(punto1.ultima_posicion::geometry, punto2.ultima_posicion::geometry) FROM usuarios punto1, colectivos punto2 WHERE punto1.id=$usuario1->id AND punto2.id=$colectivo->id;");
                            if($validador[0]->st_dwithin == true && $tiempo2>$tiempo1 && $validarPosicion[0]->st_equals!=true){
                            $colectivo->ultima_posicion=new Point ($usuario1->latitud,$usuario1->longitud);
                            $colectivo->save();
                            $contador=0;
                        }
                        break;
                    }
                    return $coincidenciap = $colectivo;
                }
            }
        }
        return $coincidenciap;
    }
}