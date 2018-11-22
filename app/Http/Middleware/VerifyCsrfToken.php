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
        

        //Urls de servidor local  
        'http://ced078f2.ngrok.io/usuario',
        'http://ced078f2.ngrok.io/posicionUsuario',
        'http://ced078f2.ngrok.io/logusuario',
        'http://ced078f2.ngrok.io/logusuarioclose',
        'http://ced078f2.ngrok.io/pasajero',
        
    ];
}
