@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Administracion</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul>
                        <li>
                            <a href="{{url('/inicioUsuario')}}">Adm.Usuarios</a>
                        </li>
                        <li>
                            <a href="{{url('/inicioColectivos')}}" >Adm.Colectivos</a>
                        </li>
                        <li>
                            <a href="{{url('/inicioParadas')}}" >Adm.Paradas</a>
                        </li>
                        <li>
                            <a href="{{url('/inicioTarifas')}}" >Adm.Tarifas</a>
                        </li>
                        <li>
                            <a href="{{url('/inicioHorarios')}}" >Adm.Horarios</a>
                        </li>
                        <li>
                            <a href="{{url('/inicioRecorridos')}}" >Adm.Recorridos</a>
                        </li>
                        <li>
                            <a href="{{url('/inicioTramos')}}" >Adm.Tramos</a>
                        </li>
                        <li>
                            <a href="{{url('/inicioPuntosRecarga')}}" >Adm.Puntos de Recarga</a>
                        </li>
                        <li>
                            <a href="{{url('/inicioMapa')}}" >MAPA</a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
