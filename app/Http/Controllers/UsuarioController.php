<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;

class UsuarioController extends Controller
{
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
}
