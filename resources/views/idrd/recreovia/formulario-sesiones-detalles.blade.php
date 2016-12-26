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
		<div class="col-xs-12"><br></div>
		<div class="col-xs-12 col-md-12">
			<div class="row">
				<form action="{{ url('/sesiones/procesar') }}" method="post">
					<fieldset>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12 form-group">
											<label for="">Programación</label>
											<p class="form-control-static">{{ $sesion->cronograma->toString() }}</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-12 form-group">
											<label for="">Punto</label>
											<p class="form-control-static">{{ $sesion->cronograma->punto->toString() }}</p>
										</div>
										<div class="col-md-12 form-group">
											<label for="">Jornada</label>
											<p class="form-control-static">{{ $sesion->cronograma->jornada->toString() }}</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div id="map" style="height:145px;"></div>
								</div>
							</div>
						</div>
						<div class="col-md-3 form-group">
							<label for="">Fecha</label>
							<p class="form-control-static">{{ $sesion['Fecha'] }}</p>
						</div>
						<div class="col-md-3 form-group">
							<label for="">Inicio</label>
							<p class="form-control-static">{{ $sesion['Inicio'] }}</p>
						</div>
						<div class="col-md-3 form-group">
							<label for="">Fin</label>
							<p class="form-control-static">{{ $sesion['Fin'] }}</p>
						</div>
						<div class="col-md-3 form-group">
							<label for="">Profesor</label>
							<p class="form-control-static">{{ $sesion->profesor->persona->toString() }}</p>
						</div>
						<div class="col-md-6 form-group">
							<label for="">Objetivo general</label>
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
							<label for="">Objetivos especificos</label>
							<textarea class="form-control" name="Objetivos_Especificos">{{ $sesion ? $sesion['Objetivos_Especificos'] : old('Objetivos_Especificos') }}</textarea>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6 form-group">
									<label for="">Metodologia a aplicar</label>
									<textarea class="form-control" name="Metodologia_Aplicar">{{ $sesion ? $sesion['Metodologia_Aplicar'] : old('Metodologia_Aplicar') }}</textarea>
								</div>
								<div class="col-md-6 form-group">
									<label for="">Recursos</label>
									<textarea class="form-control" name="Recursos">{{ $sesion ? $sesion['Recursos'] : old('Recursos') }}</textarea>
								</div>
							</div>
						</div>
						<div class="col-md-4 form-group">
							<label for="">Fase inicial</label>
							<textarea class="form-control x2 nivel1" name="Fase_Inicial">{{ $sesion ? $sesion['Fase_Inicial'] : old('Fase_Inicial') }}</textarea>
						</div>
						<div class="col-md-4 form-group">
							<label for="">Fase central</label>
							<textarea class="form-control x2 nivel2" name="Fase_Central">{{ $sesion ? $sesion['Fase_Central'] : old('Fase_Central') }}</textarea>
						</div>
						<div class="col-md-4 form-group">
							<label for="">Fase final</label>
							<textarea class="form-control x2 nivel3" name="Fase_Final">{{ $sesion ? $sesion['Fase_Final'] : old('Fase_Final') }}</textarea>
						</div>
						<div class="col-md-12 form-group">
							<label for="">Estado</label><br>
                    		<label class="radio-inline">
                                <input type="radio" name="Estado" id="estado1" value="Pendiente" {{ ($sesion && $sesion['Estado'] == 'Pendiente') || old('Estado') == 'Pendiente' ? 'checked' : '' }}> Pendiente
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="Estado" id="estado2" value="Diligenciado" {{ ($sesion && $sesion['Estado'] == 'Diligenciado') || old('Estado') == 'Diligenciado' ? 'checked' : '' }}> Diligenciado
                            </label>
                            @if($tipo == "gestor")
	                            <label class="radio-inline">
	                                <input type="radio" name="Estado" id="estado3" value="Aprobado" {{ ($sesion && $sesion['Estado'] == 'Aprobado') || old('Estado') == 'Aprobado' ? 'checked' : '' }}> Aprobado
	                            </label>
	                            <label class="radio-inline">
	                                <input type="radio" name="Estado" id="estado4" value="Rechazado" {{ ($sesion && $sesion['Estado'] == 'Rechazado') || old('Estado') == 'Rechazado' ? 'checked' : '' }}> Rechazado
	                            </label>
                            @endif
						</div>
						<div class="col-md-12">
							<hr>
						</div>
						<div class="col-md-12">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="Id" value="{{ $sesion ? $sesion['Id'] : 0 }}">
							<input type="hidden" name="origen" value="{{ $tipo }}">
			                <input type="hidden" id="latitud" value="{{ $sesion->cronograma->punto ? $sesion->cronograma->punto['Latitud'] : 4.666575 }}">
			                <input type="hidden" id="longitud" value="{{ $sesion->cronograma->punto ? $sesion->cronograma->punto['Longitud'] : -74.125786 }}">
							<input type="submit" class="btn btn-primary" value="Guardar">
							@if($tipo == "profesor")
                            	<a href="{{ url('/profesores/sesiones') }}" class="btn btn-default">Volver</a>
                            @else
                            	<a href="{{ url('/gestores/sesiones') }}" class="btn btn-default">Volver</a>
                            @endif
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>