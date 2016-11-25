@section('script')
    @parent

    <script src="{{ asset('public/Js/puntos/buscador.js') }}"></script>
@stop
    
<div class="content">
    <div id="main" class="row" data-url="{{ url('jornadas') }}">
        <div id="alerta" class="col-xs-12" style="display:none;">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Datos actualizados satisfactoriamente.
            </div>                                
        </div>
        <div class="col-xs-12">
            <a href="{{ url('/jornadas/crear/') }}" class="btn btn-primary" id="crear">Crear</a>
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <ul class="list-group" id="principal">
                @foreach($elementos as $jornada)
                	<?php
                		$label = '';
                		$titulo = '';
				        $periodo = '';
				        $periodo_dias = '';

				        if($jornada->Fecha_Evento_Fin)
				            $periodo = 'del '.$jornada->Fecha_Evento_Inicio.' al '.$jornada->Fecha_Evento_Fin;
				        else
				            $periodo = 'el dia '.$jornada->Fecha_Evento_Inicio;
				        
				        if($jornada->Dias)
				            periodo_dias = (count(explode(',', $jornada->Dias)) > 1 ? 'los dias ' : 'el dia ').$jornada->Dias;

				        switch($jornada->Jornada)
				        {
				            case 'dia': 
				                label = 'Jornada diurna '+periodo_dias+' de '+$jornada->Inicio+' a '+$jornada->Fin;
				            break;
				            case 'noche': 
				                label = 'Jornada nocturna '+periodo_dias+' de '+$jornada->Inicio+' a '+$jornada->Fin;
				            break;
				            case 'fds': 
				                label = 'Jornada de fin de semana '+periodo_dias+' de '+$jornada->Inicio+' a '+$jornada->Fin;
				            break;
				            case 'clases_grupales':
				                label = 'Clase grupal '+periodo+' '+periodo_dias+' de '+$jornada->Inicio+' a '+$jornada->Fin;
				            break;
				            case 'mega_eventos': 
				                label = 'Mega evento de actividad física '+periodo+' '+periodo_dias+' de '+$jornada->Inicio+' a '+$jornada->Fin;
				            break;
				        }
                	?>
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            Jornada {{ strtoupper($punto['Escenario']) }}
                            <a data-role="editar" href="{{ url('jornadas/editar/'.$jornada['Id_Jornada']) }}" class="pull-right btn btn-primary btn-xs">
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