@extends('master-land')

@section('script')
    @parent
    <script src="{{ asset('public/Js/localidades/selectores_dependientes_multiples.js') }}"></script>
@stop

@section('content')
    <div class="col-md-12">
        <form action="{{ url('/reportes/asistencia') }}" method="POST">
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="">Jornada</label>
                    <select name="id_jornada[]" id="" data-live-search="true" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                        @foreach($jornadas as $jornada)
                            <option value="{{ $jornada['Id_Jornada'] }}">{{ $jornada->toString() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="">Localidad</label>
                    <select name="id_localidad[]" id="" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos"  class="form-control" data-json="{{ $localidades }}" title="Seleccionar" multiple>
                        @foreach($localidades as $localidad)
                            <option value="{{ $localidad['Id_Localidad'] }}">{{ $localidad['Localidad'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="">Upz</label>
                    <select name="id_upz[]" id="" data-live-search="true" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="">Punto</label>
                    <select name="id_punto[]" id="" data-live-search="true" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="">Fecha inicio</label>
                    <input name="fecha_inicio" type="text" placeholder="Fecha inicio" class="form-control" data-role="datepicker" data-rel="fecha_inicio" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('fecha_inicio') }}">
                </div>
                <div class="col-md-2 form-group">
                    <label for="">Fecha fin</label>
                    <input name="fecha_fin" type="text" placeholder="Fecha fin" class="form-control" data-role="datepicker" data-rel="fecha_fin" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('fecha_fin') }}">
                </div>
                <div class="col-md-12">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button class="btn btn-default" type="submit">Generar</button>
                </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-6">

            </div>
            <div class="col-md-6">

            </div>
        </form>
    </div>
@stop