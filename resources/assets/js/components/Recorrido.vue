<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                 <!--MENSAJE de notificacion-->
                    <div v-if="mensaje!=''" style="border-bottom: solid green 2px">
                        <button type="button" class="close" @click="cerrarMensaje" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                        <b><p style="color:green"><small>{{mensaje}}</small>&nbsp;<span class="glyphicon glyphicon-ok"></span></p></b>
                    </div>
                    <br> 
                <!--Fin del panel de mensaje de notificacion-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button @click="iniciarRegistro()" class="btn btn-primary btn-xs pull-right">
                            + Crear Nuevo Recorrido
                        </button>
                        <h4 style="color:#6DD5B7">Recorridos Registrados</h4>
                    </div>
                    
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="recorridos.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Puntos
                                </th>
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="recorrido in recorridos" :key="recorrido.id">
                                <td>{{ recorrido.id }}</td>
                                <td>
                                    {{ recorrido.nombre }}
                                </td>
                                <td>
                                    {{ recorrido.geom.coordinates}}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(recorrido)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="Eliminar(recorrido.id)" class="btn btn-danger btn-xs">Eliminar</button>
                                    <button @click="iniciarMostrar(recorrido.id)" class="btn btn-info btn-xs">Mostrar Paradas</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal show" id="anadir" v-if="create" tabindex="-1" role="dialog" aria-labelledby="anadir">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeCreate" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Registrar Recorrido</h4>
                    </div>
                    <div class="modal-body" >
                        <div class="form-group">
                            <label for="name">Nombre del recorrido:</label>
                            <br>
                            <span v-if="recorrido.nombre.trim().length<3" class="label label-danger" >El nombre del recorrido debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del recorrido" class="form-control"
                                   v-model="recorrido.nombre">
                        </div>
                        <div id="Punto" style="overflow: scroll; width: 550px; height: 250px;">
                                        <label for="new-todo">Puntos agregados</label>
                                        <ul v-for="punto in puntos" :key="punto.id">
                                            <li>
                                                {{punto.id}}: {{punto.latitud}} // {{punto.longitud}}
                                            </li>
                                        </ul>
                                        <br>
                                           
                                        <label for="nuevo punto">Latitud</label><br>   
                                        <input v-model="nuevoPunto.latitud" type="number" name="latitud">
                                        <span v-if="nuevoPunto.latitud.trim().length<5" class="label label-danger">Debe ingresar una latitud valida</span>
                                        <span v-else class="label label-success">Correcto!</span>
​                                            <br>
                                        <label for="nuevo punto">Longitud</label><br>                 
                                        <input v-model="nuevoPunto.longitud" type="number" name="longitud">
                                        <span v-if="nuevoPunto.longitud.trim().length<5" class="label label-danger">Debe ingresar una latitud valida</span>
                                        <span v-else class="label label-success">Correcto!</span>
                                        <br><br>

                                        <button type="submit" @click="crearPunto" class="btn btn-primary">agregar punto</button>
                                        
                        </div>
                        <div>
                            <p>Eliga las paradas que contiene el recorrido</p>
                            <span v-if="recorrido.paradas.length<2" class="label label-danger" >El recorrido debe contener al menos 2 paradas</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                             <select v-model="recorrido.paradas" multiple>
                                <option v-for="parada in paradas" :key="parada.id" v-bind:value="parada.id">{{parada.nombre}}</option>
                             </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeCreate">Cerrar</button>
                        <button class="btn btn-success" @click="Crear" v-if="puntos.length>2">Crear recorrido</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal show" id="actualizar" v-if="update" tabindex="-1" role="dialog" aria-labelledby="actualizar">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeUpdate" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Actualizar recorrido</h4>
                    </div>
                    <div class="modal-body" style="overflow: scroll; width: 550; height: 400px;">
                        <div class="form-group">
                            <label for="name">Nombre del recorrido:</label>
                            <br>
                            <span v-if="actualizar.nombre.trim().length<3" class="label label-danger" >El nombre del recorrido debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del recorrido" class="form-control"
                                   v-model="actualizar.nombre">
                        </div>
                        <div class="form-group" >
                            <label for="puntosCargados">Puntos Cargados:</label>
                            <br>
                            <p>{{actualizar.coordinates}}</p>                                
                            <br>
                        </div>
                        <div id="Punto" style="overflow: scroll; width: 550px; height: 250px;">
                            <label for="new-todo">Puntos agregados</label>
                            <ul v-for="punto in puntos" :key="punto.id">
                                <li>
                                    {{punto.id}}: {{punto.latitud}} // {{punto.longitud}}
                                </li>
                            </ul>
                            <br>
                                
                            <label for="nuevo punto">Latitud</label><br>   
                            <input v-model="nuevoPunto.latitud" type="number" name="latitud">
                            <span v-if="nuevoPunto.latitud.trim().length<5" class="label label-danger">Debe ingresar una latitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
    ​                                            <br>
                            <label for="nuevo punto">Longitud</label><br>                 
                            <input v-model="nuevoPunto.longitud" type="number" name="longitud">
                            <span v-if="nuevoPunto.longitud.trim().length<5" class="label label-danger">Debe ingresar una latitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br><br>

                            <button type="submit" @click="crearPunto" class="btn btn-primary">agregar punto</button>
                        </div>
                        <div>
                            <p>Eliga las paradas que contiene el recorrido</p>
                            <span v-if="!actualizar.paradas" class="label label-danger">Debe Seleccionar una parada Por lo menos</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                             <select v-model="actualizar.paradas" multiple>
                                <option v-for="parada in paradas" :key="parada.id" v-bind:value="parada.id">{{parada.nombre}}</option>
                             </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeUpdate">Cerrar</button>
                        <button type="button" @click="Actualizar" class="btn btn-primary">Actualizar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
        <!--MODAL PARA MOSTRAR PARADAS-->
        <div class="modal show" id="mostrar" v-if="mostrar" tabindex="-1" role="dialog" aria-labelledby="mostrar">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeMostrar" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Paradas que Contiene el Recorrido</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new-todo">Paradas</label>
                            <ul v-for="parada in paradasr" :key="parada.id">
                                <li>
                                    {{parada.id}}: {{parada.nombre}}
                                </li>
                            </ul>
                            <br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeMostrar">Cerrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->    
    </div></template>

<script>
export default {
    data(){
        return {
            recorrido: {
                nombre: '',
                puntos:[],
                paradas:[],
                
            },
            paradasr:[],
            paradas:[],
            puntos:[],
            mensaje:'',
            nuevoPunto:{
                latitud:'',
                longitud:''
            },
            mostrar: false,
            create: false,
            update: false,
            recorridos: [],
            actualizar: {
                nombre: '',
                coordinates:'',
                puntos:[],
                paradas:[],
            },
        }
    },
    mounted()
    {
        this.Leer();
        this.obtenerParadas();

    },
    methods: {
        ParadasRecorrido(id){
            axios.get("/recorrido/" + id).then(response => {
            this.paradasr = response.data.paradas;
            });
        },
        obtenerParadas(){
            axios.get("/parada").then(response => {
            this.paradas = response.data.paradas;
            });
        },
        crearPunto(){
                    if(this.nuevoPunto.latitud.trim().length>4 && this.nuevoPunto.longitud.trim().length>4){
                            this.puntos.push(this.nuevoPunto);
                            this.nuevoPunto = {latitud:'',longitud:''};
                    }    
        },
        validarRegistro(){ //validacion del formulario de registro
            if(this.recorrido.nombre.trim().length<3 ||this.puntos.length<2 || this.recorrido.paradas.length<2){
                return false;
            }else{
                return true;
            }
        },
        validarActualizacion(){ //validacion del formulario de actualizacion
            if(this.actualizar.nombre.trim().length<3 || this.puntos.length<2 || !this.recorrido.paradas){
                return false;
            }else{
                return true;
            }
        },
        iniciarRegistro()
        {
           this.create = true;
        },
        closeCreate(){
            this.create = false;
        },
        iniciarMostrar(id)
        {
           this.ParadasRecorrido(id);
           this.mostrar = true;
        
        },
        closeMostrar(){
            this.mostrar = false;
        },
        cerrarMensaje(){
                this.mensaje='';
        },
        Crear() {
            if(this.validarRegistro()){
                this.recorrido.puntos=this.puntos;            
                axios.post('/recorrido', {
                    nombre: this.recorrido.nombre,
                    puntos: this.recorrido.puntos,
                    paradas: this.recorrido.paradas,
                })
                .then(response => {
                    
                    this.mensaje="Recorrido creado correctamente";
                    this.puntos = [];
                    this.reset();
                    this.create = false;
                    this.Leer();
                });
            }else{
                return;
            }
        },
        reset() {
            this.recorrido.nombre = '';
            this.recorrido.puntos = [];
        },
        resetActualizar() {
            this.actualizar.nombre = '';
            this.actualizar.puntos = [];
            this.actualizar.paradas=[];
        },
        Leer() {
            axios.get("/recorrido").then(response => {
            this.recorridos = response.data.recorridos;
            });
        },
        IniciarActualizacion(recorrido) {
            this.actualizar.id = recorrido.id;
            this.actualizar.nombre = recorrido.nombre;
            this.actualizar.coordinates = recorrido.geom.coordinates; 
            //this.actualizar.paradas=recorrido.paradas;
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            if(this.validarActualizacion()){
                this.actualizar.puntos=this.puntos;
                axios.patch("/recorrido/" + this.actualizar.id, {
                    nombre: this.actualizar.nombre,
                    puntos: this.actualizar.puntos,
                    paradas: this.actualizar.paradas
                })
                .then(response => {
                    this.mensaje="Datos de recorrido actualizados";
                    this.puntos = [];
                    this.resetActualizar();
                    this.update = false;
                    this.Leer();
                });
            }else{
                return;
            }    
        },
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar esta Recorrido?");
            if (conf) {
            axios({
                method: "delete",
                url: "/recorrido/" + id
            })
            .then(response => {
                this.mensaje="Recorrido eliminado";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>