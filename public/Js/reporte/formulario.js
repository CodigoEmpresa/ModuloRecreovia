$(function()
{
    var PUNTOS = $('select[name="Id_Cronograma"]').length ? $.parseJSON(JSON.stringify($('select[name="Id_Cronograma"]').data('json'))) : {};

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

    var tbl_sesiones = $('#sesiones').DataTable();

    $('input[name="Dias"]').selectize({
        delimiter: ',',
        persist: true,
        create:  true
    });

    /*
    var selectize_dias = $('input[name="Dias"]')[0].selectize;

    $('input[name="Dia"]').on('change', function(e)
    {
        selectize_dias.addOption({text:$(this).val(), value:$(this).val()});
        selectize_dias.addItem($(this).val());
        $(this).val('');
    });*/

    $('select[name="Id_Punto"]').on('changed.bs.select', function(e)
    {
    	var Id_Punto = $(this).val();
    	var punto = $.grep(PUNTOS, function(o, i)
    	{
    		return o.Id_Punto == Id_Punto;
    	})[0];

    	if(punto.cronogramas)
    	{
    		$('select[name="Id_Cronograma"]').html('');
    		$.each(punto.cronogramas, function(i, cronograma)
    		{
    			$('select[name="Id_Cronograma"]').append('<option data-desde="'+cronograma.Desde+'" data-dias="'+cronograma.jornada.Dias+'" data-hasta="'+cronograma.Hasta+'" value="'+cronograma.Id+'">'+('Del '+cronograma.Desde+' al '+cronograma.Hasta+' / '+cronograma.jornada.Code+' - '+cronograma.jornada.Label)+'</option>');
    		});

            $('select[name="Id_Cronograma"]').selectpicker('refresh');
    	}

        if ($('select[name="Id_Cronograma"]').data('value') != '')
        {
            $('select[name="Id_Cronograma"]').val($('select[name="Id_Cronograma"]').data('value')).trigger('change');
        }
    });

    $('select[name="Id_Cronograma"]').on('change', function(e)
    {
    	/*var option = $('select[name="Id_Cronograma"] option:selected');
    	$('input[name="Dia"]').attr('data-fecha-inicio', '').attr('data-fecha-fin', '');

    	var dias = option.data('dias');
    	var fecha_inicio = moment(option.data('desde'));
    	var fecha_fin = moment(option.data('hasta'));
    	$('input[name="Dia"]').attr('data-fecha-inicio', fecha_inicio.format('YYYY-MM-DD')).attr('data-fecha-fin', fecha_fin.format('YYYY-MM-DD')).attr('data-dias', dias);
        $('input[name="Dia"]').val('');*/

        var punto = $.grep(PUNTOS, function(o, i)
        {
            return o.Id_Punto == $('select[name="Id_Punto"]').val();
        })[0];

        var cronograma = $.grep(punto.cronogramas, function(o, i)
        {
            return o.Id == $('select[name="Id_Cronograma"]').val();
        })[0];


        var sesiones = cronograma.sesiones;
        tbl_sesiones.clear().draw();

        $.each(sesiones, function(i, e){
            tbl_sesiones.row.add($('<tr>'+
                    '<td>'+e.Fecha+'</td>'+
                    '<td>'+e.Objetivo_General+'</td>'+
                    '<td><input type="checkbox" name="sesion[]" value="'+e.Id+'"/></td>'+
               '</tr>')).draw(false);
        });
    });

    $('#check_all').on('click', function(e){
        var state = $(this).is(':checked');
        tbl_sesiones.rows({filter: 'applied'}).every(function (rowIdx, tableLoop, rowLoop) {
            $tr = $(this.node());
            $tr.find('input[type="checkbox"]').prop('checked', state);
        });
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
