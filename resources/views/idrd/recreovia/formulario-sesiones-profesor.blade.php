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
				<form action="{{ url('programacion/sesion/procesar') }}" method="post">
					<fieldset>
						<div class="col-md-12 form-group">
							<label for="">Punto</label>
							<p class="form-control-static">{{ $sesion->cronograma->punto->toString() }}</p>
						</div>
						<div class="col-md-12 form-group">
							<label for="">Jornada</label>
							<p class="form-control-static">{{ $sesion->cronograma->jornada->toString() }}</p>
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
							<p class="form-control-static">{{ $sesion ? $sesion['Objetivo_General'] : old('Objetivo_General') }}</p>
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
								<div class="col-md-6 form-group">
									<label for="">Fase inicial</label>
									<textarea class="form-control" name="Fase_Inicial">{{ $sesion ? $sesion['Fase_Inicial'] : old('Fase_Inicial') }}</textarea>
								</div>
								<div class="col-md-6 form-group">
									<label for="">Fase central</label>
									<textarea class="form-control" name="Fase_Central">{{ $sesion ? $sesion['Fase_Central'] : old('Fase_Central') }}</textarea>
								</div>
								<div class="col-md-6 form-group">
									<label for="">Fase final</label>
									<textarea class="form-control" name="Fase_Final">{{ $sesion ? $sesion['Fase_Final'] : old('Fase_Final') }}</textarea>
								</div>
								<div class="col-md-6 form-group">
									<label for="">Estado</label><br>
		                    		<label class="radio-inline">
		                                <input type="radio" name="Estado" id="estado1" value="Pendiente" {{ ($sesion && $sesion['Estado'] == 'Pendiente') || old('Estado') == 'Pendiente' ? 'checked' : '' }}> Pendiente
		                            </label>
		                            <label class="radio-inline">
		                                <input type="radio" name="Estado" id="estado2" value="Diligenciado" {{ ($sesion && $sesion['Estado'] == 'Diligenciado') || old('Estado') == 'Diligenciado' ? 'checked' : '' }}> Diligenciado
		                            </label>
		                            @if($tipo == "gestor")
			                            <label class="radio-inline">
			                                <input type="radio" name="Estado" id="estado3" value="Aprovado" {{ ($sesion && $sesion['Estado'] == 'Aprovado') || old('Estado') == 'Aprovado' ? 'checked' : '' }}> Aprovado
			                            </label>
			                            <label class="radio-inline">
			                                <input type="radio" name="Estado" id="estado4" value="Rechazado" {{ ($sesion && $sesion['Estado'] == 'Rechazado') || old('Estado') == 'Rechazado' ? 'checked' : '' }}> Rechazado
			                            </label>
		                            @endif
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
							<input type="hidden" name="origen" value="{{ $tipo }}">
							<input type="submit" class="btn btn-primary" value="Guardar">
							@if($tipo == "profesor")
                            	<a href="{{ url('programacion/profesor/sesiones') }}" class="btn btn-default">Volver</a>
                            @else
                            	<a href="{{ url('programacion/gestor/sesiones') }}" class="btn btn-default">Volver</a>
                            @endif
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>