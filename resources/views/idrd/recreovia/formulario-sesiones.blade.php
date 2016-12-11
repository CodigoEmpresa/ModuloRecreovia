@section('script')
    @parent

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
				<form action="{{ url('programacion/gestores/sesiones/procesar') }}" method="post">
					<fieldset>
						<div class="col-md-12 form-group">
							<label for="">Punto</label>
							<p class="form-control-static">{{ $cronograma->punto->toString() }}</p>
						</div>
						<div class="col-md-12 form-group">
							<label for="">Jornada</label>
							<p class="form-control-static">{{ $cronograma->jornada->toString() }}</p>
						</div>
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
							<input type="text" class="form-control" name="Objetivo_General" value="{{ $sesion ? $sesion['Objetivo_General'] : old('Objetivo_General') }}">
						</div>
						<div class="col-md-6 form-group {{ $errors->has('Recursos') ? 'has-error' : '' }}">
							<label for="">Materiales</label>
							<input type="text" class="form-control" name="Recursos" value="{{ $sesion ? $sesion['Recursos'] : old('Recursos') }}">
						</div>
						<div class="col-md-12">
							<hr>
						</div>
						<div class="col-md-12">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="Id" value="{{ $sesion ? $sesion['Id'] : 0 }}">
							<input type="hidden" name="Id_Cronograma" value="{{ $cronograma->Id }}">
							<input type="submit" class="btn btn-primary" value="Guardar">
                            <a href="{{ url('programacion/gestores/editar/'.$cronograma['Id']) }}" class="btn btn-default">Volver</a>
						</div>
					</fieldset>
				</form>
			</div> 
		</div>
	</div>
</div>
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
				<a data-url="{{ url('programacion/gestores/sesion/eliminar/') }}" href="" class="btn btn-danger">Eliminar</a>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>