$(function()
{
	var PUNTOS = $.parseJSON(JSON.stringify($('select[name="Id_Jornada"]').data('json')));
	
	var populateJornada = function()
	{
		var deferred = $.Deferred();
		var Id_Punto = $('select[name="Id_Punto"]').val();
		var punto = $.grep(PUNTOS, function(punto)
		{
			return punto.Id_Punto == Id_Punto;
		})[0];

		$('select[name="Id_Jornada"]').html('<option value="">Seleccionar</option>');

		$.each(punto.jornadas, function(i, jornada)
		{
			$('select[name="Id_Jornada"]').append('<option value="'+jornada.Id_Jornada+'">'+jornada.Label+'</option>');
		});
		
		deferred.resolve();
		return deferred;
	}

	$('select[name="Id_Punto"]').on('change', populateJornada);

	if ($('select[name="Id_Punto"]').data('value') != '')
	{
		populateJornada().then(function()
		{
			$('select[name="Id_Jornada"]').val($('select[name="Id_Jornada"]').data('value'));
		});
	}
});