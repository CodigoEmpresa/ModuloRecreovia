@section('script')
    @parent

    <script src="{{ asset('public/Js/puntos/buscador.js') }}"></script>
@stop
    
<div class="content">
    <div id="main" class="row" data-url="{{ url('puntos') }}">
        <div id="alerta" class="col-xs-12" style="display:none;">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Datos actualizados satisfactoriamente.
            </div>                                
        </div>
        <div class="col-xs-12 form-group">
            <div class="input-group">
                <input name="buscador" type="text" class="form-control" placeholder="Buscar" id="buscador">
                <span class="input-group-btn">
                    <button id="buscar" data-role="buscar" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </span>
            </div>
        </div>
        <div class="col-xs-12">
            <a href="{{ url('/puntos/crear/') }}" class="btn btn-primary" id="crear">Crear</a>
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <ul class="list-group" id="principal">
                @foreach($elementos as $punto)
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            {{ strtoupper($punto['Escenario']) }}
                            <a data-role="editar" href="{{ url('puntos/editar/'.$punto['Id_Punto']) }}" class="pull-right btn btn-primary btn-xs">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                        </h5>
                        <p class="list-group-item-text">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <small>Dirección: {{ $punto['Direccion'] }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                        <span class="label label-default">{{ $punto->localidad['Localidad'] }}</span> 
                        <span class="label label-default">{{ $punto->upz['Upz'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="paginador" class="col-xs-12">{!! $elementos->render() !!}</div>
    </div>
</div>