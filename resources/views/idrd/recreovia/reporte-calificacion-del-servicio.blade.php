@extends('master-land')

@section('script')
    @parent
    <script src="{{ asset('public/Js/localidades/selectores_dependientes_multiples.js?v=1') }}"></script>
    <script>
        $(function(e)
        {
            $('table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                lengthChange: false,
                scrollCollapse: true,
                paging: false,
                buttons: [
                    'copy', 'excel', {extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL'}
                ],
                columnDefs: [
                    {
                        targets: 'no-sort',
                        orderable: false
                    }
                ]
            });
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ url('/reportes/calificacion_del_servicio') }}" method="POST">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Jornada</label>
                        <select name="id_jornada[]" data-value="{!! implode(',', old('id_jornada', [])) !!}" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                            @foreach($jornadas as $jornada)
                                <option value="{{ $jornada['Id_Jornada'] }}">{{ $jornada->toString() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Localidad</label>
                        <select name="id_localidad[]" data-value="{!! implode(',', old('id_localidad', [])) !!}" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos"  class="form-control" data-json="{{ $localidades }}" title="Seleccionar" multiple>
                            @foreach($localidades as $localidad)
                                <option value="{{ $localidad['Id_Localidad'] }}">{{ $localidad['Localidad'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Upz</label>
                        <select name="id_upz[]" data-value="{!! implode(',', old('id_upz', [])) !!}" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Punto</label>
                        <select name="id_punto[]" data-value="{!! implode(',', old('id_punto', [])) !!}" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Fecha inicio</label>
                        <input name="fecha_inicio" value="{!! old('fecha_inicio', '') !!}" type="text" placeholder="Fecha inicio" class="form-control" data-role="datepicker" data-rel="fecha_inicio" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('fecha_inicio') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Fecha fin</label>
                        <input name="fecha_fin" value="{!! old('fecha_fin', '') !!}" placeholder="Fecha fin" class="form-control" data-role="datepicker" data-rel="fecha_fin" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('fecha_fin') }}">
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button class="btn btn-default" type="submit">Generar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                @if($sesiones)
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="panel-title">
                                SESIONES
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <br>
                        </div>
                        <div class="col-md-12">
                            <table id="actividades" class="display nowrap table table-bordered table-min">
                                <thead>
                                <tr>
                                    <th>Sesion</th>
                                    <th>PAF</th>
                                    <th>Puntualidad PAF</th>
                                    <th>Tiempo de la sesión</th>
                                    <th>Escenario y montaje</th>
                                    <th>Cumplimiento del objetivo</th>
                                    <th>Variedad y creatividad</th>
                                    <th>Imagen institucional</th>
                                    <th>Divulgación</th>
                                    <th>Seguridad</th>
                                    <th>Usuario</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sesiones as $sesion)
                                    <tr>
                                        <td>{{ $sesion->getCode() }}</td>
                                        <td>{{ $sesion->AsumidaPorElGestor ? $sesion->gestorSiAsume->persona->toFriendlyString() : $sesion->profesor->persona->toFriendlyString() }}</td>
                                        <td>{{ $sesion->calificacionDelServicio['Puntualidad_PAF'] }}</td>
                                        <td>{{ $sesion->calificacionDelServicio['Tiempo_De_La_Sesion'] }}</td>
                                        <td>{{ $sesion->calificacionDelServicio['Escenario_Y_Montaje'] }}</td>
                                        <td>{{ $sesion->calificacionDelServicio['Cumplimiento_Del_Objetivo'] }}</td>
                                        <td>{{ $sesion->calificacionDelServicio['Variedad_Y_Creatividad'] }}</td>
                                        <td>{{ $sesion->calificacionDelServicio['Imagen_Institucional'] }}</td>
                                        <td>{{ $sesion->calificacionDelServicio['Divulgacion'] }}</td>
                                        <td>{{ $sesion->calificacionDelServicio['Seguridad'] }}</td>
                                        <td>
                                            {{ $sesion->calificacionDelServicio['Nombre'] }}<br>
                                            {{ $sesion->calificacionDelServicio['Telefono'] }}<br>
                                            {{ $sesion->calificacionDelServicion['Correo'] }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
@stop