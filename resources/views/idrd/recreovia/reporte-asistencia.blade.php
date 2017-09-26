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
            <form action="{{ url('/reportes/asistencia') }}" method="POST">
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
                @if($sesiones)
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="panel-title">
                                PARTICIPACIÓN Y ASISTENCIA
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <br>
                        </div>
                        <div class="col-md-12">
                            <label for="">Participantes (Frecuencia Relativa)</label><br>
                            <table id="participaciones" class="display nowrap table table-bordered table-min">
                                <thead>
                                <tr>
                                    <th valign="center" align="center" width="30px" rowspan="2">#</th>
                                    @foreach($gruposPoblacionales as $grupo)
                                        <th style="width:104px;" colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</th>
                                    @endforeach
                                    <th style="width:104px;" colspan="2">Subtotal<br>Participantes</th>
                                    <th style="width:52px;" valign="center" rowspan="2">Total</th>
                                </tr>
                                <tr>
                                    @foreach($gruposPoblacionales as $grupo)
                                        <th style="width: 52px;">H</th>
                                        <th style="width: 52px;">M</th>
                                    @endforeach
                                    <th style="width: 52px;">H</th>
                                    <th style="width: 52px;">M</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 0;
                                    $subtotal_grupo = [];
                                    $subtotal_grupo_m = 0;
                                    $subtotal_grupo_f = 0;
                                    $total = 0;
                                ?>

                                @foreach ($gruposPoblacionales as $grupo)
                                    <?php
                                    if (!array_key_exists($grupo['Id'], $subtotal_grupo))
                                        $subtotal_grupo[$grupo['Id']] = ['M' => 0, 'F' => 0];
                                    ?>
                                @endforeach

                                @foreach ($sesiones as $sesion)
                                    <?php
                                    $subtotal_genero_m = 0;
                                    $subtotal_genero_f = 0;
                                    $cancelado = $sesion['Estado'] == 'Cancelado' ? true : false;
                                    $asumida_por_el_gestor = $sesion['Asumida_Por_El_Gestor'] ? true : false;
                                    ?>
                                    @foreach ($gruposPoblacionales as $grupo)
                                        @if (count($sesion->gruposPoblacionales) && !$cancelado)
                                            @foreach ($sesion->gruposPoblacionales()->where('Id_Grupo', $grupo['Id'])->where('Grupo_Asistencia', 'Participantes')->orderBy('Genero')->get() as $participacion)
                                                <?php
                                                switch ($participacion->pivot['Genero'])
                                                {
                                                    case 'M':
                                                        $subtotal_genero_m += $participacion->pivot['Cantidad'];
                                                        break;
                                                    case 'F':
                                                        $subtotal_genero_f += $participacion->pivot['Cantidad'];
                                                        break;
                                                }

                                                $subtotal_grupo[$grupo['Id']][$participacion->pivot['Genero']] += $participacion->pivot['Cantidad'];
                                                ?>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td colspan="1"><strong>SUBTOTAL</strong></td>
                                    @foreach ($gruposPoblacionales as $grupo)
                                        <?php
                                        $subtotal_grupo_m += $subtotal_grupo[$grupo['Id']]['M'];
                                        $subtotal_grupo_f += $subtotal_grupo[$grupo['Id']]['F'];
                                        $total += $subtotal_grupo[$grupo['Id']]['M'] + $subtotal_grupo[$grupo['Id']]['F'];
                                        ?>
                                        <td style="text-align:right;"><strong>{{ $subtotal_grupo[$grupo['Id']]['M'] }}</strong></td>
                                        <td style="text-align:right;"><strong>{{ $subtotal_grupo[$grupo['Id']]['F'] }}</strong></td>
                                    @endforeach
                                    <td style="text-align:right;"><strong>{{ $subtotal_grupo_m }}</strong></td>
                                    <td style="text-align:right;"><strong>{{ $subtotal_grupo_f }}</strong></td>
                                    <td style="text-align:right;"><strong>--</strong></td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr class="active">
                                    <td colspan="1"><strong>TOTAL</strong></td>
                                    @foreach ($gruposPoblacionales as $grupo)
                                        <td style="text-align:center;" colspan="2"><strong>{{ $subtotal_grupo[$grupo['Id']]['M'] + $subtotal_grupo[$grupo['Id']]['F'] }}</strong></td>
                                    @endforeach
                                    <td style="text-align:center;" colspan="3">
                                        <strong>{{ $subtotal_grupo_m + $subtotal_grupo_f }}</strong>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            <?php $total_participantes = $subtotal_grupo; ?>
                        </div>
                        <div class="col-md-12"><br></div>
                        <div class="col-md-12">
                            <label for="">Asistentes (Frecuencia Relativa)</label><br>
                            <table id="asistencias" class="display nowrap table table-bordered table-min">
                                <thead>
                                <tr>
                                    <th valign="center" align="center" width="30px" rowspan="2">#</th>
                                    @foreach($gruposPoblacionales as $grupo)
                                        <th style="width:104px;" colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</th>
                                    @endforeach
                                    <th style="width:104px;" colspan="2">Subtotal<br>Participantes</th>
                                    <th style="width:52px;" valign="center" rowspan="2">Total</th>
                                </tr>
                                <tr>
                                    @foreach($gruposPoblacionales as $grupo)
                                        <th style="width: 52px;">H</th>
                                        <th style="width: 52px;">M</th>
                                    @endforeach
                                    <th style="width: 52px;">H</th>
                                    <th style="width: 52px;">M</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                $subtotal_grupo = [];
                                $subtotal_grupo_m = 0;
                                $subtotal_grupo_f = 0;
                                $total = 0;
                                ?>

                                @foreach ($gruposPoblacionales as $grupo)
                                    <?php
                                    if(!array_key_exists($grupo['Id'], $subtotal_grupo))
                                        $subtotal_grupo[$grupo['Id']] = ['M' => 0, 'F' => 0];
                                    ?>
                                @endforeach

                                @foreach ($sesiones as $sesion)
                                    <?php
                                    $subtotal_genero_m = 0;
                                    $subtotal_genero_f = 0;
                                    $cancelado = $sesion['Estado'] == 'Cancelado' ? true : false;
                                    $asumida_por_el_gestor = $sesion['Asumida_Por_El_Gestor'] ? true : false;
                                    ?>
                                    @foreach ($gruposPoblacionales as $grupo)
                                        @if (count($sesion->gruposPoblacionales) && !$cancelado)
                                            @foreach ($sesion->gruposPoblacionales()->where('Id_Grupo', $grupo['Id'])->where('Grupo_Asistencia', 'Asistentes')->orderBy('Genero')->get() as $participacion)
                                                <?php
                                                switch ($participacion->pivot['Genero'])
                                                {
                                                    case 'M':
                                                        $subtotal_genero_m += $participacion->pivot['Cantidad'];
                                                        break;
                                                    case 'F':
                                                        $subtotal_genero_f += $participacion->pivot['Cantidad'];
                                                        break;
                                                }

                                                $subtotal_grupo[$grupo['Id']][$participacion->pivot['Genero']] += $participacion->pivot['Cantidad'];
                                                ?>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td><strong>SUBTOTAL</strong></td>
                                    @foreach ($gruposPoblacionales as $grupo)
                                        <?php
                                        $subtotal_grupo_m += $subtotal_grupo[$grupo['Id']]['M'];
                                        $subtotal_grupo_f += $subtotal_grupo[$grupo['Id']]['F'];
                                        $total += $subtotal_grupo[$grupo['Id']]['M'] + $subtotal_grupo[$grupo['Id']]['F'];
                                        ?>
                                        <td style="text-align:right;"><strong>{{ $subtotal_grupo[$grupo['Id']]['M'] }}</strong></td>
                                        <td style="text-align:right;"><strong>{{ $subtotal_grupo[$grupo['Id']]['F'] }}</strong></td>
                                    @endforeach
                                    <td style="text-align:right;"><strong>{{ $subtotal_grupo_m }}</strong></td>
                                    <td style="text-align:right;"><strong>{{ $subtotal_grupo_f }}</strong></td>
                                    <td style="text-align:right;"><strong>--</strong></td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr class="active">
                                    <td><strong>TOTAL</strong></td>
                                    @foreach ($gruposPoblacionales as $grupo)
                                        <td style="text-align:center;" colspan="2"><strong>{{ $subtotal_grupo[$grupo['Id']]['M'] + $subtotal_grupo[$grupo['Id']]['F'] }}</strong></td>
                                    @endforeach
                                    <td style="text-align:center;" colspan="3">
                                        <strong>{{ $subtotal_grupo_m + $subtotal_grupo_f }}</strong>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            <?php $total_asistentes = $subtotal_grupo; ?>
                        </div>
                        <div class="col-md-12">
                            <br>
                        </div>
                        <div class="col-md-12">
                            <?php
                            $subtotal_participantes_m = 0;
                            $subtotal_participantes_f = 0;
                            $subtotal_asistentes_m = 0;
                            $subtotal_asistentes_f = 0;
                            $subtotal_m = 0;
                            $subtotal_f = 0;
                            $total = 0;
                            ?>
                            <label for="">Total</label><br>
                            <table class="display nowrap table table-bordered table-min">
                                <thead>
                                <tr>
                                    @foreach($gruposPoblacionales as $grupo)
                                        <th style="width:104px;" colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</th>
                                    @endforeach
                                    <th style="width:104px;" colspan="2">Subtotal<br>Participantes</th>
                                    <th style="width:52px;" valign="center" rowspan="2">Total</th>
                                </tr>
                                <tr>
                                    @foreach($gruposPoblacionales as $grupo)
                                        <th style="width: 52px;">H</th>
                                        <th style="width: 52px;">M</th>
                                    @endforeach
                                    <th style="width: 52px;">H</th>
                                    <th style="width: 52px;">M</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($gruposPoblacionales as $grupo)
                                        <?php
                                        $subtotal_participantes_m += $total_participantes[$grupo['Id']]['M'];
                                        $subtotal_participantes_f += $total_participantes[$grupo['Id']]['F'];
                                        ?>
                                        <td align="right">{{ $total_participantes[$grupo['Id']]['M'] }}</td>
                                        <td align="right">{{ $total_participantes[$grupo['Id']]['F'] }}</td>
                                    @endforeach
                                    <td align="right">{{ $subtotal_participantes_m }}</td>
                                    <td align="right">{{ $subtotal_participantes_f }}</td>
                                    <td align="right">{{ $subtotal_participantes_m + $subtotal_participantes_f }}</td>
                                </tr>
                                <tr>
                                    @foreach($gruposPoblacionales as $grupo)
                                        <?php
                                        $subtotal_asistentes_m += $total_asistentes[$grupo['Id']]['M'];
                                        $subtotal_asistentes_f += $total_asistentes[$grupo['Id']]['F'];
                                        ?>
                                        <td align="right">{{ $total_asistentes[$grupo['Id']]['M'] }}</td>
                                        <td align="right">{{ $total_asistentes[$grupo['Id']]['F'] }}</td>
                                    @endforeach
                                    <td align="right">{{ $subtotal_asistentes_m }}</td>
                                    <td align="right">{{ $subtotal_asistentes_f }}</td>
                                    <td align="right">{{ $subtotal_asistentes_m + $subtotal_asistentes_f }}</td>
                                </tr>
                                <tr class="active">
                                    @foreach($gruposPoblacionales as $grupo)
                                        <?php
                                        $subtotal_m += $total_asistentes[$grupo['Id']]['M'] + $total_participantes[$grupo['Id']]['M'];
                                        $subtotal_f += $total_asistentes[$grupo['Id']]['F'] + $total_participantes[$grupo['Id']]['F'];
                                        ?>
                                        <td align="right">{{ $total_asistentes[$grupo['Id']]['M'] + $total_participantes[$grupo['Id']]['M'] }}</td>
                                        <td align="right">{{ $total_asistentes[$grupo['Id']]['F'] + $total_participantes[$grupo['Id']]['F'] }}</td>
                                    @endforeach
                                    <td align="right">{{ $subtotal_m }}</td>
                                    <td align="right">{{ $subtotal_f }}</td>
                                    <td align="right">{{ $subtotal_m + $subtotal_f }}</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="active">
                                        @foreach($gruposPoblacionales as $grupo)
                                            <?php $total += $total_participantes[$grupo['Id']]['M'] + $total_asistentes[$grupo['Id']]['M'] + $total_participantes[$grupo['Id']]['F'] + $total_asistentes[$grupo['Id']]['F'] ?>
                                            <td align="center" colspan="2">{{ $total_participantes[$grupo['Id']]['M'] + $total_asistentes[$grupo['Id']]['M'] + $total_participantes[$grupo['Id']]['F'] + $total_asistentes[$grupo['Id']]['F'] }}</td>
                                        @endforeach
                                        <td align="center" colspan="3"><strong>{{ $total }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
@stop