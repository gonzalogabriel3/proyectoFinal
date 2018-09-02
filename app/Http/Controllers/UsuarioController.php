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
    public function normalizarPosicion($idUsuario){
        
        $usuario=Usuario::find($idUsuario);
        //Obtengo todas las esquinas/vertices
        $vertices=\DB::select("SELECT *,st_x(the_geom) as latitud ,st_y(the_geom) as longitud FROM public.calles_rawson_vertices_pgr ORDER BY id ASC");
        
        $menorDistancia=0.0;

        foreach ($vertices as $vertice) {
           //Obtengo la distancia entre la posicion del usuario y un vertice
           $distanciaAvertice=\DB::select("SELECT ST_Distance('POINT($usuario->ultima_posicion)','POINT($vertice->latitud $vertice->longitud)')as distancia ORDER BY distancia DESC");
           $distanciaAvertice=(float)$distanciaAvertice[0]->distancia;
           /*Si la distancia es menor a la menor distancia hasta el momento,
           y esa distancia no es 0(es decir que no se encuentra en el mismo lugar)*/
           if($distanciaAvertice>$menorDistancia){
               $menorDistancia=$distanciaAvertice;
               
           }
           var_dump($menorDistancia);
          
        }
        
    }
}