<template>
<div>
    <!--div donde se va a contener el mapa-->
    <div id="mapid" style="width: 1000px; height: 400px;">
    <!-- Contenedor del Mapa -->
    </div>
    <!--BOTONES PARA LAS ACCIONES-->
    <button class="btn btn-info" @click="mostrarParadas">Mostrar Paradas</button>
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
                        <select v-model="recorrido_identificador">
                            <option disabled value="">--Seleccione un recorrido--</option>
                            <option v-for="recorrido in recorridos" :key="recorrido.id" v-bind:value="recorrido.id">{{recorrido.nombre}}</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" @click="BuscarRecorrido()" v-if="recorrido_identificador">Mostrar recorrido</button>
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
            recorrido_identificador:'',
            recorridos:[],
            paradas:[],
            puntosRecorrido:null,
            mapa:[],//Variable donde se va a contener el mapa principal
            polyline:[],//Variable que va a contener el linestring(puntos) de un recorrido
            
        }
    },
    mounted()
    {
        this.Leer();
        this.mapa=this.GenerarMapa();
    },
    methods:{
        GenerarMapa(){
            /*Genero el mapa*/
            var mymap = L.map('mapid').setView([-43.2991348,-65.1056655], 13);

            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets',
            updateWhenIdle: true,
            reuseTiles: true

            }).addTo(mymap);
                return mymap;
        },
        BuscarRecorrido(id){
            
            /*Si ya hay un linestring(recorrido) cargado en el mapa,lo elimino*/ 
            if(this.mapa.hasLayer(this.polyline)){
                this.mapa.removeLayer(this.polyline);
            }
            //Cargo los puntos del recorrido seleccionado
             axios.get("/mapa/"+this.recorrido_identificador).then(response => {
                this.puntosRecorrido = response.data.puntos;
                //Genero el linestring a partir de los puntos recibidos
                this.polyline = L.polyline(this.puntosRecorrido, {color: 'red'}).addTo(this.mapa);
                
                this.closeModalRecorrido();
            });

        },
        Leer(){
            //Cargo los recorridos para que el usuario pueda seleccionar uno
            axios.get("/recorrido").then(response => {
                this.recorridos = response.data.recorridos;
            });
        },
        mostrarParadas(){
            var i;
            //Cargo las paradas y las muestro en el mapa
            axios.get("/parada").then(response => {
                this.paradas = response.data.paradas;
                for (i = 0; i < this.paradas.length; i++) { 
                var marker = L.marker([this.paradas[i].latitud,this.paradas[i].longitud]).addTo(this.mapa);
                }
            });         
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