  @section('script')
    @parent

    <!--<script src="{{ asset('public/Js/profesores/buscador.js') }}"></script>-->
@stop

<div class="content">
    <div id="main" class="row" data-url="{{ url('profesores') }}">
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
        <form action="{{ in_array('Gestor', $_SESSION['Usuario']['Roles']) ? url('/informes/jornadas') : url('/informes/jornadas/profesor') }}" method="post">
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
                        <label for="">Punto</label>
                        <select name="punto" id="punto" title="Punto" class="form-control" data-value="{{ old('punto') }}" data-live-search="true">
                            <option value="Todos">Todos</option>
                            @foreach($puntos as $punto)
                                <option value="{{ $punto['Id_Punto'] }}">{{ $punto->getCode() }} - {{ $punto->toString() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">Fecha inicio</label>
                        <input name="fecha_inicio" type="text" placeholder="Fecha inicio" class="form-control" data-role="datepicker" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('fecha_inicio') }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">Fecha fin</label>
                        <input name="fecha_fin" type="text" placeholder="Fecha fin" class="form-control" data-role="datepicker" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('fecha_fin') }}">
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
        @if(count($elementos) > 0)
            <div class="col-xs-12">
                <table class="default display no-wrap responsive table table-min table-striped" width="100%">
                    <thead>
                        <tr>
                            <th class="all" style="width: 70px;">Cod.</th>
                            <th class="all" style="width: 200px;">Punto</th>
                            <th class="all">Jornada</th>
                            <th class="all" style="width: 80px;">Estado</th>
                            <th class="none" style="width: 80px;">Gestor</th>
                            <th class="none" style="width: 80px;">Profesor(es)</th>
                            <th class="none" style="width: 80px;">Sesiones</th>
                            <th class="all" style="width: 100px;">F. Creación</th>
                            <th class="all no-sort" data-priority="2" style="width: 35px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($elementos as $reporte)
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
                            <tr class="{{ $class }}">
                                <td>{{ $reporte->getCode() }}</td>
                                <td>{{ $reporte->punto->toString() }}</td>
                                <td>{!! $reporte->toString() !!}</td>
                                <td>{{ empty($reporte->Estado) ? 'Pendiente' : $reporte->Estado }}</td>
                                <td>
                                    {{ $reporte->cronograma->gestor->persona->toFriendlyString() }}
                                </td> <td>
                                    @foreach($reporte->profesores as $profesor)
                                        {{ $profesor->persona->toFriendlyString() }},
                                    @endforeach
                                </td>
                                <td>{{ count($reporte->sesiones) }}</td>
                                <td>{{ $reporte->created_at }}</td>
                                <td>
                                    <a href="{{ url('/informes/jornadas/'.$reporte['Id'].'/editar') }}" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
