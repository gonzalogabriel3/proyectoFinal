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
                            + Crear Nuevo Horario
                        </button>
                        <h4 style="color:#6DD5B7"> Horarios Registrados</h4>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="horarios.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Salida
                                </th>
                                <th>
                                    Llegada
                                </th>  
                                <th>
                                    Dias
                                </th>
                                <th>
                                    Nro de Tramo
                                </th>
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="horario in horarios" :key="horario.id">
                                <td>{{ horario.id }}</td>
                                <td>
                                    {{ horario.salida }}
                                </td>
                                <td>
                                    {{ horario.llegada }}
                                </td>
                                <td>
                                    {{ horario.dias }}
                                </td>
                                <td>
                                    {{ horario.tramo_id }}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(horario)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="Eliminar(horario.id)" class="btn btn-danger btn-xs">Eliminar</button>
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
                        <h4 class="modal-title">Registrar Horario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Horario de Salida:</label>
                            <br>
                            <span v-if="horario.salida.trim().length<=4" class="label label-danger" >Debe ingresar un horario valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="salida" id="salida" placeholder="Horario de Salida" class="form-control"
                                   v-model="horario.salida">
                        </div>
                        <div class="form-group">
                            <label for="name">Horario de Llegada:</label>
                            <br>
                            <span v-if="horario.llegada.trim().length<=4" class="label label-danger" >Debe ingresar un horario valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="llegada" id="llegada" placeholder="Horario de LLegada" class="form-control"
                                   v-model="horario.llegada">
                        </div>
                        <div class="form-group">
                            <label for="name">Tramo que Realiza:</label>
                            <br>
                            <span v-if="!horario.tramo" class="label label-danger" >Debe Seleccionar un tramo</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <select v-model="horario.tramo">
                                <option v-for="tramo in tramos" :key="tramo.id" v-bind:value="tramo.id">{{tramo.nombre}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Dias que Funciona:</label>
                            <br>
                            <span v-if="horario.dias.trim().length<=6" class="label label-danger" >Debe ingresar los dias que funciona</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="dias" id="dias" placeholder="Dias de Funcion" class="form-control"
                                   v-model="horario.dias">
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
                        <h4 class="modal-title">Actualizar Horarios</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Horario de Salida:</label>
                            <br>
                            <span v-if="actualizar.salida.trim().length<=4" class="label label-danger" >Debe ingresar un horario valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="salida" id="salida" placeholder="Horario de Salida a Actualizar" class="form-control"
                                   v-model="actualizar.salida">
                        </div>
                        <div class="form-group">
                            <label for="name">Horario de Llegada:</label>
                            <br>
                            <span v-if="actualizar.llegada.trim().length<=4" class="label label-danger" >Debe ingresar un horario valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="llegada" id="llegada" placeholder="Horario de Llegada a actualizar" class="form-control"
                                   v-model="actualizar.llegada">
                        </div>
                                                <div class="form-group">
                            <label for="name">Tramo que Realiza:</label>
                            <br>
                            <span v-if="!actualizar.tramo" class="label label-danger" >Debe Seleccionar un tramo</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <select v-model="actualizar.tramo">
                                <option v-for="tramo in tramos" :key="tramo.id" v-bind:value="tramo.id">{{tramo.nombre}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Dias que Funciona:</label>
                            <br>
                            <span v-if="actualizar.dias.trim().length<=6" class="label label-danger" >Debe ingresar los dias que funciona</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="dias" id="dias" placeholder="Dias de Funcion" class="form-control"
                                   v-model="actualizar.dias">
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
            horario: {
                salida: '',
                llegada: '',
                tramo:'',
                dias:''
            },
            mensaje:'',
            create: false,
            update: false,
            horarios: [],
            tramos: [],
            actualizar: {
                llegada: '',
                salida: '',
                tramo:'',
                dias:''
            },
        }
    },
    mounted()
    {
        this.Leer();
    },
    methods: {
        Tramos(){
            axios.get("/tramo").then(response => {
            this.tramos = response.data.tramos;
            });
        },
        validarRegistro(){ //validacion del formulario de registro
            if(this.horario.salida.trim().length <= 4 || this.horario.llegada.trim().length <= 4 || !this.horario.tramo || this.horario.dias.trim().length <= 6){
                return false;
            }else{
                return true;
            }
        },
        validarActualizacion(){ //validacion del formulario de actualizacion
            if(this.actualizar.salida.trim().length <= 4 && this.actualizar.llegada.trim().length <= 4 || this.actualizar.dias.trim().length <= 6 || !this.actualizar.tramo){
                return false;
            }else{
                return true;
            }
        },
        iniciarRegistro()
        {
           this.Tramos();
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
                axios.post('/horario', {
                    salida: this.horario.salida,
                    llegada: this.horario.llegada,
                    dias: this.horario.dias,
                    tramo: this.horario.tramo
                })
                .then(response => {
                    
                    this.mensaje="Horario creado correctamente";
                    this.reset();
                    this.create = false;
                    this.Leer();
                });
            }else{
                return;
            }
        },
        reset() {
            this.horario.salida = '';
            this.horario.llegada = '';
            this.horario.dias = '';
            this.horario.tramo = '';
        },
        resetActualizar() {
            this.actualizar.salida = '';
            this.actualizar.llegada = '';
            this.actualizar.dias = '';
            this.actualizar.tramo = '';
        },
        Leer() {
            axios.get("/horario").then(response => {
            this.horarios = response.data.horarios;
        });
        },
        IniciarActualizacion(horario) {
            this.actualizar.id = horario.id;
            this.actualizar.salida = horario.salida;
            this.actualizar.llegada = horario.llegada;
            this.actualizar.dias = horario.dias;
            this.actualizar.tramo = horario.tramo;
            this.Tramos();
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            if(this.validarActualizacion()){
                axios.patch("/horario/" + this.actualizar.id, {
                    salida: this.actualizar.salida,
                    llegada : this.actualizar.llegada,
                    dias : this.actualizar.dias,
                    tramo : this.actualizar.tramo
                })
                .then(response => {
                    this.mensaje="Datos de horario actualizados";
                    this.resetActualizar();
                    this.update = false;
                    this.Leer();
                });
            }else{
                return;
            }    
        },
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar este horario?");
            if (conf) {
            axios({
                method: "delete",
                url: "/horario/" + id
            })
            .then(response => {
                this.mensaje="Horario Eliminado";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>