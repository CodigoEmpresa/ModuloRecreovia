$(function()
{
    var URL = $('#main').data('url');

    function popular_ciudades(id)
    {
        $.ajax({
            url: URL+'/service/ciudad/'+id,
            data: {},
            dataType: 'json',
            success: function(data)
            {
                var html = '<option value="">Seleccionar</option>';
                if(data.length > 0)
                {
                    $.each(data, function(i, e)
                    {
                        html += '<option value="'+e['Nombre_Ciudad']+'">'+e['Nombre_Ciudad']+'</option>';
                    });
                }
                $('select[name="Nombre_Ciudad"]').html(html).val($('select[name="Nombre_Ciudad"]').data('value'));
            }
        });
    };

    $('select[name="Id_Pais"]').on('change', function(e)
    {
        if($(this).val() != '')
            popular_ciudades($(this).val());
    });

    $('select[name="Id_Pais"]').trigger('change');
});