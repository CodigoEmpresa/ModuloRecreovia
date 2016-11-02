<div id="main_list" class="row" data-url="{{ url('contratos') }}">
	<div class="col-xs-12">
		<h4>Zonas</h4>
	</div>
	<div id="alerta" class="col-xs-12" style="display:none;">
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Datos actualizados satisfactoriamente.
		</div>
	</div>
	<div class="col-xs-12">
    	<hr>
	</div>
	<div class="col-xs-12">
		<br>
    </div>
	<div class="col-xs-12">
		@if(count($elementos) == 0)
			No se ha registrado ninguna zona hasta el momento.
		@endif
		<div class="row">
			@foreach($elementos as $zona)
				<div class="col-md-4 col-xs-12">
					<div class="zona">
						<h5>{{ $zona['Nombre'] }}</h5>
						<p class="small">
							Total puntos: {{ count($zona->puntos) }}<br>
							Total profesores: {{ count($zona->personas) }}<br>
						</p>
						<a href="" class="btn btn-xs btn-default">Puntos</a>
						<a href="" class="btn btn-xs btn-default">Profesores</a>
					</div>  
				</div>
			@endforeach
		</div>
	</div>
	<div id="paginador" class="col-xs-12">
		{!! $elementos->render() !!}
	</div>
</div>