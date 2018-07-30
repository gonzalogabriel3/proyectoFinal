<template>
<div>
    <!--div donde se va a contener el mapa-->
    <div style="width:80%; height:80%" id="mapa">

        <!-- MAPA -->>
    </div>

    <!--BOTONES PARA LAS ACCIONES-->
    <button class="btn btn-info">Mostrar Paradas</button>
    <button class="btn btn-success" @click="showModalRecorrido">Mostrar un Recorrido</button>

    <!--MODAL para seleccionar un recorrido-->
    <div class="modal show" id="anadir" v-if="modalRecorrido" tabindex="-1" role="dialog" aria-labelledby="anadir">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModalRecorrido" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Eliga un Recorrido</h4>
                    </div>
                    <div class="modal-body" >
                        <select v-model="recorrido">
                            <option disabled value="">--Seleccione un recorrido--</option>
                            <option v-for="recorrido in recorridos" :key="recorrido.id" v-bind:value="recorrido.id">{{recorrido.nombre}}</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" @click="closeModalRecorrido" v-if="recorrido">Mostrar recorrido</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <!--FIN DEL MODAL para seleccionar un recorrido-->

</div>
</template>
<script>
export default {
    data(){
        return {
            modalRecorrido:false,
            recorrido:'',
            paradas: [],
            recorridos:[],
            puntosRecorrido:[],
        }
    },
    mounted()
    {
        this.Leer();
        this.GenerarMapa2();
    },
    methods:{
        Leer(){

            //Cargo paradas
            axios.get("/parada").then(response => {
                this.paradas = response.data.paradas;
            });
            //Cargo recorridos
            axios.get("/recorrido").then(response => {
                this.recorridos = response.data.recorridos;
            });
            //Cargo recorridos
            axios.get("/mapa").then(response => {
                this.puntosRecorrido = response.data.puntos;
            });
        },
        GenerarMapa2(){
                //Creo un array con las coordenadas de rawson,tambien indico el sistema de coordenadas(EPGS)a usar
    var coordenadas_rawson=ol.proj.transform([-65.1056655,-43.2991348], 'EPSG:4326', 'EPSG:3857');

   
   /*
   La creación de una nueva instancia "ol.Map"(mapa) requiere que el usuario especifique un objeto con las siguientes propiedades:
    -target el elemento HTML de destino, donde se representará el mapa.
    -layers una o más referencias de la capa con los datos que se mostrarán(en este caso la fuente es Open Street Map).
    -view una instancia ol.View es responsable de gestionar la forma de visualizar el mapa.
   */
   var mapa = new ol.Map({ 
        layers: [ 
            new ol.layer.Tile({ 
                source: new ol.source.OSM()
            }) 
        ], 
        target: 'mapa', 
        view: new ol.View({ 
            center:coordenadas_rawson,
            zoom: 13
        }) 
    });
   
/*---AGREGAR UN MARCADOR AL MAPA---*/

var marcadores=[];//Creo un array donde van a ir todos los marcadores que quiero agregar al mapa

//Creo una nueva geometria donde defino las coordenadas,sistema de coordenadas,etc.
var iconFeature = new ol.Feature({
  geometry: new ol.geom.Point(ol.proj.transform([-65.0521686,-43.3081713], 'EPSG:4326','EPSG:3857')),
  name: 'Parada entrada 3 de abril',  
});

//Añado la geometria creada(punto)  al array de marcadores
marcadores.push(iconFeature);

//Fuentes
var vectorSource = new ol.source.Vector({
  features: marcadores //add an array of features
});

//Defino el estilo del marcador
var iconStyle = new ol.style.Style({
  image: new ol.style.Icon(({
    opacity: 0.75,
    src: 'http://static.websguru.com.ar/var/m_a/a7/a78/32892/522624-icono-bus.png',
  }))
});

/*Creo una capa vectorial,indicando la fuente y el estilo para aplicar a dicha capa,
que posteriormente se agregara al mapa*/
var vectorLayer = new ol.layer.Vector({
  source: vectorSource,
  style: iconStyle
});

//Añado la capa vectorial al mapa
mapa.addLayer(vectorLayer);

/*---FIN AGREGAR MARCADOR---*/
/*--- INICIO LINESTRING ---*/
    //Creo un Vector de Tipo LineString y le paso las Coordenadas
    var layerLines = new ol.layer.Vector({
        source: new ol.source.Vector({
            features: [new ol.Feature({
                geometry: new ol.geom.LineString(this.puntosRecorrido),
                name: 'Line'
            })]
        }),
    });

//añado la capa del Linestring al mapa
  mapa.addLayer(layerLines);
  
  
  /*---- FIN DE LINESTRING ----*/
        },
        //MODALS funciones
        showModalRecorrido(){
            this.modalRecorrido=true;
        },
        closeModalRecorrido(){
            this.modalRecorrido=false;
        }
    }
}   
</script>