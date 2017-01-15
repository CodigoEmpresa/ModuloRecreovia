@extends('master')                              

@section('content')    
    <div class="content">
    	<div class="row">
    		@if ($programadas && in_array('Gestor', $_SESSION['Usuario']['Roles']))
    			@if (count($programadas->where('Estado', 'Diligenciado')->all()))
		    		<div class="col-md-12">
		    			<div class="alert alert-dismissible alert-warning">
					  		<p><strong>Atención!</strong>. Hay ({{ count($programadas->where('Estado', 'Diligenciado')->all()) }}) sesiones pendientes por revisar, para consultarlas has click <a href="{{ url('/gestores/sesiones') }}" class="alert-link">aquí</a></p>
						</div>
		    		</div>
		    	@else
		    		<div class="col-md-12">
		    			<div class="alert alert-dismissible alert-success">
					  		<p><strong>Bien!</strong>. En el momento no hay ninguna sesión pendiente por revisar, para programar nuevas sesiónes has click <a href="{{ url('programacion') }}" class="alert-link">aquí</a></p>
						</div>
		    		</div>
    			@endif
    		@endif
    	</div>
    	<div class="row">
    		<div class="col-md-3 col-sm-6">
				<div class="circle">
    				<h2>{{ count($recreopersona->puntos) }}</h2>
    				<small> PUNTOS <br> ASIGNADOS </small>
				</div>
			</div>
    		@if ($programadas && in_array('Gestor', $_SESSION['Usuario']['Roles']))
    			<div class="col-md-3 col-sm-6">
    				<div class="circle">
	    				<h2>{{ count($programadas->where('Estado', 'Aprobado')->all()) }}</h2>
	    				<small> SESIONES <br> PROGRAMADAS </small>
    				</div>
    			</div>
    			<div class="col-md-3 col-sm-6">
    				<div class="circle">
    					<?php
    						$total_participaciones = 0;
    						$grupo_impacto = [];
    						foreach ($programadas as $sesion) 
    						{
    							if ($sesion->gruposPoblacionales)
    							{
	    							foreach ($sesion->gruposPoblacionales as $grupo) 
	    							{
	    								$total_participaciones += $grupo->pivot['Grupo_Asistencia'] == 'Participantes' ? $grupo->pivot['Cantidad'] : 0;
	    							}
    							}
    						}
    					?>
	    				<h3>{{ $total_participaciones }}</h3>
	    				<small> PARTICIPANTES <br> REGISTRADOS </small>
    				</div>
    			</div>
    			<div class="col-md-3 col-sm-6">
    				<div class="circle">
	    				<h3>0 a 5 </h3>
	    				<small>AÑOS GRUPO MAS <br> IMPACTADO </small>
    				</div>
    			</div>
    		@endif
    	</div>
    	<div class="row">
    		<br>
    	</div>
    </div>
@stop
