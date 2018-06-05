@extends("layouts.plantilla")
    @section("content")
        <center><h2 style="color:#40B893">Registrar nuevo usuario</h2></center>
        <br>
        <form action="{{route('usuarios.store')}}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <p class="lead">Nombre</p>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <p class="lead">Email</p>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <p class="lead">Password</p>
                <input type="password" name="password" class="form-control" required>
            </div>
            <center><button class="btn btn-success">Registrar usuario</button></center>
        </form>

    @endsection