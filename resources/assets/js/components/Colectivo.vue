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
                            + Crear Nuevo Colectivo
                        </button>
                        <h4 style="color:#6DD5B7"> Colectivos Registrados</h4>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="colectivos.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Empresa
                                </th>
                                <th>
                                    Numero de Coche
                                </th>    
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="colectivo in colectivos" :key="colectivo.id">
                                <td>{{ colectivo.id }}</td>
                                <td>
                                    {{ colectivo.empresa }}
                                </td>
                                <td>
                                   {{ colectivo.num_coche }}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(colectivo)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="EliminarColectivo(colectivo.id)" class="btn btn-danger btn-xs">Eliminar</button>
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
                        <h4 class="modal-title">Registrar Colectivo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre de la Empresa:</label>
                            <br>
                            <span v-if="colectivo.empresa.trim().length<3" class="label label-danger" >El nombre de la Empresa debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="empresa" id="empresa" placeholder="Nombre de la Empresa" class="form-control"
                                   v-model="colectivo.empresa">
                        </div>
                        <div class="form-group">
                            <label for="name">Numero de Coche:</label>
                            <br>
                            <span v-if="!colectivo.numero" class="label label-danger" >Debe ingresar un Numero de Coche Valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <input type="number" name="numero" id="numero" placeholder="Numero de la empresa" class="form-control"
                                   v-model="colectivo.numero">       
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeCreate">Cerrar</button>
                        <button type="button" @click="crearColectivo" class="btn btn-primary">Registrar</button>
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
                        <h4 class="modal-title">Actualizar Colectivo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre de la Empresa:</label>
                            <br>
                            <span v-if="actualizar.empresa.trim().length<3" class="label label-danger" >El tramo debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="empresa" id="empresa" placeholder="Nombre de la Empresa" class="form-control"
                                   v-model="actualizar.empresa">
                        </div>
                        <div class="form-group">
                            <label for="name">Numero de Coche:</label>
                            <br>
                            <span v-if="!actualizar.numero" class="label label-danger" >Debe ingresar un Numero Valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <input type="number" name="numero" id="numero" placeholder="Numero de la empresa" class="form-control"
                                   v-model="actualizar.numero">       
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeUpdate">Cerrar</button>
                        <button type="button" @click="ActualizarColectivo" class="btn btn-primary">Actualizar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->    
    </div></template>

<script>
export default {
  data(){
        return {
            colectivo: {
                empresa: '',
                numero:'',
            },
            mensaje:'',
            create: false,
            update: false,
            colectivos: [],
            actualizar: {
                empresa: '',
                numero:'',
            },
        }
    },
    mounted()
    {
        this.LeerColectivos();
    },
    methods: {
        validarRegistro(){ //validacion del formulario de registro
            if(this.colectivo.empresa.trim().length<3 || !this.colectivo.numero){
                return false;
            }else{
                return true;
            }
        },
        validarActualizacion(){ //validacion del formulario de actualizacion
            if(this.actualizar.empresa.trim().length<3 || !this.actualizar.numero){
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
        crearColectivo() {
            if(this.validarRegistro()){
                    axios.post('/colectivo', {
                        empresa: this.colectivo.empresa,
                        numero: this.colectivo.numero
                    })
                    .then(response => {
                        this.mensaje="Colectivo creado correctamente";
                        this.reset();
                        this.create = false;
                        this.LeerColectivos();
                    });                   
            }else{
                return;
            }
    },
    reset() {
      this.colectivo.empresa = '';
      this.colectivo.numero='';
    },
    resetActualizar() {
      this.actualizar.empresa = '';
      this.actualizar.numero = '';
    },
    LeerColectivos() {
      axios.get("/colectivo").then(response => {
        this.colectivos = response.data.colectivos;
      });
    },
    IniciarActualizacion(colectivo) {
        this.actualizar.id = colectivo.id;
        this.actualizar.empresa = colectivo.empresa;
        this.actualizar.numero = colectivo.numero;
        this.update = true;
    },
    closeUpdate(){
        this.update = false;
    },
    ActualizarColectivo() {
        
        if(this.validarActualizacion()){
            axios.patch("/colectivo/" + this.actualizar.id, {
            empresa: this.actualizar.empresa,
            numero: this.actualizar.numero
            })
            .then(response => {
                this.mensaje="Datos de colectivo actualizados";
                this.resetActualizar();
                this.update = false;
                this.LeerColectivos();
            });               
        }else{
            return;
        }    
    },
    EliminarColectivo(id) {
      let conf = confirm("De verdad quiere borrar este Colectivo?");
      if (conf) {
        axios({
          method: "delete",
          url: "/colectivo/" + id
        })
          .then(response => {
            this.mensaje="Colectivo eliminado";
            this.LeerColectivos();
          })
          .catch(error => {});
      }
    }
  }
};
</script>