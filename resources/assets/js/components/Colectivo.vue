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
                                    Tramo
                                </th>
                                <th>
                                    Tarifa
                                </th>
                                <th>
                                    Tarifa_id
                                </th>
                                <th>
                                    Horario
                                </th>    
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="colectivo in colectivos" :key="colectivo.id">
                                <td>{{ colectivo.id }}</td>
                                <td>
                                    {{ colectivo.tramo }}
                                </td>
                                <td>
                                   ${{ colectivo.monto }}
                                </td>
                                <td>
                                   {{ colectivo.tarifa_id }}
                                </td>
                                <td>
                                    {{ colectivo.horas}}
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
                            <label for="name">Nombre del Tramo:</label>
                            <br>
                            <span v-if="colectivo.tramo.trim().length<3" class="label label-danger" >El tramo debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="tramo" id="tramo" placeholder="Nombre del Tramo" class="form-control"
                                   v-model="colectivo.tramo">
                        </div>
                        <div class="form-group">
                            <label for="name">Tarifa:</label>
                            <br>
                            <span v-if="!colectivo.tarifa_id" class="label label-danger" >Debe ingresar una tarifa valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <select v-model="colectivo.tarifa_id">
                                <option disabled value="">Eliga una tarifa</option>
                                <option v-for="tarifa in tarifas" :key="tarifa.id" v-bind:value="tarifa.id">${{tarifa.monto}}</option>
                            </select>       
                        </div>
                        <div class="form-group">
                            <label for="name">Horarios:</label>
                            <br>
                            <span v-if="!colectivo.horarios" class="label label-danger" >Debe ingresar un horario valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <select v-model="colectivo.horarios" multiple>
                                <option disabled value="">Elija los horarios</option>
                                <option v-for="horario in horarios" :key="horario.id" v-bind:value="horario.id">{{horario.horas}}</option>
                            </select>       
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
                            <label for="name">Nombre del Tramo:</label>
                            <br>
                            <span v-if="actualizar.tramo.trim().length<3" class="label label-danger" >El tramo debe contener al menos 3 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="tramo" id="tramo" placeholder="Nombre del Tramo" class="form-control"
                                   v-model="actualizar.tramo">
                        </div>
                        <div class="form-group">
                            <label for="name">Tarifa:</label>
                            <br>
                            <span v-if="!actualizar.tarifa_id" class="label label-danger" >Debe ingresar una tarifa valida</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <select v-model="actualizar.tarifa_id">
                                <option disabled value="">Eliga una tarifa</option>
                                <option v-for="tarifa in tarifas" :key="tarifa.id" v-bind:value="tarifa.id">${{tarifa.monto}}</option>
                            </select>       
                        </div>
                        <div class="form-group">
                            <label for="name">Horarios:</label>
                            <br>
                            <span v-if="!actualizar.horarios" class="label label-danger" >Debe ingresar un horario valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <select v-model="actualizar.horarios" multiple>
                                <option disabled value="">Elija los horarios</option>
                                <option v-for="horario in horarios" :key="horario.id" v-bind:value="horario.id">{{horario.horas}}</option>
                            </select>       
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
                tramo: '',
                tarifa_id:'',
                horarios: [],
            },
            mensaje:'',
            create: false,
            update: false,
            colectivos: [],
            tarifas: [],
            horarios: [],
            actualizar: {
                tramo: '',
                tarifa_id:'',
                horarios: '',
            },
        }
    },
    mounted()
    {
        this.LeerColectivos();
        this.obtenerTarifas();
        this.obtenerHorarios();
    },
    methods: {
        obtenerTarifas() {
            axios.get("/tarifa").then(response => {
            this.tarifas = response.data.tarifas;
            });
        },
        obtenerHorarios() {
            axios.get("/horario").then(response => {
            this.horarios = response.data.horarios;
            });
        },
        validarRegistro(){ //validacion del formulario de registro
            if(this.colectivo.tramo.trim().length<3 || !this.colectivo.tarifa_id){
                return false;
            }else{
                return true;
            }
        },
        validarActualizacion(){ //validacion del formulario de actualizacion
            if(this.actualizar.tramo.trim().length<3 || !this.actualizar.tarifa_id){
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
                        tramo: this.colectivo.tramo,
                        tarifa_id: this.colectivo.tarifa_id,
                        horarios : this.colectivo.horarios,
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
      this.colectivo.tramo = '';
      this.colectivo.tarifa_id='';
    },
    resetActualizar() {
      this.actualizar.tramo = '';
      this.actualizar.tarifa_id = '';
    },
    LeerColectivos() {
      axios.get("/colectivo").then(response => {
        this.colectivos = response.data.colectivos;
      });
    },
    IniciarActualizacion(colectivo) {
        this.actualizar.id = colectivo.id;
        this.actualizar.tramo = colectivo.tramo;
        this.actualizar.tarifa_id=colectivo.tarifa_id;
        this.update = true;
    },
    closeUpdate(){
        this.update = false;
    },
    ActualizarColectivo() {
        
        if(this.validarActualizacion()){
            axios.patch("/colectivo/" + this.actualizar.id, {
            tramo: this.actualizar.tramo,
            tarifa_id:this.actualizar.tarifa_id
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