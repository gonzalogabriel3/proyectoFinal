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
                            + Crear Nueva Tarifa
                        </button>
                        <h4 style="color:#6DD5B7"> Tarifas Registradas</h4>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="tarifas.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Tarifa
                                </th>  
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="tarifa in tarifas" :key="tarifa.id">
                                <td>{{ tarifa.id }}</td>
                                <td>
                                    ${{ tarifa.monto }}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(tarifa)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="Eliminar(tarifa.id)" class="btn btn-danger btn-xs">Eliminar</button>
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
                        <h4 class="modal-title">Registrar Tarifa</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Monto de la tarifa:</label>
                            <br>
                            <span v-if="tarifa.monto.trim().length<2" class="label label-danger" >Debe ingresar un monto valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="monto" id="monto" placeholder="Monto de la Tarifa" class="form-control"
                                   v-model="tarifa.monto">
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
                            <label for="name">Monto de la tarifa:</label>
                            <br>
                            <span v-if="actualizar.monto.trim().length<2" class="label label-danger" >Debe ingresar un monto valido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="number" name="monto" id="monto" placeholder="Monto a actualizar" class="form-control"
                                   v-model="actualizar.monto">
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
            tarifa: {
                monto: '',
            },
            mensaje:'',
            create: false,
            update: false,
            tarifas: [],
            actualizar: {
                monto: '',
            },
        }
    },
    mounted()
    {
        this.Leer();
    },
    methods: {
        validarRegistro(){ //validacion del formulario de registro
            if(this.tarifa.monto.trim().length<2){
                return false;
            }else{
                return true;
            }
        },
        validarActualizacion(){ //validacion del formulario de actualizacion
            if(this.actualizar.monto.trim().length<2){
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
                axios.post('/tarifa', {
                    monto: this.tarifa.monto,
                })
                .then(response => {
                    
                    this.mensaje="Tarifa creada correctamente";
                    this.reset();
                    this.create = false;
                    this.Leer();
                });
            }else{
                return;
            }
        },
        reset() {
            this.tarifa.monto = '';
        },
        resetActualizar() {
            this.actualizar.monto = '';
        },
        Leer() {
            axios.get("/tarifa").then(response => {
            this.tarifas = response.data.tarifas;
        });
        },
        IniciarActualizacion(tarifa) {
            this.actualizar.id = tarifa.id;
            this.actualizar.monto = tarifa.monto;
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            if(this.validarActualizacion()){
                axios.patch("/tarifa/" + this.actualizar.id, {
                    monto: this.actualizar.monto
                })
                .then(response => {
                    this.mensaje="Datos de tarifa actualizados";
                    this.resetActualizar();
                    this.update = false;
                    this.Leer();
                });
            }else{
                return;
            }    
        },
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar esta Tarifa?");
            if (conf) {
            axios({
                method: "delete",
                url: "/tarifa/" + id
            })
            .then(response => {
                this.mensaje="Tarifa eliminada";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>