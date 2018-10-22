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
                            + Guardar nueva Posicion
                        </button>
                    </div>
                    
                    <div class="panel-body">
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
                        <h4 class="modal-title">Registrar Punto de Recarga</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del punto de Recarga:</label>
                            <br>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre de la punto" class="form-control"
                                   v-model="punto.id">
                        </div>
                        <div class="form-group">
                            <label for="name">Latitud del punto de recarga:</label>
                            <br>
                            <!--Se cambio type de text a number-->
                            <input type="number" name="latitud" id="latitud" placeholder="Latitud del punto de recarga" class="form-control"
                                   v-model="punto.latitud">
                        </div>
                        <div class="form-group">
                            <label for="name">Longitud del punto de recarga:</label>
                            <br>
                            <input type="number" name="longitud" id="longitud" placeholder="Longitud del punto de recarga" class="form-control"
                                   v-model="punto.longitud">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeCreate">Cerrar</button>
                        <button type="button" @click="Crear" class="btn btn-primary">Registrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div></template>

<script>
export default {
    data(){
        return {
            punto: {
                id: '',
                latitud: '',
                longitud: '',
            },
            mensaje:'',
            create: false,
        }
    },
    mounted()
    {

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
        Crear() {
                axios.post('/posicionUsuario', {
                    id: this.punto.id,
                    latitud: this.punto.latitud,
                    longitud: this.punto.longitud,
                })
                .then(response => {
                    this.mensaje=response.data.message;
                    this.reset();
                    this.create = false;
                });
        },
        reset() {
            this.punto.nombre = '';
            this.punto.latitud = '';
            this.punto.longitud = '';
        }
    }
};
</script>