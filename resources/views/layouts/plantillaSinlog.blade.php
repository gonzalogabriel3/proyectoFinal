<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Proyecto Final Colectivos</title>

        <!-- Latest compiled and minified CSS Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        
    </head>
    <body class="container">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                <a class="navbar-brand" href="/">Proyecto</a>
                </div>
               <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> Ingresar</a></li>
                <li><a href="{{route('usuarios.create')}}"><span class="glyphicon glyphicon-log-in"></span>Registrarse</a></li>
                </ul>
            </div>
        </nav>
        <!--Mensaje de notificacion-->
        @if($mensaje=Session::get('mensaje'))
            <div class="alert alert-success alert-dismissable fade in">
                <img src="https://4.bp.blogspot.com/-1WP9BY0dr2Y/Wk5_bmdAB_I/AAAAAAAAA1A/B_aiXhBDWpkqWT2IfriwttxHqO6BVHA7QCPcBGAYYCw/s1600/afimative.png" height=20px/>
                <b>{{$mensaje}}</b>
            </div>
        @endif
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif
            
            @yield("content")

               
        </div>
        <!-- Latest compiled and minified JavaScript Bootstrap-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>