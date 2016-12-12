@section('script')
    @parent

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmhb8BVo311Mnvr35sv8VngIvXiiTnKQ4" defer></script>
    <script src="{{ asset('public/Js/puntos/formulario.js') }}"></script>
@stop

<div class="content">
    <div id="main" class="row" data-url="{{ url('personas') }}" data-url-parques="{{ url('parques') }}">
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
        <form action="{{ url('puntos/procesar') }}" method="post" id="form">
	        <div class="col-xs-12 col-md-6">
	            <div class="row">
					<fieldset>
                        <div class="col-xs-12">
                            <div class="form-group {{ $errors->has('Direccion') ? 'has-error' : '' }}">
                                <label class="control-label" for="Direccion">Dirección</label>
                                <input type="text" name="Direccion" class="form-control" value="{{ $punto ? $punto['Direccion'] : old('Direccion') }}">
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group {{ $errors->has('Escenario') ? 'has-error' : '' }}">
                                <label class="control-label" for="Escenario">Escenario</label>
                                <input type="text" name="Escenario" class="form-control" value="{{ $punto ? $punto['Escenario'] : old('Escenario') }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group {{ $errors->has('Cod_IDRD') ? 'has-error' : '' }}">
                                <label class="control-label" for="Cod_IDRD">Cod. IDRD</label>
                                <input type="text" name="Cod_IDRD" class="form-control" value="{{ $punto ? $punto['Cod_IDRD'] : old('Cod_IDRD') }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group {{ $errors->has('Cod_Recreovia') ? 'has-error' : '' }}">
                                <label class="control-label" for="Cod_Recreovia">Cod. Recreovia</label>
                                <input type="text" name="Cod_Recreovia" class="form-control" value="{{ $punto ? $punto['Cod_Recreovia'] : old('Cod_Recreovia') }}">
                            </div>
                        </div>
                        <div class="col-xs-12"><hr></div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group {{ $errors->has('Id_Localidad') ? 'has-error' : '' }}">
                                <label class="control-label" for="Id_Localidad">Localidad </label>
                                <select name="Id_Localidad" id="" class="form-control" data-value="{{ $punto ? $punto['Id_Localidad'] : old('Id_Localidad') }}">
                                    <option value="">Seleccionar</option>
                                    @foreach($localidades as $localidad)
                                        <option value="{{ $localidad['Id_Localidad'] }}">{{ $localidad['Id_Localidad'].' - '.$localidad['Localidad'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group {{ $errors->has('Id_Upz') ? 'has-error' : '' }}">
                                <label class="control-label" for="Id_Upz">Upz</label>
                                <select name="Id_Upz" id="" class="form-control" data-json="{{ $upz }}" data-value="{{ $punto ? $punto['Id_Upz'] : old('Id_Upz') }}">
                                    <option value="">Seleccionar</option>
                                    @foreach($upz as $u)
                                        <option data-localidad="{{ $u['IdLocalidad'] }}" value="{{ $u['Id_Upz'] }}">{{ $u['cod_upz'].' - '.$u['Upz'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </fieldset>
	            </div>
	        </div>
	        <div class="col-xs-12 col-md-6">
	        	<div class="form-group {{ $errors->has('Latitud') || $errors->has('Longitud') ? 'has-error' : '' }}">
	        		<label class="control-label" for="">Ubicación</label>
	        		<div id="map"></div>
	        	</div>
	        </div>
	        <div class="col-xs-12 col-md-12">
	        	<div class="row">
                    <div class="col-xs-12 form-group">
	                    <label for="">Jornadas </label>
                        @if($jornadas)
                            <div class="input-group">
                                <select name="select-jornadas" id="select-jornadas" class="form-control">
                                    @foreach($jornadas as $jornada)
                                        <option value="{{ $jornada['Id_Jornada'] }}">{{ $jornada->toString() }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
    	                           <button class="btn btn-default" id="agregar-jornada" type="button"><span class="glyphicon glyphicon-plus"></span></button>
                                </span>
                            </div>
                        @else
                            <p class="form-control-static">
                                Aun no has creado ninguna jornada para crear una has click <a href="{{ url('jornadas') }}">aquí</a>
                            </p>
                        @endif
	                </div>
	                <div class="col-xs-12">
	                    <table id="table-jornadas" class="table table-striped">
	                        <thead>
	                            <tr>
	                                <th width="50px">N°</th>
                                    <th>Jornada</th>
	                                <th width="15px"></th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            
	                        </tbody>
	                    </table>
	                </div>
                    <div class="col-xs-12">
                        <hr>
                    </div>
	                <div class="col-xs-12">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="Latitud" value="{{ $punto ? $punto['Latitud'] : old('Latitud') }}"> 
                        <input type="hidden" name="Longitud" value="{{ $punto ? $punto['Longitud'] : old('Longitud') }}"> 
                        <input type="hidden" name="Id_Punto" value="{{ $punto ? $punto['Id_Punto'] : 0 }}">
                        <input type="hidden" name="Jornadas" value="{{ $punto ? implode(',', $punto->jornadas()->lists('Jornadas.Id_Jornada')->all()).',' : old('Jornadas') }}"> 
                        <button id="guardar" type="submit" class="btn btn-primary">Guardar</button> 
                        @if ($punto)
                            <a data-toggle="modal" data-target="#modal-eliminar" class="btn btn-danger">Eliminar</a>
                        @endif
                        <a href="{{ url('puntos') }}" class="btn btn-default">Volver</a> 
	                </div>
                </div>
	        </div>
        </form>
    </div>
</div>
@if ($punto)
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminar</h4>
            </div>
            <div class="modal-body">
                <p>Realmente desea eliminar este punto de recreovia.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ url('puntos/'.$punto['Id_Punto'].'/eliminar/') }}" class="btn btn-danger">Eliminar</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endif