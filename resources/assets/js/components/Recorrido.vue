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
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del recorrido:</label>
                            <br>
                            <span v-if="recorrido.nombre.trim().length<3" class="label label-danger" >El nombre del recorrido debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del recorrido" class="form-control"
                                   v-model="recorrido.nombre">
                        </div>
                        <div id="Punto">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeCreate">Cerrar</button>
                        <button class="btn btn-success" v-if="puntos.length>2">Crear recorrido</button>
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
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del recorrido:</label>
                            <br>
                            <span v-if="actualizar.nombre.trim().length<3" class="label label-danger" >El nombre del recorrido debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del recorrido" class="form-control"
                                   v-model="actualizar.nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud de la parada:</label>
                            <br>
                            <span v-if="actualizar.latitud.trim().length<5" class="label label-danger" >El nombre de la parada debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="latitud" id="latitud" placeholder="Latitud de la Parada" class="form-control"
                                   v-model="actualizar.latitud">
                        </div>
                        <div class="form-group">
                            <label for="name">Longitud de la parada:</label>
                            <br>
                            <span v-if="actualizar.longitud.trim().length<5" class="label label-danger" >El nombre de la parada debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="longitud" id="longitud" placeholder="Longitud de la Parada" class="form-control"
                                   v-model="actualizar.longitud">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeUpdate">Cerrar</button>
                        <button type="button" @click="Actualizar" class="btn btn-primary">Actualizar</button>
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
                puntos:[]
            },
            puntos:[],
            mensaje:'',
            nuevoPunto:{
                latitud:'',
                longitud:''
            },
            create: false,
            update: false,
            recorridos: [],
            actualizar: {
                nombre: '',
                puntos:[]
            },
        }
    },
    mounted()
    {
        this.Leer();
    },
    methods: {
        crearPunto(){
                    if(this.nuevoPunto.latitud.trim().length>4 && this.nuevoPunto.longitud.trim().length>4){
                            this.puntos.push(this.nuevoPunto);
                            this.nuevoPunto = {latitud:'',longitud:''};
                    }    
        },
        validarRegistro(){ //validacion del formulario de registro
            if(this.recorrido.nombre.trim().length<3 ||this.puntos.length>2){
                return false;
            }else{
                return true;
            }
        },
        validarActualizacion(){ //validacion del formulario de actualizacion
            if(this.actualizar.nombre.trim().length<3 || this.actualizar.latitud.trim().length<5 ||this.actualizar.longitud.trim().length<5){
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
        cerrarMensaje(){
                this.mensaje='';
        },
        Crear() {
            if(this.validarRegistro()){
                this.recorrido.puntos=this.puntos;            
                axios.post('/recorrido', {
                    nombre: this.recorrido.nombre,
                    puntos: this.recorrido.puntos
                })
                .then(response => {
                    
                    this.mensaje="Recorrido creado correctamente";
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
            this.recorrido.latitud = '';
            this.recorrido.longitud = '';
        },
        resetActualizar() {
            this.actualizar.nombre = '';
            this.actualizar.latitud = '';
            this.actualizar.longitud = '';
        },
        Leer() {
            axios.get("/recorrido").then(response => {
            this.recorridos = response.data.recorridos;
            });
        },
        IniciarActualizacion(recorrido) {
            this.actualizar.id = recorrido.id;
            this.actualizar.nombre = recorrido.nombre;
            this.actualizar.latitud = recorrido.latitud;
            this.actualizar.longitud = recorrido.longitud;
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            if(this.validarActualizacion()){
                axios.patch("/recorrido/" + this.actualizar.id, {
                    nombre: this.actualizar.nombre,
                    latitud: this.actualizar.latitud,
                    longitud: this.actualizar.longitud
                })
                .then(response => {
                    this.mensaje="Datos de recorrido actualizados";
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
                this.mensaje="Recorrido eliminada";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>