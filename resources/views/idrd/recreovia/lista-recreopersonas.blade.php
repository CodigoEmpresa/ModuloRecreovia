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
        <div class="col-xs-12">
            <a class="btn btn-primary" href="{{ url('profesores/crear') }}">Crear</a>
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            Total de personas en recreovía: {{ count($elementos) }}
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
             <table class="default display no-wrap responsive table table-min table-striped" width="100%">
                <thead>
                    <tr>
                        <th>
                            Cod.
                        </th>
                        <th>
                            Persona
                        </th>
                        <th>
                            Identificación
                        </th>
                        <th>
                            Contrato
                        </th>
                        <th>
                            Correo
                        </th>
                        <th data-priority="2" class="no-sort" style="width: 35px;">
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($elementos as $persona)
                        <tr>
                            <td style="text-align: center;" width=60>
                                {{ $persona->recreopersona->getCode() }}
                            </td>
                            <td>
                                {{ $persona->toString() }}
                            </td>
                            <td>
                                {{ $persona['Cedula'] }}
                            </td>
                            <td>
                                {{ $persona->recreopersona['contrato'] }}
                            </td>
                            <td>
                                {{ $persona->recreopersona['correo'] }}
                            </td>
                            <td>
                                <div class="pull-right btn-group">
                                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="" data-role="editar" href="{{ url('profesores/'.$persona['Id_Persona'].'/editar') }}" >Editar</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </ul>
        </div>
    </div>
</div>