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
        <div class="col-xs-12">
            <a href="{{ url('/programacion/crear/') }}" class="btn btn-primary" id="crear">Crear</a>
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <ul class="list-group" id="principal">
                @foreach($elementos as $cronograma)
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            Cronograma
                            <a data-role="editar" href="{{ url('/programacion/'.$cronograma['Id'].'/editar') }}" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                            <a data-role="sesiones" target="_blank" href="{{ url('/gestores/'.$cronograma['Id'].'/sesiones') }}" class="pull-right btn btn-default btn-xs separe-right" data-toggle="tooltip" data-placement="bottom" title="Sesiones">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            </a>
                        </h5>
                        <p class="list-group-item-text">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <small>
                                                {{ $cronograma->toString() }}.
                                                <br>
                                                Punto: {{ $cronograma->punto->toString() }}.
                                                <br>
                                                Jornada: {{ $cronograma->jornada->toString() }}.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                        <span class="label label-default">Total sesiones: {{ count($cronograma->sesiones) }}</span> 
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="paginador" class="col-xs-12">{!! $elementos->render() !!}</div>
    </div>
</div>