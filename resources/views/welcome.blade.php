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

				@if(count($programadas))
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
					<div class="col-md-12">
						<div class="col-md-4 col-sm-6 resaltar">
		    				<div class="center">
			    				<h4>{{ count($programadas->where('Estado', 'Aprobado')->all()) }}</h4>
			    				<small> SESIONES <br> PROGRAMADAS </small>
		    				</div>
		    			</div>
		    			<div class="col-md-4 col-sm-6 resaltar">
		    				<div class="center">
		    					<?php
		    						$total_participaciones = 0;
		    						$grupos_impacto = [];
		    						$sesion_mayor_afluencia = null;
		    						$total_mayor_afluencia = 0;

		    						foreach ($programadas as $sesion) 
		    						{
		    							if ($sesion->gruposPoblacionales)
		    							{
		    								//obtener la sesion con mas afluencia
		    								if($sesion->gruposPoblacionales->sum('pivot.Cantidad') > $total_mayor_afluencia)
		    								{
		    									$total_mayor_afluencia = $sesion->gruposPoblacionales->sum('pivot.Cantidad');
		    									$sesion_mayor_afluencia = $sesion;
		    								}

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
		    			<div class="col-md-4 col-sm-12 resaltar">
							<?php
								$grupo_mayor_impacto = 'N/P<br><small>0</small>';
								$total = 0;
								foreach ($grupos_impacto as $key => $grupo) 
								{
									$sub_total = $grupo['Participantes']['M'] + $grupo['Participantes']['F'] + $grupo['Asistentes']['M'] + $grupo['Asistentes']['F'];
									
									if($sub_total > $total)
									{
										$total = $sub_total;
										$grupo_mayor_impacto = $key.'<br><small>'.$grupo['Edad'].'</small>';
									}
								}
							?>
		    				<div class="center">
			    				<h4>{!! $grupo_mayor_impacto !!}</h4>
			    				<small>GRUPO MAS IMPACTADO </small>
		    				</div>
		    			</div>
						<div class="col-md-12">
							<br><br>
						</div>
						<div class="col-md-6">
							<input type="hidden" name="grupos_impacto_participantes" data-json="{!! htmlspecialchars(json_encode($grupos_impacto)) !!}">
							<div id="grupos_impacto_participantes" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
						</div>
						<div class="col-md-6">
							<input type="hidden" name="grupos_impacto_asistentes" data-json="{!! htmlspecialchars(json_encode($grupos_impacto)) !!}">
							<div id="grupos_impacto_asistentes" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
						</div>
						<div class="col-md-12">
							<br><br>
						</div>
						@if ($sesion_mayor_afluencia)
			    			<div class="col-md-12">
								<p class="lead">La sesión con mayor afluencia fue:</p>
								{{ $sesion_mayor_afluencia->toSuccessString() }} <br>
								Realizada por: {{ $sesion->profesor ? $sesion->profesor->persona->toFriendlyString() : 'Sin profesor asignado' }} <br>
								<small>
									{{ $total_mayor_afluencia }} personas.
								</small>
							</div>
						@endif
					</div>
				@endif
    		@elseif (in_array('Profesor', $_SESSION['Usuario']['Roles']))
				@if (count($asignadas->whereIn('Estado', ['Pendiente', 'Rechazado', 'Corregir'])->all()))
	    			<div class="col-md-12">
						<p class="lead">
							Hay ({{ count($asignadas->whereIn('Estado', ['Pendiente', 'Rechazado', 'Corregir'])->all()) }}) sesiones pendientes por revisar, para consultarlas has click <a href="{{ url('/profesores/sesiones') }}" class="alert-link">aquí.</a>
						</p>
	    			</div>
		    	@else
			    	<div class="col-md-12">
	    				<p class="lead">
	    					Al parecer no tiene ninguna sesión pendiente por revisar en el momento, para verificar mas adelante haga click en <a href="{{ url('/profesores/sesiones') }}">consultar sesiones</a>
	    				</p>
	    			</div>
    			@endif

				@if(count($asignadas))
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
					<div class="col-md-12">
						<div class="col-md-3 col-sm-6 resaltar">
		    				<div class="center">
			    				<h4>{{ count($asignadas->where('Estado', 'Aprobado')->all()) }}</h4>
			    				<small> SESIONES <br> REALIZADAS </small>
		    				</div>
		    			</div>
		    			<div class="col-md-3 col-sm-6 resaltar">
		    				<div class="center">
		    					<?php
		    						$total_participaciones = 0;
		    						$grupos_impacto = [];
		    						$total_mayor_afluencia = 0;
		    						$sesion_mayor_afluencia = null;

		    						foreach ($asignadas as $sesion) 
		    						{
		    							if ($sesion->gruposPoblacionales)
		    							{
		    								//obtener la sesion con mas afluencia
		    								if($sesion->gruposPoblacionales->sum('pivot.Cantidad') > $total_mayor_afluencia)
		    								{
		    									$total_mayor_afluencia = $sesion->gruposPoblacionales->sum('pivot.Cantidad');
		    									$sesion_mayor_afluencia = $sesion;
		    								}

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
		    			<div class="col-md-3 col-sm-6 resaltar">
							<?php
								$grupo_mayor_impacto = 'N\P';
								$total = 0;

								foreach ($grupos_impacto as $key => $grupo) 
								{
									$sub_total = $grupo['Participantes']['M'] + $grupo['Participantes']['F'] + $grupo['Asistentes']['M'] + $grupo['Asistentes']['F'];

									if($sub_total > $total)
									{
										$total = $sub_total;
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
					<div class="col-md-12">
						<br><br>
					</div>
					<div class="col-md-6">
						<input type="hidden" name="grupos_impacto_participantes" data-json="{!! htmlspecialchars(json_encode($grupos_impacto)) !!}">
						<div id="grupos_impacto_participantes" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
					</div>
					<div class="col-md-6">
						<input type="hidden" name="grupos_impacto_asistentes" data-json="{!! htmlspecialchars(json_encode($grupos_impacto)) !!}">
						<div id="grupos_impacto_asistentes" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
					</div>
					<div class="col-md-12">
						<br><br>
					</div>
					@if($sesion_mayor_afluencia)
						<div class="col-md-12">
							<p class="lead">La sesión con mayor afluencia fue:</p>
							{{ $sesion_mayor_afluencia->toSuccessString() }} <br>
							<small>
								{{ $total_mayor_afluencia }} personas.
							</small>
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
