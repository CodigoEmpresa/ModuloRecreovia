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
            Total de cronogramas encontrados: {{ count($elementos) }}
        </div>
        <div class="col-xs-12"><br></div>
        <form action="{{ $crear ? url('/programacion') : url('/sesiones/administrar') }}" method="post">
            <div class="col-md-12">
                <div class="row">
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
                        <label for="">Jornada</label>
                        <select name="jornada" id="jornada" title="Estado" class="form-control" data-value="{{ old('jornada') }}">
                            <option value="Todos">Todos</option>
                            @foreach($jornadas as $jornada)
                                <option value="{{ $jornada['Id_Jornada'] }}">{{ $jornada->toString() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">&nbsp;</label><br>
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-success"><i class="fa fa-filter"></i></button>
                        @if ($crear)
                            <a href="{{ url('/programacion/crear/') }}" class="btn btn-primary" id="crear">Crear</a>
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
                        <th>Cod.</th>
                        <th width="350px">Cronograma</th>
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
            @endif
        </div>
    </div>
</div>
