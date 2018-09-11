<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Usuario;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;

class UsuarioController extends Controller
{
    
    public function __construct()
    {
        //Aplico el middleware a todos los metodos del controlador menos al index
        $this->middleware('auth')->except(['index','show','normalizarPosicion']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Obtengo todos los usuarios de la DB y los devuelvo en formato json
        $usuarios=Usuario::orderBy('id','DESC')->get();
        return response()->json([
            'usuarios' => $usuarios,
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
            'nombre'=> 'required',
            'email' => 'required',
            'password'=>'required'
        ]);
        $usuario = new Usuario;
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        
        //Realizo hash de la contraseña ingresada por el usuario
        $pass=password_hash('{{$request->password}}',PASSWORD_DEFAULT)."\n";
       
        $usuario->password = $pass;
        
        $usuario->save();
        
        return response()->json([
            'usuario'    => $usuario,
            'message' => 'Usuario Creado Correctamente'
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
        $usuario=Usuario::find($id);

        $usuario->latitud=$usuario->ultima_posicion->getLat();
        $usuario->longitud=$usuario->ultima_posicion->getLng();
        
        return response()->json([
            'usuario' => $usuario,
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
    public function update(Request $request,Usuario $usuario)
    {
        $this->validate($request, [
            'nombre'=> 'required',
            'email' => 'required',
            'password'=>'required'
        ]);
        $usuario->nombre = $request->get('nombre');
        $usuario->email = $request->get('email');
        //Realizo hash de la contraseña ingresada por el usuario
        $pass=password_hash('{{$request->password}}',PASSWORD_DEFAULT)."\n";
        $usuario->password = $pass;
        $usuario->save();
 
        return response()->json([
            'message' => 'Usuario Actualizado Correctamente'
        ], 200);
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->json([
            'message' => 'Colectivo eliminado Correctamente'
        ], 200);
    }

    public function guardarPosicion($id,$latitud,$longitud){
        
        $usuario=Usuario::find($id);
       
        $usuario->ultima_posicion= new Point( $latitud , $longitud );

        $usuario->save();

        
        
        return response()->json([
            'message' => 'Ultima posicion guardada'
        ], 200);
    }

    //Funcion que normaliza la posicion de un usuario,lo acerca a la esquina mas cercana
    public function normalizarPosicion($idUsuario)
    {
        
        $usuario=Usuario::find($idUsuario);
        //Obtengo todas las esquinas/vertices
        $vertices=\DB::select("SELECT *,st_x(the_geom) as latitud ,st_y(the_geom) as longitud FROM public.calles_rawson_vertices_pgr ORDER BY id ASC");
        
        //Defino la menor distancia a 5
        $menorDistancia=5;
        $id_vertice=0;

        foreach ($vertices as $vertice) {
           //Obtengo la distancia entre la posicion del usuario y un vertice
           $distanciaAvertice=\DB::select("SELECT ST_Distance('POINT($usuario->ultima_posicion)','POINT($vertice->latitud $vertice->longitud)')as distancia ORDER BY distancia DESC");
           $distanciaAvertice=(float)$distanciaAvertice[0]->distancia;
           /*Si la distancia es menor a la menor distancia hasta el momento*/
           if($distanciaAvertice<$menorDistancia){
               //Guardo la menor distancia a ese vertice,y a su vez guardo el id del vertice
               $menorDistancia=$distanciaAvertice;
               $id_vertice=$vertice->id;
           }           
          
        }

        if($id_vertice!=0){
            //Obtengo los datos del vertice mas cercano al usuario
            $verticeCercano=\DB::select("SELECT *,ST_x(the_geom) as latitud,ST_y(the_geom) as longitud FROM calles_rawson_vertices_pgr WHERE id=$id_vertice");
            
            //Guardo la nueva posicion del usuario
            $usuario->ultima_posicion= new Point((float)$verticeCercano[0]->longitud,(float)$verticeCercano[0]->latitud);
            
            $usuario->save();

            return response()->json([
                'message' => 'Posicion normalizada al vertice mas cercano',
                'id_vertice'=>$id_vertice
            ], 200);
        }else{
            return response()->json([
                'message' => 'No se encontro un vertice cercano a la posicion del usuario'
            ], 200);
        }    
            
    }
    public function RecorridoValido($idUsuario,$idTramo)
    {
        /*
        obtener un usuario 
        calcular la parada mas cercana al usuario
        obtener la posicion del colectivo dentro de ese recorrido
        obtener las vertices de esas posiciones
        calcular paso a paso de esas vertices
        obtener todos los nodos resultantes 
        armar linestring 
        enviarlo al frontend
        */

        //Obtengo los datos del usuario
        $usuario = Usuario::find($idUsuario);

        //Obtengo todas las paradas
        $paradas = \DB::select("SELECT *,st_x(geom::geometry) as longitud , st_y(geom::geometry) as latitud FROM paradas");

        //Obtengo los colectivos
        $calcularcolectivos = \DB::select("SELECT colectivos.* FROM tramos INNER JOIN colectivo_tramo ON colectivo_tramo.tramo_id = tramos.id INNER JOIN colectivos ON colectivo_tramo.colectivo_id = colectivos.id WHERE tramos.id = $idTramo AND colectivos.id = 68");
        $colectivo = $calcularcolectivos[0];

        //Obtengo todos los vertices
        $vertices = \DB::select("SELECT *,st_x(the_geom::geometry) as longitud , st_y(the_geom::geometry) as latitud FROM calles_rawson_vertices_pgr ORDER BY id DESC");

        
        //obtengo el vertice perteneciente a la posicion del colectivo del colectivo
        for ($i=0; $i < count($vertices) ; $i++) { 
            //creo una variable que va a contener el vertice que este en la posicion i
            $pvertice = $vertices[$i];
 
            //comparo la posicion del colectivo con el del vertice para saber si son iguales
            $Vcolectivo = \DB::select("SELECT ST_Equals(punto1.ultima_posicion::geometry, punto2.the_geom) FROM colectivos punto1, calles_rawson_vertices_pgr punto2 WHERE punto2.id=$pvertice->id AND punto1.id=$colectivo->id;");
 
            //si el resultado de la consulta es true guardo ese vertice para crear el recorrido
            if ($Vcolectivo[0] == true) {
                $verticeColectivo = $pvertice;
            }
        }

        //Defino una distancia y una variable que guarda los datos de la parada mas cercana a un usuario
        $distmascercana = 1000;
        $paradamascerca = null;
        
        //Comparo todas las distancias de las paradas y guardo la mas cercana al usuario
        for ($i=0; $i < count($paradas); $i++) {
            $parada = new Point($paradas[$i]->latitud,$paradas[$i]->longitud);
            $calculo =  \DB::select("SELECT ST_Distance('POINT($usuario->ultima_posicion)','POINT($parada)') as distancia");
            if($calculo[0]->distancia < $distmascercana){
                $distmascercana = $calculo;
                $paradamascerca = $parada;
            }
        }

        //defino una distancia y una variable que obtiene el vertice mas cercano a la parada definida como mas cercana al usuario
        $distancia2 = 1000;
        $verticemascerca = null;

        //Comparo todas las distancias de los vertices y guardo el id del mas cercano a la parada
        for ($i=0; $i < count($vertices); $i++) {
            $vertice2 = new Point($vertices[$i]->latitud,$vertices[$i]->longitud);
            $calculo2 =  \DB::select("SELECT ST_Distance('POINT($paradamascerca)','POINT($vertice2)') as distancia");
            if($calculo2[0]->distancia < $distancia2){
                $distancia2 = $calculo2;
                $verticemascerca = $vertices[$i];
            }
        }

        //Armo el recorrido manhattan de la posicion del colectivo a la parada mas cercana al usuario
        $recorrido =\DB::select("SELECT node, edge, cost, agg_cost FROM pgr_dijkstra('SELECT gid as id, source, target, st_length(geom) as cost FROM calles_rawson',$verticeColectivo->id,$verticemascerca->id,FALSE)");
       
        //creo un arreglo vacio
        $puntos = array();
        
        //recorro y obtengo todas las posiciones de los vertices que compongan el recorrido y creo un nuevo LineString
        for ($i=0 ; $i < count($recorrido) ; $i++ ) { 
            $puntor = $recorrido[$i];
            $punto = \DB::select("SELECT st_x(the_geom::geometry) as longitud , st_y(the_geom::geometry) as latitud FROM calles_rawson_vertices_pgr WHERE id = $puntor->node");
            array_push($puntos, new Point ($punto->latitud,$punto->longitud));
        }

        //Envio ese linestring al Frontend
        return response()->json([
            'puntos' => $puntos,
            'message' => 'Se calculo la distancia manhattan correctamente'
        ], 200);


    }
}