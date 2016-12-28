@section('script')
    @parent
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmhb8BVo311Mnvr35sv8VngIvXiiTnKQ4" defer></script>
    <script src="{{ asset('public/Js/localidades/formulario.js') }}"></script>
@stop

<div class="content">
    <div id="main" class="row" data-url="{{ url('profesores') }}" data-url-localidades="{{ url('localidades') }}">
        @if ($status == 'success')
            <div id="alerta" class="col-xs-12">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Datos actualizados satisfactoriamente.
                </div>                                
            </div>
        @endif
        @if (!empty($errors->all()))
            <div class="col-xs-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Solucione los siguientes inconvenientes y vuelva a intentarlo</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="col-md-12">
            Localidad {{ $localidad['Localidad'] }}
        </div>
        <div class="col-md-12"><br></div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Punto</label>
                        <select name="id_punto" id="id_punto" class="form-control" data-value="{{ $punto ? $punto['Id_Punto'] : '' }}" data-localidad="{{ $localidad['Id_Localidad'] }}">
                            <option value="">Selecciona un punto</option>
                            @foreach($localidad->puntos as $p)
                                <option value="{{ $p->Id_Punto }}">{{ $p->toString() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <form action="{{ url('localidades/personal/agregar') }}" method="post">
                            <input type="hidden" name="id_localidad" value="{{ $localidad['Id_Localidad'] }}">
                            <input type="hidden" name="id_punto" value="{{ $punto ? $punto['Id_Punto'] : '' }}">
                            <div class="col-md-12">
                                <label for="">Gestores</label>
                                <div class="input-group">
                                    <input name="id_persona" type="hidden" value="">
                                    <input name="tipo" type="hidden" value="Gestor">
                                    <input name="Gestor" type="text" class="form-control" placeholder="Documento" {{ $punto ? '' : 'disabled' }}>
                                    <span class="input-group-btn">
                                        <input type="hidden" name="_method" value="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn btn-default" type="submit" data-role="agregar" data-rel="Gestor"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th width="15px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($punto)
                                            @foreach ($punto->recreopersonas->filter(function($item){ return $item->pivot['tipo'] == 'Gestor'; })->all() as $gestor)
                                                <tr>
                                                    <td>{{ $gestor->persona['Primer_Apellido'].' '.$gestor->persona['Primer_Nombre'] }}</td>
                                                    <td><a class="btn btn-default btn-xs" href="{{ url('/localidades/'.$localidad->Id_Localidad.'/punto/'.$punto['Id_Punto'].'/personal/'.$gestor['Id_Recreopersona'].'/remover') }}" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <form action="{{ url('localidades/personal/agregar') }}" method="post">
                            <input type="hidden" name="id_localidad" value="{{ $localidad['Id_Localidad'] }}">
                            <input type="hidden" name="id_punto" value="{{ $punto ? $punto['Id_Punto'] : '' }}">
                            <div class="col-md-12">
                                <label for="">Profesores</label>
                                <div class="input-group">
                                    <input name="id_persona" type="hidden" value="">
                                    <input name="tipo" type="hidden" value="Profesor">
                                    <input name="Profesor" type="text" class="form-control" placeholder="Documento" {{ $punto ? '' : 'disabled' }}>
                                    <span class="input-group-btn">
                                        <input type="hidden" name="_method" value="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn btn-default" type="submit" data-role="agregar" data-rel="Profesor"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th width="15px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($punto)
                                            @foreach ($punto->recreopersonas->filter(function($item){ return $item->pivot['tipo'] == 'Profesor'; })->all() as $profesor)
                                                <tr>
                                                    <td>{{ $profesor->persona['Primer_Apellido'].' '.$profesor->persona['Primer_Nombre'] }}</td>
                                                    <td><a class="btn btn-default btn-xs" href="{{ url('/localidades/'.$localidad->Id_Localidad.'/punto/'.$punto['Id_Punto'].'/personal/'.$profesor['Id_Recreopersona'].'/remover') }}" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="hidden" id="latitud" value="{{ $punto ? $punto['Latitud'] : 4.666575 }}">
                <input type="hidden" id="longitud" value="{{ $punto ? $punto['Longitud'] : -74.125786 }}">
                <label class="control-label" for="">Ubicación</label>
                <div id="map"></div>
            </div>
        </div>
        <div class="col-md-12">
            <hr>
        </div>
        <div class="vol-md-12">
            <div class="col-md-12">
                <a href="{{ url('localidades/administrar') }}" class="btn btn-default">Volver</a>
            </div>
        </div>
    </div>
</div>