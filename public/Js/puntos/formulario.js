$(function()
{
    var URL = $('#main').data('url');
    var URL_PARQUES = $('#main').data('url-parques');
    var UPZ = $.parseJSON(JSON.stringify($('select[name="Id_Upz"]').data('json')));
    var jornadas = {};
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
        $('#table-jornadas tr').each(function(i, e)
        {
            $(this).attr('data-row', i).find('td.index').text(i);
        });
    };

    function validar_jornada(obj)
    {
        var valido = true;
        $.each(obj, function(key, val)
        {
            if($.trim(val) == '' || typeof val == 'undefined')
            {
                $('input[name^="'+key.toLowerCase()+'"]').closest('.form-group').addClass('has-error');
                valido = false;
            } else {
                $('input[name^="'+key.toLowerCase()+'"]').closest('.form-group').removeClass('has-error');
            }
        });

        return valido;
    }

    function popular_jornada(obj)
    {
        var dias = obj.Dias.split(',');
        $('input[name="Id_Jornada"]').val(obj.Id_Jornada);
        $('input[name="jornada"]').prop('checked', false);
        $('input[name="jornada"][value="'+obj.Jornada+'"]').prop('checked', true);
        $('input[name="inicio"]').val(obj.Inicio);
        $('input[name="fin"]').val(obj.Fin);
        $('input[name="dias[]"]').map(function()
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

    function popular_modal(obj)
    {
        $('input[name="Direccion"]').val($.trim(obj['Direccion']));
        $('input[name="Escenario"]').val($.trim(obj['Escenario']));
        $('input[name="Cod_IDRD"]').val($.trim(obj['Cod_IDRD']));
        $('input[name="Cod_Recreovia"]').val($.trim(obj['Cod_Recreovia']));
        $.when($('select[name="Id_Localidad"]').val($.trim(obj['Id_Localidad'])).trigger('change')).done(
            function(){
                $('select[name="Id_Upz"]').val($.trim(obj['Id_Upz']));
                $('input[name="Id_Punto"]').val($.trim(obj['Id_Punto']));
            });
        jornadas = $.parseJSON(JSON.stringify(obj['jornadas']));
        $('#table-jornadas tbody').html('');
        $.each(jornadas, function(i, e)
        {
            var tr = $('<tr data-row="0" data-jornada=""><td class="index">0</td><td>'+e.Dias+'</td><td>'+e.Jornada+' de '+e.Inicio+' a '+e.Fin+'</td><td><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td></tr>');
            tr.data('jornada', e);
            $('#table-jornadas tbody').append(tr);
        });
        reindexar_tabla_jornadas();
        $('#modal-principal').modal('show');
        $('#crear').button('reset');
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

    $("#guardar-jornada").on('click', function(e)
    {
        var jornada = {
            Id_Jornada: $('input[name="Id_Jornada"]').val(),
            Id_Punto: $('input[name="Id_Punto"]').val(),
            Jornada: $('input[name="jornada"]:checked').val(),
            Dias: $('input[name="dias[]"]:checked').map(function()
            {
                return $(this).val();
            }).get().join(','),
            Inicio: $('input[name="inicio"]').val(),
            Fin: $('input[name="fin"]').val()
        };

        if(validar_jornada(jornada))
        {
            if(jornada_actual == -1)
            {
                var tr = $('<tr data-row="0" data-jornada=""><td class="index">0</td><td> </td><td> </td><td><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td></tr>');
                $('#table-jornadas tbody').append(tr);
            } else {
                var tr = $('#table-jornadas tbody').find('tr[data-row="'+jornada_actual+'"]');
            }
            
            tr.data('jornada', jornada);
            tr.children('td').eq(1).text(jornada.Dias);
            tr.children('td').eq(2).text(jornada.Jornada+' de '+jornada.Inicio+' a '+jornada.Fin);

            $('#form-jornadas').hide();
            reindexar_tabla_jornadas();
        }
    });

    $('#eliminar-jornada').on('click', function(e){
        var tr = $('#table-jornadas tbody').find('tr[data-row="'+jornada_actual+'"]');
        tr.remove();

        $('#form-jornadas').hide();
    });

    $("#cancelar-jornada").on('click', function(e)
    {
        var jornada = {
            Id_Jornada: 0,
            Id_Punto: '',
            Jornada: '',
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

    /*$('#form-principal').on('submit', function(e)
    {
        var jornadas_actualizadas = [];
        $('#guardar').button('loading');

        $('#table-jornadas tbody tr').each(function(i, e)
        {
            jornadas_actualizadas.push($(e).data('jornada'))
        });

        var form_data = $(this).serializeArray();
        form_data.push({name: 'Jornadas', value:  JSON.stringify(jornadas_actualizadas)});

        $.post(URL+'/service/procesar', form_data, 'json')
        .done(function(msg) {
            $('#alerta').show();
            $('#modal-principal').modal('hide');
            $("#guardar").button('reset');

            setTimeout(function(){
                $('#alerta').hide();
                location.reload();
            }, 500);
        })
        .fail(function(xhr, status, error) {
            popular_errores_modal(xhr.responseJSON);
        });
        e.preventDefault();
    });*/
});