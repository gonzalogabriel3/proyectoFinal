<template>
<div>
    <!--div donde se va a contener el MAPA-->
    <div id="mapid" style="width: 1200px; height: 500px;"></div>

    <!--BOTONES PARA LAS ACCIONES-->
    <button class="btn btn-info" @click="agregarMarcador">Mostrar Paradas</button>
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
            paradas: [],
            recorridos:[],
            puntosRecorrido:[],
            mapa:null,//Variable donde se va a contener el mapa principal
        }
    },
    mounted()
    {
        this.Leer();
        this.GenerarMapa();
    },
    methods:{
        GenerarMapa(){
            /*Genero el mapa*/
            var coordenadas_rawson=[-43.2991348,-65.1056655];
		    this.mapa = L.map('mapid').setView(coordenadas_rawson,12);

            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                maxZoom: 18,
                id: 'mapbox.streets'
            }).addTo(this.mapa);
            
        },
        BuscarRecorrido(id){
            this.puntosRecorrido=[];
            //Cargo los puntos del recorrido seleccionado
            axios.get("/mapa/"+this.recorrido_identificador).then(response => {
                this.puntosRecorrido = response.data.puntos;
            });
            this.closeModalRecorrido();
            //this.puntosRecorrido=[[-43.3397844,-65.0585075],[-43.3398908,-65.0577351],[-43.3397519,-65.0571293],[-43.3396336,-65.0568447],[-43.3394532,-65.0565236],[-43.3391457,-65.0560845],[-43.3388914,-65.0557105],[-43.3386519,-65.055369],[-43.3383267,-65.0549136],[-43.3380812,-65.0545843],[-43.337895,-65.0543811],[-43.3378095,-65.0543046],[-43.3376646,-65.0542326],[-43.3375127,-65.0541774],[-43.3372386,-65.0540742],[-43.3370936,-65.0540526],[-43.3368771,-65.0540093],[-43.3367549,-65.0539877],[-43.3365506,-65.0539493],[-43.3363393,-65.0538773],[-43.3361997,-65.0538293],[-43.3360154,-65.0537681],[-43.3359212,-65.0537297],[-43.3357771,-65.0536817],[-43.3356304,-65.0536336],[-43.3354776,-65.0535808],[-43.335379,-65.053546],[-43.3352637,-65.053504],[-43.3351127,-65.0534488],[-43.3349407,-65.053384],[-43.3347853,-65.0533312],[-43.3346299,-65.0532759],[-43.3344789,-65.0532267],[-43.3343357,-65.0531583],[-43.334155,-65.0530923],[-43.3340781,-65.0530563],[-43.3339777,-65.0530215],[-43.3338948,-65.0529795],[-43.3337891,-65.0529603]];
            this.AgregarLineString();
        },
        Leer(){
            //Cargo paradas
            axios.get("/parada").then(response => {
                this.paradas = response.data.paradas;
            });
            //Cargo recorridos
            axios.get("/recorrido").then(response => {
                this.recorridos = response.data.recorridos;
            });
            //Cargo un recorrido
            axios.get("/mapa").then(response => {
                this.puntosRecorrido = response.data.puntos;
            });
        },
        agregarMarcador(){
            var marker = L.marker([-43.3081713,-65.0521686]).addTo(this.mapa).bindPopup('<b>Usted esta aqui</b>').openPopup();
        },
        AgregarLineString(){
           var polyline = L.polyline(this.puntosRecorrido, {color: 'blue'}).addTo(this.mapa);
        
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