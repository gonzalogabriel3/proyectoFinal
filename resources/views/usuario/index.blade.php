@extends("layouts.plantilla")
    @section("content")
        <h2 style="color:grey">Usuarios registrados</h2>
        <br>
            <a href="{{route('usuarios.create')}}" class="btn btn-success">Nuevo usuario&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        <br><br>
        <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th style="color:green"><b>Id</b></th>
                <th style="color:green"><b>Nombre de usuario</b></th>
                <th style="color:green"><b>Email</b></th>
                <th style="color:green">Acciones</th>
            </tr>
            @foreach($usuarios as $usuario)
                <tr>
                    <th>{{$usuario->id}}</th>
                    <th>{{$usuario->nombre}}</th>
                    <th>{{$usuario->email}}</th>
                    <th>
                        <button class="btn btn-info">Editar&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil"></span></button>
                        <a class="btn btn-danger" href="{{route('usuarios.destroy',$usuario->id)}}" onclick="return confirm('Eliminar al usuario {{$usuario->nombre}}?');">Borrar&nbsp;&nbsp;<span class="glyphicon glyphicon-trash"></span></a>
                    </th>
                </tr>
            @endforeach
            </thead>
        </table>
    @endsection
    