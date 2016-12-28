@section('script')
    @parent

    <script src="{{ asset('public/Js/puntos/buscador.js') }}"></script>
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
        <div class="col-xs-12">
            <a href="{{ url('/jornadas/crear/') }}" class="btn btn-primary" id="crear">Crear</a>
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            Total de jornadas registradas: {{ count($elementos) }}
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <ul class="list-group" id="principal">
                @foreach($elementos as $jornada)
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            Jornada
                            <a data-role="editar" href="{{ url('jornadas/'.$jornada['Id_Jornada'].'/editar') }}" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                        </h5>
                        <p class="list-group-item-text">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <small>
                                                Descripción: {{ $jornada->toString() }}. <br>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                        <span class="label label-default">Disponible en {{ count($jornada->puntos) }} puntos</span> 
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="paginador" class="col-xs-12">{!! $elementos->render() !!}</div>
    </div>
</div>