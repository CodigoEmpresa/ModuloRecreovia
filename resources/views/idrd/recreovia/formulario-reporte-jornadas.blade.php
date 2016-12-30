@section('script')
    @parent

    <script src="{{ asset('public/Js/reporte/formulario.js') }}"></script>
@stop
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
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12 col-md-12">
            <div class="row">
                <form action="{{ url('jornadas/procesar') }}" method="post">
                    <fieldset>
                        <div class="col-md-4 form-group">
                            <label for="">Punto</label>
                            <select name="Id_Punto" id="Id_Punto" class="form-control">
                                <option value="">Seleccionar</option>
                                @foreach($puntos as $punto)
                                    <option value="{{ $punto['Id_Punto'] }}">{{ $punto->toString() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8 form-group">
                            <label for="">Periodo y jornada</label>
                            <select name="Id_Cronograma" id="Id_Cronograma" class="form-control" data-json="{{ $puntos }}">
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Fecha</label>
                            <input type="text" name="Fecha" class="form-control" data-role="datepicker" data-fecha-inicio="" data-fecha-fin="" data-dias="">
                        </div>
                        <div class="col-xs-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="Id_Jornada" value="{{ $informe ? $informe['Id'] : 0 }}">
                            <input type="submit" value="Generar reporte" id="generar" class="btn btn-primary">
                            @if ($informe)
                                <a data-toggle="modal" data-target="#modal-eliminar" class="btn btn-danger">Eliminar</a>
                            @endif
                            <a href="{{ url('informes/jornadas') }}" class="btn btn-default">Volver</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@if ($informe)
    <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body">
                    <p>Realmente desea eliminar esta jornada.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ url('jornadas/'.$informe['Id_Jornada'].'/eliminar') }}" class="btn btn-danger">Eliminar</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endif