<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Usuario;
use App\Colectivo;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;

class UsuarioController extends Controller
{
    
    public function __construct()
    {
        //Aplico el middleware a todos los metodos del controlador menos al index
        $this->middleware('auth')->except(['index','show','logusuario','store','guardarPosicion','logusuarioclose','marcarPasajero','finalizarViaje']);
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
            'usuario' => 'required',
            'email' => 'required',
            'password'=>'required'
        ]);
        $usuario = new Usuario;
        $usuario->nombre = $request->nombre;
        $usuario->usuario = $request->usuario;
        $usuario->email = $request->email;
        
        //Realizo hash de la contraseña ingresada por el usuario
        $usuario->password = \Hash::make($request->password);        
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
            'usuario' => 'required',
            'email' => 'required',
            'password'=>'required'
        ]);
        $usuario->nombre = $request->get('nombre');
        $usuario->usuario = $request->get('usuario');
        $usuario->email = $request->get('email');
        //Realizo hash de la contraseña ingresada por el usuario
        $usuario->password = \Hash::make($request->get('password'));
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
    public function logusuario(Request $request){

        $usuario = \DB::select("SELECT id,email,nombre,usuario,st_x(ultima_posicion::geometry) as longitud,st_y(ultima_posicion::geometry) as latitud
        FROM usuarios WHERE usuario = '$request->usuario'");
        if($usuario != null ) {
            $usuariop = Usuario::find($usuario[0]->id);
            if(\Hash::check($request->password, $usuariop->password)) {
                $usuariop->logueado=true;
                $usuariop->save();
        
                return response()->json([
                    'logueado' => 'true',
                    'usuario' => $usuario
                ], 200);     
            } else  {
                return response()->json([
                    'message' => 'no se pudo iniciar sesion, ya que la contraseña no coincide'
                ], 401);
            }
        }

        return response()->json([
            'message' => 'no se pudo iniciar sesion, ya que no existe ese usuario'
        ], 401);

    }
    public function logusuarioclose (Request $request){

            $usuariop = Usuario::find($request->id);
            if($usuariop != null) {
                $usuariop->logueado=false;
                $usuariop->save();
        
                return response()->json([
                    'message' => 'Se cerro la sesion correctamente',
                ], 200);     
            } else  {
                return response()->json([
                    'message' => 'no se pudo  cerrar la sesion , intentelo nuevamente'
                ], 401);
            }

        return response()->json([
            'message' => 'no se pudo cerrar sesion, ya que no existe ese usuario'
        ], 401);

    }


    public function guardarPosicion(Request $request){

        $id = (string) $request->id;

        $latitud = (string) $request->latitud;
        $longitud = (string) $request->longitud;
    
        $usuario=Usuario::find($id);

        $usuario->ultima_posicion= new Point( $latitud , $longitud );
          
        $usuario->save();

        return response()->json([
            'message' => 'Ultima posicion guardada'
        ], 200);
        
    }

    //Funcion que recibe un id de usuario y un tramo y lo marca como pasajero de un colectivo en ese tramo
    public function marcarPasajero(Request $request)
    {
        $idUsuario = $request->id;
        
        $idTramo = $request->tramo;
        
        $usuariop = Usuario::find($idUsuario); 

        $recorridop = \DB::select("SELECT recorridos.id as id FROM tramos INNER JOIN recorridos ON recorridos.id = tramos.recorrido_id WHERE tramos.id=$idTramo");
        
        $recorrido = $recorridop[0]->id;
        
        $validador= \DB::select("SELECT ST_Intersects(u1.ultima_posicion, g1.geom ) FROM recorridos g1, usuarios u1 WHERE g1.id = $recorrido AND u1.id = $usuariop->id");
        
        if ($validador[0]->st_intersects = true) {

            $usuariop->tramo_id = $idTramo;
            $usuariop->pasajero = true;
            $usuariop->save();
            
            return response()->json([
                'message' => "el usuario se como pasajero de un colectivo correctamente"
            ], 200);

        } else {

            return response()->json([
                'error' => "el usuario no se pudo registrar como pasajero de un colectivo correctamente"
            ], 200);

        }
    }

    //funcion que recibe un id de usuario y con ello lo marca como finalizo su viaje
    public function finalizarViaje($idUsuario)
    {

        $usuario = Usuario::find($idUsuario);        
 
        $usuario->tramo_id = null;
        
        $usuario->pasajero = false;
        
        if($usuario->save()){
            return response()->json([
                'message' => "el viaje se marco como finalizado correctamente"
            ], 200);    
        } else {
            return response()->json([
                'error' => "el viaje no se pudo marcar como finalizado correctamente"
            ], 200);
    
        }
        
        
    }
    
}