$(function()
{
    var URL_PROFESORES = $('#main').data('url');
    var $personas_actuales = $('#personas').html();

    function validarCampo(e)
    {
    	var	code = (document.all) ? e.keyCode : e.which;
   		if (code == 8) return true;
     	var key = String.fromCharCode(code);
    	return /[A-Za-z0-9\s]/.test(key);
    }

    function reset(e)
    {
        $('input[name="buscador"]').val('');
        $('#buscar span').removeClass('glyphicon-refresh spin-r').addClass('glyphicon-search');
        $('#buscar span').empty();
		$("#buscar").prop('disabled', false);
		$("#buscador").prop('disabled', false);
        $('#personas').html($personas_actuales);
        $('#paginador').fadeIn();
    }

    function buscar(e)
    {
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
                                '<a href="'+URL_PROFESORES+'/'+e['Id_Persona']+'/editar" class="pull-right btn btn-primary btn-xs">'+
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
                            '<span class="label label-default capitalize">'+(e.recreopersona ? e.recreopersona['contrato']: 'Tipo no asignado')+'</span> '+
                            '<span class="label label-default capitalize">'+(e.recreopersona ? e.recreopersona['correo']: 'Correo no asignado')+'</span> '+
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
    
    //Eventos
    $('input[name="buscador"]').on('keyup', function(e)
    {
        var code = e.which; //http://stackoverflow.com/questions/3462995/jquery-keydown-keypress-keyup-enterkey-detection
        if(code==13) buscar(e);
    });

    $('input[name="buscador"]').on('keypress', validarCampo);

    $('#buscar').on('click', function(e)
    {  
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
});