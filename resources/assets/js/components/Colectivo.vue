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
                                    {{ colectivo.tarifa_id }}
                                </td>
                                <td>
                                    {{ colectivo.horario_id}}
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
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors" :key="error.id">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Nombre del Tramo:</label>
                            <input type="text" name="tramo" id="tramo" placeholder="Nombre del Tramo" class="form-control"
                                   v-model="colectivo.tramo">
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
            },
            mensaje:'',
            create: false,
            update: false,
            errors: [],
            colectivos: [],
            actualizar: {
                tramo: '',
            },
        }
    },
    mounted()
    {
        this.LeerColectivos();
    },
    methods: {
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
            axios.post('/colectivo', {
                tramo: this.colectivo.tramo,
             })
            .then(response => {
                this.mensaje="Colectivo creado correctamente";
                this.reset();
                this.create = false;
                this.LeerColectivos();
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
      this.colectivo.tramo = '';
    },
    resetActualizar() {
      this.actualizar.tramo = '';
    },
    LeerColectivos() {
      axios.get("/colectivo").then(response => {
        this.colectivos = response.data.colectivos;
      });
    },
    IniciarActualizacion(colectivo) {
        this.actualizar.id = colectivo.id;
        this.actualizar.tramo = colectivo.tramo;
        this.update = true;
    },
    closeUpdate(){
        this.update = false;
    },
    ActualizarColectivo() {
      axios
        .patch("/colectivo/" + this.actualizar.id, {
          tramo: this.actualizar.tramo
        })
        .then(response => {
            this.mensaje="Datos de colectivo actualizados";
            this.resetActualizar();
            this.update = false;
            this.LeerColectivos();
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