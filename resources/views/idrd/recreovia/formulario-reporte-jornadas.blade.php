@section('script')
    @parent

    <script src="{{ asset('public/Js/reporte/formulario.js') }}"></script>
@stop
<div class="content">
    <div id="main" class="row" data-url="{{ url('informes/jornadas') }}">
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
            @if(count($puntos) > 0)
                <div class="row">
                    <form id="principal" action="{{ url('informes/jornadas/generar') }}" method="post">
                        <fieldset>
                            <div class="col-md-4 form-group {{ $errors->has('Id_Punto') ? 'has-error' : '' }}">
                                <label for="">Punto</label>
                                <select name="Id_Punto" id="Id_Punto" class="form-control" data-value="{{ $informe ? $informe['Id_Punto'] : old('Id_Punto') }}" title="Seleccionar">
                                    @foreach($puntos as $punto)
                                        <option value="{{ $punto['Id_Punto'] }}">{{ $punto->getCode().' - '.$punto->toString() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 form-group {{ $errors->has('Id_Cronograma') ? 'has-error' : '' }}">
                                <label for="">Periodo y jornada</label>
                                <select name="Id_Cronograma" id="Id_Cronograma" class="form-control" data-json="{{ json_encode($puntos->toArray()) }}" data-value="{{ $informe ? $informe['Id_Cronograma'] : old('Id_Cronograma') }}" title="Seleccionar">
                                </select>
                            </div>
                            <!--<div class="col-md-4 form-group {{ $errors->has('Dia') ? 'has-error' : '' }}">
                                <label for="">Día</label>
                                <input type="text" name="Dia" class="form-control" data-role="datepicker" data-fecha-inicio="" data-fecha-fin="" data-dias="" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ $informe ? $informe['Dia'] : old('Dia') }}">
                            </div>
                            <div class="col-md-8 form-group {{ $errors->has('Dias') ? 'has-error' : '' }}">
                                <label for="">Días seleccionados</label> <br>
                                <input type="text" class="form-control" name="Dias" value="{{ $informe ? $informe['Dias'] : old('Dias') }}">
                            </div>-->
                            <div class="col-xs-12">
                                <table id="sesiones" class="default table table-min">
                                    <thead>
                                        <tr>
                                            <th style="width:100px;">Fecha</th>
                                            <th>Sesion</th>
                                            <th width="100px">Presente en</th>
                                            <th class="no-sort" style="width:30px;"><input type="checkbox" id="check_all"></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-xs-12">
                                <hr>
                            </div>
                            @if (!$informe || ($informe->cronograma->gestor['Id_Recreopersona'] == $_SESSION['Usuario']['Recreopersona']['Id_Recreopersona']))
                                <div class="col-md-12">
                                    <input type="hidden" name="_method" value="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="sesiones" value="{{ $informe ? implode(',', $informe->sesiones->pluck('Id')->toArray()) : old('sesiones') }}">
                                    <input type="hidden" name="Id" value="{{ $informe ? $informe['Id'] : 0 }}">
                                    @if ($informe['Estado'] != 'Finalizado')
                                        <input type="submit" value="{{ $informe ? 'Regenerar reporte' : 'Generar reporte' }}" id="generar" class="btn btn-primary">
                                        @if ($informe)
                                            <a data-toggle="modal" data-target="#modal-eliminar" class="btn btn-danger">Eliminar</a>
                                        @endif
                                    @endif
                                    <a href="{{ url('informes/jornadas') }}" class="btn btn-default">Volver</a>
                                </div>
                                <div class="col-md-12">
                                    <br><br>
                                </div>
                            @elseif (in_array('Profesor', $_SESSION['Usuario']['Roles']))
                                <div class="col-md-12">
                                    <a href="{{ url('informes/jornadas/profesor') }}" class="btn btn-default">Volver</a>
                                </div>
                                <div class="col-md-12">
                                    <br><br>
                                </div>
                            @elseif ($_SESSION['Usuario']['Permisos']['validar_reportes_jornadas'])
                                <div class="col-md-12">
                                    <a href="{{ url('informes/jornadas/revisar') }}" class="btn btn-default">Volver</a>
                                </div>
                                <div class="col-md-12">
                                    <br><br>
                                </div>
                            @endif
                        </fieldset>
                    </form>
                </div>
            @endif
            @if ($informe)
                <div class="row" id="formularios_complementarios">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="panel-title">
                                    DATOS GENERALES
                                </h4>
                            </div>
                            <div class="col-md-12">
                                <br>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Cod.</label>
                                <p class="form-control-static">{{ $informe->getCode() }}</p>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Gestor</label>
                                <p class="form-control-static">{{ $informe->cronograma->gestor->persona->toFriendlyString() }}</p>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Última actualización</label>
                                <p class="form-control-static">{{ $informe->updated_at }}</p>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <form action="{{ url('informes/jornadas/actualizar') }}" method="post">
                                        <fieldset>
                                            @if ($informe->cronograma->gestor['Id_Recreopersona'] == $_SESSION['Usuario']['Recreopersona']['Id_Recreopersona'])
                                                <div class="col-md-12 form-group">
                                                    <label for="">Estado</label><br>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="Estado" value="Pendiente" {{ $informe && $informe['Estado'] == 'Pendiente' ? 'checked' : '' }}> Pendiente
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="Estado" value="Aprobado" {{ $informe && $informe['Estado'] == 'Aprobado' ? 'checked' : '' }}> Aprobado
                                                    </label>
                                                    @if ($_SESSION['Usuario']['Permisos']['validar_reportes_jornadas'])
                                                        <label class="radio-inline">
                                                            <input type="radio" name="Estado" value="Rechazado" {{ $informe && $informe['Estado'] == 'Rechazado' ? 'checked' : '' }}> Rechazado
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="Estado" value="Corregir" {{ $informe && $informe['Estado'] == 'Corregir' ? 'checked' : '' }}> Corregir
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="Estado" value="Finalizado" {{ $informe && $informe['Estado'] == 'Finalizado' ? 'checked' : '' }}> Finalizado
                                                        </label>
                                                    @else
                                                        <p class="form-control-static">{{ $informe['Estado'] }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="col-md-12 form-group">
                                                <label for="">Observaciones</label>
                                                <textarea name="Observaciones" class="form-control">{{ $informe['Observaciones'] }}</textarea>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for="">Condiciones climáticas</label> <br>
                                                <label class="radio-inline">
                                                    <input type="radio" name="Condiciones_Climaticas" id="Condiciones_Climaticas1" value="soleado" {{ $informe && $informe['Condiciones_Climaticas'] == 'soleado' ? 'checked' : '' }}> Soleado
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="Condiciones_Climaticas" id="Condiciones_Climaticas2" value="frio" {{ $informe && $informe['Condiciones_Climaticas'] == 'frio' ? 'checked' : '' }}> Frio
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="Condiciones_Climaticas" id="Condiciones_Climaticas3" value="opaco" {{ $informe && $informe['Condiciones_Climaticas'] == 'opaco' ? 'checked' : '' }}> Opaco
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="Condiciones_Climaticas" id="Condiciones_Climaticas3" value="lluvia" {{ $informe && $informe['Condiciones_Climaticas'] == 'lluvia' ? 'checked' : '' }}> Lluvia
                                                </label>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="Id" value="{{ $informe ? $informe['Id'] : 0 }}">
                                                <input type="hidden" name="Area" value="datos_generales">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br>
                            </div>
                            <div class="col-md-12">
                                <h4 class="panel-title">
                                    INFORMACIÓN PROFESORES DE ACTIVIDAD FÍSICA
                                </h4>
                            </div>
                            <div class="col-md-12">
                                <br>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <form action="{{ url('informes/jornadas/actualizar') }}" method="post">
                                        <fieldset>
                                            <div class="col-md-12 form-group">
                                                <table class="table table-striped table-min">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:200px; text-align:center;">Profesor</th>
                                                            <th style="width:100px; text-align:center;">Hora<br>Llegada</th>
                                                            <th style="width:100px; text-align:center;">Hora<br>Salida</th>
                                                            <th style="width:100px; text-align:center;">Sesiones<br>realizadas</th>
                                                            <th style="width:100px; text-align:center;">Planificación</th>
                                                            <th style="width:100px; text-align:center;">Sistema<br>de datos</th>
                                                            <th>Novedades</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($informe->profesores as $profesor)
                                                            <tr>
                                                                <td>
                                                                    {{ $profesor->persona->toFriendlyString() }}
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Hora_Llegada_{{ $profesor['Id_Recreopersona'] }}" type="text" data-role="clockpicker" placeholder="HH:MM:SS" value="{{ $profesor->pivot['Hora_Llegada'] ? $profesor->pivot['Hora_Llegada'] : '' }}">
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Hora_Salida_{{ $profesor['Id_Recreopersona'] }}" type="text" data-role="clockpicker" placeholder="HH:MM:SS" value="{{ $profesor->pivot['Hora_Salida'] ? $profesor->pivot['Hora_Salida'] : '' }}">
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Sesiones_Realizadas_{{ $profesor['Id_Recreopersona'] }}" type="number" placeholder="#" value="{{ $profesor->pivot['Sesiones_Realizadas'] }}">
                                                                </td>
                                                                <td align="center" class="input" style="text-align:center;">
                                                                    <input name="Planificacion_{{ $profesor['Id_Recreopersona'] }}" type="checkbox" {{ $profesor->pivot['Planificacion'] ? 'checked' : '' }}>
                                                                </td>
                                                                <td align="center" class="input" style="text-align:center;">
                                                                    <input name="Sistema_De_Datos_{{ $profesor['Id_Recreopersona'] }}" type="checkbox" {{ $profesor->pivot['Sistema_De_Datos'] ? 'checked' : '' }}>
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Novedades_{{ $profesor['Id_Recreopersona'] }}" type="text" placeholder="Novedades" style="text-align:left;" value="{{ $profesor->pivot['Novedades'] }}">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="Id" value="{{ $informe ? $informe['Id'] : 0 }}">
                                                <input type="hidden" name="Area" value="informacion_profesores_de_actividad_fisica">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4 class="panel-title">
                                    NOVEDADES ESPECIALES
                                </h4>
                            </div>
                            <div class="col-md-12">
                                <br>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <form action="{{ url('informes/jornadas/actualizar') }}" method="post">
                                        <fieldset>
                                            <div class="col-md-12 form-group">
                                                <label for="">Recreovia</label><br>
                                                <table class="table table-striped table-min">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:30px; text-align:center;"></th>
                                                            <th style="width:100px; text-align:center;">514<br>523</th>
                                                            <th style="width:100px; text-align:center;">514<br>541</th>
                                                            <th style="width:100px; text-align:center;">514<br>542</th>
                                                            <th>Novedades</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td class="input">
                                                                <input name="Cod_514_523" type="text" data-role="clockpicker" placeholder="HH:MM:SS" value="{{ $informe->novedad ? $informe->novedad['Cod_514_523'] : '' }}">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Cod_514_541" type="text" data-role="clockpicker" placeholder="HH:MM:SS" value="{{ $informe->novedad ? $informe->novedad['Cod_514_541'] : '' }}">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Cod_514_542" type="text" data-role="clockpicker" placeholder="HH:MM:SS" value="{{ $informe->novedad ? $informe->novedad['Cod_514_542'] : '' }}">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Novedades" type="text" placeholder="Novedades" style="text-align:left;" value="{{ $informe->novedad ? $informe->novedad['Novedades'] : '' }}">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for="">Servicios</label><br>
                                                <table id="servicios" class="table table-striped table-min">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:30px; text-align:center;"></th>
                                                            <th style="width:100px; text-align:center;">Tipo</th>
                                                            <th style="width:100px; text-align:center;">514<br>523</th>
                                                            <th style="width:100px; text-align:center;">514<br>541</th>
                                                            <th style="width:100px; text-align:center;">514<br>542</th>
                                                            <th style="width:100px; text-align:center;">Empresa</th>
                                                            <th style="width:100px; text-align:center;">Placa<br>camión</th>
                                                            <th style="width:200px; text-align:center;">Operarios</th>
                                                            <th>Observaciones generales</th>
                                                            <th style="width: 30px;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr id="plantilla_servicio" style="display:none;">
                                                            <td data-role="item">0</td>
                                                            <td class="select">
                                                                <select name="tipo" data-name="tipo" data-ignore-selectpicker>
                                                                    <option value="Sonido">Sonido</option>
                                                                    <option value="Tarima">Tarima</option>
                                                                </select>
                                                            </td>
                                                            <td class="input">
                                                                <input name="Cod_514_523_" data-name="Cod_514_523" type="text" data-role="dynamic-clockpicker" placeholder="HH:MM:SS" value="">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Cod_514_541_" data-name="Cod_514_541" type="text" data-role="dynamic-clockpicker" placeholder="HH:MM:SS" value="">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Cod_514_542_" data-name="Cod_514_542" type="text" data-role="dynamic-clockpicker" placeholder="HH:MM:SS" value="">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Empresa" data-name="Empresa" type="text" placeholder="Empresa" style="text-align:left;">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Placa_Camion" data-name="Placa" type="text" placeholder="Placa" style="text-align:left;">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Operarios" data-name="Operarios" type="text" placeholder="Operarios" style="text-align:left;">
                                                            </td>
                                                            <td class="input">
                                                                <input name="Observaciones_Generales" data-name="Observaciones_Generales" type="text" placeholder="Observaciones generales" style="text-align:left;">
                                                            </td>
                                                            <td>
                                                                <a href="#" class="btn btn-default btn-xs" data-role="eliminar" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                            $i = 0;
                                                        ?>
                                                        @foreach($informe->servicios as $servicio)
                                                            <?php $i++; ?>
                                                            <tr id="plantilla_servicio">
                                                                <td data-role="item">{{ $i }}</td>
                                                                <td class="select">
                                                                    <select name="tipo_{{ $i-1 }}" data-name="tipo" data-value="{{ $servicio->tipo }}" data-ignore-selectpicker>
                                                                        <option value="Sonido">Sonido</option>
                                                                        <option value="Tarima">Tarima</option>
                                                                    </select>
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Cod_514_523_{{ $i-1 }}" data-name="Cod_514_523" type="text" data-role="dynamic-clockpicker" placeholder="HH:MM:SS" value="{{ $servicio['Cod_514_523'] }}">
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Cod_514_541_{{ $i-1 }}" data-name="Cod_514_541" type="text" data-role="dynamic-clockpicker" placeholder="HH:MM:SS" value="{{ $servicio['Cod_514_541'] }}">
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Cod_514_542_{{ $i-1 }}" data-name="Cod_514_542" type="text" data-role="dynamic-clockpicker" placeholder="HH:MM:SS" value="{{ $servicio['Cod_514_542'] }}">
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Empresa_{{ $i-1 }}" data-name="Empresa" type="text" placeholder="Empresa" style="text-align:left;" value="{{ $servicio['Empresa'] }}">
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Placa_Camion_{{ $i-1 }}" data-name="Placa" type="text" placeholder="Placa" style="text-align:left;" value="{{ $servicio['Placa_Camion'] }}">
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Operarios_{{ $i-1 }}" data-name="Operarios" type="text" placeholder="Operarios" style="text-align:left;" value="{{ $servicio['Operarios'] }}">
                                                                </td>
                                                                <td class="input">
                                                                    <input name="Observaciones_Generales_{{ $i-1 }}" data-name="Observaciones_Generales" type="text" placeholder="Observaciones generales" value="{{ $servicio['Observaciones_Generales'] }}" style="text-align:left;">
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="btn btn-default btn-xs" data-role="eliminar" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="10">
                                                                <button id="agregar_servicio" type="button" class="btn btn-xs btn-default">Agregar servicio</button>
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="Total_Servicios" value="{{ $informe ? count($informe->servicios) : 0 }}">
                                                <input type="hidden" name="Id" value="{{ $informe ? $informe['Id'] : 0 }}">
                                                <input type="hidden" name="Area" value="novedades_especiales">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
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
                                            <th style="width:100px;" valign="center" rowspan="2">Sesión</th>
                                            <th style="width:100px;" valign="center" rowspan="2">Fecha</th>
                                            <th style="width:100px;" valign="center" rowspan="2">PAF</th>
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
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{!!
                                                        $sesion['Objetivo_General'].
                                                        ($cancelado ? '<br><span class="label label-danger">Cancelada</span>' : '').
                                                        ($asumida_por_el_gestor ? '<br><span class="label label-primary">Gestor</span>':'')
                                                    !!}
                                                </td>
                                                <td align="center">{!! $sesion['Fecha'].'<br>'.$sesion['Inicio'] !!}</td>
                                                <td>
                                                    @if($sesion->profesor)
                                                        @if ($asumida_por_el_gestor)
                                                            {{ $informe->cronograma->gestor->persona->toFriendlyString() }}
                                                        @else
                                                            {{ $sesion->profesor->persona->toFriendlyString() }}
                                                        @endif
                                                    @else
                                                        Sin profesor asignado
                                                    @endif
                                                </td>
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
                                                            <td style="text-align:right;">{{ $participacion->pivot['Cantidad'] }}</td>
                                                        @endforeach
                                                    @else
                                                        <td style="width: 52px;">--</td>
                                                        <td style="width: 52px;">--</td>
                                                    @endif
                                                @endforeach
                                                <td style="text-align:right;">{{ $subtotal_genero_m }}</td>
                                                <td style="text-align:right;">{{ $subtotal_genero_f }}</td>
                                                <td style="text-align:right;">{{ $subtotal_genero_m + $subtotal_genero_f }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="active">
                                            <td colspan="4"><strong>SUBTOTAL</strong></td>
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
                                        <tr class="active">
                                            <td colspan="4"><strong>TOTAL</strong></td>
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
                            <div class="col-md-12">
                                <br><br>
                            </div>
                            <div class="col-md-12">
                                <label for="">Asistentes (Frecuencia Relativa)</label><br>
                                <table id="asistencias" class="display nowrap table table-bordered table-min">
                                    <thead>
                                        <tr>
                                            <th valign="center" align="center" width="30px" rowspan="2">#</th>
                                            <th style="width:100px;" valign="center" rowspan="2">Sesión</th>
                                            <th style="width:100px;" valign="center" rowspan="2">Fecha</th>
                                            <th style="width:100px;" valign="center" rowspan="2">PAF</th>
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
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{!!
                                                        $sesion['Objetivo_General'].
                                                        ($cancelado ? '<br><span class="label label-danger">Cancelada</span>' : '').
                                                        ($asumida_por_el_gestor ? '<br><span class="label label-primary">Gestor</span>':'')
                                                    !!}
                                                </td>
                                                <td align="center">{!! $sesion['Fecha'].'<br>'.$sesion['Inicio'] !!}</td>
                                                <td>
                                                    @if($sesion->profesor)
                                                        @if ($asumida_por_el_gestor)
                                                            {{ $informe->cronograma->gestor->persona->toFriendlyString() }}
                                                        @else
                                                            {{ $sesion->profesor->persona->toFriendlyString() }}
                                                        @endif
                                                    @else
                                                        Sin profesor asignado
                                                    @endif
                                                </td>
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
                                                            <td style="text-align:right;">{{ $participacion->pivot['Cantidad'] }}</td>
                                                        @endforeach
                                                    @else
                                                        <td style="width: 52px;">--</td>
                                                        <td style="width: 52px;">--</td>
                                                    @endif
                                                @endforeach
                                                <td style="text-align:right;">{{ $subtotal_genero_m }}</td>
                                                <td style="text-align:right;">{{ $subtotal_genero_f }}</td>
                                                <td style="text-align:right;">{{ $subtotal_genero_m + $subtotal_genero_f }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="active">
                                            <td colspan="4"><strong>SUBTOTAL</strong></td>
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
                                        <tr class="active">
                                            <td colspan="4"><strong>TOTAL</strong></td>
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
                                        <tr class="active">
                                            @foreach($gruposPoblacionales as $grupo)
                                                <?php $total += $total_participantes[$grupo['Id']]['M'] + $total_asistentes[$grupo['Id']]['M'] + $total_participantes[$grupo['Id']]['F'] + $total_asistentes[$grupo['Id']]['F'] ?>
                                                <td align="center" colspan="2">{{ $total_participantes[$grupo['Id']]['M'] + $total_asistentes[$grupo['Id']]['M'] + $total_participantes[$grupo['Id']]['F'] + $total_asistentes[$grupo['Id']]['F'] }}</td>
                                            @endforeach
                                            <td align="center" colspan="3"><strong>{{ $total }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12"><br></div>
                            <?php $ultima_sesion = $sesiones->last() ?>
                            @if ($ultima_sesion)
                                <div class="col-md-12">
                                    <h4 class="panel-title">
                                        PRODUCTO NO CONFORME
                                    </h4>
                                </div>
                                @if ($ultima_sesion->productoNoConforme)
                                    <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12"><br></div>
                                        <div class="col-md-6">
                                            <table class="table table-bordered table-min">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">Requisito</th>
                                                        <th colspan="2">Se cumple con el requisito</th>
                                                        <th rowspan="2">Requisito</th>
                                                        <th colspan="2">Se cumple con el requisito</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:100px;">Si</th>
                                                        <th style="width:100px;">No</th>
                                                        <th style="width:100px;">Si</th>
                                                        <th style="width:100px;">No</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_1'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_1'] === '0' ? 'x' : '' }}</td>
                                                        <td>8</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_8'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_8'] === '0' ? 'x' : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_2'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_2'] === '0' ? 'x' : '' }}</td>
                                                        <td>9</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_9'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_9'] === '0' ? 'x' : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_3'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_3'] === '0' ? 'x' : '' }}</td>
                                                        <td>10</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_10'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_10'] === '0' ? 'x' : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_4'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_4'] === '0' ? 'x' : '' }}</td>
                                                        <td>11</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_11'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_11'] === '0' ? 'x' : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_5'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_5'] === '0' ? 'x' : '' }}</td>
                                                        <td>12</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_12'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_12'] === '0' ? 'x' : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_6'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_6'] === '0' ? 'x' : '' }}</td>
                                                        <td>13</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_13'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_13'] === '0' ? 'x' : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_7'] === '1' ? 'x' : '' }}</td>
                                                        <td align="center">{{ $ultima_sesion->productoNoConforme['Requisito_7'] === '0' ? 'x' : '' }}</td>
                                                        <td colspan="3">TRATAMIENTOS: <br>(C) Concesión (I) Identificación de no uso</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="">Descripción de la no conformidad:</label>
                                                    <p class="form-control-static">
                                                        {{ $ultima_sesion->productoNoConforme['Descripcion_De_La_No_Conformidad'] ? : 'Sin especificar' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="">Descripción de la acción tomada:</label>
                                                    <p class="form-control-static">
                                                        {{ $ultima_sesion->productoNoConforme['Descripcion_De_La_Accion_Tomada'] ? : 'Sin especificar' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="">Tratamiento:</label>
                                                    <p class="form-control-static">
                                                        {{ $ultima_sesion->productoNoConforme['Tratamiento'] ? : 'Sin especificar' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <div class="col-md-12">
                                        No se ha registrado el formato de producto no conforme. (Sesión {{ $ultima_sesion->getCode() }})
                                    </div>
                                @endif
                                <div class="col-md-12"><br></div>
                                <div class="col-md-12">
                                    <h4 class="panel-title">
                                        CALIFICACIÓN DEL SERVICIO
                                    </h4>
                                </div>
                                @if ($ultima_sesion->calificacionDelServicio)
                                    <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12"><br></div>
                                        <div class="col-md-12">
                                            <table class="table table-min">
                                                <tr>
                                                    <td>1. Puntualidad PAF</td>
                                                    <td style="width:30px;">{{ $ultima_sesion->calificacionDelServicio['Puntualidad_PAF'] }}</td>
                                                    <td>3. Escenario y Montaje</td>
                                                    <td style="width:30px;">{{ $ultima_sesion->calificacionDelServicio['Escenario_Y_Montaje'] }}</td>
                                                    <td>5. Variedad y Creatividad</td>
                                                    <td style="width:30px;">{{ $ultima_sesion->calificacionDelServicio['Variedad_Y_Creatividad'] }}</td>
                                                    <td>7. Divulgación</td>
                                                    <td style="width:30px;">{{ $ultima_sesion->calificacionDelServicio['Divulgacion'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2. Tiempo de la Sesión</td>
                                                    <td style="width:30px;">{{ $ultima_sesion->calificacionDelServicio['Tiempo_De_La_Sesion'] }}</td>
                                                    <td>4. Cumplimiento del Objetivo:</td>
                                                    <td style="width:30px;">{{ $ultima_sesion->calificacionDelServicio['Cumplimiento_Del_Objetivo'] }}</td>
                                                    <td>6. Imagen Institucional</td>
                                                    <td style="width:30px;">{{ $ultima_sesion->calificacionDelServicio['Imagen_Institucional'] }}</td>
                                                    <td>8. Seguridad</td>
                                                    <td style="width:30px;">{{ $ultima_sesion->calificacionDelServicio['Seguridad'] }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="">Nombre representante de la comunidad que califica el servicio: </label>
                                            <p class="form-control-static">
                                                {{ $ultima_sesion->calificacionDelServicio['Nombre'] }}
                                            </p>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="">Correo: </label>
                                            <p class="form-control-static">
                                                {{ $ultima_sesion->calificacionDelServicio['Correo'] }}
                                            </p>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="">Teléfono: </label>
                                            <p class="form-control-static">
                                                {{ $ultima_sesion->calificacionDelServicio['Telefono'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <div class="col-md-12">
                                        No se ha registrado el formato de calificación del servicio. (Sesión {{ $ultima_sesion->getCode() }})
                                    </div>
                                @endif
                            @else
                                <div class="col-md-12">
                                    <h4 class="panel-title">No se han registrado sesiónes para esta jornada</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if ($informe['Estado'] != 'Finalizado' || $_SESSION['Usuario']['Permisos']['validar_reportes_jornadas'])
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <input type="button" class="btn btn-primary" id="actualizar_reporte" value="Actualizar reporte">
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@if ($informe)
    <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body">
                    <p>Realmente desea eliminar este informe.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ url('informes/jornadas/'.$informe['Id'].'/eliminar') }}" class="btn btn-danger">Eliminar</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endif
