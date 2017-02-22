@section('script')
    @parent

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmhb8BVo311Mnvr35sv8VngIvXiiTnKQ4" defer></script>
    <script src="{{ asset('public/Js/sesiones/formulario.js') }}"></script>
@stop

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
		<div class="col-md-12">
			<ul class="nav nav-tabs">
				<li role="presentation" class="{{ $area != 'Asistencia' ? 'active' : '' }}"><a href="#Detalles" data-toggle="tab" aria-expanded="false">Detalles</a></li>
				@if($sesion && in_array($sesion['Estado'], ['Aprobado', 'Finalizado']))
					<li role="presentation" class="{{ $area == 'Asistencia' ? 'active' : '' }}"><a href="#Asistencia" data-toggle="tab" aria-expanded="false">Asistencia</a></li>
				@endif
			</ul>
			<div id="myTabContent" class="tab-content">
	  			<div class="tab-pane fade {{ $area != 'Asistencia' ? 'active in' : '' }}" id="Detalles">
					<div class="row">
						<div class="col-xs-12"><br></div>
						<div class="col-xs-12 col-md-12">
							<div class="row">
								<form action="{{ url('/sesiones/procesar') }}" method="post">
									<fieldset>
										<div class="col-md-12">
											<label for="">Estado</label>
											<?php
				                                switch ($sesion->Estado)
				                                {
				                                    case 'Pendiente':
				                                        $class = 'default';
				                                    break;
				                                    case 'Diligenciado':
				                                        $class = 'warning';
				                                    break;
				                                    case 'Aprobado':
				                                        $class = 'success';
				                                    break;
				                                    case 'Finalizado':
				                                        $class = 'info';
				                                    break;
				                                    case 'Rechazado':
				                                    case 'Corregir':
				                                        $class = 'danger';
				                                    break;
				                                    default:
				                                        $class= 'default';
				                                    break;
				                                }
				                            ?>
											<p class="form-control-static text-{{ $class }}">
												{{ $sesion->Estado }}
											</p>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-12">
													<div class="row">
														<div class="col-md-12 form-group">
															<label for="">Programación</label>
															<p class="form-control-static">{{ $sesion->cronograma->toString() }}</p>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-12 form-group">
															<label for="">Punto</label>
															<p class="form-control-static">{{ $sesion->cronograma->punto->toString() }}</p>
														</div>
														<div class="col-md-12 form-group">
															<label for="">Jornada</label>
															<p class="form-control-static">{{ $sesion->cronograma->jornada->toString() }}
																<?php
																	$html = '';

																	if ($sesion->cronograma->jornada->Contacto_Nombre || $sesion->cronograma->jornada->Contacto_Telefono || $sesion->cronograma->jornada->Contacto_Correo)
																	{
														                $html = '<br><br><small class="text-muted">';
														                $html .= $sesion->cronograma->jornada->Contacto_Nombre ? 'Contacto: '.$sesion->cronograma->jornada->Contacto_Nombre.'<br>' : '';
														                $html .= $sesion->cronograma->jornada->Contacto_Telefono ? 'Telefono: '.$sesion->cronograma->jornada->Contacto_Telefono.'<br>' : '';
														                $html .= $sesion->cronograma->jornada->Contacto_Correo ? 'Correo: '.$sesion->cronograma->jornada->Contacto_Correo.'<br>' : '';
														                $html .= '</small>';
														            }

														            echo $html;
																?>
															</p>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div id="map" style="height:145px;"></div>
												</div>
											</div>
										</div>
										<div class="col-md-3 form-group">
											<label for="">Fecha</label>
											<p class="form-control-static">{{ $sesion['Fecha'] }}</p>
										</div>
										<div class="col-md-3 form-group">
											<label for="">Inicio</label>
											<p class="form-control-static">{{ $sesion['Inicio'] }}</p>
										</div>
										<div class="col-md-3 form-group">
											<label for="">Fin</label>
											<p class="form-control-static">{{ $sesion['Fin'] }}</p>
										</div>
										<div class="col-md-3 form-group">
											<label for="">Profesor</label>
											<p class="form-control-static">
												@if($sesion->profesor) 
	                            					{{ $sesion->profesor->persona->toString() }} 
	                            				@else 
	                            					Sin profesor asignado
	                            				@endif
											</p>
										</div>
										<div class="col-md-6 form-group">
											<label for="">Sesión</label>
											<select name="Objetivo_General" id="Objetivo_General" class="form-control" data-value="{{ $sesion ? $sesion['Objetivo_General'] : old('Objetivo_General') }}" {{ $tipo == 'profesor' ? 'disabled' : '' }}>
												<option value="">Seleccionar</option>
												<option value="Gimnasia de Mantenimiento (GM)">Gimnasia de Mantenimiento (GM)</option>
												<option value="Estimulación Muscular (EM)">Estimulación Muscular (EM)</option>
												<option value="Movilidad Articular (MA)">Movilidad Articular (MA)</option>
												<option value="Rumba Tropical Folclorica (RTF)">Rumba Tropical Folclorica (RTF)</option>
												<option value="Actividad Rítmica para Niños (ARN) Rumba para Niños.">Actividad Rítmica para Niños (ARN) Rumba para Niños.</option>
												<option value="Gimnasia Aeróbica Musicalizada (GAM)">Gimnasia Aeróbica Musicalizada (GAM)</option>
												<option value="Artes Marciales Musicalizadas (AMM)">Artes Marciales Musicalizadas (AMM)</option>
												<option value="Gimnasia Psicofísica (GPF)">Gimnasia Psicofísica (GPF)</option>
												<option value="Pilates (Pil)">Pilates (Pil)</option>
												<option value="Taller de Danzas (TD)">Taller de Danzas (TD)</option>
												<option value="Gimnasio Saludable al Aire Libre (GSAL)">Gimnasio Saludable al Aire Libre (GSAL)</option>
											</select>
										</div>
										<div class="col-md-12 form-group">
											<label for="detalle_objetivo_general">Objetivo general</label>
											<p id="detalle_objetivo_general" class="form-control-static"></p>
										</div>
										<div class="col-md-6 form-group">
											<label for="">Objetivo específico 1</label>
											<textarea class="form-control" name="Objetivos_Especificos">{{ $sesion ? $sesion['Objetivos_Especificos'] : old('Objetivos_Especificos') }}</textarea>
										</div>
										<div class="col-md-6 form-group">
											<label for="">Objetivo específico 2</label>
											<textarea class="form-control" name="Objetivos_Especificos_1">{{ $sesion ? $sesion['Objetivos_Especificos_1'] : old('Objetivos_Especificos_1') }}</textarea>
										</div>
										<div class="col-md-6 form-group">
											<label for="">Objetivo específico 3</label>
											<textarea class="form-control" name="Objetivos_Especificos_2">{{ $sesion ? $sesion['Objetivos_Especificos_2'] : old('Objetivos_Especificos_2') }}</textarea>
										</div>
										<div class="col-md-6 form-group">
											<label for="">Objetivo específico 4</label>
											<textarea class="form-control" name="Objetivos_Especificos_3">{{ $sesion ? $sesion['Objetivos_Especificos_3'] : old('Objetivos_Especificos_3') }}</textarea>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-6 form-group">
													<label for="">Metodología a aplicar</label>
													<textarea class="form-control" name="Metodologia_Aplicar">{{ $sesion ? $sesion['Metodologia_Aplicar'] : old('Metodologia_Aplicar') }}</textarea>
												</div>
												<div class="col-md-6 form-group">
													<label for="">Recursos</label>
													<textarea class="form-control" name="Recursos">{{ $sesion ? $sesion['Recursos'] : old('Recursos') }}</textarea>
												</div>
											</div>
										</div>
										<div class="col-md-4 form-group next">
											<label for="">Fase inicial</label>
											<textarea class="form-control x2" name="Fase_Inicial">{{ $sesion ? $sesion['Fase_Inicial'] : old('Fase_Inicial') }}</textarea>
										</div>
										<div class="col-md-4 form-group next">
											<label for="">Fase central</label>
											<textarea class="form-control x2" name="Fase_Central">{{ $sesion ? $sesion['Fase_Central'] : old('Fase_Central') }}</textarea>
										</div>
										<div class="col-md-4 form-group">
											<label for="">Fase final</label>
											<textarea class="form-control x2" name="Fase_Final">{{ $sesion ? $sesion['Fase_Final'] : old('Fase_Final') }}</textarea>
										</div>
										<div class="col-md-12 form-group">
											<label for="">Observaciones</label>
											<textarea class="form-control" name="Observaciones">{{ $sesion ? $sesion['Observaciones'] : old('Observaciones') }}</textarea>
										</div>
				                        @if ($tipo == "gestor")
											<div class="col-md-12 form-group">
												<label for="">Estado</label><br>
					                            <label class="radio-inline">
					                                <input type="radio" name="Estado" id="estado3" value="Aprobado" {{ ($sesion && $sesion['Estado'] == 'Aprobado') || old('Estado') == 'Aprobado' ? 'checked' : '' }}> Aprobado
					                            </label>
					                            <label class="radio-inline">
					                                <input type="radio" name="Estado" id="estado5" value="Finalizado" {{ ($sesion && $sesion['Estado'] == 'Finalizado') || old('Estado') == 'Finalizado' ? 'checked' : '' }}> Finalizado
					                            </label>
					                            <label class="radio-inline">
					                                <input type="radio" name="Estado" id="estado4" value="Rechazado" {{ ($sesion && $sesion['Estado'] == 'Rechazado') || old('Estado') == 'Rechazado' ? 'checked' : '' }}> Rechazado
					                            </label>
					                            <label class="radio-inline">
					                                <input type="radio" name="Estado" id="estado4" value="Corregir" {{ ($sesion && $sesion['Estado'] == 'Corregir') || old('Estado') == 'Corregir' ? 'checked' : '' }}> Corregir
					                            </label>
											</div>
				                        @endif
										<div class="col-md-12">
				                            <input type="hidden" name="_method" value="POST">
				                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="Id" value="{{ $sesion ? $sesion['Id'] : 0 }}">
											<input type="hidden" name="origen" value="{{ $tipo }}">
											<input type="hidden" name="area" value="Detalles">
							                <input type="hidden" id="latitud" value="{{ $sesion->cronograma->punto ? $sesion->cronograma->punto['Latitud'] : 4.666575 }}">
							                <input type="hidden" id="longitud" value="{{ $sesion->cronograma->punto ? $sesion->cronograma->punto['Longitud'] : -74.125786 }}">
											@if($sesion && $sesion['Estado'] != 'Finalizado')
												<div class="row">
													<div class="col-md-12"><hr></div>
													<div class="col-md-12">
														@if($tipo == "profesor")
															<input type="submit" class="btn btn-primary" value="Guardar" {{ $sesion && $sesion['Estado'] == 'Aprobado' ? 'disabled' : '' }}>
							                            	<a href="{{ url('/profesores/sesiones') }}" class="btn btn-default">Volver</a>
							                            @else
															<input type="submit" class="btn btn-primary" value="Guardar">
							                            	<a href="{{ url('/gestores/sesiones') }}" class="btn btn-default">Volver</a>
							                            @endif
						                            </div>
						                        </div>
						                    @else
						                    	<div class="row">
													<div class="col-md-12"><hr></div>
													<div class="col-md-12">
														@if($tipo == "profesor")
															<a href="{{ url('/profesores/sesiones') }}" class="btn btn-default">Volver</a>
							                            @else
							                            	<a href="{{ url('/gestores/sesiones') }}" class="btn btn-default">Volver</a>
							                            @endif
						                            </div>
						                        </div>
					                        @endif
										</div>
									</fieldset>
								</form>
							</div>
						</div>
					</div>		
				</div>
				@if($sesion && in_array($sesion['Estado'], ['Aprobado', 'Finalizado']))
					<div class="tab-pane fade {{ $area == 'Asistencia' ? 'active in' : '' }}" id="Asistencia">
						<div class="row">
							<div class="col-xs-12"><br></div>
							<div class="col-xs-12">
								<div class="row">
									<form action="{{ url('/asistencia/procesar') }}" method="post">
										<fieldset>
											<div class="col-md-6 form-group">
												<label for="">Participantes</label>
												<table id="participantes" class="table table-striped" width="100%">
													<thead>
														<tr>
															<th>Grupo</th>
															<th width="57px" style="text-align:center;">M</th>
															<th width="57px" style="text-align:center;">F</th>
														</tr>
													</thead>
													<tbody>
														@foreach($gruposPoblacionales as $grupo)
															<?php
																$participante_m = $sesion->gruposPoblacionales()->where('Id_Grupo', $grupo['Id'])
																									->where('Genero', 'M')
																									->where('Grupo_Asistencia', 'Participantes')
																									->first();

																$participante_f = $sesion->gruposPoblacionales()->where('Id_Grupo', $grupo['Id'])
																									->where('Genero', 'F')
																									->where('Grupo_Asistencia', 'Participantes')
																									->first();

															?>
															<tr>
																<td>
																	{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] < 0 ? ' - mas' : ' a '.$grupo['Edad_Fin']) }}<br><small class="text-mutted">{{ $grupo['Grupo'] }}</small>
																</td>
																<td class="input">
																	<input type="text" name="participantes-m-{{ $grupo['Id'] }}" value="{{ $participante_m ? $participante_m->pivot['Cantidad'] : 0 }}" data-number>
																</td>
																<td class="input">
																	<input type="text" name="participantes-f-{{ $grupo['Id'] }}" value="{{ $participante_f ? $participante_f->pivot['Cantidad'] : 0 }}" data-number>
																</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
											<div class="col-md-6">
												<label for="">Asistentes</label>
												<table id="asistentes" class="table table-striped" width="100%">
													<thead>
														<tr>
															<th>Grupo</th>
															<th width="57px" style="text-align:center;">M</th>
															<th width="57px" style="text-align:center;">F</th>
														</tr>
													</thead>
													<tbody>
														@foreach($gruposPoblacionales as $grupo)
															<?php
																$asistente_m = $sesion->gruposPoblacionales()->where('Id_Grupo', $grupo['Id'])
																									->where('Genero', 'M')
																									->where('Grupo_Asistencia', 'Asistentes')
																									->first();

																$asistente_f = $sesion->gruposPoblacionales()->where('Id_Grupo', $grupo['Id'])
																									->where('Genero', 'F')
																									->where('Grupo_Asistencia', 'Asistentes')
																									->first();

															?>
															<tr>
																<td>
																	{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] < 0 ? ' - mas' : ' a '.$grupo['Edad_Fin']) }}<br><small class="text-mutted">{{ $grupo['Grupo'] }}</small>
																</td>
																<td class="input">
																	<input type="text" name="asistentes-m-{{ $grupo['Id'] }}" value="{{ $asistente_m ? $asistente_m->pivot['Cantidad'] : 0 }}" data-number>
																</td>
																<td class="input">
																	<input type="text" name="asistentes-f-{{ $grupo['Id'] }}" value="{{ $asistente_f ? $asistente_f->pivot['Cantidad'] : 0 }}" data-number>
																</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
											<div class="col-md-12">
					                            <input type="hidden" name="_method" value="POST">
					                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="Id" value="{{ $sesion ? $sesion['Id'] : 0 }}">
												<input type="hidden" name="origen" value="{{ $tipo }}">
												<input type="hidden" name="area" value="Asistencia">
												@if($sesion && $sesion['Estado'] != 'Finalizado')
													<div class="row">
														<div class="col-md-12"><hr></div>
														<div class="col-xs-12"><input type="submit" class="btn btn-primary" value="Registrar asistencia"></div>
													</div>
												@endif
											</div>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>