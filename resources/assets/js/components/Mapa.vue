<template>
<div>
    <!--div donde se va a contener el mapa-->
    <div id="mapid" style="width: 1000px; height: 400px;">
    <!-- Contenedor del Mapa -->
    </div>
    <!--BOTONES PARA LAS ACCIONES-->
    <button class="btn btn-warning" @click="showModalParadasCercanas">Mostrar Paradas mas cercanas a un usuario</button>
    <button class="btn btn-warning" @click="mostrarParadas">Mostrar paradas</button>
    <button class="btn btn-danger" @click="showModalRecorrido">Mostrar un Recorrido</button>
    <button class="btn btn-info" @click="mostrarPuntosRecarga">Mostrar puntos de recarga</button>
    <button class="btn btn-info" @click="showModalPuntosCercanos">Mostrar puntos de recarga cercanos a un usuario</button>
    <button class="btn btn-success" @click="showModalUsuario">Mostrar ultima posicion de un usuario</button>
    <button class="btn btn-success" @click="showModalTramo">Mostrar Colectivo</button>
    

    <!--MODAL para seleccionar un recorrido-->
    <div class="modal show" id="anadir" v-if="modalRecorrido" tabindex="-1" role="dialog" aria-labelledby="anadir">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModalRecorrido" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Elija un Recorrido</h4>
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
    
    <!--MODAL para seleccionar un usuario-->
    <div class="modal show" id="anadir" v-if="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="anadir">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModalUsuario" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Elija un Usuario</h4>
                    </div>
                    <div class="modal-body" >
                        <select v-model="usuario_id">
                            <option disabled value="">--Seleccione un usuario--</option>
                            <option v-for="usuario in usuarios" :key="usuario.id" v-bind:value="usuario.id">{{usuario.nombre}}/{{usuario.id}}</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" @click="MostrarPosicionUsuario()" v-if="usuario_id">Mostrar posicion usuario</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <!--FIN DEL MODAL para seleccionar un recorrido-->

     <!--MODAL para seleccionar un usuario,para mostrar sus paradas cercanas-->
    <div class="modal show" id="anadir" v-if="modalParadasCercanas" tabindex="-1" role="dialog" aria-labelledby="anadir">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModalParadasCercanas" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Elija un Usuario para mostrar sus paradas mas cercanas</h4>
                    </div>
                    <div class="modal-body" >
                        <select v-model="usuario_id">
                            <option disabled value="">--Seleccione un usuario--</option>
                            <option v-for="usuario in usuarios" :key="usuario.id" v-bind:value="usuario.id">{{usuario.nombre}}</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" @click="mostrarParadasCercanas()" v-if="usuario_id">Mostrar posicion usuario</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <!--FIN DEL MODAL para seleccionar un recorrido-->

    <!--MODAL para seleccionar un usuario para mostrar sus puntos de recarga cercanos-->
    <div class="modal show" id="anadir" v-if="modalPuntosCercanos" tabindex="-1" role="dialog" aria-labelledby="anadir">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModalPuntosCercanos" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Elija un Usuario para mostrar sus puntos de recarga mas cercanos</h4>
                    </div>
                    <div class="modal-body" >
                        <select v-model="usuario_id">
                            <option disabled value="">--Seleccione un usuario--</option>
                            <option v-for="usuario in usuarios" :key="usuario.id" v-bind:value="usuario.id">{{usuario.nombre}}</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" @click="mostrarPuntosCercanos()" v-if="usuario_id">Mostrar puntos cercanos</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <!--FIN MODAL para seleccionar un usuario para mostrar sus puntos de recarga cercanos-->


    <!--MODAL para seleccionar un tramo-->
    <div class="modal show" id="anadir" v-if="modalTramo" tabindex="-1" role="dialog" aria-labelledby="anadir">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModalTramo" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Elija un Tramo</h4>
                    </div>
                    <div class="modal-body" >
                        <select v-model="tramo_id">
                            <option disabled value="">--Seleccione un Tramo--</option>
                            <option v-for="tramo in tramos" :key="tramo.id" v-bind:value="tramo.id">{{tramo.nombre}}</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" @click="mostrarColectivo()" v-if="tramo_id">Mostrar posicion Colectivo</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <!--FIN DEL MODAL para seleccionar un tramo-->

    
</div>
</template>
<script>
export default {
    data(){
        return {
            modalPuntosCercanos:false,
            modalRecorrido:false,
            modalUsuario:false,
            modalTramo:false,
            modalParadasCercanas:false,
            recorrido_identificador:'',
            recorridos:[],
            usuarios:[],
            usuario_id:'',
            colectivo:'',
            colectivoi:[],
            paradas:[],
            puntosRecarga:[],
            tramos:[],
            tramo_id:'',
            puntosRecorrido:null,
            /**Variables para el dibujado en leaflet**/
            mapa:[],//Variable donde se va a contener el mapa principal
            polyline:[],//Variable que va a contener el linestring(puntos) de un recorrido
            polyline2:[],//variable que va a contener el recorrido armado con manhattan
            usuario:[],//Variable que va a contener el icono(marker/punto) de un usuario
            usuario_latitud:'',//Variable que va a contener la latitud de un usuario
            usuario_longitud:'',//Variable que va a contener la longitud de un usuario

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
        MostrarPosicionUsuario(){
            //Si ya hay un icono cargado de un usuario en el mapa lo elimino
            if(this.mapa.hasLayer(this.usuario)){
                this.mapa.removeLayer(this.usuario);
            }
            
            //Creo el icono para dibujar al usuario en el mapa
            var iconoUsuario = L.icon({
                iconUrl: 'usuario.png',
                iconSize: [45, 45] // size of the icon
            });

            //Obtengo el arreglo con las coordenadas pasadas
            axios.get("/usuario/"+this.usuario_id).then(response => {
                //Obtengo las coordenadas de la utima posicion de un usuario
                var coordenadas=[response.data.usuario.latitud,response.data.usuario.longitud];
                
                //Dibujo las coordenadas del usuario en el mapa
                this.usuario = L.marker(coordenadas,{icon: iconoUsuario}).addTo(this.mapa).bindPopup('<b>Usted esta aqui</b>').openPopup();
                //Centro el mapa en la ubicacion del usuario
                this.mapa.setView(coordenadas,16);
                this.closeModalUsuario();
            });               
        },
        BuscarRecorrido(){
            
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
            //Cargo los usuarios
            axios.get("/usuario").then(response => {
                this.usuarios = response.data.usuarios;
            });

            axios.get("/tramo").then(response => {
                this.tramos = response.data.tramos;
            });
        },
        mostrarParadas(){
            var i;
            //Creo el icono para dibujar al usuario en el mapa
            var iconoParada = L.icon({
                iconUrl: 'parada.png',
                iconSize: [35, 35] // size of the icon
            });
            //Cargo las paradas y las muestro en el mapa
            axios.get("/parada").then(response => {
                this.paradas = response.data.paradas;
                for (i = 0; i < this.paradas.length; i++) { 
                var marker = L.marker([this.paradas[i].latitud,this.paradas[i].longitud],{icon: iconoParada}).addTo(this.mapa);
                    /*Funcion que muestra el nombre de una parada cuando se pasa el mouse*/
                    marker.bindPopup(this.paradas[i].nombre);
                        marker.on('mouseover', function (e) {
                            this.openPopup();
                        });
                        marker.on('mouseout', function (e) {
                            this.closePopup();
                    });
                }
                
            });
        },
        mostrarParadasCercanas(){
            var i;
            //Creo el icono para dibujar al usuario en el mapa
            var iconoParada = L.icon({
                iconUrl: 'parada.png',
                iconSize: [35, 35] // size of the icon
            });
            //Cargo las paradas y las muestro en el mapa
            axios.get("/paradasCercanas/"+this.usuario_id).then(response => {
                this.paradas = response.data.paradas;
                //Si se encontro al menos una parada cercana
                if(this.paradas.length>0){
                    for (i = 0; i < this.paradas.length; i++) { 
                    var marker = L.marker([this.paradas[i].latitud,this.paradas[i].longitud],{icon: iconoParada}).addTo(this.mapa);
                        /*Funcion que muestra el nombre de una parada cuando se pasa el mouse*/
                        marker.bindPopup(this.paradas[i].nombre);
                            marker.on('mouseover', function (e) {
                                this.openPopup();
                            });
                            marker.on('mouseout', function (e) {
                                this.closePopup();
                            });                        
                    }    
                }
                this.closeModalParadasCercanas();
                
            });
            //Si no se encontro ninguna parada,unicamente cierro el modal
            this.closeModalParadasCercanas();         
        },
        mostrarColectivo(){
             //Si ya hay un icono cargado de un usuario en el mapa lo elimino
            if(this.mapa.hasLayer(this.colectivoi)){
                this.mapa.removeLayer(this.colectivoi);
            }

            //Creo el icono para dibujar al usuario en el mapa
            var iconoColectivo = L.icon({
                iconUrl: 'colectivo.png',
                iconSize: [55, 55] // size of the icon
            });
            //Cargo las paradas y las muestro en el mapa
            axios.get("/posicionColectivo/" + this.tramo_id).then(response => {

                this.colectivo = response.data.colectivo;
                this.colectivoi = L.marker([this.colectivo.latitud,this.colectivo.longitud],{icon: iconoColectivo}).addTo(this.mapa);
                this.closeModalTramo();
            });         
        },
        mostrarPuntosRecarga(){
            var i;
            //Creo el icono para dibujar al usuario en el mapa
            var iconoPuntoRecarga = L.icon({
                iconUrl: 'sube.png',
                iconSize: [50, 50] // size of the icon
            });
            //Cargo los puntos de recarga y los muestro en el mapa
            axios.get("/punto").then(response => {
                this.puntosRecarga = response.data.puntos;
                for (i = 0; i < this.puntosRecarga.length; i++) { 
                var marker = L.marker([this.puntosRecarga[i].latitud,this.puntosRecarga[i].longitud],{icon: iconoPuntoRecarga}).addTo(this.mapa);
                    /*Funcion que muestra el nombre de una parada cuando se pasa el mouse*/
                    marker.bindPopup(this.puntosRecarga[i].nombre);
                        marker.on('mouseover', function (e) {
                            this.openPopup();
                        });
                        marker.on('mouseout', function (e) {
                            this.closePopup();
                        });
                }
            });         
        },
        mostrarPuntosCercanos(){
            var i;
            //Creo el icono para dibujar al usuario en el mapa
            var iconoPuntoRecarga = L.icon({
                iconUrl: 'sube.png',
                iconSize: [50, 50] // size of the icon
            });
            //Cargo las paradas y las muestro en el mapa
            axios.get("/puntosCercanos/"+this.usuario_id).then(response => {
                this.puntosRecarga = response.data.puntos;
                //Si se encontro al menos una parada cercana
                if(this.puntosRecarga.length>0){
                for (i = 0; i < this.puntosRecarga.length; i++) { 
                var marker = L.marker([this.puntosRecarga[i].latitud,this.puntosRecarga[i].longitud],{icon: iconoPuntoRecarga}).addTo(this.mapa);
                    /*Funcion que muestra el nombre de una parada cuando se pasa el mouse*/
                    marker.bindPopup(this.puntosRecarga[i].nombre);
                        marker.on('mouseover', function (e) {
                            this.openPopup();
                        });
                        marker.on('mouseout', function (e) {
                            this.closePopup();
                        });
                }    
                }
                this.closeModalPuntosCercanos();
                
            });
            //Si no se encontro ninguna parada,unicamente cierro el modal
            this.closeModalPuntosCercanos();         
        },
        //MODALS funciones
        showModalPuntosCercanos(){
            this.modalPuntosCercanos=true;
        },
        
        closeModalPuntosCercanos(){
            this.modalPuntosCercanos=false;
        },
        showModalRecorrido(){
            this.modalRecorrido=true;
        },
        closeModalRecorrido(){
            this.modalRecorrido=false;
        },
        showModalTramo(){
            this.modalTramo=true;
        },
        closeModalTramo(){
            this.modalTramo=false;
        },
        showModalUsuario(){
            this.modalUsuario=true;
        },
        closeModalUsuario(){
            this.modalUsuario=false;
        },
        showModalParadasCercanas(){
            this.modalParadasCercanas=true;
        },
        closeModalParadasCercanas(){
            this.modalParadasCercanas=false;
        }
    }
}   
</script>