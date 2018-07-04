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
                            + Crear Nueva Parada
                        </button>
                        <h4 style="color:#6DD5B7">Paradas Registradas</h4>
                    </div>
                    
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="paradas.length > 0">
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
                                    Iluminada
                                </th>
                                <th>
                                    Cubierta
                                </th>    
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="parada in paradas" :key="parada.id">
                                <td>{{ parada.id }}</td>
                                <td>
                                    {{ parada.nombre }}
                                </td>
                                <td>
                                    {{ parada.latitud }}
                                </td>
                                <td>
                                    {{ parada.longitud}}
                                </td>
                                <td>
                                    <p v-if="parada.iluminada">Si</p>
                                    <p v-else>No</p>
                                </td>
                                <td>
                                    <p v-if="parada.cubierta">Si</p>
                                    <p v-else>No</p>
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(parada)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="Eliminar(parada.id)" class="btn btn-danger btn-xs">Eliminar</button>
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
                        <h4 class="modal-title">Registrar Parada</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre de la parada:</label>
                            <br>
                            <span v-if="parada.nombre.trim().length<3" class="label label-danger" >El nombre de la parada debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre de la Parada" class="form-control"
                                   v-model="parada.nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud de la parada:</label>
                            <br>
                            <span v-if="parada.latitud.trim().length<5" class="label label-danger">Debe ingresar una latitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <input type="number" name="latitud" id="latitud" placeholder="Latitud de la Parada" class="form-control"
                                   v-model="parada.latitud">
                        </div>
                        <div class="form-group">
                            <label for="name">Longitud de la parada:</label>
                            <br>
                            <span v-if="parada.longitud.trim().length<5" class="label label-danger" >Debe ingresar una longitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="longitud" id="longitud" placeholder="Longitud de la Parada" class="form-control"
                                   v-model="parada.longitud">
                        </div>
                        <div class="form-group">
                            <input type="checkbox" id="iluminada" value="true" v-model="parada.iluminada">
                            <label for="iluminada">Iluminada</label>
                            <input type="checkbox" id="cubierta" value="true" v-model="parada.cubierta">
                            <label for="cubierta">Cubierta</label>
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
                        <h4 class="modal-title">Actualizar Parada</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre de la parada:</label>
                            <br>
                            <span v-if="actualizar.nombre.trim().length<3" class="label label-danger" >El nombre de la parada debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre de la Parada" class="form-control"
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
                        <div class="form-group">
                            <input type="checkbox" id="iluminada" value="true" v-model="actualizar.iluminada">
                            <label for="iluminada">Iluminada</label>
                            <input type="checkbox" id="cubierta" value="true" v-model="actualizar.cubierta">
                            <label for="cubierta">Cubierta</label>
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
            parada: {
                nombre: '',
                latitud: '',
                longitud: '',
                iluminada:'',
                cubierta:''
            },
            mensaje:'',
            create: false,
            update: false,
            paradas: [],
            actualizar: {
                nombre: '',
                latitud: '',
                longitud: '',
                iluminada:'',
                cubierta:''
            },
        }
    },
    mounted()
    {
        this.Leer();
    },
    methods: {
        validarRegistro(){ //validacion del formulario de registro
            if(this.parada.nombre.trim().length<3 || this.parada.latitud.trim().length<5 ||this.parada.longitud.trim().length<5){
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
                axios.post('/parada', {
                    nombre: this.parada.nombre,
                    latitud: this.parada.latitud,
                    longitud: this.parada.longitud,
                    cubierta: this.parada.cubierta,
                    iluminada: this.parada.iluminada
                })
                .then(response => {
                    
                    this.mensaje="Parada creada correctamente";
                    this.reset();
                    this.create = false;
                    this.Leer();
                });
            }else{
                return;
            }
        },
        reset() {
            this.parada.nombre = '';
            this.parada.latitud = '';
            this.parada.longitud = '';
            this.parada.iluminada='';
            this.parada.cubierta='';
        },
        resetActualizar() {
            this.actualizar.nombre = '';
            this.actualizar.latitud = '';
            this.actualizar.longitud = '';
            this.actualizar.iluminada='';
            this.actualizar.cubierta='';
        },
        Leer() {
            axios.get("/parada").then(response => {
            this.paradas = response.data.paradas;
        });
        },
        IniciarActualizacion(parada) {
            this.actualizar.id = parada.id;
            this.actualizar.nombre = parada.nombre;
            this.actualizar.latitud = parada.latitud;
            this.actualizar.longitud = parada.longitud;
            this.actualizar.iluminada=parada.iluminada;
            this.actualizar.cubierta=parada.cubierta;
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            if(this.validarActualizacion()){
                axios.patch("/parada/" + this.actualizar.id, {
                    nombre: this.actualizar.nombre,
                    latitud: this.actualizar.latitud,
                    longitud: this.actualizar.longitud,
                    iluminada: this.actualizar.iluminada,
                    cubierta: this.actualizar.cubierta
                })
                .then(response => {
                    this.mensaje="Datos de parada actualizados";
                    this.resetActualizar();
                    this.update = false;
                    this.Leer();
                });
            }else{
                return;
            }    
        },
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar esta Parada?");
            if (conf) {
            axios({
                method: "delete",
                url: "/parada/" + id
            })
            .then(response => {
                this.mensaje="Parada eliminada";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>