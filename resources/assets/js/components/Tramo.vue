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
                            + Crear Nuevo Tramo
                        </button>
                        <h4 style="color:#6DD5B7">Tramos Registrados</h4>
                    </div>
                    
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="tramos.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Lugar de Inicio
                                </th>
                                <th>
                                    Lugar de Fin
                                </th>
                                <th>
                                    Nro de Recorrido    
                                </th>    
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="tramo in tramos" :key="tramo.id">
                                <td>
                                    {{ tramo.id }}
                                </td>
                                <td>
                                    {{ tramo.nombre }}
                                </td>
                                <td>
                                    {{ tramo.iniciola }} / {{ tramo.iniciolo }}
                                </td>
                                <td>
                                    {{ tramo.finla }} / {{ tramo.finlo }}
                                </td>
                                <td>
                                    {{ tramo.recorrido_id }}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(tramo)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="Eliminar(tramo.id)" class="btn btn-danger btn-xs">Eliminar</button>
                                    <button @click="iniciarMostrar(tramo.id)" class="btn btn-info btn-xs">Mostrar Colectivos</button>
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
                        <h4 class="modal-title">Registrar Tramo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del Tramo:</label>
                            <br>
                            <span v-if="tramo.nombre.trim().length<4" class="label label-danger" >El nombre del tramo debe contener al menos 4 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del Tramo" class="form-control"
                                   v-model="tramo.nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Lugar de Incio del Recorrido:</label>
                            <br>
                            <span v-if="!tramo.inicio" class="label label-danger">Debe Seleccionar un Lugar de Inicio</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <select v-model="tramo.inicio">
                                <option v-for="parada in paradas" :key="parada.id" v-bind:value="parada.id">{{parada.nombre}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Lugar de Fin del Recorrido:</label>
                            <br>
                            <span v-if="!tramo.fin" class="label label-danger">Debe Seleccionar un Lugar de Fin</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <select v-model="tramo.fin">
                                <option v-for="parada in paradas" :key="parada.id" v-bind:value="parada.id">{{parada.nombre}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Recorrido al que Pertenece el Tramo:</label>
                            <br>
                            <span v-if="!tramo.recorrido_id" class="label label-danger">Debe Seleccionar un Recorrido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <select v-model="tramo.recorrido_id">
                                <option v-for="recorrido in recorridos" :key="recorrido.id" v-bind:value="recorrido.id">{{recorrido.nombre}}</option>
                            </select> 
                        </div>
                        <div class="form-group">
                            <label for="name">Colectivos que Recorren este Tramo:</label>
                            <br>
                            <span v-if="!tramo.colectivos.length > 0" class="label label-danger">Debe Seleccionar un Colectivo Por lo menos</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <br>
                            <!--Se cambio type de text a number-->
                            <select v-model="tramo.colectivos" multiple>
                                <option v-for="colectivo in colectivos" :key="colectivo.id" v-bind:value="colectivo.id">Empresa: {{colectivo.empresa}} / Nro: {{colectivo.num_coche}} </option>
                            </select> 
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
                        <h4 class="modal-title">Actualizar Tramo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del Tramo:</label>
                            <br>
                            <span v-if="actualizar.nombre.trim().length<4" class="label label-danger" >El nombre del tramo debe contener al menos 4 caracteres</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del tramo" class="form-control"
                                   v-model="actualizar.nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Lugar de Incio del Recorrido:</label>
                            <br>
                            <span v-if="!actualizar.inicio" class="label label-danger">Debe Seleccionar un Lugar de Inicio</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <select v-model="actualizar.inicio">
                                <option v-for="parada in paradas" :key="parada.id" v-bind:value="parada.id">{{parada.nombre}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Lugar de Fin del Recorrido:</label>
                            <br>
                            <span v-if="!actualizar.fin" class="label label-danger">Debe Seleccionar un Lugar de Fin</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <select v-model="actualizar.fin">
                                <option v-for="parada in paradas" :key="parada.id" v-bind:value="parada.id">{{parada.nombre}}</option>
                            </select>
                        </div>
                         <div class="form-group">
                            <label for="name">Recorrido al que Pertenece el Tramo:</label>
                            <br>
                            <span v-if="!actualizar.recorrido_id" class="label label-danger">Debe Seleccionar un Recorrido</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <select v-model="actualizar.recorrido_id">
                                <option v-for="recorrido in recorridos" :key="recorrido.id" v-bind:value="recorrido.id">{{recorrido.nombre}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Colectivos que Recorren este Tramo:</label>
                            <br>
                            <span v-if="!actualizar.colectivos" class="label label-danger">Debe Seleccionar un Colectectivo Por lo menos</span>
                            <span v-else class="label label-success">Correcto!</span>
                            <!--Se cambio type de text a number-->
                            <select v-model="actualizar.colectivos" multiple>
                                <option v-for="colectivo in colectivos" :key="colectivo.id" v-bind:value="colectivo.id">Empresa: {{colectivo.empresa}} / Nro: {{colectivo.num_coche}} </option>
                            </select> 
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeUpdate">Cerrar</button>
                        <button type="button" @click="Actualizar" class="btn btn-primary">Actualizar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!--MODAL PARA MOSTRAR COLECTIVOS-->
        <div class="modal show" id="mostrar" v-if="mostrar" tabindex="-1" role="dialog" aria-labelledby="mostrar">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeMostrar" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Colectivos que recorren este tramo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new-todo">Colectivos</label>
                            <ul v-for="colectivo in colectivost" :key="colectivo.id">
                                <li>
                                    Numero de Coche: {{colectivo.num_coche}} | Empresa: {{colectivo.empresa}} 
                                </li>
                            </ul>
                            <br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeMostrar">Cerrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal --> 
    </div></template>

<script>
export default {
    data(){
        return {
            tramo: {
                recorrido_id: '',
                inicio: '',
                fin: '',
                nombre:'',
                colectivos:[]
            },
            mensaje:'',
            create: false,
            update: false,
            mostrar: false,
            tramos: [],
            paradas:[],
            recorridos:[],
            colectivost:[],
            actualizar: {
                recorrido_id: '',
                inicio: '',
                fin: '',
                nombre:'',
                colectivos:[]
            },
        }
    },
    mounted()
    {
        this.Leer();
        this.Paradas();
        this.Recorridos();
        this.Colectivos();

    },
    methods: {
        ColectivosTramo(id){
            axios.get("/tramo/" + id).then(response => {
            this.colectivost = response.data.colectivos;
            });
        },
        iniciarMostrar(id){
           this.ColectivosTramo(id);
           this.mostrar = true;
        
        },
        closeMostrar(){
            this.mostrar = false;
        },
        Paradas() {
            axios.get("/parada").then(response => {
            this.paradas = response.data.paradas;
        });
        },
        Recorridos() {
            axios.get("/recorrido").then(response => {
            this.recorridos = response.data.recorridos;
        });
        },
        Colectivos() {
            axios.get("/colectivo").then(response => {
            this.colectivos = response.data.colectivos;
        });
        },
        validarRegistro(){ //validacion del formulario de registro
            if(this.tramo.nombre.trim().length<4 || !this.tramo.recorrido_id || !this.tramo.inicio || !this.tramo.fin || !this.tramo.colectivos){
                return false;
            }else{
                return true;
            }
        },
        validarActualizacion(){ //validacion del formulario de actualizacion
            if(this.actualizar.nombre.trim().length<4 || !this.actualizar.recorrido_id || !this.actualizar.inicio || !this.actualizar.fin || !this.tramo.colectivos){
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
                axios.post('/tramo', {
                    nombre: this.tramo.nombre,
                    inicio: this.tramo.inicio,
                    fin: this.tramo.fin,
                    recorrido_id: this.tramo.recorrido_id,
                    colectivos: this.tramo.colectivos
                })
                .then(response => {
                    
                    this.mensaje="Tramo creado correctamente";
                    this.reset();
                    this.create = false;
                    this.Leer();
                });
            }else{
                return;
            }
        },
        reset() {
            this.tramo.nombre = '';
            this.tramo.inicio = '';
            this.tramo.fin = '';
            this.tramo.recorrido_id = '';
            this.tramo.colectivos = [];
        },
        resetActualizar() {
            this.actualizar.nombre = '';
            this.actualizar.inicio = '';
            this.actualizar.fin = '';
            this.actualizar.recorrido_id = '';
            this.actualizar.colectivos = [];        
        },
        Leer() {
            axios.get("/tramo").then(response => {
            this.tramos = response.data.tramos;
        });
        },
        IniciarActualizacion(tramo) {
            this.actualizar.id = tramo.id;
            this.actualizar.nombre = tramo.nombre;
            this.actualizar.inicio = tramo.inicio;
            this.actualizar.fin = tramo.fin;
            this.actualizar.recorrido_id = tramo.recorrido_id;
            this.actualizar.colectivos = tramo.colectivos;
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            if(this.validarActualizacion()){
                axios.patch("/tramo/" + this.actualizar.id, {
                    nombre: this.actualizar.nombre,
                    inicio: this.actualizar.inicio,
                    fin: this.actualizar.fin,
                    recorrido_id: this.actualizar.recorrido_id,
                    colectivos: this.actualizar.colectivos
                })
                .then(response => {
                    this.mensaje="Datos de Tramo actualizados";
                    this.resetActualizar();
                    this.update = false;
                    this.Leer();
                });
            }else{
                return;
            }    
        },
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar este Tramo?");
            if (conf) {
            axios({
                method: "delete",
                url: "/tramo/" + id
            })
            .then(response => {
                this.mensaje="Tramo eliminado correctamente";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>