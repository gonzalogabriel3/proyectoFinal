<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button @click="iniciarRegistro()" class="btn btn-primary btn-xs pull-right">
                            + Crear Nuevo Colectivo
                        </button>
                        Colectivos Registrados
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
                                    <button @click="IniciarActualizacion(colectivo.id)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="EliminarColectivo(colectivo.id)" class="btn btn-danger btn-xs">Eliminar</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="anadir_colectivo_modelo">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
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
                        <!--<div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                      placeholder="Task Description" v-model="task.description"></textarea>
                        </div>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" @click="crearColectivo" class="btn btn-primary">Registrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="actualizar_modelo">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
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
                            <label>Nombre del Tramo</label>
                            <input type="text" name="tramo" placeholder="Nombre del Tramo" id="tramo" class="form-control"
                                   v-model="actualizar.tramo">
                        </div>
                        <!--<div class="form-group">
                            <label for="description">Description:</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Task Description" v-model="update_task.description"></textarea>
                        </div>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" @click="ActualizarColectivo" class="btn btn-primary">Actualizar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</template>

<script>
    export default {
        data(){
            return {
                colectivo: {
                    tramo: '',
                },
                errors: [],
                colectivos: [],
                actualizar: []
            }
        },
        mounted()
        {
            this.LeerColectivos();
        },
        methods: {
            iniciarRegistro()
            {
                $("#anadir_colectivo_modelo").modal("show");
            },
            crearColectivo()
            {
                axios.post('/colectivo', {
                    tramo: this.colectivo.tramo,
                    
                })
                    .then(response => {
                        
                        this.LeerColectivos();
                        
                        this.reset();

                        $("#anadir_colectivo_modelo").modal("hide");


                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.errors != undefined && error.errors.name != undefined) {
                            this.errors.push(error.errors.name[0]);
                        }

                        if (error.errors != undefined && error.errors.description != undefined) {
                            this.errors.push(error.errors.description[0]);
                        }
                    });
            },
            reset()
            {
                this.colectivo.tramo = '';
            },
            resetActualizar()
            {
                this.actualizar.tramo = '';
                
            },
            LeerColectivos()
            {
                axios.get('/colectivo')
                    .then(response => {

                        this.colectivos = response.data.colectivos;

                    });
            },
            IniciarActualizacion(id)
            {
                this.errors = [];
                this.resetActualizar();
                $("#actualizar_modelo").modal("show");
                this.actualizar = this.colectivos[id];
            
            },
            ActualizarColectivo()
            {
                axios.patch('/colectivo/' + this.actualizar.id, {
                    tramo: this.actualizar.tramo,
                    
                })
                    .then(response => {
                        $("#actualizar_modelo").modal("hide");
                        
                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.errors != undefined && error.errors.name != undefined) {
                            this.errors.push(error.errors.name[0]);
                        }
                        if (error.errors != undefined && error.errors.description != undefined) {
                            this.errors.push(error.errors.description[0]);
                        }
                    });
            },
            EliminarColectivo(id)
            {
                let conf = confirm("De verdad quiere borrar este Colectivo?");
                if (conf) {

                    axios({
                        method:'delete',
                        url:'/colectivo/'+id
                        })
                        .then(
                            response => {

                                this.LeerColectivos();

                        })

                        .catch(error => {

                        });
                }
            }
        }
    }
</script>