$(function()
{
    var URL = $('#main').data('url');
    var URL_PARQUES = $('#main').data('url-parques');
    var UPZ = $.parseJSON(JSON.stringify($('select[name="Id_Upz"]').data('json')));
    var latitud = $('input[name="Latitud"]').val() ? parseFloat($('input[name="Latitud"]').val()) : 4.666575;
    var longitud = $('input[name="Longitud"]').val() ? parseFloat($('input[name="Longitud"]').val()) : -74.125786;
    var zoom = $('input[name="Id_Punto"]').val() == '0' ? 11 : 13;

    function actualizarPosicion(e)
    {
        $('input[name="Latitud"]').val(e.latLng.lat());
        $('input[name="Longitud"]').val(e.latLng.lng());
    }

    function toggleBounce() 
    {
        if (marker.getAnimation() !== null) 
        {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }

    function registrar_jornadas(jornadas)
    {
        var temp = $('input[name="Jornadas"]').val();
        temp = temp.endsWith(',') ? temp.slice(0, -1) : temp;
        var jornadas_punto = temp.split(',');

        if (jornadas_punto.length > 0)
        {
            $.each(jornadas_punto, function(i, e)
            {

                if(e && !$('#table-jornadas tr[data-id="'+e+'"]').length)
                {
                    var option = $('select[name="select-jornadas"] option[value="'+e+'"]');
                    $('#table-jornadas').append('<tr data-id="'+e+'"><td class="index"></td><td>'+option.text()+'</td><td><a href="#" class="btn btn-link" data-role="eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td></tr>');
                    option.remove();
                }
            });
        }

        $('#table-jornadas tr').each(function(i, e)
        {
            $(this).find('td.index').text(i);
        });
    };

    $('select[name="Id_Localidad"]').on('change', function(e)
    {
        var localidad = $(this).val();
        var upz_localidad = $.grep(UPZ, function(o, i){
                    return o.IdLocalidad == localidad;
                });

        if (upz_localidad.length > 0)
        {
            $('select[name="Id_Upz"]').html('<option value="">Seleccionar</option>');
            $.each(upz_localidad, function(i, e)
            {
                $('select[name="Id_Upz"]').append('<option value="'+e.Id_Upz+'">'+e.Upz+'</option>');
            });
        }
    });

    $('#agregar-jornada').on('click', function(e)
    {
        var id = $('select[name="select-jornadas"]').val();
        if (id)
            $('input[name="Jornadas"]').val($('input[name="Jornadas"]').val()+id+',');

        registrar_jornadas();
        $('select[name="select-jornadas"]').focus();
    });
    
    $("#table-jornadas").delegate('a', 'click', function(e)
    {
        var $tr = $(this).closest('tr');
        $('select[name="select-jornadas"]').append('<option value="'+$tr.data('id')+'">'+$tr.find('td').eq(1).text()+'</option>');
        
        var temp = $('input[name="Jornadas"]').val();
        temp = temp.endsWith(',') ? temp.slice(0, -1) : temp;
        var jornadas_punto = temp.split(',');
        
        temp = '';
        $.each(jornadas_punto, function(i, e)
        {
            if(e != $tr.data('id'))
                temp += e+',';
        });

        $('input[name="Jornadas"]').val(temp);
        $tr.remove();
        e.preventDefault();
    });

    $('input[name="Cod_IDRD"]').on('blur', function(e)
    {
        var key = $(this).val();

        if (key)
        {
            $.get(
                URL_PARQUES+'/service/buscar/'+$(this).val(),
                {},
                function(data)
                {
                    if(data)
                    {
                        $('input[name="Direccion"]').val(data[0].Direccion);
                        $('input[name="Escenario"]').val(data[0].Nombre);
                        $.when($('select[name="Id_Localidad"]').val(data[0].Id_Localidad).trigger('change')).done(function(){
                            $('select[name="Id_Upz"]').val(data[0].upz['Id_Upz']);
                        });
                    }
                },
                'json'
            )
        }
    });

    var map = new google.maps.Map($("#map").get(0), {
      center: {lat: latitud, lng: longitud},
      zoom: zoom
    });

    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        position: {lat: latitud, lng: longitud}
    });
    
    marker.addListener('click', toggleBounce);

    marker.addListener('dragend', actualizarPosicion);

    registrar_jornadas();
});