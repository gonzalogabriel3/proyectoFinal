<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button @click="iniciarRegistro()" class="btn btn-primary btn-xs pull-right">
                            + Crear Nueva Parada
                        </button>
                        <h4 style="color:#6DD5B7">Paradas Registradas</h4>
                    </div>
                    
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="paradas.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Latitud
                                </th>
                                <th>
                                    Longitud
                                </th>    
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="parada in paradas" :key="parada.id">
                                <td>{{ parada.id }}</td>
                                <td>
                                    {{ parada.nombre }}
                                </td>
                                <td>
                                    {{ parada.latitud }}
                                </td>
                                <td>
                                    {{ parada.longitud}}
                                </td>
                                <td>
                                    <button @click="IniciarActualizacion(parada)" class="btn btn-success btn-xs">Editar</button>
                                    <button @click="Eliminar(parada.id)" class="btn btn-danger btn-xs">Eliminar</button>
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
                        <h4 class="modal-title">Registrar Parada</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors" :key="error.id">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Nombre de la parada:</label>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre de la Parada" class="form-control"
                                   v-model="parada.nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud de la parada:</label>
                            <input type="text" name="latitud" id="latitud" placeholder="Latitud de la Parada" class="form-control"
                                   v-model="parada.latitud">
                        </div>
                        <div class="form-group">
                            <label for="name">Longitud de la parada:</label>
                            <input type="text" name="longitud" id="longitud" placeholder="Longitud de la Parada" class="form-control"
                                   v-model="parada.longitud">
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
                        <h4 class="modal-title">Actualizar Parada</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors" :key="error.id">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre de la parada:</label>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre de la Parada" class="form-control"
                                   v-model="actualizar.nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud de la parada:</label>
                            <input type="text" name="latitud" id="latitud" placeholder="Latitud de la Parada" class="form-control"
                                   v-model="actualizar.latitud">
                        </div>
                        <div class="form-group">
                            <label for="name">Longitud de la parada:</label>
                            <input type="text" name="longitud" id="longitud" placeholder="Longitud de la Parada" class="form-control"
                                   v-model="actualizar.longitud">
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
            parada: {
                nombre: '',
                latitud: '',
                longitud: '',
            },
            create: false,
            update: false,
            errors: [],
            paradas: [],
            actualizar: {
                nombre: '',
                latitud: '',
                longitud: '',
            },
        }
    },
    mounted()
    {
        this.Leer();
    },
    methods: {
        iniciarRegistro()
        {
           this.create = true;
        },
        closeCreate(){
            this.create = false;
        },
        Crear() {
            axios.post('/parada', {
                nombre: this.parada.nombre,
                latitud: this.parada.latitud,
                longitud: this.parada.longitud
             })
            .then(response => {
                
                this.reset();
                this.create = false;
                this.Leer();
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
            this.parada.nombre = '';
            this.parada.latitud = '';
            this.parada.longitud = '';
        },
        resetActualizar() {
            this.actualizar.nombre = '';
            this.actualizar.latitud = '';
            this.actualizar.longitud = '';
        },
        Leer() {
            axios.get("/parada").then(response => {
            this.paradas = response.data.paradas;
        });
        },
        IniciarActualizacion(parada) {
            this.actualizar.id = parada.id;
            this.actualizar.nombre = parada.nombre;
            this.actualizar.latitud = parada.latitud;
            this.actualizar.longitud = parada.longitud;
            this.update = true;
        },
        closeUpdate(){
            this.update = false;
        },
        Actualizar() {
            axios
            .patch("/parada/" + this.actualizar.id, {
                nombre: this.actualizar.nombre,
                latitud: this.actualizar.latitud,
                longitud: this.actualizar.longitud
            })
            .then(response => {
                this.resetActualizar();
                this.update = false;
                this.Leer();
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
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar esta Parada?");
            if (conf) {
            axios({
                method: "delete",
                url: "/parada/" + id
            })
            .then(response => {
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>