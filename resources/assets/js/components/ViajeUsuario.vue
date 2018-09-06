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
                            + Crear nuevo viaje de usuario
                        </button>
                       <h4 style="color:#6DD5B7"> Viajes de Usuarios Registrados</h4>
                    </div>
             <!--LISTADO DE USUARIOS-->
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="viajes.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    id_usuario
                                </th>
                                <th>
                                   Punto de Inicio
                                </th>    
                                <th>
                                   Punto de Fin
                                </th>    
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="viaje in viajes" :key="viaje.id">
                                <td>{{ viaje.id }}</td>
                                <td>
                                    {{ viaje.id_usuario }}
                                </td>
                                <td>
                                    {{ viaje.latinicio / viaje.longinicio }}
                                </td>
                                <td>
                                    {{ viaje.latfin / viaje.longfin }}
                                </td>
                                <td>
                                    <button @click="Eliminar(viaje.id)" class="btn btn-danger btn-xs">Eliminar</button>
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
                        <h4 class="modal-title">Registrar Viaje</h4>
                    </div>
                    <div class="modal-body" style="overflow: scroll; width: 550px; height: 450px;">
                        <div class="form-group">
                            <label for="name">Usuario al que Pertenece el Viaje:</label>
                            <br>
                            <span v-if="!viaje.id_usuario" class="label label-danger">Debe Seleccionar un Usuario</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <select v-model="viaje.id_usuario">
                                <option v-for="usuario in usuarios" :key="usuario.id" v-bind:value="usuario.id">{{usuario.nombre}}</option>
                            </select> 
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud del Punto Inicio:</label>
                            <br>
                            <span v-if="viaje.latpunto_inicio.trim().length<5" class="label label-danger">Debe ingresar una latitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <input type="number" name="latitudinicio" id="latitudinicio" placeholder="Latitud del Punto Inicio" class="form-control"
                                   v-model="viaje.latpunto_inicio">
                            <br>
                            <label for="name">Longitud del Punto Inicio:</label>
                            <br>
                            <span v-if="viaje.lonpunto_inicio.trim().length<5" class="label label-danger" >Debe ingresar una longitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="longitudinicio" id="longitudinicio" placeholder="Longitud del Punto Inicio" class="form-control"
                                   v-model="viaje.lonpunto_inicio">
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud del Punto Fin:</label>
                            <br>
                            <span v-if="viaje.latpunto_fin.trim().length<5" class="label label-danger">Debe ingresar una latitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <input type="number" name="latitudfin" id="latitudfin" placeholder="Latitud del Punto Fin" class="form-control"
                                   v-model="viaje.latpunto_fin">
                            <br>
                            <label for="name">Longitud del Punto Fin:</label>
                            <br>
                            <span v-if="viaje.lonpunto_fin.trim().length<5" class="label label-danger" >Debe ingresar una longitud valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="longitud" id="longitud" placeholder="Longitud del Punto Fin" class="form-control"
                                   v-model="viaje.lonpunto_fin">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeCreate">Cerrar</button>
                        <button type="button" @click="crearViaje" class="btn btn-primary">Registrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!--Fin formulario registrar-->
    </div>
        
</template>
<script>
export default{
    data(){
        return {
                viaje: {
                    id_usuario: '',
                    latpunto_inicio:'',
                    lonpunto_inicio: '',
                    latpunto_fin:'',
                    lonpunto_fin: '',
                    punto_inicio: '',
                    punto_fin: ''
                },
                mensaje:'',
                create: false,
                update: false,
                viajes: [],
                usuarios: [],
            }
        },
        mounted(){
            //metodo que trae todos los viajes de usuario registrados
            this.LeerViajes();
            this.LeerUsuario();
        },
        methods:{
            //metodo que valida el registro de los viajes de usuario
            validarRegistro(){ //validacion del formulario de registro
                if( !this.viaje.id_usuario || this.viaje.latpunto_inicio.trim().length<5 ||this.viaje.lonpunto_inicio.trim().length<5
                    || this.viaje.latpunto_fin.trim().length<5 ||this.viaje.lonpunto_fin.trim().length<5){
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
            /*Metodo que obtiene todos los viajes y los carga en el arreglo 'viajes' del componente vue,
            para poder visualizarlo en pantalla*/
            LeerViajes(){ 
                axios.get('/viaje').then(response => {
                    this.viajes=response.data.viajes;                    
                });
            },
            LeerUsuario(){ 
                axios.get('/usuario').then(response => {
                    this.usuarios=response.data.usuarios;                    
                });
            },
            cerrarMensaje(){
                this.mensaje='';
            },
            crearViaje() {
                if(this.validarRegistro()){
                    axios.post('/viaje', {
                        id_usuario: this.viaje.id_usuario,
                        latinicio: this.viaje.latpunto_inicio,
                        longinicio: this.viaje.lonpunto_inicio,
                        latfin: this.viaje.latpunto_fin,
                        longfin: this.viaje.lonpunto_fin,
                        punto_inicio: 1,
                        punto_fin: 1
                    })
                    .then(response => {
                        
                        this.mensaje="Viaje de usuario creado correctamente";
                        this.reset();
                        this.create = false;
                        this.LeerViajes();
                    });
                }else{
                    return;
                }    
            },
            reset() {
                this.viaje.id_usuario = '';
                this.viaje.latpunto_inicio = '';
                this.viaje.lonpunto_inicio = '';
                this.viaje.latpunto_fin = '';
                this.viaje.lonpunto_fin = '';

            },
            Eliminar(id) {
            let conf = confirm("De verdad quiere borrar este viaje de usuario?");
            if (conf) {
            axios({
                method: "delete",
                url: "/viaje/" + id
            })
            .then(response => {
                this.mensaje="Viaje de Usuario eliminado correctamente";
                this.Leer();
            })
            .catch(error => {});
            }
        }
        //Fin de los metodos    
        },
}
</script>