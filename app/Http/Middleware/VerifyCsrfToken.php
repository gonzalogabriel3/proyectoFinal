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

        //Urls de servidor local
        'http://1d6c93dd.ngrok.io/usuario',
        'http://b449c634.ngrok.io/posicionUsuario/*'
        
    ];
}
