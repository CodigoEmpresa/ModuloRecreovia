@extends('master')

@section('script')
	@parent
	<script src="{{ asset('public/Js/dashboard.js') }}"></script>
@stop

@section('content')
    <div class="content">
    	<?php
    		$perfil = '';

    		if(in_array('Gestor', $_SESSION['Usuario']['Roles']))
    		{
    			$perfil = 'Gestor';
    		} elseif (in_array('Profesor', $_SESSION['Usuario']['Roles'])) {
    			$perfil = 'Profesor';
    		}
    	?>
    	<input type="hidden" name="perfil" value="{{ $perfil }}">
    	<div class="row">
    		<div class="col-md-12">
    			<br>
    		</div>
    	</div>
		<div class="row">
			<div id="alerta" class="col-xs-12">
	            <div class="alert alert-info alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong><i class="fa fa-info-circle" aria-hidden="true"></i> Notas de la versión</strong> <br><br>
                    <ul>
						<li>Se solucionó el error que borraba los datos diligenciados al regenerar un informe de jornada.</li>
						<li>Estados para los informes de jornadas</li>
						<li>Visualización de la calificación del servicio y el producto no conforme en los informes de jornadas</li>
						<li>--</li>
                    	<li>Ajustes de seguridad.</li>
						<li>Nuevo estado para los reportes de jornadas (Finalizado) el cual bloquea la edición.</li>
						<li>Nueva opción en las sesiones para permitir que el gestor sea quien asuma la ejecución de la sesión.</li>
						<li>Se agregó filtro por localidad al buscador de informes de jornadas.</li>
                    	<li>Se creó un buscador público de sesiones. <a href="https://www.idrd.gov.co/SIM/ModuloRecreovia/buscador" target="_blank">www.idrd.gov.co/SIM/ModuloRecreovia/buscador</a></li>
                    </ul>
	            </div>
	        </div>
		</div>
		<div class="row">
    		<div class="col-md-12">
    			<br>
    		</div>
    	</div>
    	<div class="row">
			@if($sesiones)
				@if (in_array('Gestor', $_SESSION['Usuario']['Roles']))
					@if ($sesiones->where('Estado', 'Diligenciado')->count())
						<div class="col-md-12">
							<p class="lead">
								Hay ({{ $sesiones->where('Estado', 'Diligenciado')->count() }}) sesiones pendientes por revisar, para consultarlas has click <a href="{{ url('/gestores/sesiones') }}" class="alert-link">aquí.</a>
							</p>
						</div>
					@else
						<div class="col-md-12">
							<p class="lead">
								En el momento no hay ninguna sesión pendiente por revisar, para programar nuevas sesiónes has click <a href="{{ url('programacion') }}" class="alert-link">aquí.</a>
							</p>
						</div>
					@endif
				@elseif (in_array('Profesor', $_SESSION['Usuario']['Roles']))
					@if ($sesiones->whereIn('Estado', ['Pendiente', 'Rechazado', 'Corregir'])->count())
						<div class="col-md-12">
							<p class="lead">
								Hay ({{ $sesiones->whereIn('Estado', ['Pendiente', 'Rechazado', 'Corregir'])->count() }}) sesiones pendientes por revisar, para consultarlas has click <a href="{{ url('/profesores/sesiones') }}" class="alert-link">aquí.</a>
							</p>
						</div>
					@else
						<div class="col-md-12">
							<p class="lead">
								Al parecer no tiene ninguna sesión pendiente por revisar en el momento, para verificar mas adelante haga click en <a href="{{ url('/profesores/sesiones') }}">consultar sesiones</a>
							</p>
						</div>
					@endif
				@endif
			@endif
    	</div>
    	<div class="row">
    		<br>
    	</div>
    </div>
@stop
