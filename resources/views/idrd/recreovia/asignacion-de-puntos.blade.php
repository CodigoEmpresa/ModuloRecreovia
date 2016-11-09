<div class="content">
    <div id="main" class="row" data-url="{{ url('puntos') }}">
        <div id="alerta" class="col-xs-12" style="display:none;">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Datos actualizados satisfactoriamente.
            </div>                                
        </div>
        <div class="col-xs-12 form-group">
            <div class="input-group">
                <select name="localidades" id="localidades" class="form-control">
                    <option value="">Seleccionar una localidad</option>
                    @foreach ($localidades as $localidad)
                        <option value="{{ $localidad['Id_Localidad'] }}">{{ $localidad['Localidad'] }}</option>
                    @endforeach
                </select>
                <span class="input-group-btn">
                    <button id="buscar" data-role="buscar" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </span>
            </div>
        </div>
    </div>
</div>