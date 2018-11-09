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
        

        //Urls de servidor local  
        'http://dd5cbfad.ngrok.io/usuario',
        'http://dd5cbfad.ngrok.io/posicionUsuario',
        'http://dd5cbfad.ngrok.io/logusuario',
        'http://dd5cbfad.ngrok.io/logusuarioclose',
        
    ];
}
