<?php

namespace App\Http\Controllers;

use App\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comentarios=Comentario::orderBy('id','DESC')->get();

        return response()->json([
            'comentarios'  => $comentarios,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'comentario' => 'required',
            'usuario' => 'required',
        ]);

        $comentario=new Comentario;
        $comentario->usuario_id=$request->usuario;
        $comentario->comentario=$request->comentario;
        $comentario->save();

        return response()->json([
            'comentario'    => $comentario,
            'message' => 'Comentario creado correctamente'
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
    public function update(Request $request, Comentario $comentario)
    {
        $this->validate($request, [
            'comentario' => 'required',
            'usuario' => 'required'
        ]);
 
        $comentario->comentario = $request->comentario;
        $comentario->usuario_id=$request->usuario;
        $comentario->save();
 
        return response()->json([
            'message' => 'Comentario Actualizado Correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comentario $comentario)
    {
        $comentario->delete();
        return response()->json([
            'message' => 'Comentario eliminado Correctamente'
        ], 200);
    }
}
