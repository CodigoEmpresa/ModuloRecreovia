$(function()
{
    var PUNTOS = $.parseJSON(JSON.stringify($('select[name="Id_Cronograma"]').data('json')));

    $('select[name="Id_Punto"]').on('change', function(e)
    {
    	var Id_Punto = $(this).val();
    	var punto = $.grep(PUNTOS, function(o, i)
    	{
    		return o.Id_Punto == Id_Punto;
    	})[0];

    	if(punto.cronogramas.length) 
    	{
    		$('select[name="Id_Cronograma"]').html('<option value="Id_Cronograma">Seleccionar</option>');
    		$.each(punto.cronogramas, function(i, cronograma)
    		{
    			$('select[name="Id_Cronograma"]').append('<option data-desde="'+cronograma.Desde+'" data-dias="'+cronograma.jornada.Dias+'" data-hasta="'+cronograma.Hasta+'" value="'+cronograma.Id_Cronograma+'">'+('Del '+cronograma.Desde+' al '+cronograma.Hasta+' / '+cronograma.jornada.Label)+'</option>');
    		});
    	}
    });

    $('select[name="Id_Cronograma"]').on('change', function(e)
    {
    	var option = $('select[name="Id_Cronograma"] option:selected');
    	$('input[name="Fecha"]').attr('data-fecha-inicio', '').attr('data-fecha-fin', '');

    	var dias = option.data('dias');
    	var fecha_inicio = moment(option.data('desde'));
    	var fecha_fin = moment(option.data('hasta'));
    	$('input[name="Fecha"]').attr('data-fecha-inicio', fecha_inicio.format('YYYY-MM-DD')).attr('data-fecha-fin', fecha_fin.format('YYYY-MM-DD')).attr('data-dias', dias);
    });
});