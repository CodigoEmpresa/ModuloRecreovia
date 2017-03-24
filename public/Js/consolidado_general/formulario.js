$(function(e)
{
	var JORNADAS = $.parseJSON(JSON.stringify($('select[name="Id_Jornada"]').data('json')));

	$('input[name="Dias"]').selectize({
			delimiter: ',',
			persist: true,
			create:  true
	});

	var selectize_dias = $('input[name="Dias"]')[0].selectize;

	$('input[name="Dia"]').on('change', function(e)
	{
			selectize_dias.addOption({text:$(this).val(), value:$(this).val()});
			selectize_dias.addItem($(this).val());
			$(this).val('');
	});

	$('select[name="Id_Jornada"]').on('change', function(i, e)
	{
		var value = $(this).val();
		var jornada;

		if (value.length > 0)
		{
			jornada = $.grep(JORNADAS, function(jornada)
			{
				return jornada.Id_Jornada == value;
			})[0];

			if (jornada)
			{
				if (jornada.Fecha_Evento_Inicio)
			    {
					var fecha_inicio = moment(jornada.Fecha_Evento_Inicio);//.subtract(1, 'days');
					$('input[name="Fecha"]').attr('data-fecha-inicio', fecha_inicio.format('YYYY-MM-DD'));
			    } else {
			    	$('input[name="Fecha"]').attr('data-fecha-inicio', '');
			    }

				if (jornada.Fecha_Evento_Fin)
				{
					var fecha_fin = moment(jornada.Fecha_Evento_Fin);//.add(1, 'days');
					$('input[name="Fecha"]').attr('data-fecha-fin', fecha_fin.format('YYYY-MM-DD'));
				} else {
					$('input[name="Fecha"]').attr('data-fecha-fin', '');
				}

				if (jornada.Dias)
				{
					$('input[name="Fecha"]').attr('data-dias', jornada.Dias);
				} else {
					$('input[name="Fecha"]').attr('data-dias', '');
				}
			}
		} else {
			$('input[name="Fecha"]').attr('data-fecha-inicio', '');
			$('input[name="Fecha"]').attr('data-fecha-fin', '');
			$('input[name="Fecha"]').attr('data-dias', '');
		}

		$('input[name="Fecha"]').val('');
	});
});
