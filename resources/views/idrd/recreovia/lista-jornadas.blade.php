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
            <ul class="list-group" id="principal">
            	<?php
            		$i = 0;
            	?>
                @foreach($elementos as $jornada)
                	<?php
				        $i++;
                	?>
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            Jornada {{ $i }}
                            <a data-role="editar" href="{{ url('jornadas/editar/'.$jornada['Id_Jornada']) }}" class="pull-right btn btn-primary btn-xs">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                        </h5>
                        <p class="list-group-item-text">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <small>{{ $jornada->toString() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="paginador" class="col-xs-12">{!! $elementos->render() !!}</div>
    </div>
</div>