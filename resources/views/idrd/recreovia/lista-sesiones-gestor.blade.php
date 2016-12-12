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
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <ul class="list-group" id="principal">
                @foreach($elementos as $sesion)
                     <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            Sesión {{ $sesion->Objetivo_General }}
                            <a data-role="editar" href="{{ url('/gestor/sesion/'.$sesion['Id'].'/editar') }}" class="pull-right btn btn-primary btn-xs">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                        </h5>
                        <p class="list-group-item-text">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <small>
                                            	Fecha: {{ $sesion->Fecha }} / Horario: {{ $sesion->Inicio.' a '.$sesion->Fin }} <br>
                                            	Objetivos especificos: {{ $sesion->Objetivos_Especificos }} <br>
                                            	Recursos: {{ $sesion->Recursos }} <br>
                                            	Metodologia a aplicar: {{ $sesion->Metodologia_Aplicar }} <br>
                                            	Fase Inicial: {{ $sesion->Fase_Inicial }} <br>
                                            	Fase Central: {{ $sesion->Fase_Central }} <br>
                                            	Fase Final: {{ $sesion->Fase_Final }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                        <span class="label label-default">{{ $sesion->cronograma->punto->toString() }}</span> 
                        <span class="label label-default">{{ $sesion->Estado }}</span> 
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="paginador" class="col-xs-12">{!! $elementos->render() !!}</div>
    </div>
</div>