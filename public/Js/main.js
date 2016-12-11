$(function()
{
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


	//utilodades para datepickers
    function getDate(value) {
    	var date;
		try {
			date = $.datepicker.parseDate('yy-mm-dd', element.value);
		} catch( error ) {
    		date = null;
  		}
      	return date;
    }
	
	$.datepicker.regional['es'] = {
 		closeText: 'Cerrar',
		prevText: '<Ant',
		nextText: 'Sig>',
		currentText: 'Hoy',
		monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
 	};

 	$.datepicker.setDefaults($.datepicker.regional['es']);

    $('input[data-role="datepicker"]').each(function(i, e)
    {
    	var dias = $(this).data('dias') ? $(this).data('dias') : '';

    	$(this).datepicker({
		  	dateFormat: 'yy-mm-dd',
		  	yearRange: "-80:+20",
		  	changeMonth: true,
		  	changeYear: true,
		  	beforeShowDay: function(date)
		  	{
		  		var day = date.getDay();
		  		if(dias)
		  		{
		  			var dias_habiles = [];
		  			var res = dias.split(',');

		  			$.each(res, function(i, d)
		  			{
		  				switch(d)
		  				{
		  					case 'lunes':
		  						dias_habiles.push(1);
		  					break;
		  					case 'martes':
		  						dias_habiles.push(2);
		  					break;
		  					case 'miercoles':
		  						dias_habiles.push(3);
		  					break;
		  					case 'jueves':
		  						dias_habiles.push(4);
		  					break;
		  					case 'viernes':
		  						dias_habiles.push(5);
		  					break;
		  					case 'sabado':
		  						dias_habiles.push(6);
		  					break;
		  					case 'domingo':
		  						dias_habiles.push(7);
		  					break;
		  				}
		  			});

		  			if ($.inArray(day, dias_habiles) != -1)
		  				return [true, ""];
		  			else
		  				return [false, ""];
		  		}

		  		return [true, ""];
		  	}
		});
    });

    if ($('input[data-fecha-inicio]').length)
		$('input[data-fecha-inicio]').datepicker('option', 'minDate', $('input[data-fecha-inicio]').data('fecha-inicio'));
    if ($('input[data-fecha-fin]').length)
		$('input[data-fecha-fin]').datepicker('option', 'maxDate', $('input[data-fecha-fin]').data('fecha-fin'));

	$('input[data-rel="fecha_inicio"]').on('change', function(e)
	{
		$('input[data-rel="fecha_fin"]').datepicker('option', 'minDate', $('input[data-rel="fecha_inicio"]').datepicker('getDate'));
	});

	$('input[data-rel="fecha_fin"]').on('change', function(e)
	{
		$('input[data-rel="fecha_inicio"]').datepicker('option', 'maxDate', $('input[data-rel="fecha_fin"]').datepicker('getDate'));
	});
   
    //utilidades para datetimepicker
    $('input[data-role="clockpicker"]').datetimepicker({
        format: 'HH:mm:ss',
        ignoreReadonly:true,
        useCurrent:false
    });

    if ($('input[data-hora-inicio]').length)
    	$('input[data-hora-inicio]').data("DateTimePicker").minDate($('input[data-hora-inicio]').data('hora-inicio')).maxDate($('input[data-hora-fin]').data('hora-fin'));
    if ($('input[data-hora-fin]').length)
    	$('input[data-hora-fin]').data("DateTimePicker").minDate($('input[data-hora-inicio]').data('hora-inicio')).maxDate($('input[data-hora-fin]').data('hora-fin'));

    $('input[data-rel="hora_inicio"]').on('dp.change', function (e) 
    {
        $('input[data-rel="hora_fin"]').data("DateTimePicker").minDate(e.date);
    });

    $('input[data-rel="hora_fin"]').on('dp.change', function (e) 
    {
        $('input[data-rel="hora_inicio"]').data("DateTimePicker").maxDate(e.date);
    });

    //utilidades de formularios
	$('select').each(function(i, e){
	  	if ($(this).attr('data-value'))
	  	{
	      	if ($.trim($(this).data('value')) !== '')
	      	{
	          	var dato = $(this).data('value');
	          	$(this).val(dato);
	      	}
	  	}
	  	$(this).trigger('change');
	});
});