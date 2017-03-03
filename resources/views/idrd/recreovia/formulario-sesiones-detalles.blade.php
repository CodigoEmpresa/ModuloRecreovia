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

		<?php
			$area_detalles = ($area == 'Asistencia' || old('area') == 'Asistencia') || ($area == 'Producto_No_Conforme' || old('area') == 'Producto_No_Conforme') || ($area == 'Calificacion_Del_Servicio' || old('area') == 'Calificacion_Del_Servicio');
		?>
		<div class="col-md-12">
			<ul class="nav nav-tabs">
				<li role="presentation" class="{{ !$area_detalles ? 'active in' : '' }}"><a href="#Detalles" data-toggle="tab" aria-expanded="false">Detalles</a></li>
				@if($sesion && in_array($sesion['Estado'], ['Aprobado', 'Finalizado']))
					<li role="presentation" class="{{ ($area == 'Asistencia' || old('area') == 'Asistencia') ? 'active' : '' }}"><a href="#Asistencia" data-toggle="tab" aria-expanded="false">Asistencia</a></li>
					<li role="presentation" class="{{ ($area == 'Producto_No_Conforme' || old('area') == 'Producto_No_Conforme') ? 'active' : '' }}"><a href="#Producto_No_Conforme" data-toggle="tab" aria-expanded="false">Producto no conforme</a></li>
					<li role="presentation" class="{{ ($area == 'Calificacion_Del_Servicio' || old('area') == 'Calificacion_Del_Servicio') ? 'active' : '' }}"><a href="#Calificacion_Del_Servicio" data-toggle="tab" aria-expanded="false">Calificación del servicio</a></li>
				@endif
			</ul>
			<div id="myTabContent" class="tab-content">
	  			<div class="tab-pane fade {{ !$area_detalles ? 'active in' : '' }}" id="Detalles">
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
															<p class="form-control-static">
																{{ $sesion->cronograma->punto->toString() }}
																<?php
																	$html = '';

																	if ($sesion->cronograma->punto->Contacto_Nombre || $sesion->cronograma->punto->Contacto_Telefono || $sesion->cronograma->punto->Contacto_Correo)
																	{
														                $html = '<br><br><small class="text-muted">';
														                $html .= $sesion->cronograma->punto->Contacto_Nombre ? 'Contacto: '.$sesion->cronograma->punto->Contacto_Nombre.'<br>' : '';
														                $html .= $sesion->cronograma->punto->Contacto_Telefono ? 'Telefono: '.$sesion->cronograma->punto->Contacto_Telefono.'<br>' : '';
														                $html .= $sesion->cronograma->punto->Contacto_Correo ? 'Correo: '.$sesion->cronograma->punto->Contacto_Correo.'<br>' : '';
														                $html .= '</small>';
														            }

														            echo $html;
																?>
															</p>
														</div>
														<div class="col-md-12 form-group">
															<label for="">Jornada</label>
															<p class="form-control-static">{{ $sesion->cronograma->jornada->toString() }}</p>
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
											<div class="row">
												<div class="col-md-12 form-group">
													<label for="">Fase inicial</label>
													<textarea class="form-control x2" name="Fase_Inicial">{{ $sesion ? $sesion['Fase_Inicial'] : old('Fase_Inicial') }}</textarea>
												</div>
												<div class="col-md-6 form-group">
													<label for="">Duración fase inicial</label>
													<input type="number" min="0" class="form-control" name="Tiempo_Inicial" value="{{ $sesion ? $sesion['Tiempo_Inicial'] : old('Tiempo_Inicial') }}">
												</div>
											</div>
										</div>
										<div class="col-md-4 next">
											<div class="row">
												<div class="col-md-12 form-group">
													<label for="">Fase central</label>
													<textarea class="form-control x2" name="Fase_Central">{{ $sesion ? $sesion['Fase_Central'] : old('Fase_Central') }}</textarea>
												</div>
												<div class="col-md-6 form-group">
													<label for="">Duración fase central</label>
													<input type="number" min="0" class="form-control" name="Tiempo_Central" value="{{ $sesion ? $sesion['Tiempo_Central'] : old('Tiempo_Central') }}">
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="row">
												<div class="col-md-12 form-group">
													<label for="">Fase final</label>
													<textarea class="form-control x2" name="Fase_Final">{{ $sesion ? $sesion['Fase_Final'] : old('Fase_Final') }}</textarea>
												</div>
												<div class="col-md-6 form-group">
													<label for="">Duración fase final</label>
													<input type="number" min="0" class="form-control" name="Tiempo_Final" value="{{ $sesion ? $sesion['Tiempo_Final'] : old('Tiempo_Final') }}">
												</div>
											</div>
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
					<?php
						$calificacion = $sesion->calificacionDelServicio;
					?>
					<div class="tab-pane fade {{ $area == 'Asistencia' || old('area') == 'Asistencia' ? 'active in' : '' }}" id="Asistencia">
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
					<div class="tab-pane fade {{ $area == 'Producto_No_Conforme' || old('area') == 'Producto_No_Conforme' ? 'active in' : '' }}" id="Producto_No_Conforme">
						<div class="row">
							<div class="col-xs-12"><br></div>
							<div class="col-xs-12">
								<div class="row">
									<form action="{{ url('/producto_no_conforme/procesar') }}" method="post">
										<fieldset>
											<div class="col-md-12 form-group {{ $errors->has('Requisito') ? 'has-error' : '' }}">
												<label for="">* Requisito</label>
												<select name="Requisito" id="Requisito" class="form-control" data-value="{{ old('Requisito') }}">
													<option value="">Seleccionar</option>
													<option value="1">1. Puntualidad</option>
													<option value="2">2. Personal competente para el desarrollo de la actividad</option>
													<option value="3">3. Contar con el Talento Humano mínimo requerido</option>
													<option value="4">4. Escenario adecuado</option>
													<option value="5">5. Contar con los parámetros del IDIGER</option>
													<option value="6">6. Cumplir con los niveles de competencia de Ruido</option>
													<option value="7">7. Cumplir con la Resolución 512 de 2003</option>
													<option value="8">8. Elementos de producción (sonido)</option>
													<option value="9">9. Planificación de la sesión</option>
													<option value="10">10. Presentación Personal del Talento Humano</option>
													<option value="11">11. Mantener actualizada la información sobre los Puntos de Recreovía en Planeación del IDRD</option>
													<option value="12">12. Accesorios (bicicletas estáticas, step)</option>
													<option value="13">13. Cumplir con el instructivo de selección y contratación</option>
												</select>
											</div>
											<div class="col-md-6 form-group {{ $errors->has('Descripcion_De_La_No_Conformidad') ? 'has-error' : '' }}">
												<label for="">* Descripción de la no conformidad</label>
												<textarea class="form-control" name="Descripcion_De_La_No_Conformidad" id="Descripcion_De_La_No_Conformidad">{{ old('Descripcion_De_La_No_Conformidad') }}</textarea>
											</div>
											<div class="col-md-6 form-group {{ $errors->has('Descripcion_De_La_Accion_Tomada') ? 'has-error' : '' }}">
												<label for="">* Descripción de la acción tomada</label>
												<textarea class="form-control" name="Descripcion_De_La_Accion_Tomada" id="Descripcion_De_La_Accion_Tomada">{{ old('Descripcion_De_La_Accion_Tomada') }}</textarea>
											</div>
											<div class="col-md-6 form-group {{ $errors->has('Tratamiento') ? 'has-error' : '' }}">
												<label for="">* Tratamiento</label>
												<textarea class="form-control" name="Tratamiento" id="Tratamiento">{{ old('Tratamiento') }}</textarea>
											</div>
											<div class="col-md-12">
												<table class="table table-min table-hover">
													<thead>
														<tr>
															<th style="width:30px;">Req.</th>
															<th style="width:30%;">Descripción de la no conformidad</th>
															<th style="width:30%;">Descripción de la no acción tomada</th>
															<th style="width:30%;">Tratamiento</th>
															<th style="width:30px;"></th>
														</tr>
													</thead>
													<tbody>
														@foreach($sesion->ProductosNoConformes as $productoNoConforme)
															<tr>
																<td>{{ $productoNoConforme['Requisito'] }}</td>
																<td>{{ $productoNoConforme['Descripcion_De_La_No_Conformidad'] }}</td>
																<td>{{ $productoNoConforme['Descripcion_De_La_Accion_Tomada'] }}</td>
																<td>{{ $productoNoConforme['Tratamiento'] }}</td>
																<td>
																	<a href="{{ url('/producto_no_conforme/'.$productoNoConforme['Id'].'/eliminar/'.$tipo) }}" class="pull-right btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Eliminar">
									                                	<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
									                            	</a>
																</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
											<div class="col-md-12">
												<small>
													 1. Puntualidad, 2. Personal competente para el desarrollo de la actividad, 3. Contar con el Talento Humano mínimo requerido, 4. Escenario adecuado, 5. Contar con los parámetros del IDIGER, 6. Cumplir con los niveles de competencia de Ruido, 7. Cumplir con la Resolución 512 de 2003, 8. Elementos de producción (sonido), 9. Planificación de la sesión, 10. Presentación Personal del Talento Humano, 11. Mantener actualizada la información sobre los Puntos de Recreovía en Planeación del IDRD, 12. Accesorios (bicicletas estáticas, step), 13. Cumplir con el instructivo de selección y contratación.
												</small>
											</div>
											<div class="col-md-12">
					                            <input type="hidden" name="_method" value="POST">
					                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="Id" value="{{ $sesion ? $sesion['Id'] : 0 }}">
												<input type="hidden" name="origen" value="{{ $tipo }}">
												<input type="hidden" name="area" value="Producto_No_Conforme">
												@if($sesion && $sesion['Estado'] != 'Finalizado')
													<div class="row">
														<div class="col-md-12"><hr></div>
														<div class="col-xs-12"><input type="submit" class="btn btn-primary" value="Registrar producto no conforme"></div>
													</div>
												@endif
											</div>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade {{ $area == 'Calificacion_Del_Servicio' || old('area') == 'Calificacion_Del_Servicio' ? 'active in' : '' }}" id="Calificacion_Del_Servicio">
						<div class="row">
							<div class="col-xs-12"><br></div>
							<div class="col-xs-12">
								<div class="row">
									<form action="{{ url('/calificacion_del_servicio/procesar') }}" method="post">
										<fieldset>
											<div class="col-md-3">
												<div class="row">
													<div class="col-md-12 form-group {{ $errors->has('Puntualidad_PAF') ? 'has-error' : '' }}">
														<label for="">1. Puntualidad PAF</label> <br>
														<label class="radio-inline">
															<input type="radio" name="Puntualidad_PAF" id="Puntualidad_PAF1" value="1" {{ ($calificacion && $calificacion['Puntualidad_PAF'] == '1' || old('Puntualidad_PAF') == '1' ? 'checked' : '') }}> 1
														</label>
														<label class="radio-inline">
															<input type="radio" name="Puntualidad_PAF" id="Puntualidad_PAF2" value="2" {{ ($calificacion && $calificacion['Puntualidad_PAF'] == '2' || old('Puntualidad_PAF') == '2' ? 'checked' : '') }}> 2
														</label>
														<label class="radio-inline">
															<input type="radio" name="Puntualidad_PAF" id="Puntualidad_PAF3" value="3" {{ ($calificacion && $calificacion['Puntualidad_PAF'] == '3' || old('Puntualidad_PAF') == '3' ? 'checked' : '') }}> 3
														</label>
														<label class="radio-inline">
															<input type="radio" name="Puntualidad_PAF" id="Puntualidad_PAF4" value="4" {{ ($calificacion && $calificacion['Puntualidad_PAF'] == '4' || old('Puntualidad_PAF') == '4' ? 'checked' : '') }}> 4
														</label>
														<label class="radio-inline">
															<input type="radio" name="Puntualidad_PAF" id="Puntualidad_PAF5" value="5" {{ ($calificacion && $calificacion['Puntualidad_PAF'] == '5' || old('Puntualidad_PAF') == '5' ? 'checked' : '') }}> 5
														</label>
													</div>
													<div class="col-md-12 form-group {{ $errors->has('Tiempo_De_La_Sesion') ? 'has-error' : '' }}">
														<label for="">2. Tiempo de la Sesión</label> <br>
														<label class="radio-inline">
															<input type="radio" name="Tiempo_De_La_Sesion" id="Tiempo_De_La_Sesion1" value="1" {{ ($calificacion && $calificacion['Tiempo_De_La_Sesion'] == '1' || old('Tiempo_De_La_Sesion') ==  '1') ? 'checked' : '' }}> 1
														</label>
														<label class="radio-inline">
															<input type="radio" name="Tiempo_De_La_Sesion" id="Tiempo_De_La_Sesion2" value="2" {{ ($calificacion && $calificacion['Tiempo_De_La_Sesion'] == '2' || old('Tiempo_De_La_Sesion') ==  '2') ? 'checked' : '' }}> 2
														</label>
														<label class="radio-inline">
															<input type="radio" name="Tiempo_De_La_Sesion" id="Tiempo_De_La_Sesion3" value="3" {{ ($calificacion && $calificacion['Tiempo_De_La_Sesion'] == '3' || old('Tiempo_De_La_Sesion') ==  '3') ? 'checked' : '' }}> 3
														</label>
														<label class="radio-inline">
															<input type="radio" name="Tiempo_De_La_Sesion" id="Tiempo_De_La_Sesion4" value="4" {{ ($calificacion && $calificacion['Tiempo_De_La_Sesion'] == '4' || old('Tiempo_De_La_Sesion') ==  '4') ? 'checked' : '' }}> 4
														</label>
														<label class="radio-inline">
															<input type="radio" name="Tiempo_De_La_Sesion" id="Tiempo_De_La_Sesion5" value="5" {{ ($calificacion && $calificacion['Tiempo_De_La_Sesion'] == '5' || old('Tiempo_De_La_Sesion') ==  '5') ? 'checked' : '' }}> 5
														</label>
													</div>
													<div class="col-md-12 form-group {{ $errors->has('Escenario_Y_Montaje') ? 'has-error' : '' }}">
														<label for="">3. Escenario y Montaje</label> <br>
														<label class="radio-inline">
															<input type="radio" name="Escenario_Y_Montaje" id="Escenario_Y_Montaje1" value="1" {{ ($calificacion && $calificacion['Escenario_Y_Montaje'] == '1' || old('Escenario_Y_Montaje') ==  '1') ? 'checked' : '' }}> 1
														</label>
														<label class="radio-inline">
															<input type="radio" name="Escenario_Y_Montaje" id="Escenario_Y_Montaje2" value="2" {{ ($calificacion && $calificacion['Escenario_Y_Montaje'] == '2' || old('Escenario_Y_Montaje') ==  '2') ? 'checked' : '' }}> 2
														</label>
														<label class="radio-inline">
															<input type="radio" name="Escenario_Y_Montaje" id="Escenario_Y_Montaje3" value="3" {{ ($calificacion && $calificacion['Escenario_Y_Montaje'] == '3' || old('Escenario_Y_Montaje') ==  '3') ? 'checked' : '' }}> 3
														</label>
														<label class="radio-inline">
															<input type="radio" name="Escenario_Y_Montaje" id="Escenario_Y_Montaje4" value="4" {{ ($calificacion && $calificacion['Escenario_Y_Montaje'] == '4' || old('Escenario_Y_Montaje') ==  '4') ? 'checked' : '' }}> 4
														</label>
														<label class="radio-inline">
															<input type="radio" name="Escenario_Y_Montaje" id="Escenario_Y_Montaje5" value="5" {{ ($calificacion && $calificacion['Escenario_Y_Montaje'] == '5' || old('Escenario_Y_Montaje') ==  '5') ? 'checked' : '' }}> 5
														</label>
													</div>
													<div class="col-md-12 form-group {{ $errors->has('Cumplimiento_Del_Objetivo') ? 'has-error' : '' }}">
														<label for="">4. Cumplimiento del Objetivo</label> <br>
														<label class="radio-inline">
															<input type="radio" name="Cumplimiento_Del_Objetivo" id="Cumplimiento_Del_Objetivo1" value="1" {{ ($calificacion && $calificacion['Cumplimiento_Del_Objetivo'] == '1' || old('Cumplimiento_Del_Objetivo') ==  '1') ? 'checked' : '' }}> 1
														</label>
														<label class="radio-inline">
															<input type="radio" name="Cumplimiento_Del_Objetivo" id="Cumplimiento_Del_Objetivo2" value="2" {{ ($calificacion && $calificacion['Cumplimiento_Del_Objetivo'] == '2' || old('Cumplimiento_Del_Objetivo') ==  '2') ? 'checked' : '' }}> 2
														</label>
														<label class="radio-inline">
															<input type="radio" name="Cumplimiento_Del_Objetivo" id="Cumplimiento_Del_Objetivo3" value="3" {{ ($calificacion && $calificacion['Cumplimiento_Del_Objetivo'] == '3' || old('Cumplimiento_Del_Objetivo') ==  '3') ? 'checked' : '' }}> 3
														</label>
														<label class="radio-inline">
															<input type="radio" name="Cumplimiento_Del_Objetivo" id="Cumplimiento_Del_Objetivo4" value="4" {{ ($calificacion && $calificacion['Cumplimiento_Del_Objetivo'] == '4' || old('Cumplimiento_Del_Objetivo') ==  '4') ? 'checked' : '' }}> 4
														</label>
														<label class="radio-inline">
															<input type="radio" name="Cumplimiento_Del_Objetivo" id="Cumplimiento_Del_Objetivo5" value="5" {{ ($calificacion && $calificacion['Cumplimiento_Del_Objetivo'] == '5' || old('Cumplimiento_Del_Objetivo') ==  '5') ? 'checked' : '' }}> 5
														</label>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="row">
													<div class="col-md-12 form-group {{ $errors->has('Variedad_Y_Creatividad') ? 'has-error' : '' }}">
														<label for="">5. Variedad y Creatividad</label> <br>
														<label class="radio-inline">
															<input type="radio" name="Variedad_Y_Creatividad" id="Variedad_Y_Creatividad1" value="1" {{ ($calificacion && $calificacion['Variedad_Y_Creatividad'] == '1' || old('Variedad_Y_Creatividad') ==  '1') ? 'checked' : '' }}> 1
														</label>
														<label class="radio-inline">
															<input type="radio" name="Variedad_Y_Creatividad" id="Variedad_Y_Creatividad2" value="2" {{ ($calificacion && $calificacion['Variedad_Y_Creatividad'] == '2' || old('Variedad_Y_Creatividad') ==  '2') ? 'checked' : '' }}> 2
														</label>
														<label class="radio-inline">
															<input type="radio" name="Variedad_Y_Creatividad" id="Variedad_Y_Creatividad3" value="3" {{ ($calificacion && $calificacion['Variedad_Y_Creatividad'] == '3' || old('Variedad_Y_Creatividad') ==  '3') ? 'checked' : '' }}> 3
														</label>
														<label class="radio-inline">
															<input type="radio" name="Variedad_Y_Creatividad" id="Variedad_Y_Creatividad4" value="4" {{ ($calificacion && $calificacion['Variedad_Y_Creatividad'] == '4' || old('Variedad_Y_Creatividad') ==  '4') ? 'checked' : '' }}> 4
														</label>
														<label class="radio-inline">
															<input type="radio" name="Variedad_Y_Creatividad" id="Variedad_Y_Creatividad5" value="5" {{ ($calificacion && $calificacion['Variedad_Y_Creatividad'] == '5' || old('Variedad_Y_Creatividad') ==  '5') ? 'checked' : '' }}> 5
														</label>
													</div>
													<div class="col-md-12 form-group {{ $errors->has('Imagen_Institucional') ? 'has-error' : '' }}">
														<label for="">6. Imagen Institucional</label> <br>
														<label class="radio-inline">
															<input type="radio" name="Imagen_Institucional" id="Imagen_Institucional1" value="1" {{ ($calificacion && $calificacion['Imagen_Institucional'] == '1' || old('Imagen_Institucional') ==  '1') ? 'checked' : '' }}> 1
														</label>
														<label class="radio-inline">
															<input type="radio" name="Imagen_Institucional" id="Imagen_Institucional2" value="2" {{ ($calificacion && $calificacion['Imagen_Institucional'] == '2' || old('Imagen_Institucional') ==  '2') ? 'checked' : '' }}> 2
														</label>
														<label class="radio-inline">
															<input type="radio" name="Imagen_Institucional" id="Imagen_Institucional3" value="3" {{ ($calificacion && $calificacion['Imagen_Institucional'] == '3' || old('Imagen_Institucional') ==  '3') ? 'checked' : '' }}> 3
														</label>
														<label class="radio-inline">
															<input type="radio" name="Imagen_Institucional" id="Imagen_Institucional4" value="4" {{ ($calificacion && $calificacion['Imagen_Institucional'] == '4' || old('Imagen_Institucional') ==  '4') ? 'checked' : '' }}> 4
														</label>
														<label class="radio-inline">
															<input type="radio" name="Imagen_Institucional" id="Imagen_Institucional5" value="5" {{ ($calificacion && $calificacion['Imagen_Institucional'] == '5' || old('Imagen_Institucional') ==  '5') ? 'checked' : '' }}> 5
														</label>
													</div>
													<div class="col-md-12 form-group {{ $errors->has('Divulgacion') ? 'has-error' : '' }}">
														<label for="">7. Divulgación</label> <br>
														<label class="radio-inline">
															<input type="radio" name="Divulgacion" id="Divulgacion1" value="1" {{ ($calificacion && $calificacion['Divulgacion'] == '1' || old('Divulgacion') ==  '1') ? 'checked' : '' }}> 1
														</label>
														<label class="radio-inline">
															<input type="radio" name="Divulgacion" id="Divulgacion2" value="2" {{ ($calificacion && $calificacion['Divulgacion'] == '2' || old('Divulgacion') ==  '2') ? 'checked' : '' }}> 2
														</label>
														<label class="radio-inline">
															<input type="radio" name="Divulgacion" id="Divulgacion3" value="3" {{ ($calificacion && $calificacion['Divulgacion'] == '3' || old('Divulgacion') ==  '3') ? 'checked' : '' }}> 3
														</label>
														<label class="radio-inline">
															<input type="radio" name="Divulgacion" id="Divulgacion4" value="4" {{ ($calificacion && $calificacion['Divulgacion'] == '4' || old('Divulgacion') ==  '4') ? 'checked' : '' }}> 4
														</label>
														<label class="radio-inline">
															<input type="radio" name="Divulgacion" id="Divulgacion5" value="5" {{ ($calificacion && $calificacion['Divulgacion'] == '5' || old('Divulgacion') ==  '5') ? 'checked' : '' }}> 5
														</label>
													</div>
													<div class="col-md-12 form-group {{ $errors->has('Seguridad') ? 'has-error' : '' }}">
														<label for="">8. Seguridad</label> <br>
														<label class="radio-inline">
															<input type="radio" name="Seguridad" id="Seguridad1" value="1" {{ ($calificacion && $calificacion['Seguridad'] == '1' || old('Seguridad') ==  '1') ? 'checked' : '' }}> 1
														</label>
														<label class="radio-inline">
															<input type="radio" name="Seguridad" id="Seguridad2" value="2" {{ ($calificacion && $calificacion['Seguridad'] == '2' || old('Seguridad') ==  '2') ? 'checked' : '' }}> 2
														</label>
														<label class="radio-inline">
															<input type="radio" name="Seguridad" id="Seguridad3" value="3" {{ ($calificacion && $calificacion['Seguridad'] == '3' || old('Seguridad') ==  '3') ? 'checked' : '' }}> 3
														</label>
														<label class="radio-inline">
															<input type="radio" name="Seguridad" id="Seguridad4" value="4" {{ ($calificacion && $calificacion['Seguridad'] == '4' || old('Seguridad') ==  '4') ? 'checked' : '' }}> 4
														</label>
														<label class="radio-inline">
															<input type="radio" name="Seguridad" id="Seguridad5" value="5" {{ ($calificacion && $calificacion['Seguridad'] == '5' || old('Seguridad') ==  '5') ? 'checked' : '' }}> 5
														</label>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-12 form-group {{ $errors->has('Nombre') ? 'has-error' : '' }}">
														<label for="">Nombre representante de la comunidad que califica el servicio </label>
														<input type="text" name="Nombre" class="form-control" value="{{ ($calificacion ? $calificacion['Nombre'] : old('Nombre') ) }}">
													</div>
													<div class="col-md-6 form-group {{ $errors->has('Telefono') ? 'has-error' : '' }}">
														<label for="">Teléfono </label>
														<input type="text" name="Telefono" class="form-control" value="{{ ($calificacion ? $calificacion['Telefono'] : old('Telefono') ) }}">
													</div>
													<div class="col-md-6 form-group {{ $errors->has('Correo') ? 'has-error' : '' }}">
														<label for="">Correo </label>
														<input type="text" name="Correo" class="form-control" value="{{ ($calificacion ? $calificacion['Correo'] : old('Correo') ) }}">
													</div>
												</div>
											</div>
											<div class="col-md-12">
					                            <input type="hidden" name="_method" value="POST">
					                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="Id" value="{{ $sesion ? $sesion['Id'] : 0 }}">
												<input type="hidden" name="origen" value="{{ $tipo }}">
												<input type="hidden" name="area" value="Calificacion_Del_Servicio">
												@if($sesion && $sesion['Estado'] != 'Finalizado')
													<div class="row">
														<div class="col-md-12"><hr></div>
														<div class="col-xs-12"><input type="submit" class="btn btn-primary" value="Registrar producto no conforme"></div>
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