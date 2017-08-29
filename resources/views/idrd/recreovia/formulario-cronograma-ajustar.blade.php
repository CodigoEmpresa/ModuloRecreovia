@section('script')
    @parent
    <script src="{{ asset('public/Js/cronogramas/buscador.js') }}"></script>
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
                <div class="col-md-12">
                    <h5>Agrupar cronogramas</h5>
                </div>
                <div class="col-md-12">
                    <br>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-9 form-group">
                                <label for="">Cronogramas</label>
                                <input type="text" name="cronogramas" class="form-control buscador-cronogramas" data-target="#cronogramas" placeholder="Códigos separados por ( , )">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="">Cronograma de destino</label>
                                <input type="text" name="cronogramas" class="form-control" placeholder="Códigos">
                            </div>
                            <div class="col-md-12">
                                <table id="cronogramas" class="default display no-wrap responsive table table-min table-striped">
                                    <thead>
                                        <tr>
                                            <th width="60px">Cod.</th>
                                            <th width="350px">Cronograma</th>
                                            <th>Jornada</th>
                                            <th width="60px">Sesiones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>