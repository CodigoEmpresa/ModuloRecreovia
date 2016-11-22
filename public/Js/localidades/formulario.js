$(function()
{
	var URL = $('#main').data('url');
	var URL_LOCALIDADES = $('#main').data('url-localidades');

	$('input[name="Profesor"]').autocomplete({
		source: function(request, response)
		{
			$.getJSON(URL+'/service/buscar/'+$('input[name="Profesor"]').val()+'/1', function(data)
				{
					response($.map(data, function(e)
					{
						return {
							label: e.Primer_Apellido+' '+e.Primer_Nombre, 
							value: e.recreopersona.Id_Recreopersona
						}
					}));
				});
		},
    	minLength: 0,
    	focus: function(event, ui)
    	{
    		$('input[name="Profesor"]').val(ui.item.label);
        	return false;
    	},
    	select: function(event, ui)
    	{
	    	$('input[name="Profesor"]').val(ui.item.label);
	    	$('input[name="Profesor"]').closest('form').find('input[name="id_persona"]').val(ui.item.value);
        	return false;
      	}
	}).data("ui-autocomplete")._renderItem = function(ul, item) {
	  	return $("<li>")
	    	.data("item.ui-autocomplete", item)
	    	.append("<a>" + item.label + "</a>")
	    	.appendTo(ul);
	};

	$('input[name="Gestor"]').autocomplete({
		source: function(request, response)
		{
			$.getJSON(URL+'/service/buscar/'+$('input[name="Gestor"]').val()+'/1', function(data)
				{
					response($.map(data, function(e)
					{
						return {
							label: e.Primer_Apellido+' '+e.Primer_Nombre, 
							value: e.recreopersona.Id_Recreopersona
						}
					}));
				});
		},
    	minLength: 0,
    	focus: function(event, ui)
    	{
    		$('input[name="Gestor"]').val(ui.item.label);
        	return false;
    	},
    	select: function(event, ui)
    	{
	    	$('input[name="Gestor"]').val(ui.item.label);
	    	$('input[name="Gestor"]').closest('form').find('input[name="id_persona"]').val(ui.item.value);
        	return false;
      	}
	}).data("ui-autocomplete")._renderItem = function(ul, item) {
	  	return $("<li>")
	    	.data("item.ui-autocomplete", item)
	    	.append("<a>" + item.label + "</a>")
	    	.appendTo(ul);
	};

	$('button[data-role="agregar"]').on('click', function(e)
	{
		var tipo = $(this).data('rel');
		var id_localidad = $('input[name="id"]').val();
		var id_persona = $('input[name="'+tipo+'-seleccionado"]').val();

		if (id_persona.length > 0)
		{
			$.post(
				URL_LOCALIDADES+'/personal/agregar',
				{
					id: id_localidad,
					id_persona: id_persona,
					tipo: tipo,
				},
				function(data){},
				'json'
			).done(function(e){
				//window.location.reload();
			});
		}
	});
});