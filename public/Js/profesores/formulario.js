$(function()
{
    var URL = $('#main').data('url');
    var URL_PROFESORES = $('#main').data('url-profesores');

    function buscar(key)
    {
        $.get(
            URL+'/service/buscar/'+key,
            {}, 
            function(data)
            {
                if(data.length == 1) 
                {
                    window.location.href = URL_PROFESORES+'/'+data[0]['Id_Persona']+'/editar';
                }
            },
            'json'
        );
    }

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
    $('input[name="Cedula"]').on('blur', function(e)
    {
        buscar($(this).val());
    });
});