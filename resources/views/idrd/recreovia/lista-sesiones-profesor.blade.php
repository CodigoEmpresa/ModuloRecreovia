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
                <table id="sesiones" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sesión</th>
                            <th>Fecha</th>
                            <th>H. Inicio</th>
                            <th>H. Fin</th>
                            <th>Estado</th>
                            <th></th>
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
                                    case 'Rechazado':
                                    case 'Corregir':
                                        $class = 'danger';
                                    break;
                                }
                            ?>
                            <tr class="{{ $class }}">
                                <td>{{ $sesion->Objetivo_General }}<br><small class="text-mutted">{{ $sesion->profesor->persona->toFriendlyString() }}</small></td>
                                <td>{{ $sesion->Fecha }}</td>
                                <td>{{ $sesion->Inicio }}</td>
                                <td>{{ $sesion->Fin }}</td>
                                <td>{{ $sesion->Estado }}</td>
                                <td> 
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