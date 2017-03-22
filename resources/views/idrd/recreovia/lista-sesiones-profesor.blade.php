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
            Total de sesiones asignadas: {{ count($elementos) }}
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            @if (count($elementos) > 0)
                <table id="sesiones" class="display nowrap table table-striped table-min">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Sesión</th>
                            <th>Punto / Jornada</th>
                            <th stlye="width:50px;">Fecha</th>
                            <th stlye="width:50px;">H. Inicio</th>
                            <th stlye="width:50px;">H. Fin</th>
                            <th>Estado</th>
                            <th data-priority="2"></th>
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
                                        $class = 'warning';
                                    break;
                                    case 'Aprobado':
                                        $class = 'success';
                                    break;
                                    case 'Finalizado':
                                        $class = 'info';
                                    break;
                                    case 'Rechazado':
                                    case 'Corregir':
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
