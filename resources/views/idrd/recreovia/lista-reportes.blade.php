@section('script')
    @parent

    <!--<script src="{{ asset('public/Js/profesores/buscador.js') }}"></script>-->
@stop
    
<div class="content">
    <div id="main" class="row" data-url="{{ url('profesores') }}">
        @if ($status == 'success')
            <div id="alerta" class="col-xs-12">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Datos actualizados satisfactoriamente.
                </div>                                
            </div>
        @endif
        <div class="col-xs-12 form-group">
            <div class="input-group">
                <input name="buscador" type="text" class="form-control" placeholder="Buscar" id="buscador">
                <span class="input-group-btn">
                    <button id="buscar" data-role="buscar" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </span>
            </div>
        </div>
        <div class="col-xs-12">
            <a class="btn btn-primary" href="{{ url('informes/jornadas/crear') }}">Crear</a>
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            Total de reportes creados: {{ count($elementos) }}
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <ul class="list-group" id="personas">
                @foreach($elementos as $reporte)
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            {{ $reporte->punto->toString() }}
                            <a href="{{ url('/informes/jornadas/'.$reporte['Id'].'/editar') }}" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                        </h5>
                        <p class="list-group-item-text">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <small>
                                                Reporte de actividades del punto {{ $reporte->toString() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                        <span class="label label-default capitalize">Profesores: {{ count($reporte->profesores) }}</span>
                        <span class="label label-default capitalize">
                            Sesiones: {{ 
                                count($reporte->cronograma->sesiones
                                    ->filter(function($item) use ($reporte){ 
                                        return $item->Fecha == $reporte->Dia; 
                                    })->filter(function($item) { 
                                        return $item->Estado == 'Aprobado'; 
                                    })->all()) 
                            }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="paginador" class="col-xs-12">{!! $elementos->render() !!}</div>
    </div>
</div>