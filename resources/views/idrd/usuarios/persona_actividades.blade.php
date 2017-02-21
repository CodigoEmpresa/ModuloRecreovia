@extends('master')
@section('script')
	@parent
    <script src="{{ asset('public/Js/usuarios/persona_actividades.js') }}"></script>   
@stop
@section('content')         
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token"/>
    <div id="main_tipoPersona" class="row" data-url="{{ url(config('usuarios.prefijo_ruta')) }}" data-url-actividades="{{ url('actividadesModulo') }}" data-url-actividades-persona="{{ url('actividadesPersona') }}" data-url-proceso="{{ url('PersonasActividadesProceso') }}"></div>          
	<div class="content" id="TipoPersona" class="row" data-url="TipoPersona">
		<div class="row">
			<div class="col-xs-12">
				<h4>
					Asignar actividades
				</h4>
			</div>
			<form action="" id="form_persona_tipoPersona">
				<div class="col-xs-12">
	                <div tabindex="-1" id="mensaje-incorrectoB" class=" text-left alert alert-success alert-danger" role="alert" style="display: none; margin-top: 10px;">
	                    <strong>Error </strong> <span id="mensajeIncorrectoB"></span>
	                </div>
	                <div tabindex="-1" id="mensaje-correctoB" class=" text-left alert alert-success alert-success" role="alert" style="display: none; margin-top: 10px;">
	                    <strong>Bien </strong> <span id="mensajecorrectoB"></span>
	                </div>
				</div>
				<div class="col-xs-12">
					<label class="control-label" for="Id_TipoDocumento">* Persona: </label>
	                <div class="input-group">
	                    <input name="buscador" type="text" class="form-control" placeholder="Buscar" id="buscador" value="{{ $identificacion ? $identificacion : '' }}" onkeypress="return ValidaCampo(event);">
	                    <span class="input-group-btn">
	                        <button id="buscar" data-role="buscar" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
	                    </span>
	                </div>
	            </div>
	            <div class="col-xs-12">
	            	<br>
	            </div>
	        	<div class="col-xs-12" id="resultado" style="display: none;">
					<div class="list-group" id="personas">
                    </div>
	        	</div>
	        </form>
        </div>
	</div> 
@stop