var CBActividades = '';
var ActividadesMod = [];

$(function()
{
    var URL = $('#main_tipoPersona').data('url');
    var URL_ACTIVIDADES = $('#main_tipoPersona').data('url-actividades');
    var URL_ACTIVIDADES_PERSONA = $('#main_tipoPersona').data('url-actividades-persona');

	function buscar(e){		
        var key = $('input[name="buscador"]').val(); 
        $('#buscar span').removeClass('glyphicon-search').addClass('glyphicon-refresh spin-r');
        $('#buscador').prop('disabled', true);
        $('#buscar').data('role', 'reset');
        $.get(URL+'/service/buscar/'+key,{}, function(data){
            if(data.length > 0){
                var html = '';
                $.each(data, function(i, e){
                	document.getElementById("resultado").style.display = "block";
                        html +='<div class="list-group-item">'+
                                    '<h5 class="list-group-item-heading">'+
                        			     e['Primer_Apellido'].toUpperCase()+' '+e['Segundo_Apellido'].toUpperCase()+' '+e['Primer_Nombre'].toUpperCase()+' '+e['Segundo_Nombre'].toUpperCase()+
                                    '</h5>'+
                                    '<div class="row">'+
            	                        '<div class="col-xs-12 col-sm-6 col-md-3"><small>Identificación: '+e.tipo_documento['Nombre_TipoDocumento']+' '+e['Cedula']+'</small></div>'+
                                    '</div>'+
                                    '<div class="row">'+
                                        '<div class="col-md-12" id="actividadesCheck'+e.Id_Persona+'">'+

                                        '</div>'+
                                    '</div>'+
                                    '<div class="row">'+
			        					'<div class="col-md-12 form-group">'+
			        						'<button disabled type="button" class="btn btn-sm btn-primary" id="Agregar'+e.Id_Persona+'" onclick="Agregar('+e.Id_Persona+');">Asignar</button>'+
			        					'</div>'+
			        				'</div>'+
                                '</div>'+
                                '<br><br>';
                        actividadesCheck(e.Id_Persona);
                });
                $('#personas').html(html);
                $('#paginador').fadeOut();
            }else{
            	document.getElementById("resultado").style.display = "block";
                $('#buscar span').removeClass('glyphicon-refresh').addClass('glyphicon-remove');
                $('#buscar span').empty();
                document.getElementById("buscar").disabled = false;
                $('#personas').html( '<li class="list-group-item" style="border:0"><div class="row"><h4 class="list-group-item-heading">No se encuentra ninguna persona registrada con estos datos.</h4></dvi><br>');
                $('#paginador').fadeOut();
            }
        },'json').done(function(){
            $("#buscar").prop('disabled', false);
            $('#buscar span').removeClass('glyphicon-search glyphicon-refresh spin-r').addClass('glyphicon-remove');
        });
    }

    function actividadesCheck(id)
    {
        $.get(URL_ACTIVIDADES, function(Stipo){
            CBActividades = '';     
            ActividadesMod = [];
            i=0;
            $.each(Stipo, function(i, e){
                CBActividades +='<div class="checkbox"><label><input type="checkbox" name="CB'+e.Id_Actividad+'" id="CB'+e.Id_Actividad+'" value="'+e.Id_Actividad+'"/>'+e.Nombre_Actividad+'</label></div>';
                ActividadesMod[i] = {'id' : e.Id_Actividad, 'Nombre': e.Nombre_Actividad};
                i=i+1;
            });     
            $('#actividadesCheck'+id).append(CBActividades);            
                    
        }).done(function(){
            $.get(URL_ACTIVIDADES_PERSONA+'/'+id, function(act_Per){
                $.each(act_Per, function(i, e){
                    $("#CB"+e.Id_Actividad).prop('checked', true);
                });
                $("#Agregar"+id).prop('disabled', false);
            });      
        });
    }

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

    if($('#buscador').val() != '')
    {
        $('#buscar').trigger('click');
    }

	function reset(e)
    {
		document.getElementById("resultado").style.display = "none";
        $('input[name="buscador"]').val('');
        $('#buscar span').removeClass('glyphicon-refresh').addClass('glyphicon-search');
        $('#buscar span').empty();
                document.getElementById("buscar").disabled = false;
                document.getElementById("buscador").disabled = false;
        $('#paginador').fadeIn();
    }
});

function ValidaCampo(e)
{
    tecla = (document.all) ? e.keyCode : e.which;
     if (tecla==8) return true;
     patron =/[A-Za-z0-9\s]/;
     te = String.fromCharCode(tecla);
     return patron.test(te);
}

function Agregar(id)
{
    var URL_PROCESO = $('#main_tipoPersona').data('url-proceso');
    var ArrayActividades = [];
    for(i=0;i<ActividadesMod.length;i++){
        nombre = '#CB'+ActividadesMod[i].id;
        if($(nombre).is(":checked") == true){                
            ArrayActividades[(ArrayActividades.length)] = {'id_actividad':ActividadesMod[i].id, 'estado': 1};
        }else{
            ArrayActividades[(ArrayActividades.length)] = {'id_actividad':ActividadesMod[i].id, 'estado': 0};
        }
    }
    var token = $("#token").val();
    var datos = {Datos: ArrayActividades, Id: id}
    $.ajax({
        type: 'POST',
        url: URL_PROCESO,
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        data: datos,
        success: function (xhr) {  
            $("#mensajeIncorrectoB").empty();
            $("#mensaje-incorrectoB").fadeOut();
            $("#mensajecorrectoB").empty();
            $("#mensaje-correctoB").fadeOut();
            if(xhr.Bandera == 1){//OK
                $("#Id_Tipo"+id).css({ 'border-color': '#B94A48' });    
                $("#mensajecorrectoB").html(xhr.Mensaje);
                $("#mensaje-correctoB").fadeIn();
                $('#mensaje-correctoB').focus();            
                return false;
            }else{
                $("#Id_Tipo"+id).css({ 'border-color': '#CCCCCC' });    
                $("#mensajeIncorrectoB").html(xhr.Mensaje);
                $("#mensaje-incorrectoB").fadeIn();
                $('#mensaje-incorrectoB').focus();            
            }
        }
    });
}