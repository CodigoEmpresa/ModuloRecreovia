@section('script')
    @parent
    <script src="{{ asset('public/Js/cronogramas/buscador.js') }}"></script>
    <script>
        $(function(e)
        {
            $('#programacion').on('submit', function(e)
            {
                var code = $('input[name="codigo_destino"]').val();
                var numb = code.match(/\d/g);
                numb = numb.join('');
                $('input[name="codigo"]').val(+numb);
            });

            if($('input[name="cronogramas_origen"]').val() !== '')
            {
                $('input[name="cronogramas_origen"]').trigger('blur');
            }
        });
    </script>
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
                <div class="col-md-12">
                    <form action="{{ url('/programacion/agrupar') }}" id="programacion" method="post">
                        <div class="row">
                            <div class="col-md-9 form-group {{ $errors->has('cronogramas_origen') || $errors->has('cronogramas') ? 'has-error' : '' }}">
                                <label for="">Cronogramas</label>
                                <input type="text" name="cronogramas_origen" class="form-control buscador-cronogramas" value="{{ old('cronogramas_origen') }}" data-target="#table_cronogramas" data-input="#cronogramas" data-field="Id" placeholder="Códigos separados por ( , )">
                            </div>
                            <div class="col-md-3 form-group {{ $errors->has('codigo_destino') || $errors->has('codigo') ? 'has-error' : '' }}">
                                <label for="">Cronograma de destino</label>
                                <input type="text" name="codigo_destino" class="form-control" placeholder="Códigos" value="{{ old('codigo_destino') }}">
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="cronogramas" value="{{ old('cronogramas') }}" id="cronogramas">
                                <input type="hidden" name="codigo" value="{{ old('codigo') }}" id="codigo">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-primary" type="submit">Agrupar</button>
                            </div>
                            <div class="col-md-12">
                                <br>
                            </div>
                            <div class="col-md-12">
                                <table id="table_cronogramas" class="default display no-wrap responsive table table-min table-striped">
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>