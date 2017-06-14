@extends('master-web')

@section('script')
    @parent

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmhb8BVo311Mnvr35sv8VngIvXiiTnKQ4" defer></script>
    <script src="{{ asset('public/Js/buscador/formulario.js') }}"></script>
@stop

@section('content')
    <div class="container-fluid">
        <div id="buscador">
            <form id="form-buscador" action="{{ url('buscador') }}" method="POST">
                <div class="col-md-12">
                    <h3>
                        <img src="{{ asset('public/Img/RECREOVIA.jpg') }}" height="30px;" class="pull-left"/>
                        <img src="{{ asset('public/Img/IDRD.JPG') }}" height="30px;" class="pull-right"/><br><br><small>Buscador de actividades</small>
                    </h3>
                    <br>
                    <div class="row">
                        <div class="col-xs-6 form-group">
                            <label for="Id_Localidad">Localidad</label>
                            <select name="Id_Localidad" id="Id_Localidad" class="form-control" title="Seleccionar">
                                <option value="">Todos</option>
                                @foreach($localidades as $localidad)
                                    <option value="{{ $localidad['Id_Localidad'] }}">{{ $localidad['Localidad'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-6 form-group">
                            <label for="localidad">Fecha</label>
                            <input type="text" name="Fecha" class="form-control" data-role="datepicker" placeholder="Fecha">
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span id="punto" style="position:absolute;"></span>
                            <table class="table table-min sesiones">
                                <thead>
                                <tr>
                                    <th style="width:30px !important;" width="30px;">#</th>
                                    <th>Tipo</th>
                                    <th class="none">Profesor</th>
                                    <th class="none">Objetivo</th>
                                    <th style="width:100px !important;" width="100px;">Hora</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="mapa-buscador" class="map"></div>
    </div>
@stop