@section('script')
    @parent

    <script src="{{ asset('public/Js/lista_reportes/buscador.js') }}"></script>
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
                        <label for="">Fecha</label>
                        <input name="fecha" type="text" placeholder="Fecha" class="form-control" data-role="datepicker" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('fecha') }}">
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
                <table class="default display no-wrap responsive table table-min table-striped" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 200px;">Punto</th>
                            <th>Jornada</th>
                            <th style="width: 100px;">Estado</th>
                            <th style="width: 100px;">U. Actualización</th>
                            <th data-priority="2"  class="no-sort" style="width: 35px;">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($elementos as $reporte)
                            @if($reporte->punto)
                                <tr>
                                    <td>{{ $reporte->punto->toString() }}</td>
                                    <td>{!! $reporte->toString() !!}</td>
                                    <td>{{ empty($reporte->Estado) ? 'Pendiente' : $reporte->Estado }}</td>
                                    <td>{{ $reporte->updated_at }}</td>
                                    <td>
                                        <a href="{{ url('/informes/jornadas/'.$reporte['Id'].'/editar') }}" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>
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
