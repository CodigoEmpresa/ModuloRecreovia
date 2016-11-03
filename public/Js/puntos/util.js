$(function()
{
	var URL = $('#main').data('url');
    var UPZ = $.parseJSON(JSON.stringify($('select[name="Id_Upz"]').data('json')));
    var jornadas = {};
    var jornada_actual = -1;
	
    function popular_errores_modal(data){
        $('#form_persona .form-group').removeClass('has-error');
        var selector = '';
        for (var error in data){
            if (typeof data[error] !== 'function') {
                switch(error)
                {
                    case 'Id_Upz':
                    case 'Id_Zona':
                    case 'Id_Localidad':
                            selector = 'select';
                    break;

                    case 'Direccion':
                    case 'Escenario':
                    case 'Cod_IDRD':
                    case 'Cod_Recreovia':
                            selector = 'input';
                    break;
                }
                $('#form-principal '+selector+'[name="'+error+'"]').closest('.form-group').addClass('has-error');
            }
        }
        $("#guardar").button('reset');
    };

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
            console.log('input[name^="'+key.toLowerCase()+'"]', val);
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
        $('select[name="Id_Zona"]').val($.trim(obj['Id_Zona']));
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

	$('#crear').on('click', function(e)
    {
        $(this).button('loading');
        var obj = {
            Direccion: '',
            Escenario: '',
            Cod_IDRD: '',
            Cod_Recreovia: '',
            Id_Zona: '',
            Id_Localidad: '',
            Id_Upz: '',
            Id_Punto: 0,
            jornadas: []
        };
        popular_modal(obj);
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

    $('#principal').delegate('a[data-role="editar"]', 'click', function(e)
    {
        var id = $(this).data('rel');
        $.get(URL+'/service/obtener/'+id,{},function(data){  
            if(data)
            {
                popular_modal(data);
            }
        },'json');
    });

    $('#form-principal').on('submit', function(e)
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
    });
});