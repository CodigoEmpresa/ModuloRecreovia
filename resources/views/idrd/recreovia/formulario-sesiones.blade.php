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
                <form action="{{ url('programacion/gestores/sesion/procesar') }}" method="post">
                    <fieldset>
                        <div class="col-md-9 form-group">
                            <label for="">Sesión</label>
                            <input type="text" class="form-control" name="Objetivo_General">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Materiales</label>
                            <input type="text" class="form-control" name="Recursos">
                        </div>
                    	<div class="col-md-3 form-group">
                    		<label for="">Fecha</label>
                    		<input type="text" class="form-control" data-role="datepicker" name="Fecha">
                    	</div>
                    	<div class="col-md-3 form-group">
                    		<label for="">Inicio</label>
                    		<input type="text" class="form-control" data-role="clockpicker" data-rel="hora_inicio" name="Inicio">
                    	</div>
                    	<div class="col-md-3 form-group">
                    		<label for="">Fin</label>
                    		<input type="text" class="form-control" data-role="clockpicker" data-rel="hora_fin" name="Fin">
                    	</div>
                    	<div class="col-md-12">
                    		<hr>
                    	</div>
                    	<div class="col-md-12">
                    		<input type="hidden" name="Id" value="0">
                    		<input type="submit" class="btn btn-primary" value="Guardar">
                    	</div>
                    </fieldset>
                </form>
            </div> 
        </div>
    </div>
</div>
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminar</h4>
            </div>
            <div class="modal-body">
                <p>Realmente desea eliminar esta sesión.</p>
            </div>
            <div class="modal-footer">
                <a data-url="{{ url('programacion/gestores/sesion/eliminar/') }}" href="" class="btn btn-danger">Eliminar</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>