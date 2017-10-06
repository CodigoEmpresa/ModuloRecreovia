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
            <form action="{{ url('/reportes/producto_no_conforme') }}" method="POST">
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
                        <label for="">No conformidad</label>
                        <select name="no_conformidad[]" data-value="{!! implode(',', old('no_conformidad', [])) !!}" data-live-search="true" data-actions-box="true" data-select-all-text="Seleccionar todos" data-deselect-all-text="Deseleccionar todos" class="form-control" title="Seleccionar" multiple>
                            <option value="Requisito_1">1. Puntualidad</option>
                            <option value="Requisito_2">2. Personal competente para el desarrollo de la actividad</option>
                            <option value="Requisito_3">3. Contar con el Talento Humano mínimo requerido</option>
                            <option value="Requisito_4">4. Escenario adecuado</option>
                            <option value="Requisito_5">5. Contar con los parámetros del IDIGER</option>
                            <option value="Requisito_6">6. Cumplir con los niveles de competencia de Ruido</option>
                            <option value="Requisito_7">7. Cumplir con la Resolución 512 de 2003</option>
                            <option value="Requisito_8">8. Elementos de producción (sonido)</option>
                            <option value="Requisito_9">9. Planificación de la sesión</option>
                            <option value="Requisito_10">10. Presentación Personal del Talento Humano</option>
                            <option value="Requisito_11">11. Mantener actualizada la información sobre los Puntos de Recreovía en Planeación del IDRD</option>
                            <option value="Requisito_12">12. Accesorios (bicicletas estáticas, step)</option>
                            <option value="Requisito_13">13. Cumplir con el instructivo de selección y contratación</option>
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
                                        <th>No conformidades</th>
                                        <th>Descripción</th>
                                        <th>Acción tomada</th>
                                        <th>Tratamiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sesiones as $sesion)
                                        <?php
                                            $requisitos_html = '';
                                            $requisitos = [
                                                'Requisito_1' => '1. Puntualidad',
                                                'Requisito_2' => '2. Personal competente para el desarrollo de la actividad',
                                                'Requisito_3' => '3. Contar con el Talento Humano mínimo requerido',
                                                'Requisito_4' => '4. Escenario adecuado',
                                                'Requisito_5' => '5. Contar con los parámetros del IDIGER',
                                                'Requisito_6' => '6. Cumplir con los niveles de competencia de Ruido',
                                                'Requisito_7' => '7. Cumplir con la Resolución 512 de 2003',
                                                'Requisito_8' => '8. Elementos de producción (sonido)',
                                                'Requisito_9' => '9. Planificación de la sesión',
                                                'Requisito_10' => '10. Presentación Personal del Talento Humano',
                                                'Requisito_11' => '11. Mantener actualizada la información sobre los Puntos de Recreovía en Planeación del IDRD',
                                                'Requisito_12' => '12. Accesorios (bicicletas estáticas, step)',
                                                'Requisito_13' => '13. Cumplir con el instructivo de selección y contratación'
                                            ];
                                            foreach ($requisitos as $key => $requisito)
                                            {
                                                if($sesion->productoNoConforme[$key] == '0')
                                                {
                                                    $requisitos_html .= $requisito.'<br>';
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td>{{ $sesion->getCode() }}</td>
                                            <td>{!! $requisitos_html !!}</td>
                                            <td>{{ $sesion->productoNoConforme['Descripcion_De_La_No_Conformidad'] }}</td>
                                            <td>{{ $sesion->productoNoConforme['Descripcion_De_La_Accion_Tomada'] }}</td>
                                            <td>{{ $sesion->productoNoConforme['Tratamiento'] }}</td>
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