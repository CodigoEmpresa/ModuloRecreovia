@section('script')
    @parent

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmhb8BVo311Mnvr35sv8VngIvXiiTnKQ4" defer></script>
    <script src="{{ asset('public/Js/cronogramas/formulario.js') }}"></script>
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
                <form action="{{ url('programacion/procesar') }}" method="post">
                    <fieldset>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12 form-group {{ $errors->has('Id_Punto') ? 'has-error' : '' }}">
                                    <label for="">Punto</label>
                                    <select name="Id_Punto" id="" class="form-control" data-value="{{ $cronograma ? $cronograma['Id_Punto'] : old('Id_Punto') }}">
                                        <option value="">Seleccionar</option>
                                        @foreach($recreopersona->puntos as $punto)
                                            <option value="{{ $punto['Id_Punto'] }}">{{ $punto->toString() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 form-group {{ $errors->has('Id_Jornada') ? 'has-error' : '' }}">
                                    <label for="">Jornada</label>
                                    <select name="Id_Jornada" id="" class="form-control" data-json="{{ $recreopersona->puntos }}" data-value="{{ $cronograma ? $cronograma['Id_Jornada'] : old('Id_Jornada') }}">
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group {{ $errors->has('Desde') ? 'has-error' : '' }}">
                                    <label for="">Desde</label>
                                    <input type="text" class="form-control" name="Desde" data-role="datepicker" data-rel="fecha_inicio" data-fecha-inicio="" data-fecha-fin="" value="{{ $cronograma ? $cronograma['Desde'] : old('Desde') }}" placeholder="Desde">
                                </div>
                                <div class="col-md-6 form-group {{ $errors->has('Hasta') ? 'has-error' : '' }}">
                                    <label for="">Hasta</label>
                                    <input type="text" class="form-control" name="Hasta" data-role="datepicker" data-rel="fecha_fin" data-fecha-inicio="" data-fecha-fin="" value="{{ $cronograma ? $cronograma['Hasta'] : old('Hasta') }}" placeholder="Hasta">
                                </div>
                                <div class="col-md-12 form-group {{ $errors->has('recreovia') ? 'has-error' : '' }}">
                                    <label for="">Recreovia</label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="recreovia" id="recreovia1" value="RESD" {{ ($cronograma && $cronograma['recreovia'] == 'RESD') || old('recreovia') == 'RESD' ? 'checked' : '' }}> RESD
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="recreovia" id="recreovia2" value="RESN" {{ ($cronograma && $cronograma['recreovia'] == 'RESN') || old('recreovia') == 'RESN' ? 'checked' : '' }}> RESN
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="recreovia" id="recreovia3" value="REFS" {{ ($cronograma && $cronograma['recreovia'] == 'REFS') || old('recreovia') == 'REFS' ? 'checked' : '' }}> REFS
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="map"></div>
                        </div>
                        @if ($cronograma)
                            <div class="col-md-12">
                                <a href="{{ url('/gestores/'.$cronograma['Id'].'/sesiones') }}" class="btn btn-link">Administrar sesiones</a>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="Id" value="{{ $cronograma ? $cronograma['Id'] : 0 }}">
                            <input type="submit" value="Guardar" id="guardar-jornada" class="btn btn-primary">
                            @if ($cronograma)
                                <a data-toggle="modal" data-target="#modal-eliminar" class="btn btn-danger">Eliminar</a>
                            @endif
                            <a href="{{ url('programacion') }}" class="btn btn-default">Volver</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@if ($cronograma)
    <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body">
                    <p>Realmente desea eliminar esta programación.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ url('programacion/gestores/eliminar/'.$cronograma['Id']) }}" class="btn btn-danger">Eliminar</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endif