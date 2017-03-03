<table>
	<tr>
		<td colspan="39" align="center"><b>INSTITUTO DISTRITAL DE RECREACIÓN Y DEPORTE - IDRD</b></td>
	</tr>
	<tr>
		<td colspan="39" align="center"><b>ÁREA DE RECREOVÍA</b></td>
	</tr>
	<tr>
		<td colspan="39" align="center"><b>Reporte de Actividades por punto</b></td>
	</tr>
	<tr>
		<td colspan="39"></td>
	</tr>
	<tr>
		<td colspan="21" align="center">
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
		<td></td>
        <td valign="center" align="center" width="30px" rowspan="2">#</td>
        <td style="width:100px;" valign="center" rowspan="2">Sesión</td>
        @foreach($gruposPoblacionales as $grupo)
            <td style="width:104px;" colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</td>
        @endforeach
        <td style="width:104px;" colspan="2">Subtotal<br>Participantes</td>
        <td style="width:52px;" valign="center" rowspan="2">Total</td>
        @foreach($gruposPoblacionales as $grupo)
            <td style="width: 52px;">H</td>
            <td style="width: 52px;">M</td>
        @endforeach
        <td style="width: 52px;">H</td>
        <td style="width: 52px;">M</td>
		<td colspan="2"></td>
		<td colspan="16">
			<table border-collapse="collapse" border="none">
				<tr>
                    <th valign="center" align="center" width="30px" rowspan="2">#</th>
                    @foreach($gruposPoblacionales as $grupo)
                        <th style="width:104px;" colspan="2">{{ $grupo['Edad_Inicio'].($grupo['Edad_Fin'] > 0 ? ' a '.$grupo['Edad_Fin'].' años' : ' - mas') }}<br>{{ $grupo['Grupo'] }}</th>
                    @endforeach
                    <th style="width:104px;" colspan="2">Subtotal<br>Participantes</th>
                    <th style="width:52px;" valign="center" rowspan="2">Total</th>
                </tr>
                <tr>
                    @foreach($gruposPoblacionales as $grupo)
                        <th style="width: 52px;">H</th>
                        <th style="width: 52px;">M</th>
                    @endforeach
                    <th style="width: 52px;">H</th>
                    <th style="width: 52px;">M</th>
                </tr>
			</table>
		</td>
	</tr>
</table>