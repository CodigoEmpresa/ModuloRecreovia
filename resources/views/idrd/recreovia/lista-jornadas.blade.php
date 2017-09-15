    <div class="content">
    <div id="main" class="row" data-url="{{ url('jornadas') }}">
        @if ($status == 'success')
            <div id="alerta" class="col-xs-12">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Datos actualizados satisfactoriamente.
                </div>                                
            </div>
        @endif
        <div class="col-xs-12">
            <a href="{{ url('/jornadas/crear/') }}" class="btn btn-primary" id="crear">Crear</a>
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            Total de jornadas registradas: {{ count($elementos) }}
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <table class="default display responsive no-wrap table table-min table-striped" width="100%">
                <thead>
                    <tr>
                        <th>
                            Cod.
                        </th>
                        <th>
                            Jornada
                        </th>
                        <th data-priority="2" class="no-sort" style="width: 35px;">
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($elementos as $jornada)
                        <tr>
                            <td align="center" width=60>
                               {{ $jornada->getCode() }} 
                            </td>
                            <td>
                                JORNADA {{ strtoupper($jornada->toString()) }} <br>
                                <small>Disponible en {{ count($jornada->puntos) }} puntos</small>
                            </td>
                            <td>
                                <div class="pull-right btn-group">
                                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="" data-role="editar" href="{{ url('jornadas/'.$jornada['Id_Jornada'].'/editar') }}" >Editar</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>