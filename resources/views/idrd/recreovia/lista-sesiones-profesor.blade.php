@section('script')
    @parent

    <script src="{{ asset('public/Js/sesiones/lista-sesiones.js') }}"></script>
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
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            Total de sesiones encontradas: {{ count($elementos) }}
        </div>
        <div class="col-md-12"><br></div>
        <form action="{{ url('/profesores/sesiones') }}" method="post">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="">Estado</label>
                        <select name="estado" id="estado" title="Estado" class="form-control" data-value="{{ old('estado') }}">
                            <option value="Todos">Todos</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Diligenciado">Diligenciado</option>
                            <option value="Corregir">Corregir</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Finalizado">Finalizado</option>
                            <option value="Rechazado">Rechazado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">Desde</label>
                        <input name="desde" type="text" placeholder="Desde" class="form-control" data-role="datepicker" data-rel="fecha_inicio" data-fecha-inicio="" data-fecha-fin="" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('desde') }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">Hasta</label>
                        <input name="hasta" type="text" placeholder="Desde" class="form-control" data-role="datepicker" data-rel="fecha_fin" data-fecha-inicio="" data-fecha-fin="" data-fechas-importantes="{{ Festivos::create()->datesToString() }}" value="{{ old('hasta') }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">&nbsp;</label><br>
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-success"><i class="fa fa-filter"></i></button>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-xs-12"><hr></div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            @if (count($elementos) > 0)
                <table id="sesiones" class="display nowrap table table-striped table-min">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Sesión</th>
                            <th>Punto / Jornada</th>
                            <th style="width:50px;" width="50px">Pendientes</th>
                            <th style="width:50px;" width="50px">Fecha</th>
                            <th style="width:50px;" width="50px">H. Inicio</th>
                            <th style="width:50px;" width="50px">H. Fin</th>
                            <th style="width:50px;" width="50px">Estado</th>
                            <th style="width: 30px;" data-priority="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($elementos as $sesion)
                            <?php
                                switch ($sesion->Estado)
                                {
                                    case 'Pendiente':
                                        $class = 'default';
                                    break;
                                    case 'Diligenciado':
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
                                    case 'Cancelado':
                                        $class = 'danger';
                                    break;
                                    default:
                                        $class= 'default';
                                    break;
                                }
                            ?>
                            <tr class="{{ $class }}">
                                <td align="center" width=60>
                                    {{ $sesion->getCode() }}
                                </td>
                                <td>{{ $sesion->Objetivo_General }}<br><small class="text-mutted">{{ $sesion->profesor->persona->toFriendlyString() }}</small></td>
                                <td>{{ $sesion->cronograma->punto->toString() }}<br><small class="text-mutted">{{ $sesion->cronograma->jornada->toString() }}</small></td>
                                <td align="center">{!! $sesion->getPending() !!}</td>
                                <td>{{ $sesion->Fecha }}</td>
                                <td>{{ $sesion->Inicio }}</td>
                                <td>{{ $sesion->Fin }}</td>
                                <td>{{ $sesion->Estado }}</td>
                                <td data-priority="2">
                                    <a data-role="editar" href="{{ url('/profesores/sesiones/'.$sesion['Id'].'/editar') }}" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                No se ha registrado ninguna sesión hasta el momento.
            @endif
        </div>
    </div>
</div>
