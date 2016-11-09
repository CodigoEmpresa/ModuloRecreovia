@section('script')
    @parent

    <script src="{{ asset('public/Js/puntos/search.js') }}"></script>
    <script src="{{ asset('public/Js/puntos/util.js') }}"></script>
@stop
    
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
                <input name="buscador" type="text" class="form-control" placeholder="Buscar" id="buscador">
                <span class="input-group-btn">
                    <button id="buscar" data-role="buscar" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </span>
            </div>
        </div>
        <div class="col-xs-12">
            <button class="btn btn-primary" id="crear">Crear</button>
        </div>
        <div class="col-xs-12"><br></div>
        <div class="col-xs-12">
            <ul class="list-group" id="principal">
                @foreach($elementos as $punto)
                    <li class="list-group-item">
                        <h5 class="list-group-item-heading">
                            {{ strtoupper($punto['Escenario']) }}
                            <a data-role="editar" data-rel="{{ $punto['Id_Punto'] }}" class="pull-right btn btn-primary btn-xs">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                        </h5>
                        <p class="list-group-item-text">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <small>Dirección: {{ $punto['Direccion'] }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                        <span class="label label-default">{{ $punto->localidad['Localidad'] }}</span> 
                        <span class="label label-default">{{ $punto->upz['Upz'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="paginador" class="col-xs-12">{!! $elementos->render() !!}</div>    
        <!-- Modal formulario  principal -->
        <div class="modal fade" id="modal-principal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <form action="" id="form-principal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Crear o editar punto</h4>
                        </div>
                        <div class="modal-body">
                            <fieldset>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="Direccion">Direccion</label>
                                        <input type="text" name="Direccion" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="Escenario">Escenario</label>
                                        <input type="text" name="Escenario" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="Cod_IDRD">Cod. IDRD</label>
                                        <input type="text" name="Cod_IDRD" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="Cod_Recreovia">Cod. Recreovia</label>
                                        <input type="text" name="Cod_Recreovia" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12"><hr></div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="Id_Localidad">Localidad </label>
                                        <select name="Id_Localidad" id="" class="form-control">
                                            <option value="">Seleccionar</option>
                                            @foreach($localidades as $localidad)
                                                <option value="{{ $localidad['Id_Localidad'] }}">{{ $localidad['Localidad'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="Id_Upz">Upz</label>
                                        <select name="Id_Upz" id="" class="form-control" data-json="{{ $upz }}">
                                            <option value="">Seleccionar</option>
                                            @foreach($upz as $u)
                                                <option data-localidad="{{ $u['IdLocalidad'] }}" value="{{ $u['Id_Upz'] }}">{{ $u['Upz'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <span class="text text-primary">Jornadas</span>
                                    <button class="pull-right btn btn-xs btn-default" id="agregar-jornada">Agregar <span class="glyphicon glyphicon-plus"></span></button>
                                </div>
                                <div class="col-xs-12" id="form-jornadas" style="display:none;">
                                    <div class="row" style="background-color: #eee; margin-top: 10px; padding-top: 10px; margin-bottom: 10px; padding-bottom: 10px;">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="control-label">Dias</label> <br>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="dia1" name="dias[]" value="lunes"> Lunes&nbsp;&nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="dia2" name="dias[]" value="martes"> Martes&nbsp;&nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="dia3" name="dias[]" value="miercoles"> Miercoles
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="dia4" name="dias[]" value="jueves"> Jueves
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="dia5" name="dias[]" value="viernes"> Viernes
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="dia6" name="dias[]" value="sabado"> Sabado
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="dia7" name="dias[]" value="domingo"> Domingo
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="control-label">Jornada</label> <br>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jornada1" name="jornada" value="dia"> Dia
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jornada2" name="jornada" value="noche"> Noche
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jornada3" name="jornada" value="fds"> FDS
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-6">
                                            <div class="form-group">
                                                <label for="">Hora inicio</label>
                                                <input type="text" class="form-control input-sm" name="inicio" data-role="clockpicker" placeholder="Hora inicio">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-6">
                                            <div class="form-group">
                                                <label for="">Hora fin</label>
                                                <input type="text" class="form-control input-sm" name="fin" data-role="clockpicker" placeholder="Hora fin">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="hidden" name="Id_Zona" value="1">
                                            <input type="hidden" name="Id_Jornada" value="">
                                            <input type="button" value="Guardar" id="guardar-jornada" class="btn btn-xs btn-primary">
                                            <input style="display:none;" type="button" id="eliminar-jornada" value="Eliminar" class="btn btn-xs btn-danger">
                                            <input type="button" id="cancelar-jornada" value="Cancelar" class="btn btn-xs btn-default">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <table id="table-jornadas" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Dias</th>
                                                <th>Jornada</th>
                                                <th width="15px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="Id_Punto" value="0">
                            <input type="hidden" name="jornadas" value="">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button id="guardar" type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>