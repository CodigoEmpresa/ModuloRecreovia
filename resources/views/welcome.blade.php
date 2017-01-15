@extends('master')                              

@section('content')    
    <div class="content">
    	<div class="row">
    		<div class="col-md-12">
    			<br>
    		</div>
    	</div>
    	<div class="row">
    		@if (in_array('Gestor', $_SESSION['Usuario']['Roles']))
    			@if (count($programadas->where('Estado', 'Diligenciado')->all()))
	    			<div class="col-md-12">
						<p class="lead">
							Hay ({{ count($programadas->where('Estado', 'Diligenciado')->all()) }}) sesiones pendientes por revisar, para consultarlas has click <a href="{{ url('/gestores/sesiones') }}" class="alert-link">aquí.</a>
						</p>
	    			</div>
		    	@else
			    	<div class="col-md-12">
	    				<p class="lead">
	    					En el momento no hay ninguna sesión pendiente por revisar, para programar nuevas sesiónes has click <a href="{{ url('programacion') }}" class="alert-link">aquí.</a>
	    				</p>
	    			</div>
    			@endif

				@if($programadas)
					<div class="col-md-12">
						<br>
					</div>
					<div class="col-md-12">
	    				<p class="lead">
	    					Este {{ date('Y') }}:
	    				</p>
	    			</div>
	    			<div class="col-md-12">
						<br>
					</div>
					<div class="row">
						<div class="col-md-12 resaltar">
							<div class="row">
								<div class="col-md-3 col-sm-6">
									<div class="center">
					    				<h4>{{ count($recreopersona->puntos) }}</h4>
					    				<small> PUNTOS <br> ASIGNADOS </small>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
				    				<div class="center">
					    				<h4>{{ count($programadas->where('Estado', 'Aprobado')->all()) }}</h4>
					    				<small> SESIONES <br> PROGRAMADAS </small>
				    				</div>
				    			</div>
				    			<div class="col-md-3 col-sm-6">
				    				<div class="center">
				    					<?php
				    						$total_participaciones = 0;
				    						$grupos_impacto = [];
				    						foreach ($programadas as $sesion) 
				    						{
				    							if ($sesion->gruposPoblacionales)
				    							{
					    							foreach ($sesion->gruposPoblacionales as $grupo) 
					    							{
					    								$total_participaciones += $grupo->pivot['Grupo_Asistencia'] == 'Participantes' ? $grupo->pivot['Cantidad'] : 0;
					    								
					    								if (!array_key_exists($grupo['Grupo'], $grupos_impacto))
					    									$grupos_impacto[$grupo['Grupo']] = ['Participantes' => ['M' => 0, 'F' => 0], 'Asistentes' => ['M' => 0, 'F' => 0], 'Edad' => $grupo['Edad_Inicio'].($grupo['Edad_Fin'] < 0 ? ' - mas' : ' a '.$grupo['Edad_Fin']).' años'];
					    								
					    								$grupos_impacto[$grupo['Grupo']][$grupo->pivot['Grupo_Asistencia']][$grupo->pivot['Genero']] += $grupo->pivot['Cantidad'];
					    							}
				    							}
				    						}
				    					?>
					    				<h4>{{ $total_participaciones }}</h4>
					    				<small> PARTICIPANTES <br> REGISTRADOS </small>
				    				</div>
				    			</div>
				    			<div class="col-md-3 col-sm-6">
									<?php
										$grupo_mayor_impacto = '';
										$total = 0;

										foreach ($grupos_impacto as $key => $grupo) 
										{
											$sub_total = $grupo['Participantes']['M'] + $grupo['Participantes']['F'] + $grupo['Asistentes']['M'] + $grupo['Asistentes']['F'];
											if($sub_total > $total)
											{

												$grupo_mayor_impacto = $key.'<br><small>'.$grupo['Edad'].'</small>';
											}
										}
									?>
				    				<div class="center">
					    				<h4>{!! $grupo_mayor_impacto !!}</h4>
					    				<small>GRUPO MAS IMPACTADO </small>
				    				</div>
				    			</div>
							</div>
						</div>
					</div>
				@endif
    		@elseif (in_array('Profesor', $_SESSION['Usuario']['Roles']))
				@if (count($asignadas->where('Estado', '!=', 'Aprobado')->all()))
	    			<div class="col-md-12">
						<p class="lead">
							Hay ({{ count($asignadas->where('Estado', '!=', 'Aprobado')->all()) }}) sesiones pendientes por revisar, para consultarlas has click <a href="{{ url('/gestores/sesiones') }}" class="alert-link">aquí.</a>
						</p>
	    			</div>
		    	@else
			    	<div class="col-md-12">
	    				<p class="lead">
	    					Al parecer no tiene ninguna sesión pendiente por revisar en el momento, para verificar mas adelante haga click en <a href="{{ url('/profesores/sesiones') }}">consultar sesiones</a>
	    				</p>
	    			</div>
    			@endif

				@if($asignadas)
					<div class="col-md-12">
						<br>
					</div>
					<div class="col-md-12">
	    				<p class="lead">
	    					Este {{ date('Y') }}:
	    				</p>
	    			</div>
	    			<div class="col-md-12">
						<br>
					</div>
					<div class="row">
						<div class="col-md-12 resaltar">
							<div class="row">
								<div class="col-md-3 col-sm-6">
									<div class="center">
					    				<h4>{{ count($recreopersona->puntos) }}</h4>
					    				<small> PUNTOS <br> ASIGNADOS </small>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
				    				<div class="center">
					    				<h4>{{ count($asignadas->where('Estado', 'Aprobado')->all()) }}</h4>
					    				<small> SESIONES <br> REALIZADAS </small>
				    				</div>
				    			</div>
				    			<div class="col-md-3 col-sm-6">
				    				<div class="center">
				    					<?php
				    						$total_participaciones = 0;
				    						$grupos_impacto = [];
				    						foreach ($asignadas as $sesion) 
				    						{
				    							if ($sesion->gruposPoblacionales)
				    							{
					    							foreach ($sesion->gruposPoblacionales as $grupo) 
					    							{
					    								$total_participaciones += $grupo->pivot['Grupo_Asistencia'] == 'Participantes' ? $grupo->pivot['Cantidad'] : 0;
					    								
					    								if (!array_key_exists($grupo['Grupo'], $grupos_impacto))
					    									$grupos_impacto[$grupo['Grupo']] = ['Participantes' => ['M' => 0, 'F' => 0], 'Asistentes' => ['M' => 0, 'F' => 0], 'Edad' => $grupo['Edad_Inicio'].($grupo['Edad_Fin'] < 0 ? ' - mas' : ' a '.$grupo['Edad_Fin']).' años'];
					    								
					    								$grupos_impacto[$grupo['Grupo']][$grupo->pivot['Grupo_Asistencia']][$grupo->pivot['Genero']] += $grupo->pivot['Cantidad'];
					    							}
				    							}
				    						}
				    					?>
					    				<h4>{{ $total_participaciones }}</h4>
					    				<small> PARTICIPANTES <br> REGISTRADOS </small>
				    				</div>
				    			</div>
				    			<div class="col-md-3 col-sm-6">
									<?php
										$grupo_mayor_impacto = '';
										$total = 0;

										foreach ($grupos_impacto as $key => $grupo) 
										{
											$sub_total = $grupo['Participantes']['M'] + $grupo['Participantes']['F'] + $grupo['Asistentes']['M'] + $grupo['Asistentes']['F'];
											if($sub_total > $total)
											{

												$grupo_mayor_impacto = $key.'<br><small>'.$grupo['Edad'].'</small>';
											}
										}
									?>
				    				<div class="center">
					    				<h4>{!! $grupo_mayor_impacto !!}</h4>
					    				<small>GRUPO MAS IMPACTADO </small>
				    				</div>
				    			</div>
							</div>
						</div>
					</div>
				@endif
    		@endif
    	</div>
    	<div class="row">
    		<br>
    	</div>
    </div>
@stop
