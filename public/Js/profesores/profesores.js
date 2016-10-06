$(function()
{
    var URL = $('#main_persona').data('url');
    var URL_PROFESORES = $('#main_persona').data('url-profesores');
    var $personas_actuales = $('#personas').html();

    function validarCampo(e){
    	var	code = (document.all) ? e.keyCode : e.which;
   		if (code == 8) return true;
     	var key = String.fromCharCode(code);
    	return /[A-Za-z0-9\s]/.test(key);
    }

    function reset(e){
        $('input[name="buscador"]').val('');
        $('#buscar span').removeClass('glyphicon-refresh spin-r').addClass('glyphicon-search');
        $('#buscar span').empty();
		$("#buscar").prop('disabled', false);
		$("#buscador").prop('disabled', false);
        $('#personas').html($personas_actuales);
        $('#paginador').fadeIn();
    }

    function buscar(e){
        var key = $('input[name="buscador"]').val();
        $('#buscar span').removeClass('glyphicon-search').addClass('glyphicon-refresh spin-r');
       	$("#buscador").prop('disabled', true);
        $('#buscar').data('role', 'reset');
        $.get(URL_PROFESORES+'/service/buscar/'+key, {}, function(data){
            if(data.length > 0){
                var html = '';
                $.each(data, function(i, e){
                    html += '<li class="list-group-item">'+
                            '<h5 class="list-group-item-heading">'+
                                ''+e['Primer_Apellido'].toUpperCase()+' '+e['Segundo_Apellido'].toUpperCase()+' '+e['Primer_Nombre'].toUpperCase()+' '+e['Segundo_Nombre'].toUpperCase()+''+
                                '<a data-role="editar" data-rel="'+e['Id_Persona']+'" class="pull-right btn btn-primary btn-xs">'+
                                    '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>'+
                                '</a>'+
                            '</h5>'+
                            '<p class="list-group-item-text">'+
                                '<div class="row">'+
                                    '<div class="col-xs-12">'+
                                        '<div class="row">'+
                                            '<div class="col-xs-12 col-sm-6 col-md-3"><small>Identificación: '+e.tipo_documento['Nombre_TipoDocumento']+' '+e['Cedula']+'</small></div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</p>'+
                        '</li>';
                });
                $('#personas').html(html);
            }else{
                $('#personas').html( '<li class="list-group-item" style="border:0"><div class="row"><h4 class="list-group-item-heading">No se encuentra ninguna persona registrada con estos datos.</h4></dvi><br>');
                
            }
        },'json').done(function()
        {
        	$('#paginador').fadeOut();
        	$("#buscar").prop('disabled', false);
        	$('#buscar span').removeClass('glyphicon-search glyphicon-refresh spin-r').addClass('glyphicon-remove');
        });
    }
    
    function popular_ciudades(id){
        $.ajax({
            url: URL+'/service/ciudad/'+id,
            data: {},
            dataType: 'json',
            success: function(data){
                var html = '<option value="">Seleccionar</option>';
                if(data.length > 0){
                    $.each(data, function(i, e){
                        html += '<option value="'+e['Nombre_Ciudad']+'">'+e['Nombre_Ciudad']+'</option>';
                    });
                }
                $('select[name="Nombre_Ciudad"]').html(html).val($('select[name="Nombre_Ciudad"]').data('value'));
            }
        });
    }

    function popular_modal_persona(persona){
        $('select[name="Id_TipoDocumento"]').val(persona['Id_TipoDocumento']);
        $('input[name="Cedula"]').val($.trim(persona['Cedula']));
        $('input[name="Primer_Apellido"]').val($.trim(persona['Primer_Apellido']));
        $('input[name="Segundo_Apellido"]').val(persona['Segundo_Apellido']);
        $('input[name="Primer_Nombre"]').val($.trim(persona['Primer_Nombre']));
        $('input[name="Segundo_Nombre"]').val($.trim(persona['Segundo_Nombre']));
        $('input[name="Fecha_Nacimiento"]').val($.trim(persona['Fecha_Nacimiento']));
        $('select[name="Id_Etnia"]').val(persona['Id_Etnia']);
        $('select[name="Nombre_Ciudad"]').data('value', persona['Nombre_Ciudad']);
        $('select[name="Id_Pais"]').val(persona['Id_Pais']).trigger('change');
        $('input[name="Id_Persona"]').val(persona['Id_Persona']);
        
        if(persona.zonas.length)
        {
        	$('select[name="Id_Zona"]').val(persona.zonas[0]['Id_Zona']);
        	$('input[name="tipo"][value="'+persona.zonas[0].pivot['tipo']+'"]').prop('checked', true);
        } else {
        	$('select[name="Id_Zona"]').val('');
        	$('input[name="tipo"]').prop('checked', false);
        }

        $('input[name="Id_Genero"]').removeAttr('checked').parent('.btn').removeClass('active');
        $('input[name="Id_Genero"][value="'+persona['Id_Genero']+'"]').trigger('click');

        $('#modal_form_persona').modal('show');
        $("#crear").button('reset');
    }

    function popular_errores_modal(data){
        $('#form_persona .form-group').removeClass('has-error');
        var selector = '';
        for (var error in data){
            if (typeof data[error] !== 'function') {
                switch(error){
                    case 'tipoDocumento':
                    case 'Id_Etnia':
                    case 'Id_Pais':
                    case 'Id_Zona':
                            selector = 'select';
                    break;

                    case 'Cedula':
                    case 'Primer_Apellido':
                    case 'Primer_Nombre':
                    case 'Fecha_Nacimiento':
                    case 'Id_Genero':
                    case 'tipo':
                            selector = 'input';
                    break;
                }
                $('#form_persona '+selector+'[name="'+error+'"]').closest('.form-group').addClass('has-error');
            }
        }
        $("#guardar").button('reset');
    }

    //Eventos
    $('input[name="buscador"]').on('keyup', function(e){
        var code = e.which; //http://stackoverflow.com/questions/3462995/jquery-keydown-keypress-keyup-enterkey-detection
        if(code==13) buscar(e);
    });

    $('input[name="buscador"]').on('keypress', validarCampo);

    $('#buscar').on('click', function(e){  
        var key = $('input[name="buscador"]').val();
        if(!key && $(this).data('role') == 'buscar')
        {
            $("#buscador").closest('.form-group').addClass('has-error');  
            return false;
        }

        var role = $(this).data('role');
        $("#buscar").prop('disabled', true);

        switch(role){
            case 'buscar':
                $(this).data('role', 'reset');
                buscar(e);          
            break;
            case 'reset':                 
                $('#buscar span').removeClass('glyphicon-remove');
                $(this).data('role', 'buscar');
                reset(e);
            break;
        }
    });

    $('#crear').on('click', function(e){
        $("#crear").button('loading');
        var persona = {
            Id_TipoDocumento: '',
            Cedula: '',
            Primer_Apellido: '',
            Segundo_Apellido: '',
            Primer_Nombre: '',
            Segundo_Nombre: '',
            Fecha_Nacimiento: '',
            Id_Etnia: '',
            Id_Pais: 41,
            Nombre_Ciudad: '',
            Id_Persona: 0,
            Id_Genero: 0,
            zonas: [{
            	Id_Zona: 0,
            	pivot: {
                    tipo: ''
                }
            }]
        }
        popular_modal_persona(persona);
    });

    $('#personas').delegate('a[data-role="editar"]', 'click', function(e){
        var id = $(this).data('rel');
        $.get(URL_PROFESORES+'/service/obtener/'+id,{},function(data){	
            if(data)
            {
                popular_modal_persona(data);
            }
        },'json');
    });

    $('#personas').delegate('a[data-role="remover"]', 'click', function(e){
        var id = $(this).data('rel');
    });

    $('select[name="Id_Pais"]').on('change', function(e){
        popular_ciudades($(this).val());
    });

    //Submit formulario único de personas
    $('#form_persona input[name="Cedula"]').on('blur', function(e){
        var key = $(this).val();
        $.get(URL_PROFESORES+'/service/buscar/'+key,{}, function(data){
            if(data.length == 1)
            {
                popular_modal_persona(data[0]);
            }
        });
    });

    $('#form_persona').on('submit', function(e){
        $("#guardar").button('loading');
        $.post(URL_PROFESORES+'/service/procesar',$(this).serialize(),function(data){
            if(data.status == 'error')
            {
                popular_errores_modal(data.errors);
            } else {
                $('#alerta').show();
                $('#modal_form_persona').modal('hide');
                $("#guardar").button('reset');

                setTimeout(function(){
                    $('#alerta').hide();
                }, 4000)
            }
        },'json');
        e.preventDefault();
    });
});