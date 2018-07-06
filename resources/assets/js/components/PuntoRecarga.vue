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
                            + Crear Nuevo Punto de Recarga
                        </button>
                        <h4 style="color:#6DD5B7">Puntos de Recarga Registrados</h4>
                    </div>
                    
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="puntos.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Latitud
                                </th>
                                <th>
                                    Longitud
                                </th>
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="punto in puntos" :key="punto.id">
                                <td>{{ punto.id }}</td>
                                <td>
                                    {{ punto.nombre }}
                                </td>
                                <td>
                                    {{ punto.latitud }}
                                </td>
                                <td>
                                    {{ punto.longitud}}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(punto)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="Eliminar(punto.id)" class="btn btn-danger btn-xs">Eliminar</button>
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
                        <h4 class="modal-title">Registrar Punto de Recarga</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del punto de Recarga:</label>
                            <br>
                            <span v-if="punto.nombre.trim().length<3" class="label label-danger" >El nombre del punto debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre de la punto" class="form-control"
                                   v-model="punto.nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud del punto de recarga:</label>
                            <br>
                            <span v-if="punto.latitud.trim().length<5" class="label label-danger">Debe ingresar una latitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <input type="number" name="latitud" id="latitud" placeholder="Latitud del punto de recarga" class="form-control"
                                   v-model="punto.latitud">
                        </div>
                        <div class="form-group">
                            <label for="name">Longitud del punto de recarga:</label>
                            <br>
                            <span v-if="punto.longitud.trim().length<5" class="label label-danger" >Debe ingresar una longitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="longitud" id="longitud" placeholder="Longitud del punto de recarga" class="form-control"
                                   v-model="punto.longitud">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeCreate">Cerrar</button>
                        <button type="button" @click="Crear" class="btn btn-primary">Registrar</button>
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
                        <h4 class="modal-title">Actualizar Punto de recarga</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre de la punto de recarga:</label>
                            <br>
                            <span v-if="actualizar.nombre.trim().length<3" class="label label-danger" >El nombre del  punto debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre de la Parada" class="form-control"
                                   v-model="actualizar.nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud del punto de recarga:</label>
                            <br>
                            <span v-if="actualizar.latitud.trim().length<5" class="label label-danger" >El nombre del punto debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="latitud" id="latitud" placeholder="Latitud del punto de recarga" class="form-control"
                                   v-model="actualizar.latitud">
                        </div>
                        <div class="form-group">
                            <label for="name">Longitud del punto de recarga:</label>
                            <br>
                            <span v-if="actualizar.longitud.trim().length<5" class="label label-danger" >El nombre del punto debe contener al menos 3 caracteres</span>
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
            punto: {
                nombre: '',
                latitud: '',
                longitud: '',
            },
            mensaje:'',
            create: false,
            update: false,
            puntos: [],
            actualizar: {
                nombre: '',
                latitud: '',
                longitud: '',
            },
        }
    },
    mounted()
    {
        this.Leer();
    },
    methods: {
        validarRegistro(){ //validacion del formulario de registro
            if(this.punto.nombre.trim().length<3 || this.punto.latitud.trim().length<5 ||this.punto.longitud.trim().length<5){
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
                axios.post('/punto', {
                    nombre: this.punto.nombre,
                    latitud: this.punto.latitud,
                    longitud: this.punto.longitud,
                    
                })
                .then(response => {
                    
                    this.mensaje="Punto de recarga creado correctamente";
                    this.reset();
                    this.create = false;
                    this.Leer();
                });
            }else{
                return;
            }
        },
        reset() {
            this.punto.nombre = '';
            this.punto.latitud = '';
            this.punto.longitud = '';
        },
        resetActualizar() {
            this.actualizar.nombre = '';
            this.actualizar.latitud = '';
            this.actualizar.longitud = '';
           
        },
        Leer() {
            axios.get("/punto").then(response => {
            this.puntos = response.data.puntos;
        });
        },
        IniciarActualizacion(punto) {
            this.actualizar.id = punto.id;
            this.actualizar.nombre = punto.nombre;
            this.actualizar.latitud = punto.latitud;
            this.actualizar.longitud = punto.longitud;
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            if(this.validarActualizacion()){
                axios.patch("/punto/" + this.actualizar.id, {
                    nombre: this.actualizar.nombre,
                    latitud: this.actualizar.latitud,
                    longitud: this.actualizar.longitud,
                })
                .then(response => {
                    this.mensaje="Datos de punto de recarga actualizados";
                    this.resetActualizar();
                    this.update = false;
                    this.Leer();
                });
            }else{
                return;
            }    
        },
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar este Punto de recarga?");
            if (conf) {
            axios({
                method: "delete",
                url: "/punto/" + id
            })
            .then(response => {
                this.mensaje="Punto de recarga eliminado";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>