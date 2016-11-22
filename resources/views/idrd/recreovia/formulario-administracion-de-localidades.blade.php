@section('script')
    @parent
    
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
                <form action="{{ url('localidades/personal/agregar') }}" method="post">
                    <input type="hidden" name="id" value="{{ $localidad['Id_Localidad'] }}">
                    <div class="col-md-12">
                        <div class="input-group input-group-sm">
                            <input name="id_persona" type="hidden" value="">
                            <input name="tipo" type="hidden" value="Gestor">
                            <input name="Gestor" type="text" class="form-control" placeholder="Documento">
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
                                    <th>Gestores</th>
                                    <th width="15px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($localidad->recreopersonas()->where('tipo', 'Gestor')->get() as $gestor)
                                    <tr>
                                        <td>{{ $gestor->persona['Primer_Apellido'].' '.$gestor->persona['Primer_Nombre'] }}</td>
                                        <td><a href="{{ url('/localidades/'.$localidad->Id_Localidad.'/personal/'.$gestor['Id_Recreopersona'].'/remover') }}"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <form action="{{ url('localidades/personal/agregar') }}" method="post">
                    <input type="hidden" name="id" value="{{ $localidad['Id_Localidad'] }}">
                    <div class="col-md-12">
                        <div class="input-group input-group-sm">
                            <input name="id_persona" type="hidden" value="">
                            <input name="tipo" type="hidden" value="Profesor">
                            <input name="Profesor" type="text" class="form-control" placeholder="Documento">
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
                                    <th>Profesores</th>
                                    <th width="15px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($localidad->recreopersonas()->where('tipo', 'Profesor')->get() as $profesor)
                                    <tr>
                                        <td>{{ $profesor->persona['Primer_Apellido'].' '.$profesor->persona['Primer_Nombre'] }}</td>
                                        <td><a href="{{ url('/localidades/'.$localidad->Id_Localidad.'/personal/'.$profesor['Id_Recreopersona'].'/remover') }}"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>