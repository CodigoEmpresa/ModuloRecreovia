@section('script')
    @parent

    <script src="{{ asset('public/Js/lista_reportes/buscador.js') }}"></script>
    <script src="{{ asset('public/Js/lista_reportes/cambiador-estados.js') }}"></script>
@stop

<div class="content">
    <div id="main" class="row" data-url="{{ url('informes') }}">
        @if ($status == 'success')
            <div id="alerta" class="col-xs-12">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Datos actualizados satisfactoriamente.
                </div>
            </div>
        @endif
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            Total de reportes encontrados: {{ count($elementos) }}
        </div>
        <div class="col-xs-12"><br></div>
        <form action="{{ url('/informes/jornadas/revisar') }}" method="post">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="">Estado</label>
                        <select name="estado" id="estado" title="Estado" class="form-control" data-value="{{ old('estado') }}">
                            <option value="Todos">Todos</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Rechazado">Rechazado</option>
                            <option value="Corregir">Corregir</option>
                            <option value="Finalizado">Finalizado</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">Localidad</label>
                        <select name="localidad" id="localidad" title="Localidad" class="form-control" data-value="{{ old('localidad') }}" data-live-search="true">
                            <option value="Todos">Todos</option>
                            @foreach($localidades as $localidad)
                                <option value="{{ $localidad['Id_Localidad'] }}">{{ $localidad['Id_Localidad'] }} - {{ $localidad['Localidad'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">Punto</label>
                        <select name="punto" id="punto" title="Punto" class="form-control" data-value="{{ old('punto') }}" data-live-search="true">
                            <option value="Todos">Todos</option>
                            @foreach($puntos as $punto)
                                <option data-localidad="{{ $punto['Id_Localidad'] }}" value="{{ $punto['Id_Punto'] }}">{{ $punto->getCode() }} - {{ $punto->toString() }}</option>
                            @endforeach
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
                    <div class="col-md-2 form-group">
                        <label for="">&nbsp;</label><br>
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-success">Buscar</button>
                        @if(in_array('Gestor', $_SESSION['Usuario']['Roles']))
                            <a class="btn btn-primary" href="{{ url('informes/jornadas/crear') }}">Crear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
        <div class="col-xs-12"><hr></div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            @if (count($elementos) > 0)
                <table id="informes" class="default display no-wrap responsive table table-min table-striped" width="100%">
                    <thead>
                        <tr>
                            <th class="all" style="width: 70px;">Cod.</th>
                            <th class="all" style="width: 200px;">Punto</th>
                            <th class="all">Jornada</th>
                            <th class="all" style="width: 80px;">Estado</th>
                            <th class="none" style="width: 80px;">Gestor</th>
                            <th class="none" style="width: 80px;">Profesor(es)</th>
                            <th class="none" style="width: 80px;">Sesiones</th>
                            <th class="all" style="width: 100px;">U. Actualización</th>
                            <th class="all no-sort" data-priority="2" style="width: 35px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($elementos as $reporte)
                            @if($reporte->punto)
                                <?php
                                $class = '';

                                switch ($reporte->Estado)
                                {
                                    case 'Pendiente':
                                        $class = 'default';
                                        break;
                                    case 'Corregir':
                                        $class = 'warning';
                                        break;
                                    case 'Aprobado':
                                        $class = 'success';
                                        break;
                                    case 'Finalizado':
                                        $class = 'info';
                                        break;
                                    case 'Rechazado':
                                        $class = 'danger';
                                        break;
                                    default:
                                        $class= 'default';
                                        break;
                                }
                                ?>
                                <tr data-id="{{ $reporte->Id }}" class="{{ $class }}">
                                    <td>{{ $reporte->getCode() }}</td>
                                    <td>{{ $reporte->punto->toString() }}</td>
                                    <td>{!! $reporte->toString() !!}</td>
                                    <td data-role="Estado">{{ empty($reporte->Estado) ? 'Pendiente' : $reporte->Estado }}</td>
                                    <td>
                                        {{ $reporte->cronograma->gestor->persona->toFriendlyString() }}
                                    </td>
                                    <td>
                                        @foreach($reporte->profesores as $profesor)
                                            @if ($profesor)
                                                {{ $profesor->persona->toFriendlyString() }}
                                            @else
                                                Sin profesor asignado
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{
                                        count($reporte->cronograma->sesiones
                                            ->filter(function($item) use ($reporte){
                                                return in_array($item->Fecha, explode(',', $reporte->Dias));
                                            })->filter(function($item) {
                                                return $item->Estado == 'Finalizado';
                                            })->all())
                                    }}
                                    </td>
                                    <td>{{ $reporte->updated_at }}</td>
                                    <td>
                                        <div class="pull-right btn-group">
                                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ url('/informes/jornadas/'.$reporte['Id'].'/editar') }}">Editar</a></li>
                                                <li><a data-role="estado" href="#">Rechazado</a></li>
                                                <li><a data-role="estado" href="#">Corregir</a></li>
                                                <li><a data-role="estado" href="#">Finalizado</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
