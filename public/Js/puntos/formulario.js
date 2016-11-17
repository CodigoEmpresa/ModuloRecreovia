$(function()
{
    var URL = $('#main').data('url');
    var URL_PARQUES = $('#main').data('url-parques');
    var UPZ = $.parseJSON(JSON.stringify($('select[name="Id_Upz"]').data('json')));
    var jornadas = $('input[name="Jornadas"]').val() == '' ? {} : $.parseJSON($('input[name="Jornadas"]').val());
    var jornada_actual = -1;
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

    function reindexar_tabla_jornadas()
    {
        var jornadas_actualizadas = [];

        $('#table-jornadas tr').each(function(i, e)
        {
            $(this).attr('data-row', i).find('td.index').text(i);
            if($(e).data('jornada'))
                jornadas_actualizadas.push($(e).data('jornada'));
        });

        $('input[name="Jornadas"]').val(JSON.stringify(jornadas_actualizadas));
    };

    function validar_jornada(obj)
    {
        var valido = true;
        $('#form-jornadas .form-group').removeClass('has-error');

        $.each(obj, function(key, val)
        {
            if($.trim(val) == '' || typeof val == 'undefined')
            {
                $('*[name^="'+key+'"]').closest('.form-group').addClass('has-error');
                valido = false;
            }
        });

        return valido;
    };

    function popular_jornada(obj)
    {
        var dias = obj.Dias.split(',');
        $('input[name="Id_Jornada"]').val(obj.Id_Jornada);
        $('input[name="Tipo"]').val(obj.Tipo);
        $('select[name="Jornada"]').val(obj.Jornada).trigger('change');
        $('input[name="Fecha_Evento"]').val(obj.Fecha_Evento);
        $('input[name="Inicio"]').val(obj.Inicio);
        $('input[name="Fin"]').val(obj.Fin);
        $('input[name="Dias[]"]').map(function()
        {
            if($.inArray($(this).attr('value'), dias) > -1)
                $(this).prop('checked', true);
            else
                $(this).prop('checked', false);
        });

        if(jornada_actual > 0)
            $('#eliminar-jornada').show();
        else
            $('#eliminar-jornada').hide();

        $('#form-jornadas').show();
    };

    function registrar_jornada(jornada)
    {
        if(jornada_actual == -1)
        {
            var tr = $('<tr data-row="0" data-jornada=""><td class="index">0</td><td> </td><td><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td></tr>');
            $('#table-jornadas tbody').append(tr);
        } else {
            var tr = $('#table-jornadas tbody').find('tr[data-row="'+jornada_actual+'"]');
        }

        var label = '';

        switch(jornada.Jornada)
        {
            case 'dia': 
                label = 'Jornada diurna los dias '+jornada.Dias+' de '+jornada.Inicio+' a '+jornada.Fin;
            break;
            case 'noche': 
                label = 'Jornada nocturna los dias '+jornada.Dias+' de '+jornada.Inicio+' a '+jornada.Fin;
            break;
            case 'fds': 
                label = 'Jornada de fin de semana los dias '+jornada.Dias+' de '+jornada.Inicio+' a '+jornada.Fin;
            break;
            case 'clases_grupales': 
                label = 'Clase grupal el dia '+jornada.Fecha_Evento+' de '+jornada.Inicio+' a '+jornada.Fin;
            break;
            case 'mega_eventos': 
                label = 'Mega evento de actividad fisica el dia '+jornada.Fecha_Evento+' de '+jornada.Inicio+' a '+jornada.Fin;
            break;
        }
        
        tr.data('jornada', jornada);
        tr.children('td').eq(1).text(label);
        reindexar_tabla_jornadas();
    }

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

    $('select[name="Jornada"]').on('change', function(e)
    {
        var $option = $(this).find('option:selected');

        switch($option.data('tipo'))
        {
            case 'Periodico':
                $('input[name="Fecha_Evento"]').datepicker('option', 'disabled', true);
                $('input[name="Fecha_Evento"]').val('');
                $('input[name="Dias[]"]').closest('.form-group').show();
            break;
            case 'Eventual':
                $('input[name="Fecha_Evento"]').datepicker('option', 'disabled', false);
                $('input[name="Dias[]"]').prop('checked', false).closest('.form-group').hide();
            break;
        }
        $('input[name="Tipo"]').val($option.data('tipo'));
    });

    $("#guardar-jornada").on('click', function(e)
    {
        var jornada = {
            Id_Jornada: $('input[name="Id_Jornada"]').val(),
            Id_Punto: $('input[name="Id_Punto"]').val(),
            Jornada: $('select[name="Jornada"]').val(),
            Tipo: $('input[name="Tipo"]').val(),
            Fecha_Evento: $('input[name="Fecha_Evento"]').val(),
            Dias: $('input[name="Dias[]"]:checked').map(function()
            {
                return $(this).val();
            }).get().join(','),
            Inicio: $('input[name="Inicio"]').val(),
            Fin: $('input[name="Fin"]').val()
        };

        var temp = $.extend({}, jornada);

        switch($('input[name="Tipo"]').val())
        {
            case 'Periodico': 
                delete temp.Fecha_Evento;
            break;
            case 'Eventual':
                delete temp.Dias;
            break;
        }

        if(validar_jornada(temp))
        {
            registrar_jornada(jornada);
            $('#form-jornadas').hide();
        }
    });

    $('#eliminar-jornada').on('click', function(e){
        var tr = $('#table-jornadas tbody').find('tr[data-row="'+jornada_actual+'"]');
        tr.remove();

        $('#form-jornadas').hide();
        reindexar_tabla_jornadas();
    });

    $("#cancelar-jornada").on('click', function(e)
    {
        var jornada = {
            Id_Jornada: 0,
            Id_Punto: '',
            Jornada: '',
            Tipo: '',
            Fecha_Evento: '',
            Dias: '',
            Inicio: '',
            Fin: ''
        };

        popular_jornada(jornada);
        $('#form-jornadas').hide();
    });

    $('#agregar-jornada').on('click', function(e)
    {
        
        var jornada = {
            Id_Jornada: 0,
            Id_Punto: '',
            Jornada: '',
            Tipo: '',
            Fecha_Evento: '',
            Dias: '',
            Inicio: '',
            Fin: ''
        };

        jornada_actual = -1;
        popular_jornada(jornada);
        e.preventDefault();
    });

    $("#table-jornadas").delegate('a', 'click', function(e)
    {
        var $tr = $(this).closest('tr');
        var jornada = $.parseJSON(JSON.stringify($tr.data('jornada')));
        jornada_actual = $tr.data('row');
        popular_jornada(jornada);
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
                    console.log(data);
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

    $.each(jornadas, function(i, e){
        registrar_jornada(e);
    });
});