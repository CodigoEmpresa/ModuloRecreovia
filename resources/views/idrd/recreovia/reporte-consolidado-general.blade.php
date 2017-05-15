<table border="1">
	<tr>
		<td colspan="35" align="center"><b>INSTITUTO DISTRITAL DE RECREACIÓN Y DEPORTE - IDRD</b></td>
	</tr>
	<tr>
		<td colspan="35" align="center"><b>ÁREA DE RECREOVÍA</b></td>
	</tr>
	<tr>
		<td colspan="35" align="center"><b>Reporte de Actividades por punto</b></td>
	</tr>
	<tr>
		<td colspan="35"></td>
	</tr>
	<tr>
		<td colspan="17" align="center">
			PARTICIPANTES<br>
			(Frecuencia Relativa)
		</td>

		<td colspan="2"></td>

		<td colspan="16" align="center">
			ASISTENTES <br>
			(Frecuencia Relativa)
		</td>
	</tr>
	<tr>
        <td valign="center" align="center">#</td>
        <td valign="center" width=35>Sesión</td>
        @foreach($gruposPoblacionales as $grupo)
            <td colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</td>
        @endforeach
        <td colspan="2">Subtotal<br>participantes</td>
        <td valign="center">Total</td>

		<td colspan="2"></td>
	
		<td valign="center" align="center">#</td>
        @foreach($gruposPoblacionales as $grupo)
            <td colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</td>
        @endforeach
        <td colspan="2">Subtotal<br>asistentes</td>
        <td valign="center">Total</td>
	</tr>
	<tr>
		<td></td>
		<td width=15></td>
        @for ($i = 0; $i < $gruposPoblacionales->count(); $i++)
            <td width=10 style="text-align: center;">H</td>
            <td width=10 style="text-align: center;">M</td>
        @endfor
        <td width=10 style="text-align: center;">H</td>
        <td width=10 style="text-align: center;">M</td>
		<td></td>

		<td colspan="2"></td>

		<td></td>
        @for ($i = 0; $i < $gruposPoblacionales->count(); $i++)
            <td width=10 style="text-align: center;">H</td>
            <td width=10 style="text-align: center;">M</td>
        @endfor
        <td width=10 style="text-align: center;">H</td>
        <td width=10 style="text-align: center;">M</td>
		<td></td>
    </tr>
    <?php 
    	$j = 1; 
    	$subtotal_participantes = ['M' => 0, 'F' => 0];
    	$subtotal_asistentes = ['M' => 0, 'F' => 0];
    	$total_participantes = 0;
    	$total_asistentes = 0;
    	$total_hombres = 0;
    	$total_mujeres = 0;
    ?>
    @foreach ($gruposPoblacionales as $grupo)
        <?php 
            $subtotal_grupo_participantes[$grupo['Id']] = ['M' => 0, 'F' => 0];
            $subtotal_grupo_asistentes[$grupo['Id']] = ['M' => 0, 'F' => 0];
        ?>
    @endforeach
   	@foreach ($totales_sesiones as $key => $total_sesion)
   		<tr>
			<td>{{ $j }}</td>
			<td>{{ $key }}</td>
			<?php
				$total_participantes_h = 0;
				$total_participantes_m = 0;
			?>
	   		@foreach ($total_sesion as $id => $grupo)
				<?php 
					$total_participantes_h += $grupo['Participantes']['M'];
					$total_participantes_m += $grupo['Participantes']['F'];
					$subtotal_grupo_participantes[$id]['M'] += $grupo['Participantes']['M'];
					$subtotal_grupo_participantes[$id]['F'] += $grupo['Participantes']['F'];
					$subtotal_participantes['M'] += $grupo['Participantes']['M'];
					$subtotal_participantes['F'] += $grupo['Participantes']['F'];
				?>
				<td align="center">{{ $grupo['Participantes']['M'] }}</td>
				<td align="center">{{ $grupo['Participantes']['F'] }}</td>
			@endforeach
			<td align="center">{{ $total_participantes_h }}</td>
			<td align="center">{{ $total_participantes_m }}</td>
			<td align="center">{{ $total_participantes_h + $total_participantes_m }}</td>

			<td colspan="2"></td>

			<td>{{ $j }}</td>
			<?php
				$total_asistentes_h = 0;
				$total_asistentes_m = 0;
			?>
			@foreach($total_sesion as $id => $grupo)
				<?php 
					$total_asistentes_h += $grupo['Asistentes']['M'];
					$total_asistentes_m += $grupo['Asistentes']['F'];
					$subtotal_grupo_asistentes[$id]['M'] += $grupo['Asistentes']['M'];
					$subtotal_grupo_asistentes[$id]['F'] += $grupo['Asistentes']['F'];
					$subtotal_asistentes['M'] += $grupo['Asistentes']['M'];
					$subtotal_asistentes['F'] += $grupo['Asistentes']['F'];
				?>
				<td align="center">{{ $grupo['Asistentes']['M'] }}</td>
				<td align="center">{{ $grupo['Asistentes']['F'] }}</td>
			@endforeach
			<td align="center">{{ $total_asistentes_h }}</td>
			<td align="center">{{ $total_asistentes_m }}</td>
			<td align="center">{{ $total_asistentes_h + $total_asistentes_m }}</td>
			<?php $j++ ?>
		</tr>
   	@endforeach
	<tr>
		<td></td>
		<td>Subtotal</td>
		@foreach($subtotal_grupo_participantes as $subtotal)
			<td align="center">{{ $subtotal['M'] }}</td>
			<td align="center">{{ $subtotal['F'] }}</td>
		@endforeach
		<td align="center">{{ $subtotal_participantes['M'] }}</td>
		<td align="center">{{ $subtotal_participantes['F'] }}</td>
		<?php $total_participantes = $subtotal_participantes['M'] + $subtotal_participantes['F']; ?>
		<td align="center">{{ $total_participantes }}</td>
		
		<td colspan="2"></td>

		<td>Subtotal</td>
		@foreach($subtotal_grupo_asistentes as $subtotal)
			<td align="center">{{ $subtotal['M'] }}</td>
			<td align="center">{{ $subtotal['F'] }}</td>
		@endforeach
		<td align="center">{{ $subtotal_asistentes['M'] }}</td>
		<td align="center">{{ $subtotal_asistentes['F'] }}</td>
		<?php $total_asistentes = $subtotal_asistentes['M'] + $subtotal_asistentes['F']; ?>
		<td align="center">{{ $total_asistentes }}</td>
	</tr>
	<tr>
		<td></td>
		<td>Total</td>
		@foreach($subtotal_grupo_participantes as $subtotal)
			<?php
				$total_hombres += $subtotal['M'];
				$total_mujeres += $subtotal['F'];
			?>
			<td align="center" colspan="2" style="text-align: center;">{{ $subtotal['M'] + $subtotal['F'] }}</td>
		@endforeach
		<td align="center" colspan="2" style="text-align: center;">{{ $total_participantes }}</td>
		<td align="center"><b>{{ $total_participantes }}</b></td>
		
		<td colspan="2"></td>

		<td>Total</td>
		@foreach($subtotal_grupo_asistentes as $subtotal)
			<?php
				$total_hombres += $subtotal['M'];
				$total_mujeres += $subtotal['F'];
			?>
			<td align="center" colspan="2" style="text-align: center;">{{ $subtotal['M'] + $subtotal['F'] }}</td>
		@endforeach
		<td align="center" colspan="2" style="text-align: center;">{{ $total_asistentes }}</td>
		<td align="center"><b>{{ $total_asistentes }}</b></td>
	</tr>
	<tr>
		<td colspan="35"></td>
	</tr>
	<tr>
		<td colspan="35"></td>
	</tr>
	<tr>
        <td valign="center" align="center">#</td>
        <td valign="center" width=35>Sesión</td>
        @foreach($gruposPoblacionales as $grupo)
            <td colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</td>
        @endforeach
        <td colspan="2">Subtotal<br>participantes</td>
        <td valign="center">Total</td>

		<td colspan="2"></td>
	
		<td valign="center" align="center">#</td>
        @foreach($gruposPoblacionales as $grupo)
            <td colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</td>
        @endforeach
        <td colspan="2">Subtotal<br>asistentes</td>
        <td valign="center">Total</td>
	</tr> 
	<?php 
    	$j = 1; 
    	$subtotal_participantes = ['M' => 0, 'F' => 0];
    	$subtotal_asistentes = ['M' => 0, 'F' => 0];
    ?>
    @foreach ($gruposPoblacionales as $grupo)
        <?php 
            $subtotal_grupo_participantes[$grupo['Id']] = ['M' => 0, 'F' => 0];
            $subtotal_grupo_asistentes[$grupo['Id']] = ['M' => 0, 'F' => 0];
        ?>
    @endforeach
	@foreach ($totales_sesiones as $key => $total_sesion)
   		<tr>
			<td>{{ $j }}</td>
			<td>{{ $key }}</td>
			<?php
				$total_participantes_h = 0;
				$total_participantes_m = 0;
			?>
	   		@foreach ($total_sesion as $id => $grupo)
				<?php 
					$total_participantes_h += $grupo['Participantes']['M'];
					$total_participantes_m += $grupo['Participantes']['F'];
					$subtotal_grupo_participantes[$id]['M'] += $grupo['Participantes']['M'];
					$subtotal_grupo_participantes[$id]['F'] += $grupo['Participantes']['F'];
					$subtotal_participantes['M'] += $grupo['Participantes']['M'];
					$subtotal_participantes['F'] += $grupo['Participantes']['F'];
				?>
				<td align="center">{{ round(($grupo['Participantes']['M'] * 100) / ($total_participantes ? $total_participantes : 1) ) }}%</td>
				<td align="center">{{ round(($grupo['Participantes']['F'] * 100) / ($total_participantes ? $total_participantes : 1) ) }}%</td>
			@endforeach
			<td align="center">{{ round(($total_participantes_h * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
			<td align="center">{{ round(($total_participantes_m * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
			<td align="center">{{ round((($total_participantes_h + $total_participantes_m) * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>

			<td colspan="2"></td>

			<td>{{ $j }}</td>
			<?php
				$total_asistentes_h = 0;
				$total_asistentes_m = 0;
			?>
			@foreach($total_sesion as $id => $grupo)
				<?php 
					$total_asistentes_h += $grupo['Asistentes']['M'];
					$total_asistentes_m += $grupo['Asistentes']['F'];
					$subtotal_grupo_asistentes[$id]['M'] += $grupo['Asistentes']['M'];
					$subtotal_grupo_asistentes[$id]['F'] += $grupo['Asistentes']['F'];
					$subtotal_asistentes['M'] += $grupo['Asistentes']['M'];
					$subtotal_asistentes['F'] += $grupo['Asistentes']['F'];
				?>
				<td align="center">{{ round(($grupo['Asistentes']['M'] * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
				<td align="center">{{ round(($grupo['Asistentes']['F'] * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
			@endforeach
			<td align="center">{{ round(($total_asistentes_h * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
			<td align="center">{{ round(($total_asistentes_m * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
			<td align="center">{{ round((($total_asistentes_h + $total_asistentes_m) * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
			<?php $j++ ?>
		</tr>
   	@endforeach
   	<tr>
		<td></td>
		<td>Subtotal</td>
		@foreach($subtotal_grupo_participantes as $subtotal)
			<td align="center">{{ round(($subtotal['M'] * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
			<td align="center">{{ round(($subtotal['F'] * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
		@endforeach
		<td align="center">{{ round(($subtotal_participantes['M'] * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
		<td align="center">{{ round(($subtotal_participantes['F'] * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
		<td align="center">{{ round(($total_participantes * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
		
		<td colspan="2"></td>

		<td>Subtotal</td>
		@foreach($subtotal_grupo_asistentes as $subtotal)
			<td align="center">{{ round(($subtotal['M'] * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
			<td align="center">{{ round(($subtotal['F'] * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
		@endforeach
		<td align="center">{{ round(($subtotal_asistentes['M'] * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
		<td align="center">{{ round(($subtotal_asistentes['F'] * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
		<td align="center">{{ round(($total_asistentes * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
	</tr>
	<tr>
		<td></td>
		<td>Total</td>
		@foreach($subtotal_grupo_participantes as $subtotal)
			<td align="center" colspan="2" style="text-align: center;">{{ round((($subtotal['M'] + $subtotal['F']) * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
		@endforeach
		<td align="center" colspan="2" style="text-align: center;">{{ round(($total_participantes * 100) / ($total_participantes ? $total_participantes : 1)) }}%</td>
		<td align="center"><b>{{ round(($total_participantes * 100) / ($total_participantes ? $total_participantes : 1)) }}%</b></td>
		
		<td colspan="2"></td>

		<td>Total</td>
		@foreach($subtotal_grupo_asistentes as $subtotal)
			<td align="center" colspan="2" style="text-align: center;">{{ round((($subtotal['M'] + $subtotal['F']) * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
		@endforeach
		<td align="center" colspan="2" style="text-align: center;">{{ round(($total_asistentes * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</td>
		<td align="center"><b>{{ round(($total_asistentes * 100) / ($total_asistentes ? $total_asistentes : 1)) }}%</b></td>
	</tr>
	<tr>
		<td colspan="35"></td>
	</tr>
	<tr>
		<td colspan="35"></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="34" align="left"><b>TOTAL USUARIOS</b></td>
	</tr>
	<tr>
		<td colspan="1"></td>
		<td>Hombres</td>
		<td align="center">{{ $total_hombres }}</td>
		<td colspan="32"></td>
	</tr>
	<tr>
		<td colspan="1"></td>
		<td>Mujeres</td>
		<td align="center">{{ $total_mujeres }}</td>
		<td colspan="32"></td>
	</tr>
	<tr>
		<td colspan="1"></td>
		<td>Total</td>
		<td align="center">{{ $total_hombres + $total_mujeres}}</td>
		<td colspan="32"></td>
	</tr>
	<tr>
		<td colspan="35"></td>
	</tr>
	<tr>
		<td colspan="1"></td>
		<td>Participantes</td>
		<td align="center">{{ round((($total_participantes * 100) / ($total_hombres + $total_mujeres ? $total_hombres + $total_mujeres : 1))) }}%</td>
		<td colspan="32"></td>
	</tr>
	<tr>
		<td colspan="1"></td>
		<td>Asistentes</td>
		<td align="center">{{ round((($total_asistentes * 100) / ($total_hombres + $total_mujeres ? $total_hombres + $total_mujeres : 1))) }}%</td>
		<td colspan="32"></td>
	</tr>
</table>