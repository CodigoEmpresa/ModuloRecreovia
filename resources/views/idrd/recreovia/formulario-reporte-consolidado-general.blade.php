@section('script')
    @parent

    <script src="{{ asset('public/Js/consolidado_general/formulario.js') }}"></script>
@stop
<div class="content">
	<div id="main">
		<div class="row">
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
      </div>
      <div class="col-xs-12"><br></div>
      <div class="row">
  			<form action="{{ url('informes/consolidado_general') }}" method="post">
  				<div class="col-md-12 form-group {{ $errors->has('Id_Jornada') ? 'has-error' : '' }}">
  					<label for="">Jornada</label>
  					<select name="Id_Jornada" id="" class="form-control" data-json="{{ $jornadas }}" data-value="{{ old('Id_Jornada') }}">
  						<option value="">Seleccionar</option>
  						@foreach($jornadas as $jornada)
  							<option value="{{ $jornada['Id_Jornada'] }}">{{ $jornada->toString() }}</option>
  						@endforeach
  					</select>
  				</div>
  				<div class="col-md-4 form-group {{ $errors->has('Fecha') ? 'has-error' : '' }}">
  					<label for="">Día</label>
  					<input type="text" name="Dia" class="form-control" value="{{ old('Fecha') }}" data-role="datepicker" data-fecha-inicio="" data-fecha-fin="" data-dias="" data-fechas-importantes="{{ Festivos::create()->datesToString() }}">
  				</div>
  				<div class="col-md-8 form-group {{ $errors->has('Fecha') ? 'has-error' : '' }}">
  					<label for="">Días seleccionados</label>
  					<input type="text" class="form-control" name="Dias" value="">
  				</div>
  				<div class="col-md-12">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" value="Generar" class="btn btn-primary">
  				</div>
  			</form>
  		</div>
	</div>
</div>
