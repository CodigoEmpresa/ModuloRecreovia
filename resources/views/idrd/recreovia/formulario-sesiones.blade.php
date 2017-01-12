@section('script')
    @parent

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmhb8BVo311Mnvr35sv8VngIvXiiTnKQ4" defer></script>
    <script src="{{ asset('public/Js/sesiones/formulario.js') }}"></script>
@stop
    
<div class="content">
	<div id="main" class="row" data-url="{{ url('programacion') }}">
		@if ($status == 'success')
			<div id="alerta" class="col-xs-12">
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Datos actualizados satisfactoriamente.
				</div>                                
			</div>
		@endif
		@if (!empty($errors->all()))
			<div class="col-xs-12">
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Solucione los siguientes inconvenientes y vuelva a intentarlo</strong>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			</div>
		@endif
		<div class="col-xs-12"><br></div>
		<div class="col-xs-12 col-md-12">
			<div class="row">
				<form action="{{ url('/gestores/sesiones/procesar') }}" method="post">
					<fieldset>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="">Programación</label>
									<p class="form-control-static">{{ $cronograma->toString() }}</p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12 form-group">
									<label for="">Punto</label>
									<p class="form-control-static">{{ $cronograma->punto->toString() }}</p>
								</div>
								<div class="col-md-12 form-group">
									<label for="">Jornada</label>
									<p class="form-control-static">{{ $cronograma->jornada->toString() }}</p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="map" style="height:145px;"></div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3 form-group {{ $errors->has('Fecha') ? 'has-error' : '' }}">
									<label for="">Fecha</label>
									<input type="text" class="form-control" value="{{ $sesion ? $sesion['Fecha'] : old('Fecha') }}" data-role="datepicker" name="Fecha" data-fecha-inicio="{{ $cronograma->Desde }}" data-fecha-fin="{{ $cronograma->Hasta }}" data-dias="{{ $cronograma->jornada->Dias }}">
								</div>
								<div class="col-md-3 form-group {{ $errors->has('Inicio') ? 'has-error' : '' }}">
									<label for="">Inicio</label>
									<input type="text" class="form-control" value="{{ $sesion ? $sesion['Inicio'] : old('Inicio') }}" data-role="clockpicker" data-rel="hora_inicio" name="Inicio" data-hora-inicio="{{ $cronograma->jornada->Inicio }}">
								</div>
								<div class="col-md-3 form-group {{ $errors->has('Fin') ? 'has-error' : '' }}">
									<label for="">Fin</label>
									<input type="text" class="form-control" value="{{ $sesion ? $sesion['Fin'] : old('Fin') }}" data-role="clockpicker" data-rel="hora_fin" name="Fin" data-hora-fin="{{ $cronograma->jornada->Fin }}">
								</div>
								<div class="col-md-3 form-group {{ $errors->has('Id_Recreopersona') ? 'has-error' : '' }}">
									<label for="">Profesor</label>
									<select name="Id_Recreopersona" id="Id_Recreopersona" class="form-control" data-value="{{ $sesion ? $sesion['Id_Recreopersona'] : old('Id_Recreopersona') }}">
										<option value="">Seleccionar</option>
										@foreach($cronograma->punto->profesores as $profesor)
											<option value="{{ $profesor->Id_Recreopersona }}">{{ $profesor->persona->toString() }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-6 form-group {{ $errors->has('Objetivo_General') ? 'has-error' : '' }}">
									<label for="">Sesión</label>
									<select name="Objetivo_General" id="Objetivo_General" class="form-control" data-value="{{ $sesion ? $sesion['Objetivo_General'] : old('Objetivo_General') }}">
										<option value="">Seleccionar</option>
										<option value="Gimnasia de Mantenimiento (GM)">Gimnasia de Mantenimiento (GM)</option>
										<option value="Estimulación Muscular (EM)">Estimulación Muscular (EM)</option>
										<option value="Movilidad Articular (MA)">Movilidad Articular (MA)</option>
										<option value="Rumba Tropical Folclorica (RTF)">Rumba Tropical Folclorica (RTF)</option>
										<option value="Actividad Rítmica para Niños (ARN) Rumba para Niños.">Actividad Rítmica para Niños (ARN) Rumba para Niños.</option>
										<option value="Gimnasia Aeróbica Musicalizada (GAM)">Gimnasia Aeróbica Musicalizada (GAM)</option>
										<option value="Artes Marciales Musicalizadas (AMM)">Artes Marciales Musicalizadas (AMM)</option>
										<option value="Gimnasia Psicofísica (GPF)">Gimnasia Psicofísica (GPF)</option>
										<option value="Pilates (Pil)">Pilates (Pil)</option>
										<option value="Taller de Danzas (TD)">Taller de Danzas (TD)</option>
										<option value="Gimnasio Saludable al Aire Libre (GSAL)">Gimnasio Saludable al Aire Libre (GSAL)</option>
									</select>
								</div>
								<div class="col-md-6 form-group">
									<label for="">Materiales</label>
									<textarea class="form-control" class="form-control" name="Recursos">{{ $sesion ? $sesion['Recursos'] : old('Recursos') }}</textarea>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<hr>
						</div>
						<div class="col-md-12">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="Id" value="{{ $sesion ? $sesion['Id'] : 0 }}">
							<input type="hidden" name="Id_Cronograma" value="{{ $cronograma->Id }}">
			                <input type="hidden" id="latitud" value="{{ $cronograma->punto ? $cronograma->punto['Latitud'] : 4.666575 }}">
			                <input type="hidden" id="longitud" value="{{ $cronograma->punto ? $cronograma->punto['Longitud'] : -74.125786 }}">
							<input type="submit" class="btn btn-primary" value="Guardar">
                            @if ($sesion)
                                <a data-toggle="modal" data-target="#modal-eliminar" class="btn btn-danger">Eliminar</a>
                            @endif
                            <a href="{{ url('programacion/'.$cronograma['Id'].'/editar/') }}" class="btn btn-default">Volver</a>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="row">
				<div class="col-md-12"><br></div>
			</div> 
			<div class="row">
				<div class="col-md-12">
					<table id="sesiones" class="display nowrap table table-striped table-min">
						<thead>
							<tr>
								<th>Sesión</th>
								<th>Fecha</th>
								<th>H. Inicio</th>
								<th>H. Fin</th>
								<th>Estado</th>
								<th data-priority="2"></th>
							</tr>
						</thead>
						<tbody>
							@foreach($cronograma->sesiones as $sesion)
								<?php
		                        	switch ($sesion->Estado)
		                        	{
		                        		case 'Pendiente':
		                        			$class = 'default';
		                        		break;
		                        		case 'Diligenciado':
		                        			$class = 'warning';
		                        		break;
		                        		case 'Aprobado':
		                        			$class = 'success';
		                        		break;
		                        		case 'Rechazado':
		                        			$class = 'danger';
		                        		break;
		                        	}
		                        ?>
								<tr class="{{ $class }}">
                                	<td>{{ $sesion->Objetivo_General }}<br><small class="text-mutted">{{ $sesion->profesor->persona->toFriendlyString() }}</small></td>
									<td>{{ $sesion->Fecha }}</td>
									<td>{{ $sesion->Inicio }}</td>
									<td>{{ $sesion->Fin }}</td>
									<td>{{ $sesion->Estado }}</td>
									<td data-priority="2"> 
										<a data-role="editar" href="{{ url('/gestores/'.$cronograma['Id'].'/sesiones/'.$sesion['Id'].'/editar') }}" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
		                                	<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
		                            	</a>
		                            	<a data-role="validar" target="_blank" href="{{ url('/gestores/sesiones/'.$sesion['Id'].'/editar') }}" class="pull-right separe-right btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" title="Detalles">
		                                	<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
		                            	</a>
		                            </td>
								</tr>
							@endforeach
						</tbody>
					</table>
	            </div>
			</div>
		</div>
	</div>
</div>
@if ($sesion)
	<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Eliminar</h4>
				</div>
				<div class="modal-body">
					<p>Realmente desea eliminar esta sesión.</p>
				</div>
				<div class="modal-footer">
					<a href="{{ url('/gestores/'.$cronograma['Id'].'/sesiones/'.$sesion['Id'].'/eliminar') }}" class="btn btn-danger">Eliminar</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
@endif