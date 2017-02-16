@section('script')
    @parent

    <script src="{{ asset('public/Js/jornadas/formulario.js') }}"></script>
@stop
<div class="content">
    <div id="main" class="row" data-url="{{ url('jornadas') }}">
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
                <form action="{{ url('jornadas/procesar') }}" method="post">
                    <fieldset>
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('Jornada') ? 'has-error' : '' }}">
                                <label for="control-label">Jornada</label>
                                <select name="Jornada" id="Jornada" class="form-control" data-value="{{ $jornada ? $jornada['Jornada'] : old('Jornada') }}">
                                    <option value="">Seleccionar</option>
                                    <option data-tipo="Periodico" value="dia">Dia</option>
                                    <option data-tipo="Periodico" value="noche">Noche</option>
                                    <option data-tipo="Periodico" value="fds">FDS</option>
                                    <option data-tipo="Eventual" value="clases_grupales">Clases grupales</option>
                                    <option data-tipo="Eventual" value="clases_grupales_institucionales">Clases grupales institucionales</option>
                                    <option data-tipo="Eventual" value="mega_eventos">Mega eventos de actividad física</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6 {{ $errors->has('Fecha_Evento_Inicio') ? 'has-error' : '' }}">
                            <div class="form-group">
                                <label for="">Fecha inicio evento</label>
                                <input type="text" class="form-control" name="Fecha_Evento_Inicio" data-role="datepicker" data-rel="fecha_inicio" placeholder="Fecha inicio evento" value="{{ $jornada ? $jornada['Fecha_Evento_Inicio'] : old('Fecha_Evento_Inicio') }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group {{ $errors->has('Fecha_Evento_Fin') ? 'has-error' : '' }}">
                                <label for="">Fecha fin evento</label>
                                <input type="text" class="form-control" name="Fecha_Evento_Fin" data-role="datepicker" data-rel="fecha_fin" placeholder="Fecha fin evento" value="{{ $jornada ? $jornada['Fecha_Evento_Fin'] : old('Fecha_Evento_Fin') }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group {{ $errors->has('Inicio') ? 'has-error' : '' }}">
                                <label for="">Hora inicio</label>
                                <input type="text" class="form-control" data-rel="hora_inicio" name="Inicio" data-role="clockpicker" placeholder="Hora inicio" value="{{ $jornada ? $jornada['Inicio'] : old('Inicio') }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group {{ $errors->has('Fin') ? 'has-error' : '' }}">
                                <label for="">Hora fin</label>
                                <input type="text" class="form-control" data-rel="hora_fin" name="Fin" data-role="clockpicker" placeholder="Hora fin" value="{{ $jornada ? $jornada['Fin'] : old('Fin') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="control-label">Días</label> <br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="dia1" name="Dias[]" value="lunes" {{ ($jornada && $jornada->validarDia('lunes')) || (is_array(old('Dias')) && in_array('lunes', old('Dias'))) ? 'checked' : '' }}> Lunes
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="dia2" name="Dias[]" value="martes" {{ ($jornada && $jornada->validarDia('martes')) || (is_array(old('Dias')) && in_array('martes', old('Dias'))) ? 'checked' : '' }}> Martes 
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="dia3" name="Dias[]" value="miercoles" {{ ($jornada && $jornada->validarDia('miercoles')) || (is_array(old('Dias')) && in_array('miercoles', old('Dias'))) ? 'checked' : '' }}> Miercoles
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="dia4" name="Dias[]" value="jueves" {{ ($jornada && $jornada->validarDia('jueves')) || (is_array(old('Dias')) && in_array('jueves', old('Dias'))) ? 'checked' : '' }}> Jueves
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="dia5" name="Dias[]" value="viernes" {{ ($jornada && $jornada->validarDia('viernes')) || (is_array(old('Dias')) && in_array('viernes', old('Dias'))) ? 'checked' : '' }}> Viernes
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="dia6" name="Dias[]" value="sabado" {{ ($jornada && $jornada->validarDia('sabado')) || (is_array(old('Dias')) && in_array('sabado', old('Dias'))) ? 'checked' : '' }}> Sabado
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="dia7" name="Dias[]" value="domingo" {{ ($jornada && $jornada->validarDia('domingo')) || (is_array(old('Dias')) && in_array('domingo', old('Dias'))) ? 'checked' : '' }}> Domingo
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <hr>
                        </div>
                        <div class="col-xs-12">
                            <label for="">Datos de contacto <small class="text-muted">(En caso de ser requerido)</small></label>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label for="">Nombre</label>
                                    <input type="text" name="Contacto_Nombre" class="form-control" value="{{ $jornada ? $jornada['Contacto_Nombre'] : old('Contacto_Nombre') }}">
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label for="">Teléfono</label>
                                    <input type="text" name="Contacto_Telefono" class="form-control" value="{{ $jornada ? $jornada['Contacto_Telefono'] : old('Contacto_Telefono') }}">
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label for="">Correo</label>
                                    <input type="text" name="Contacto_Correo" class="form-control" value="{{ $jornada ? $jornada['Contacto_Correo'] : old('Contacto_Correo') }}">
                                </div>                                
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <hr>
                        </div>
                        <div class="col-xs-12">
                            <label for="">Puntos</label>
                            <div class="row">
                                @foreach ($puntos->chunk(count($puntos) / 2) as $chunk)
                                    <div class="col-md-6">
                                        @foreach ($chunk as $punto)
                                            <div class="checkbox">
                                                <label>
                                                     <input type="checkbox" name="puntos[]" value="{{ $punto['Id_Punto'] }}" {{ ($jornada && in_array($punto['Id_Punto'], $jornada->puntos->pluck('Id_Punto')->toArray())) || (in_array($punto['Id_Punto'], is_array(old('puntos')) ? old('puntos') : [])) ? 'checked' : '' }}> {{ strtoupper($punto->toString()) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="Id_Jornada" value="{{ $jornada ? $jornada['Id_Jornada'] : 0 }}">
                            <input type="hidden" name="Tipo" value="{{ $jornada ? $jornada['Tipo'] : '' }}">
                            <input type="submit" value="Guardar" id="guardar-jornada" class="btn btn-primary">
                            @if ($jornada)
                                <a data-toggle="modal" data-target="#modal-eliminar" class="btn btn-danger">Eliminar</a>
                            @endif
                            <a href="{{ url('jornadas') }}" class="btn btn-default">Volver</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@if ($jornada)
    <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body">
                    <p>Realmente desea eliminar esta jornada.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ url('jornadas/'.$jornada['Id_Jornada'].'/eliminar') }}" class="btn btn-danger">Eliminar</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endif