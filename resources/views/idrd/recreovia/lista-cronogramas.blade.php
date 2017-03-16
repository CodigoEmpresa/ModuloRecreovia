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
        @if(
            !$_SESSION['Usuario']['Permisos']['gestion_global_de_sesiones'] || 
            ($_SESSION['Usuario']['Permisos']['programar_sesiones'] && in_array('Gestor', $_SESSION['Usuario']['Roles']))
        )
            <div class="col-xs-12">
                <a href="{{ url('/programacion/crear/') }}" class="btn btn-primary" id="crear">Crear</a>
            </div>
        @endif 
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <table class="default display no-wrap responsive table table-min table-striped" width="100%">
                <thead>
                    <tr>
                        <th>Cod.</th>
                        <th>Cronograma</th>
                        <th>Jornada</th>
                        <th>Sesiones</th>
                        <th data-priority="2"  class="no-sort" style="width: 30px;"> 
                        </th>
                        <th data-priority="2"  class="no-sort" style="width: 30px;">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($elementos as $cronograma)
                        <tr>
                            <td align="center" width=60>
                                {{ $cronograma->getCode() }}
                            </td>
                            <td>
                                {{ $cronograma->punto->toString() }} <br>
                                {{ $cronograma->toString() }}
                                @if ($_SESSION['Usuario']['Permisos']['gestion_global_de_sesiones'])
                                    <br>
                                    {{ $cronograma->gestor->persona->toString() }}
                                @endif
                            </td>
                            <td>{{ $cronograma->jornada->toString() }}</td>
                            <td>{{ count($cronograma->sesiones) }}</td>
                            <td>
                                <a data-role="sesiones" href="{{ url('/gestores/'.$cronograma['Id'].'/sesiones') }}" class="pull-right btn btn-default btn-xs separe-right" data-toggle="tooltip" data-placement="bottom" title="Sesiones"> 
                                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> 
                                </a> 
                            </td>
                            <td>
                                <a data-role="editar" href="{{ url('/programacion/'.$cronograma['Id'].'/editar') }}" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                 </tbody>
            </table>
        </div>
    </div>
</div>