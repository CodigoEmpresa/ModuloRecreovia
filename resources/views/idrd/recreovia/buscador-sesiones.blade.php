@section('script')
    @parent

    <script src="{{ asset('public/Js/sesiones/buscador-sesiones.js') }}"></script>
@stop

<div class="content">
    <div id="main" class="row" data-url="{{ url('sesiones') }}">
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
        <form action="{{ url('/sesiones/buscar') }}" method="post">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="">Buscar</label>

                        <div class="input-group">
                            <input type="text" name="codigos" class="form-control" value="{{ old('codigos') }}" placeholder="Códigos separados por ( , )">
                            <div class="input-group-btn">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-success">Buscar</button>
                            </div>
                        </div>
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
                            <th style="width:80px;" width="50px">Fecha</th>
                            <th style="width:50px;" width="50px">H. Inicio</th>
                            <th style="width:50px;" width="50px">H. Fin</th>
                            <th style="width:50px;" width="50px">Estado</th>
                            <th style="width:30px;" data-priority="2"></th>
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
                            <tr data-id="{{ $sesion->Id }}" class="{{ $class }}">
                                <td align="center" width=60>
                                    {{ $sesion->getCode() }}
                                </td>
                                <td>{{ $sesion->Objetivo_General }}
                                    <br>
                                    <small class="text-mutted">
                                        @if($sesion->profesor)
                                            {{ $sesion->profesor->persona->toFriendlyString() }}
                                        @else
                                            Sin profesor asignado
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $sesion->cronograma->punto->toString() }}<br><small class="text-mutted">{{ $sesion->cronograma->jornada->toString() }}</small></td>
                                <td align="center">{!! $sesion->getPending() !!}</td>
                                <td>{{ $sesion->Fecha }}</td>
                                <td>{{ $sesion->Inicio }}</td>
                                <td>{{ $sesion->Fin }}</td>
                                <td data-role="Estado">{{ $sesion->Estado }}</td>
                                <td data-priority="2">
                                    <div class="btn-group">
                                        <button class="pull-right btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="" href="#">Pendiente</a></li>
                                            <li><a class="" href="#">Diligenciado</a></li>
                                            <li><a class="" href="#">Corregir</a></li>
                                            <li><a class="" href="#">Aprobado</a></li>
                                            <li><a class="" href="#">Finalizado</a></li>
                                            <li><a class="" href="#">Rechazado</a></li>
                                            <li><a class="" href="#">Cancelado</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
