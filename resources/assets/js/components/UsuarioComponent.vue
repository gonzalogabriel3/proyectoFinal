<template>
 <div>
    <h2 style="color:grey">Usuarios registrados</h2>
        <br>
        <button @click="formularioRegistro()" class="btn btn-success">Nuevo usuario&nbsp;<span class="glyphicon glyphicon-plus"></span></button>
        <br><br>
        <!--Listado de usuarios-->
        <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th style="color:green"><b>Id</b></th>
                <th style="color:green"><b>Nombre de usuario</b></th>
                <th style="color:green"><b>Email</b></th>
                <th style="color:green">Acciones</th>
            </tr>
            <tr v-for="usuario in usuarios" :key="usuario.id">
                    <th>{{usuario.id}}</th>
                    <th>{{usuario.nombre}}</th>
                    <th>{{usuario.email}}</th>
                    <th>
                        <button @click="formularioEditar(usuario.id)" class="btn btn-info">Editar&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil"></span></button>
                        <button @click="deleteUsuario(usuario.id)"  class="btn btn-danger">Borrar&nbsp;&nbsp;<span class="glyphicon glyphicon-trash"></span></button>
                    </th>
            </tr>
            </thead>
        </table>
        <!--Fin de listado de usuarios-->
        
        <!--Formulario de registro de usuario-->
        
        <!--Fin de formulario de registro-->
 </div>
</template>
<script>
export default{
    data(){
        return {
                usuario: {
                    nombre: '',
                    password:'',
                    email: ''
                },
                errors: [],
                usuarios: [],
                update_usuario: []
            }
        },
        mounted(){
            this.cargarUsuarios();
        },
        methods:{
            /*Metodo que obtiene todos los usuarios y los carga en el arreglo 'usuarios' del componente vue,
            para poder visualizarlo en pantalla*/
            cargarUsuarios(){ 
                axios.get('/usuarios').then(response => {
                    this.usuarios=response.data.usuarios;                    
                });
            },
            formualrioRegistro(){
               $("#formRegistro").modal("show");
            }
        },

}

</script>