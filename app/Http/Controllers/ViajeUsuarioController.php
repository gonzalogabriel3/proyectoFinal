<?php

namespace App\Http\Controllers;

use App\ViajeUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Phaza\LaravelPostgis\Geometries\Point;

class ViajeUsuarioController extends Controller
{
    /*
     * Metodo que Filtra las Funciones segun si se esta Logueado con el Rol  de Admin
    
    */
    public function __construct()
    {
        //Aplico el middleware a todos los metodos del controlador menos al index
        $this->middleware('auth')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $viajes = \DB::select("SELECT *,st_x(punto_inicio::geometry) as longinicio , st_y(punto_inicio::geometry) as latinicio,
                               st_x(punto_fin::geometry) as longfin , st_y(punto_fin::geometry) as latfin FROM viajes ORDER BY id DESC");
        return response()->json([
            'viajes' => $viajes,
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
        /*
         * Se validan los datos del request
         * Se traen todos los vertices de las calles
         * se crea el punto de inicio y fin
         * se hace un for comparando los puntos con los vertices para buscar el mas cercano
         * se cambia la posicion de los puntos por el de los vertices y se procede a guardar en la bd 
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ViajeUsuario  $viajeUsuario
     * @return \Illuminate\Http\Response
     */
    public function show(ViajeUsuario $viajeUsuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ViajeUsuario  $viajeUsuario
     * @return \Illuminate\Http\Response
     */
    public function edit(ViajeUsuario $viajeUsuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ViajeUsuario  $viajeUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ViajeUsuario $viajeUsuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ViajeUsuario  $viajeUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(ViajeUsuario $viajeUsuario)
    {
        //
    }
}
