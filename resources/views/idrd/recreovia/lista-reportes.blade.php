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
        @if(in_array('Gestor', $_SESSION['Usuario']['Roles']))
            <div class="col-xs-12">
                <a class="btn btn-primary" href="{{ url('informes/jornadas/crear') }}">Crear</a>
            </div>
        @endif
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            Total de reportes creados: {{ count($elementos) }}
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <table class="default display no-wrap responsive table table-min table-striped" width="100%">
                <thead>
                    <tr>
                        <th>Reporte</th>
                        <th>Punto</th>
                        <th style="width: 100px;">Profesores</th>
                        <th style="width: 100px;">Sesiones</th>
                        <th style="width: 100px;">U. Actualización</th>
                        <th data-priority="2"  class="no-sort" style="width: 35px;">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($elementos as $reporte)
                        <tr>
                            <td>{{ $reporte->punto->toString() }}</td>
                            <td>{{ $reporte->toString() }}</td>
                            <td>{{ count($reporte->profesores) }}</td>
                            <td>{{ 
                                    count($reporte->cronograma->sesiones
                                        ->filter(function($item) use ($reporte){ 
                                            return $item->Fecha == $reporte->Dia; 
                                        })->filter(function($item) { 
                                            return $item->Estado == 'Aprobado'; 
                                        })->all()) 
                                }}
                            </td>
                            <td>{{ $reporte->updated_at }}</td>
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
    </div>
</div>