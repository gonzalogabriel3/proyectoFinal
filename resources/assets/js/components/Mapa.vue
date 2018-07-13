<template>
<div>
    <!--div donde se va a contener el mapa-->
    <div id='map' style='width: 1100px; height: 500px;'>
            
            <!--MAPA-->       
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
            recorridos:[]
        }
    },
    mounted()
    {
        this.Leer();
        this.GenerarMapa();
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
        },
        GenerarMapa(){
            //Agrego token para que se pueda generar el mapa
            mapboxgl.accessToken = 'pk.eyJ1IjoiZ29uemFsbzAzIiwiYSI6ImNqamhvOWhyZTBiYmozdm44bXA1dXBycmcifQ.n0buLt-Sz-y6YpctafDxTg';

            //indico las coordenada en rawson
            var coordenadas_rawson=[-65.1056655,-43.2991348];
            
            //Creo el mapa
            var mapa = new mapboxgl.Map({
                container: 'map', //indico el contenedor donde se va a generar el mapa
                style: 'mapbox://styles/mapbox/streets-v9', //indico el estilo del mapa
                zoom: 12,//indico el zoom inicial en el mapa
                center:coordenadas_rawson,//indico en que coordenadas se va a centrar el mapa
                showZoom: true
            });
         
            //Agrego un marcador al mapa
            var marcador=new mapboxgl.Marker({
                color:'#312BED',
            })
            .setLngLat([-65.044885,-43.3182255])
            .addTo(mapa);
                
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