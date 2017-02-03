$(function()
{
    var PUNTOS = $.parseJSON(JSON.stringify($('select[name="Id_Cronograma"]').data('json')));

    console.log(PUNTOS);
    
    var URL = $('#main').data('url');

    var reindexarServicios = function()
    {
        $('#servicios tbody tr[id != "plantilla_servicio"]').each(function(i, tr)
        {
            $(tr).find('input, select').each(function(i2, formElement)
            {
                $(formElement).attr('name', $(formElement).data('name')+'_'+i);
            });
            
            $(tr).find('td[data-role="item"]').text(i+1);
        });

        $('input[name="Total_Servicios"]').val($('#servicios tbody tr[id != "plantilla_servicio"]').length);
    }

    $('#participaciones').DataTable({
        paging: false
    });

    $('#asistencias').DataTable({
        paging: false
    });

    $('select[name="Id_Punto"]').on('change', function(e)
    {
    	var Id_Punto = $(this).val();
    	var punto = $.grep(PUNTOS, function(o, i)
    	{
    		return o.Id_Punto == Id_Punto;
    	})[0];

    	if(punto.cronogramas.length) 
    	{
    		$('select[name="Id_Cronograma"]').html('<option value="">Seleccionar</option>');
    		$.each(punto.cronogramas, function(i, cronograma)
    		{
    			$('select[name="Id_Cronograma"]').append('<option data-desde="'+cronograma.Desde+'" data-dias="'+cronograma.jornada.Dias+'" data-hasta="'+cronograma.Hasta+'" value="'+cronograma.Id+'">'+('Del '+cronograma.Desde+' al '+cronograma.Hasta+' / '+cronograma.jornada.Label)+'</option>');
    		});
    	}

        if ($('select[name="Id_Cronograma"]').data('value') != '')
        {
            $('select[name="Id_Cronograma"]').val($('select[name="Id_Cronograma"]').data('value')).trigger('change');
        }
    });

    $('select[name="Id_Cronograma"]').on('change', function(e)
    {
    	var option = $('select[name="Id_Cronograma"] option:selected');
    	$('input[name="Dia"]').attr('data-fecha-inicio', '').attr('data-fecha-fin', '');

    	var dias = option.data('dias');
    	var fecha_inicio = moment(option.data('desde'));
    	var fecha_fin = moment(option.data('hasta'));
    	$('input[name="Dia"]').attr('data-fecha-inicio', fecha_inicio.format('YYYY-MM-DD')).attr('data-fecha-fin', fecha_fin.format('YYYY-MM-DD')).attr('data-dias', dias);
    });

    if ($('select[name="Id_Punto"]').data('value') != '')
    {
       $('select[name="Id_Punto"]').val($('select[name="Id_Punto"]').data('value')).trigger('change');
    }

    $('#actualizar_reporte').on('click', function(e)
    {
        $('#formularios_complementarios form').each(function(i, e)
        {
            var data = $(this).serialize();
            $.post(
                URL+'/actualizar', 
                data,
                function(data){},
                'json'
            ).done(function(e)
            {
                $('#alerta_ajax_ok').fadeIn();
                $('html, body').animate({
                    scrollTop: 10
                }, 100);
            });
        });
    });

    $('#agregar_servicio').on('click', function(e)
    {
        var $plantilla = $('#plantilla_servicio').clone(true, true);
        $plantilla.removeAttr('id');

        $('#servicios').append($plantilla);
        $plantilla.fadeIn();
        reindexarServicios();
    });

    $('table#servicios').delegate('a[data-role="eliminar"]', 'click', function(e)
    {
        $(this).closest('tr').remove();
        reindexarServicios();
        e.preventDefault();
    });
});