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

        //Urls de servidor local  
        'http://ebb392dc.ngrok.io/usuario',
        'http://ebb392dc.ngrok.io/posicionUsuario'
        
    ];
}
