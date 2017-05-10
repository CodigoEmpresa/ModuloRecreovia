$(function()
{
    $('select[name="Jornada"]').on('change', function(e)
    {
        var $option = $(this).find('option:selected');

        switch($option.data('tipo'))
        {
            case 'Periodico':
                $('input[name="Fecha_Evento_Inicio"]').datepicker('option', 'disabled', true);
                $('input[name="Fecha_Evento_Fin"]').datepicker('option', 'disabled', true);
                $('input[name="Fecha_Evento_Inicio"]').val('');
                $('input[name="Fecha_Evento_Fin"]').val('');
            break;
            case 'Eventual':
                $('input[name="Fecha_Evento_Inicio"]').datepicker('option', 'disabled', false);
                $('input[name="Fecha_Evento_Fin"]').datepicker('option', 'disabled', false);
            break;
        }
        $('input[name="Tipo"]').val($option.data('tipo'));
    });

    if($('select[name="Jornada"]').data('value') != '')
        $('select[name="Jornada"]').trigger('change');

    $('#puntos').delegate('input[type="checkbox"]', 'click', function(e)
    {
        var puntos = ($('input[name="puntos"]').val()).split(',');

        if($(this).is(':checked'))
        {
            puntos.push($(this).val());
        } else {
            if (puntos.indexOf($(this).val()) !== -1)
                puntos.splice(puntos.indexOf($(this).val()), 1);
        }

        puntos = puntos.filter(Boolean);

        $('input[name="puntos"]').val(puntos.join());
    });
});