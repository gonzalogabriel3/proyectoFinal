<template>
 <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button @click="iniciarRegistro()" class="btn btn-primary btn-xs pull-right">
                            + Crear nuevo usuario
                        </button>
                        Usuarios Registrados
                    </div>
             <!--LISTADO DE USUARIOS-->
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="usuarios.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                   Email
                                </th>    
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="usuario in usuarios" :key="usuario.id">
                                <td>{{ usuario.id }}</td>
                                <td>
                                    {{ usuario.nombre }}
                                </td>
                                <td>
                                    {{ usuario.email }}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(usuario.id)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="EliminarUsuario(usuario.id)" class="btn btn-danger btn-xs">Eliminar</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin listado de usuarios-->
        
        <!--Formulario registrar-->
        <div class="modal show" id="anadir" v-if="create" tabindex="-1" role="dialog" aria-labelledby="anadir">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeCreate" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Registrar Usuario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors" :key="error.id">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Nombre de usuario:</label>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del usuario" class="form-control"
                                   v-model="usuario.nombre">
                        </div>
                         <div class="form-group">
                            <label for="name">Email:</label>
                            <input type="email" name="email" id="email" placeholder="Email" class="form-control"
                                   v-model="usuario.email">
                        </div>
                         <div class="form-group">
                            <label for="name">Password:</label>
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control"
                                   v-model="usuario.password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeCreate">Cerrar</button>
                        <button type="button" @click="crearUsuario" class="btn btn-primary">Registrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!--Fin formulario registrar-->

        <!--Formulario editar-->
        <div class="modal show" id="actualizar" v-if="update" tabindex="-1" role="dialog" aria-labelledby="actualizar">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeUpdate" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Actualizar Colectivo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors" :key="error.id">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Nombre del Tramo:</label>
                            <input type="text" name="tramo" id="tramo" placeholder="Nombre del Tramo" class="form-control"
                                   v-model="actualizar.tramo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeUpdate">Cerrar</button>
                        <button type="button" @click="actualizarColectivo" class="btn btn-primary">Actualizar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!--Fin formulario editar-->    
    </div>
        
</template>
<script>
export default{
    data(){
        return {
                usuario: {
                    nombre: '',
                    password:'',
                    email: ''
                },
                create: false,
                update: false,
                errors: [],
                usuarios: [],
                actualizar: [],
                
            }
        },
        mounted(){
            this.LeerUsuarios();
        },
        methods:{
            /*Metodo que muestra el formulario para registrar un nuevo usuario*/ 
            iniciarRegistro(){
                this.create = true;
            },
             /*Metodo que muestra el formulario para editar un nuevo usuario*/ 
            closeCreate(){
                this.create = false;
            },
            /*Metodo que obtiene todos los usuarios y los carga en el arreglo 'usuarios' del componente vue,
            para poder visualizarlo en pantalla*/
            LeerUsuarios(){ 
                axios.get('/usuario').then(response => {
                    this.usuarios=response.data.usuarios;                    
                });
            },
            crearUsuario() {
                axios.post('/usuario', {
                    nombre: this.usuario.nombre,
                    email:this.usuario.email,
                    password:this.usuario.password
                })
                .then(response => {
                    
                    this.reset();
                    this.create = false;
                    this.LeerUsuarios();
                })
                .catch(error => {
                    this.errors = [];
                    if (error.errors != undefined && error.errors.name != undefined) {
                        this.errors.push(error.errors.name[0]);
                }

                if (
                    error.errors != undefined &&
                    error.errors.description != undefined
                ) {
                    this.errors.push(error.errors.description[0]);
                }
                });
            },
            reset() {
                this.usuario.nombre = '';
                this.usuario.email = '';
                this.usuario.password = '';
            },
            resetActualizar() {
                this.actualizar.nombre = '';
                this.actualizar.email = '';
                this.actualizar.password = '';
            },
            IniciarActualizacion(id) {
                this.actualizar = this.usuarios[id];
                this.update = true;
            },
            closeUpdate(){
                this.update = false;
            },
            ActualizarUsuario() {
            axios
                .patch("/usuario/" + this.actualizar.id, {
                    nombre: this.actualizar.nombre,
                    email: this.actualizar.email,
                    password:this.actualizar.password
                })
                .then(response => {
                $("#actualizar_modelo").modal("hide");
                })
                .catch(error => {
                    this.errors = [];
                    if (error.errors != undefined && error.errors.name != undefined) {
                        this.errors.push(error.errors.name[0]);
                    }
                    if (
                        error.errors != undefined &&
                        error.errors.description != undefined
                    ) {
                        this.errors.push(error.errors.description[0]);
                    }
                });
            },
            EliminarUsuario(id){
                
                let conf=confirm("De verdad quiere borrar este usuario?");

                if (conf) {
                    axios({
                            method:'delete',
                            url:'/usuario/'+id
                            })
                            .then(
                                response => {

                                    this.LeerUsuarios();

                            }).catch(error => {

                            });
                }
            },
        //Fin de los metodos    
        },

}

</script>