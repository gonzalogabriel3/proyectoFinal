<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ejemplo de MapBox</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--CND de mapbox-->
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.46.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.46.0/mapbox-gl.css' rel='stylesheet'/>

</head>
<body>

<!--div donde se va a contener el mapa-->
<div id='map' style='width: 1300px; height: 600px;'></div>

<script>
//Agrego token para que se pueda generar el mapa
mapboxgl.accessToken = 'pk.eyJ1IjoiZ29uemFsbzAzIiwiYSI6ImNqamhvOWhyZTBiYmozdm44bXA1dXBycmcifQ.n0buLt-Sz-y6YpctafDxTg';

//indico las coordenada en rawson
var coordenadas_rawson=[-65.1056655,-43.2991348]

//Creo el mapa
var mapa = new mapboxgl.Map({
    container: 'map', //indico el contenedor donde se va a generar el mapa
    style: 'mapbox://styles/mapbox/streets-v9', //indico el estilo del mapa
    zoom: 12,//indico el zoom inicial en el mapa
    center:coordenadas_rawson,//indico en que coordenadas se va a centrar el mapa
});

</script>

</body>
</html>