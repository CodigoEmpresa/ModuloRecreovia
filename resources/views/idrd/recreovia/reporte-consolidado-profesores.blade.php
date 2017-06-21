<table border=1>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			<b>INFORME DE ACTIVIDADES RECREOVIA</b>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;" >
			CENTRAL DE COMUNICACIONES - REPORTE
		</td>
	</tr>
	<tr>
		<td width=5></td>
		<td width=15></td>
		<td width=15></td>
		<td width=15></td>
		<td width=15></td>
		<td width=15></td>
		<td width=15></td>
		<td width=15></td>
		<td width=15></td>
		<td width=15></td>
		<td width=15></td>
		<td width=40></td>
	</tr>
	<tr>
		<td align="center" colspan="3" style="text-align:center;">
			Fecha
		</td>
		<td></td>
		<td align="center" style="text-align:center;">RESD</td>
		<td align="center" style="text-align:center;">RESN</td>
		<td align="center" style="text-align:center;">RFDS</td>
		<td align="center" style="text-align:center;">CG</td>
		<td align="center" style="text-align:center;">CGI</td>
		<td colspan="3"></td>
	</tr>
	<tr>
		<td align="center" colspan="3" style="text-align:center;">{{ implode(', ',  $fecha) }}</td>
		<td>Jornada</td>
		<td align="center" style="text-align:center;">{{ $jornada->Jornada == 'dia' ? 'x' : '' }}</td>
		<td align="center" style="text-align:center;">{{ $jornada->Jornada == 'noche' ? 'x' : '' }}</td>
		<td align="center" style="text-align:center;">{{ $jornada->Jornada == 'fds' ? 'x' : '' }}</td>
		<td align="center" style="text-align:center;">{{ $jornada->Jornada == 'clases_grupales' ? 'x' : '' }}</td>
		<td align="center" style="text-align:center;">{{ $jornada->Jornada == 'clases_grupales_institucionales' ? 'x' : '' }}</td>
		<td colspan="3"></td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			NOVEDADES PROFESORES DE ACTIVIDAD FÍSICA - PAF
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			Inasistencia, Retardo, Devoluciones, Servicio de Datos
		</td>
	</tr>
	<tr>
		<td align="center" style="text-align:center;">No</td>
		<td align="center" style="text-align:center;" colspan="3">Nombre</td>
		<td align="center" style="text-align:center;">CPS</td>
		<td align="center" style="text-align:center;">Objeto</td>
		<td align="center" style="text-align:center;">Punto</td>
		<td align="center" style="text-align:center;">Motivo</td>
		<td align="center" style="text-align:center;" colspan="2">Acciones a tomar</td>
		<td align="center" style="text-align:center;" colspan="2">Autorización</td>
	</tr>
	@for($i=1; $i<=5; $i++)
		<tr>
			<td align="center" style="text-align:center;">{{ $i }}</td>
			<td colspan="3"></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="2"></td>
			<td colspan="2"></td>
		</tr>
	@endfor
	<tr>
		<td align="center"  colspan="12" style="text-align:center;">
			NOVEDADES GESTORES DE ACTIVIDAD FÍSICA - GAF
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			Inasistencia, Retardo, Devoluciones, Servicio de Datos
		</td>
	</tr>
	<tr>
		<td align="center" style="text-align:center;">No</td>
		<td align="center" style="text-align:center;" colspan="3">Nombre</td>
		<td align="center" style="text-align:center;">CPS</td>
		<td align="center" style="text-align:center;">Objeto</td>
		<td align="center" style="text-align:center;">Punto</td>
		<td align="center" style="text-align:center;">Motivo</td>
		<td align="center" style="text-align:center;" colspan="2">Acciones a tomar</td>
		<td align="center" style="text-align:center;" colspan="2">Autorización</td>
	</tr>
	@for($i=1; $i<=5; $i++)
		<tr>
			<td align="center" style="text-align:center;">{{ $i }}</td>
			<td colspan="3"></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="2"></td>
			<td colspan="2"></td>
		</tr>
	@endfor
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			NOVEDADES SERVICIO SOCIAL
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			Inasistencia, Retardo, Devoluciones
		</td>
	</tr>
	<tr>
		<td align="center" style="text-align:center;">No</td>
		<td align="center" style="text-align:center;" colspan="3">Nombre</td>
		<td align="center" style="text-align:center;">Documento</td>
		<td align="center" style="text-align:center;">Colegio</td>
		<td align="center" style="text-align:center;">Punto</td>
		<td align="center" style="text-align:center;">Motivo</td>
		<td align="center" style="text-align:center;" colspan="2">Acciones a tomar</td>
		<td align="center" style="text-align:center;" colspan="2">Autorización</td>
	</tr>
	@for($i=1; $i<=5; $i++)
		<tr>
			<td align="center" style="text-align:center;">{{ $i }}</td>
			<td colspan="3"></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="2"></td>
			<td colspan="2"></td>
		</tr>
	@endfor
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			NOVEDADES DE SERVICIOS
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			SONIDO
		</td>
	</tr>
	<tr>
		<td align="center" style="text-align:center;">No.</td>
		<td align="center" style="text-align:center;" colspan="2">Punto</td>
		<td align="center" style="text-align:center;" colspan="2">Reporte</td>
		<td align="center" style="text-align:center;" colspan="2">Novedad</td>
		<td align="center" style="text-align:center;" colspan="2">Empresa</td>
		<td align="center" style="text-align:center;">CPS</td>
		<td align="center" style="text-align:center;" colspan="2">Acción tomada</td>
	</tr>
	@for($i=1; $i<=5; $i++)
		<tr>
			<td align="center" style="text-align:center;">{{ $i }}</td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
		</tr>
	@endfor
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			TRANSPORTE DE CARGA, OPERARIOS, TARIMAS
		</td>
	</tr>
	<tr>
		<td align="center" style="text-align:center;">No.</td>
		<td align="center" style="text-align:center;" colspan="2">Punto</td>
		<td align="center" style="text-align:center;" colspan="2">Reporte</td>
		<td align="center" style="text-align:center;" colspan="2">Novedad</td>
		<td align="center" style="text-align:center;" colspan="2">Empresa</td>
		<td align="center" style="text-align:center;">CPS</td>
		<td align="center" style="text-align:center;" colspan="2">Acción tomada</td>
	</tr>
	@for($i=1; $i<=5; $i++)
		<tr>
			<td align="center" style="text-align:center;">{{ $i }}</td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
		</tr>
	@endfor
	<tr>
		<td>No. Operarios</td>
		<td>Empresa 1</td>
		<td>Empresa 2</td>
		<td>Total</td>
		<td></td>
		<td>No. Camiones</td>
		<td colspan="6"></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="6"></td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			REPORTE PUNTOS DE ACTIVIDAD FÍSICA											
		</td>
	</tr>
	<tr>
		<td align="center" style="text-align:center;">No.</td>
		<td align="center" style="text-align:center;">Punto de Recreovía</td>
		<td align="center" style="text-align:center;">Localidad</td>
		<td align="center" style="text-align:center;">UPZ</td>
		<td align="center" style="text-align:center;">Dirección</td>
		<td align="center" style="text-align:center;">Responsable</td>
		<td align="center" style="text-align:center;">541 <br> Inicio Actividades</td>
		<td align="center" style="text-align:center;">Conteo</td>
		<td align="center" style="text-align:center;">542 <br> Terminación Actividades</td>
		<td align="center" style="text-align:center;">No. Operarios</td>
		<td align="center" style="text-align:center;">Transporte Placa</td>
		<td align="center" style="text-align:center;">Observaciones - Novedades</td>
	</tr>
	<?php 
		$i = 0;
		$total = 0;
	?>
	@foreach($reportes as $reporte)
		<?php $subtotal = 0; ?>
		@foreach($reporte->cronograma->sesiones->whereIn('Fecha', $fecha)->all() as $sesion)
			<?php 
				$subtotal += $sesion->gruposPoblacionales->sum('pivot.Cantidad'); 
				$total += $sesion->gruposPoblacionales->sum('pivot.Cantidad');
			?>
		@endforeach
		<tr>
			<td align="center" style="text-align:center;">{{ $i++ }}</td>
			<td align="center" style="text-align:center;">{{ $reporte->cronograma->punto->toString() }}</td>
			<td align="center" style="text-align:center;">{{ $reporte->cronograma->punto->localidad['Localidad'] }}</td>
			<td align="center" style="text-align:center;">{{ $reporte->cronograma->punto->upz['Upz'] }}</td>
			<td align="center" style="text-align:center;">{{ $reporte->cronograma->punto['Direccion'] }}</td>
			<td align="center" style="text-align:center;">{{ $reporte->cronograma->gestor->persona->toFriendlyString() }}</td>
			<td align="center" style="text-align:center;">{{ $reporte->novedad['Cod_514_541'] }}</td>
			<td align="center" style="text-align:center;">{{ $subtotal }}</td>
			<td align="center" style="text-align:center;">{{ $reporte->novedad['Cod_514_542'] }}</td>
			<td align="center" style="text-align:center;">0</td>
			<td align="center" style="text-align:center;">0</td>
			<td align="center" style="text-align:center;">{{ $reporte->novedad['Novedades'] }}</td>
		</tr>
	@endforeach
	<tr>
		<td></td>
		<td colspan="6" align="center" style="text-align:center;"><b>TOTAL PUNTOS DE RECREOVÍA</b></td>
		<td align="center" style="text-align:center;"><b>{{ $total }}</b></td>
		<td align="center" style="text-align:center;"><b>PROMEDIO</b></td>
		<td align="center" style="text-align:center;">{{ round( $total / ($reportes->count() ? $reportes->count() : 1) ) }}</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			EVENTOS ESPECIALES Y ACTIVACIONES DE MARCA EN RECREOVÍA					
		</td>
	</tr>
	<tr>
		<td align="center" style="text-align:center;">No.</td>
		<td align="center" style="text-align:center;" colspan="2">Punto</td>
		<td align="center" style="text-align:center;" colspan="2">Empresa / Entidad / Institución</td>
		<td align="center" style="text-align:center;" colspan="2">Responsable</td>
		<td align="center" style="text-align:center;">Teléfonos de contacto</td>
		<td align="center" style="text-align:center;">541 <br> (Inicio Actividades)</td>
		<td align="center" style="text-align:center;">542 <br> (Terminación Actividades)</td>
		<td align="center" style="text-align:center;" colspan="2">Novedades</td>
	</tr>
	@for($i=1; $i<=3; $i++)
		<tr>
			<td align="center" style="text-align:center;">{{ $i }}</td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
		</tr>
	@endfor
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			REPORTES DE ACCIDENTES
		</td>
	</tr>
	<tr>
		<td align="center" style="text-align:center;">No.</td>
		<td align="center" style="text-align:center;" colspan="2">Punto</td>
		<td align="center" style="text-align:center;">Hora</td>
		<td align="center" style="text-align:center;">Causa</td>
		<td align="center" style="text-align:center;">Usuario</td>
		<td align="center" style="text-align:center;">Documento</td>
		<td align="center" style="text-align:center;">Diagnostico</td>
		<td align="center" style="text-align:center;">Quien atiende</td>
		<td align="center" style="text-align:center;">Acompañante</td>
		<td align="center" style="text-align:center;" colspan="2">Novedades</td>
	</tr>
	@for($i=1; $i<=3; $i++)
		<tr>
			<td align="center" style="text-align:center;">{{ $i }}</td>
			<td align="center" style="text-align:center;" colspan="2"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;"></td>
			<td align="center" style="text-align:center;" colspan="2"></td>
		</tr>
	@endfor
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
			OBSERVACIONES DE LA JORNADA											
		</td>
	</tr>
	@for($i=1; $i<=3; $i++)
		<tr>
			<td align="center" style="text-align:center;">{{ $i }}</td>
			<td align="center" style="text-align:center;" colspan="11"></td>
		</tr>
	@endfor
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="4" style="border-top: 1px solid #000;">Responsable de Central de Comunicaciones</td>
		<td></td>
		<td colspan="4" style="border-top: 1px solid #000;">V° B° Gestor de Talento Humano</td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td>Nombre</td>
		<td colspan="3"></td>
		<td></td>
		<td>Nombre</td>
		<td colspan="3"></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td>Objeto</td>
		<td colspan="3"></td>
		<td></td>
		<td></td>
		<td colspan="3"></td>
		<td></td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="4" style="border-top: 1px solid #000;" align="center" style="text-align:center;">V° B° Gestor Operativo</td>
		<td></td>
		<td colspan="4" style="border-top: 1px solid #000;" align="center" style="text-align:center;">Responsable del Programa Recreovía</td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td>Nombre</td>
		<td colspan="3"></td>
		<td></td>
		<td>Nombre</td>
		<td colspan="3"></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td></td>
		<td colspan="3"></td>
		<td></td>
		<td>Cargo</td>
		<td colspan="3"></td>
		<td></td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="12" style="text-align:center;">
		</td>
	</tr>
	<tr>
		<td colspan="4"></td>
		<td colspan="4" style="border-top: 1px solid #000;" align="center" style="text-align:center;">Responsable del Área de Recreación</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td colspan="4"></td>
		<td>Nombre</td>
		<td colspan="3"></td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td colspan="4"></td>
		<td>Cargo</td>
		<td colspan="3"></td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td colspan="4"></td>
		<td></td>
		<td colspan="3" align="center" style="text-align:center;">Jefe Área Recreación</td>
		<td colspan="4"></td>
	</tr>
</table>