$(function()
{
    var puntos = $('select[name="punto"] option');

    $('select[name="localidad"]').on('changed.bs.select', function(event, clickedIndex, newValue, oldValue) {
        var localidad = $('select[name="localidad"]').selectpicker('val');

        $('select[name="punto"] option').remove();

        if (localidad === 'Todos') {
            $.each(puntos, function(i, punto)
            {
                $('select[name="punto"]').append($(punto).prop('outerHTML'));
            });
        } else {
            $.each(puntos, function(i, punto)
            {
                if($(punto).data('localidad') === parseInt(localidad))
                    $('select[name="punto"]').append($(punto).prop('outerHTML'));
            });
        }

        $('select[name="punto"]').selectpicker('refresh');

        if ($('select[name="punto"]').data('value') !== '') {
            $('select[name="punto"]').selectpicker('val', $('select[name="punto"]').data('value'));
        }
    });

    if ($('select[name="localidad"]').data('value') !== '') {
        $('select[name="localidad"]').val($('select[name="localidad"]').data('value'));
        $('select[name="localidad"]').trigger('change');
    }
});