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
                            + Crear nuevo usuario
                        </button>
                       <h4 style="color:#6DD5B7"> Usuarios Registrados</h4>
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
                                    <button @click="IniciarActualizacion(usuario)" class="btn btn-success btn-xs">Editar</button>
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
                        <div class="form-group">
                            <label for="name">Nombre de usuario:</label>
                            <br>
                            <span v-if="usuario.nombre.trim().length<3" class="label label-danger" >El nombre debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="usuario" id="usuario" placeholder="Nombre del usuario" class="form-control"
                                   v-model="usuario.usuario">
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre Completo:</label>
                            <br>
                            <span v-if="usuario.nombre.trim().length<3" class="label label-danger" >El nombre debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" class="form-control"
                                   v-model="usuario.nombre">
                        </div>

                         <div class="form-group">
                            <label for="name">Email:</label>
                            <br>
                            <span v-if="usuario.email.trim().length<3" class="label label-danger" >Debe ingresar un email valido</span>
                            <span v-else-if="usuario.email.indexOf('@')==-1" class="label label-danger" >Debe ingresar un email valido</span>
                            <span v-else-if="usuario.email.indexOf('.')==-1" class="label label-danger" >Debe ingresar un email valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="email" name="email" id="email" placeholder="Email" class="form-control"
                                   v-model="usuario.email">
                        </div>
                         <div class="form-group">
                            <label for="name">Password:</label>
                            <br>
                            <span v-if="usuario.password.trim().length<3" class="label label-danger" >El password debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
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
                        <h4 class="modal-title">Actualizar Usuario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre de usuario:</label>
                            <br>
                            <span v-if="actualizar.nombre.trim().length<3" class="label label-danger" >El tramo debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" v-model="actualizar.nombre" name="nombre" id="nombre" placeholder="Nombre del usuario" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">Email:</label>
                            <br>
                            <span v-if="actualizar.email.trim().length<3" class="label label-danger" >El tramo debe contener al menos 3 caracteres</span>
                            <span v-else-if="actualizar.email.indexOf('@')==-1" class="label label-danger" >Debe ingresar un email valido</span>
                            <span v-else-if="actualizar.email.indexOf('.')==-1" class="label label-danger" >Debe ingresar un email valido</span>
                           <span v-else class="label label-success">Correcto!</span>
                            <input type="email" name="email" id="email" placeholder="Email" class="form-control"
                                   v-model="actualizar.email">
                        </div>
                         <div class="form-group">
                            <label for="name">Password:</label>
                            <br>
                            <span v-if="actualizar.password.trim().length<3" class="label label-danger" >El tramo debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control"
                                   v-model="actualizar.password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeUpdate">Cerrar</button>
                        <button type="button" @click="actualizarUsuario" class="btn btn-primary">Actualizar usuario</button>
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
                    email: '',
                    usuario:''
                },
                mensaje:'',
                create: false,
                update: false,
                usuarios: [],
                actualizar: {
                    nombre: '',
                    password:'',
                    email: ''
                }
                
            }
        },
        mounted(){
            this.LeerUsuarios();
        },
        methods:{
            validarRegistro(){ //validacion del formulario de registro
                if(this.usuario.nombre.trim().length<3 || this.usuario.password.trim().length<3 ||this.usuario.email.trim().length<3
                 || this.usuario.email.indexOf('@')==-1 || this.usuario.email.indexOf('.')==-1 ){
                    return false;
                }else{
                    return true;
                }
            },
            validarActualizacion(){ //validacion del formulario de actualizacion
                if(this.actualizar.nombre.trim().length<3 || this.actualizar.password.trim().length<3 ||this.actualizar.email.trim().length<3
                 || this.actualizar.email.indexOf('@')==-1 || this.actualizar.email.indexOf('.')==-1){
                    return false;
                }else{
                    return true;
                }
            },
            /*Metodo que muestra el formulario para registrar un nuevo usuario*/ 
            iniciarRegistro(){
                this.create = true;
            }, 
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
            cerrarMensaje(){
                this.mensaje='';
            },
            crearUsuario() {
                if(this.validarRegistro()){
                    axios.post('/usuario', {
                        nombre: this.usuario.nombre,
                        email:this.usuario.email,
                        password:this.usuario.password,
                        usuario:this.usuario.usuario
                    })
                    .then(response => {
                        
                        this.mensaje="Usuario creado correctamente";
                        this.reset();
                        this.create = false;
                        this.LeerUsuarios();
                    });
                }else{
                    return;
                }    
            },
            reset() {
                this.usuario.nombre = '';
                this.usuario.email = '';
                this.usuario.password = '';
            },
            resetActualizar() {
                this.actualizar.id='';
                this.actualizar.nombre = '';
                this.actualizar.email = '';
                this.actualizar.password = '';
            },
            IniciarActualizacion(usuario) {
               this.actualizar.id=usuario.id;
               this.actualizar.nombre=usuario.nombre;
               this.actualizar.email=usuario.email;
               this.actualizar.password=usuario.password;
               this.update = true;
            },
            closeUpdate(){
                this.update = false;
            },
            actualizarUsuario() {
                if(this.validarActualizacion()){
                    axios.patch('/usuario/' + this.actualizar.id, {
                            nombre: this.actualizar.nombre,
                            email: this.actualizar.email,
                            password: this.actualizar.password
                        }).then(response => {
                            this.mensaje="Datos de usuario actualizados";
                            this.resetActualizar();
                            this.update = false;
                            this.LeerUsuarios();
                        
                        });
                }else{
                    return;
                }
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
                                    this.mensaje="Usuario eliminado";
                                    this.LeerUsuarios();
                            }).catch(error => {
                            });
                }
            },
        //Fin de los metodos    
        },
}
</script>