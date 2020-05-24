@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" style="background:#9CD1DA;"><b>Administracion</b></div>

                <div class="panel-body" style="background:#A4E0EA;">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="linkMenu">
                        <a href="{{url('/inicioUsuario')}}"><span class="glyphicon glyphicon-user"></span> Usuarios</a>
                    </div>
                    <div class="linkMenu">
                        <a href="{{url('/inicioColectivos')}}"><span class="glyphicon glyphicon-bed"></span> Colectivos</a>
                    </div>
                    <div class="linkMenu">
                        <a href="{{url('/inicioParadas')}}"><span class="glyphicon glyphicon-modal-window"></span> Paradas</a>
                    </div>
                    <br></br>
                    <div class="linkMenu">
                        <a href="{{url('/inicioTarifas')}}"><span class="glyphicon glyphicon-usd"></span> Tarifas</a>
                    </div>
                    <div class="linkMenu">
                        <a href="{{url('/inicioHorarios')}}"><span class="glyphicon glyphicon-time"></span> Horarios</a>
                    </div>
                    <div class="linkMenu">
                        <a href="{{url('/inicioRecorridos')}}"><span class="glyphicon glyphicon-road"></span> Recorridos</a>
                    </div>
                    <br></br>
                    <div class="linkMenu">
                        <a href="{{url('/inicioTramos')}}"><span class="glyphicon glyphicon-resize-full"></span>  Tramos</a>
                    </div>
                    <div class="linkMenu">
                        <a href="{{url('/inicioPuntosRecarga')}}"><span class="glyphicon glyphicon-asterisk"></span> Pts.Recarga</a>
                    </div>
                    <div class="linkMenu">
                        <a href="{{url('/inicioMapa')}}"><span class="glyphicon glyphicon-pushpin"></span> MAPA</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
