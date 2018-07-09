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
                        <h4 style="color:#6DD5B7"> Sugerencias Recibidas</h4>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="sugerencias.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Sugerencia
                                </th>
                                <th>
                                    Id de Usuario
                                </th>  
                                <th>
                                    Acciones
                                </th>
                            </tr>
                            <tr v-for="sugerencia in sugerencias" :key="sugerencia.id">
                                <td>
                                    {{ sugerencia.id }}
                                </td>
                                <td>
                                    {{ sugerencia.sugerencia }}
                                </td>
                                <td>
                                    {{sugerencia.usuario_id}}
                                </td>
                                <td>
                                    <button @click="Mostrar(sugerencia)" class="btn btn-success btn-xs">Mostrar</button>
                                    <button @click="Leida(sugerencia.id)" class="btn btn-info btn-xs">Marcar Como Leida</button>
                                    <button @click="NoLeida(sugerencia.id)" class="btn btn-warning btn-xs">Marcar Como No leida</button>
                                    <button @click="Eliminar(sugerencia.id)" class="btn btn-danger btn-xs">Eliminar</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal show" id="mostrar" v-if="mostrar" tabindex="-1" role="dialog" aria-labelledby="Mostrar">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeMostrar" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Sugerencia</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Numero de Sugerencia</label>
                            <p>{{sugerencia.id}}</p>                            
                        </div>

                        <div class="form-group">
                            <label for="">Id del Usuario</label>
                            <p>{{sugerencia.usuario_id}}</p>                            
                        </div>
                        <div class="form-group">
                            <label for="">Sugerencia Completa</label>
                            <p>{{sugerencia.sugerencia}}</p>                            
                        </div>
                        <div class="form-group">
                            <label for="">Estado de la Sugerencia</label>
                            <p v-if="sugerencia.estado">Leida</p>
                            <p v-else>No Leida</p>
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
           sugerencia: {
                id: '',
                sugerencia:'',
                estado: '',
                usuario_id:''
            },
            sugerencias:[],
            mensaje:'',
            mostrar: false,
        }
    },
    mounted()
    {
        this.Leer();
    },
    methods: {
        Leer() {
            axios.get("/sugerencia").then(response => {
            this.sugerencias = response.data.sugerencias;
        });
        },
        Mostrar(sugerencia) {
            this.sugerencia.id = sugerencia.id;
            this.sugerencia.usuario_id = sugerencia.usuario_id;
            this.sugerencia.sugerencia = sugerencia.sugerencia;
            this.sugerencia.estado = sugerencia.estado;
            this.mostrar = true;
        },
        closeMostrar(){
            this.sugerencia.id = '';
            this.sugerencia.sugerencia = '';
            this.sugerencia.estado = '';
            this.sugerencia.usuario_id = '';
            this.mostrar = false;

        },
        Leida(id) {

                axios.patch("/sugerencia/" + id, {
                    estado: true,
                })
                .then(response => {
                    this.closeMostrar();
                    this.mensaje="Estado Actualizado";
                    this.Leer();
                });    
        },
        NoLeida(id) {
                axios.patch("/sugerencia/" + id, {
                   estado: false,
                })
                .then(response => {
                    this.closeMostrar();
                    this.mensaje="Estado Actualizado";
                    this.Leer();
                });    
        },
        cerrarMensaje(){
            this.mensaje='';
        },
        Eliminar(id) {
            let conf = confirm("De verdad quiere borrar esta Sugerencia?");
            if (conf) {
            axios({
                method: "delete",
                url: "/sugerencia/" + id
            })
            .then(response => {
                this.mensaje="Sugerencia Eliminada";
                this.Leer();
            })
            .catch(error => {});
            }
        }
    }
};
</script>