@extends('master-land')

@section('script')
    @parent
    <script src="{{ asset('public/Js/localidades/selectores_dependientes_multiples.js') }}"></script>
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
            <form action="{{ url('/reportes/actividades') }}" method="POST">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Jornada</label>
                        <select name="id_jornada[]" data-value="{!! implode(',', old('id_jornada', [])) !!}" data-live-search="true" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                            @foreach($jornadas as $jornada)
                                <option value="{{ $jornada['Id_Jornada'] }}">{{ $jornada->toString() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Sesion</label>
                        <select name="sesion[]" data-value="{!! implode(',', old('sesion', [])) !!}" data-live-search="true" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                            <option value="Gimnasia de Mantenimiento (GM)">Gimnasia de Mantenimiento (GM)</option>
                            <option value="Estimulación Muscular (EM)">Estimulación Muscular (EM)</option>
                            <option value="Movilidad Articular (MA)">Movilidad Articular (MA)</option>
                            <option value="Rumba Tropical Folclorica (RTF)">Rumba Tropical Folclorica (RTF)</option>
                            <option value="Actividad Rítmica para Niños (ARN) Rumba para Niños">Actividad Rítmica para Niños (ARN) Rumba para Niños</option>
                            <option value="Gimnasia Aeróbica Musicalizada (GAM)">Gimnasia Aeróbica Musicalizada (GAM)</option>
                            <option value="Artes Marciales Musicalizadas (AMM)">Artes Marciales Musicalizadas (AMM)</option>
                            <option value="Gimnasia Psicofísica (GPF)">Gimnasia Psicofísica (GPF)</option>
                            <option value="Pilates (Pil)">Pilates (Pil)</option>
                            <option value="Taller de Danzas (TD)">Taller de Danzas (TD)</option>
                            <option value="Gimnasio Saludable al Aire Libre (GSAL)">Gimnasio Saludable al Aire Libre (GSAL)</option>
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
                        <select name="id_upz[]" data-value="{!! implode(',', old('id_upz', [])) !!}" data-live-search="true" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Punto</label>
                        <select name="id_punto[]" data-value="{!! implode(',', old('id_punto', [])) !!}" data-live-search="true" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
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
                @if($puntos)
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="panel-title">
                                ACTIVIDADES
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <br>
                        </div>
                        <div class="col-md-12">
                            <table id="actividades" class="display nowrap table table-bordered table-min">
                                <thead>
                                    <tr>
                                        <th>Punto</th>
                                        <th>Direccion</th>
                                        <th>Jornada</th>
                                        <th>Tipo</th>
                                        <th>Sesiones</th>
                                        <th>Participación</th>
                                        <th>Asistencia</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($puntos as $punto)
                                        @foreach($punto['jornadas'] as $jornada)
                                            @foreach($jornada['sesiones'] as $key => $grupos_sesiones)
                                                <?php
                                                    $participantes = 0;
                                                    $asistentes = 0;
                                                ?>
                                                @foreach($grupos_sesiones as $sesion)
                                                    @foreach ($sesion->gruposPoblacionales()->where('Grupo_Asistencia', 'Participantes')->get() as $participacion)
                                                        <?php $participantes += +$participacion->pivot['Cantidad']; ?>
                                                    @endforeach
                                                    @foreach ($sesion->gruposPoblacionales()->where('Grupo_Asistencia', 'Asistentes')->get() as $asistencia)
                                                        <?php $asistentes += +$asistencia->pivot['Cantidad']; ?>
                                                    @endforeach
                                                @endforeach

                                                @if(count($jornada['sesiones'][$key]) > 0)
                                                    <tr>
                                                        <td>{{ $punto['punto']->Escenario }}</td>
                                                        <td>{{ $punto['punto']->Direccion }}</td>
                                                        <td>{{ $jornada['jornada']->toString() }}</td>
                                                        <td>{{ $key }}</td>
                                                        <td>{{ count($jornada['sesiones'][$key])  }}</td>
                                                        <td>{{ $participantes }}</td>
                                                        <td>{{ $asistentes }}</td>
                                                        <td>{{ $participantes + $asistentes }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
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