<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;


class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    
    protected $except = [
        //
        'http://dondeestaelcole.ddns.net:8080/usuario',
        'http://dondeestaelcole.ddns.net:8080/posicionUsuario',
        'http://dondeestaelcole.ddns.net:8080/logusuario',
        'http://dondeestaelcole.ddns.net:8080/logusuarioclose',
        'http://dondeestaelcole.ddns.net:8080/pasajero',
        'http://dondeestaelcole.ddns.net:8080/usuario/*',

        //Urls de servidor local  
        'http://6a1863aa.ngrok.io/usuario',
        'http://6a1863aa.ngrok.io/posicionUsuario',
        'http://6a1863aa.ngrok.io/logusuario',
        'http://6a1863aa.ngrok.io/logusuarioclose',
        'http://6a1863aa.ngrok.io/pasajero',
        'http://6a1863aa.ngrok.io/usuario/*',
        
    ];
}
