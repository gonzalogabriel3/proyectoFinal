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
                            + Crear Nuevo comentario
                        </button>
                        <h4 style="color:#6DD5B7"> Comentarios Registrados</h4>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="comentarios.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Comentario
                                </th>
                                <th>
                                    Usuario
                                </th>  
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="comentario in comentarios" :key="comentario.id">
                                <td>{{ comentario.id }}</td>
                                <td>
                                    {{ comentario.comentario }}
                                </td>
                                <td>
                                    {{comentario.usuario_id}}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(comentario)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="Eliminar(comentario.id)" class="btn btn-danger btn-xs">Eliminar</button>
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
                        <h4 class="modal-title">Registrar Comentario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Seleccione el usuario que realiza el comentario</label>
                            <br>
                            <span v-if="!comentario.usuario" class="label label-danger">Debe Seleccionar un usuario</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <select v-model="comentario.usuario">
                                <option v-for="usuario in usuarios" :key="usuario.id" v-bind:value="usuario.id">{{usuario.nombre}}</option>
                            </select> 
                        </div>
                        <div class="form-group">
                            <label for="name">Comentario:</label>
                            <br>
                            <span v-if="comentario.comentario.trim().length<5" class="label label-danger" >El comentario debe tener al menos 5 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <textarea v-model="comentario.comentario" class="form-control"  name="comentario" id="comentario"></textarea>
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
                        <h4 class="modal-title">Actualizar Tarifa</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Seleccione el usuario que realiza el comentario</label>
                            <br>
                            <span v-if="!actualizar.usuario" class="label label-danger">Debe Seleccionar un usuario</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <select v-model="actualizar.usuario">
                                <option v-for="usuario in usuarios" :key="usuario.id" v-bind:value="usuario.id">{{usuario.nombre}}</option>
                            </select> 
                        </div>
                        <div class="form-group">
                            <label for="name">Comentario:</label>
                            <br>
                            <span v-if="actualizar.comentario.trim().length<5" class="label label-danger" >El comentario debe tener al menos 5 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <textarea v-model="actualizar.comentario" class="form-control"  name="comentario" id="comentario"></textarea>
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
            comentario: {
                usuario: '',
                comentario:''
            },
            mensaje:'',
            create: false,
            update: false,
            comentarios: [],
            usuarios:[],
            actualizar: {
                usuario: '',
                comentario:''
            },
        }
    },
    mounted()
    {
        this.Leer();
        this.obtenerUsuarios();
    },
    methods: {
        obtenerUsuarios(){
            axios.get("/usuario").then(response => {
            this.usuarios = response.data.usuarios;
            });
        },
        validarRegistro(){ //validacion del formulario de registro
            if(this.comentario.comentario.trim().length<5 || !this.comentario.usuario){
                return false;
            }else{
                return true;
            }
        },
        validarActualizacion(){ //validacion del formulario de actualizacion
            if(this.actualizar.comentario.trim().length<5 || !this.actualizar.usuario){
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
                axios.post('/comentario', {
                    comentario: this.comentario.comentario,
                    usuario:this.comentario.usuario
                })
                .then(response => {
                    
                    this.mensaje="Comentario creado correctamente";
                    this.reset();
                    this.create = false;
                    this.Leer();
                });
            }else{
                return;
            }
        },
        reset() {
            this.comentario.comentario = '';
            this.comentario.usuario='';
        },
        resetActualizar() {
            this.actualizar.comentario = '';
            this.actualizar.usuario='';
        },
        Leer() {
            axios.get("/comentario").then(response => {
            this.comentarios = response.data.comentarios;
        });
        },
        IniciarActualizacion(comentario) {
            this.actualizar.id = comentario.id;
            this.actualizar.comentario = comentario.comentario;
            this.actualizar.usuario=comentario.usuario;
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            if(this.validarActualizacion()){
                axios.patch("/comentario/" + this.actualizar.id, {
                    comentario: this.actualizar.comentario,
                    usuario: this.actualizar.usuario
                })
                .then(response => {
                    this.mensaje="Comentario actualizado";
                    this.resetActualizar();
                    this.update = false;
                    this.Leer();
                });
            }else{
                return;
            }    
        },
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar este comentario?");
            if (conf) {
            axios({
                method: "delete",
                url: "/comentario/" + id
            })
            .then(response => {
                this.mensaje="Comentario eliminado";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>