$(function()
{
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document)
	.ajaxStart(function(){
		console.log('start ajax');
	    $('div.ajaxloader').fadeIn();
	})
	.ajaxStop(function(){
	    $('div.ajaxloader').fadeOut();
	});

	$('body').tooltip({
	    selector: '[data-toggle="tooltip"]'
	});

	$('body').delegate('input[type="text"][data-number]', 'keypress', function(event) {
    	if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
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
		var _this = $(this);
		_this.datepicker({
		  	dateFormat: 'yy-mm-dd',
		  	yearRange: "-80:+20",
		  	changeMonth: true,
		  	changeYear: true,
		  	beforeShow: function(e, o)
		  	{
		  		_this.datepicker('option', 'minDate', null);
		  		_this.datepicker('option', 'maxDate', null);

		  		if (_this.attr('data-fecha-inicio'))
		  			_this.datepicker('option', 'minDate', _this.attr('data-fecha-inicio'));
				
		  		if (_this.attr('data-fecha-fin'))
		  			_this.datepicker('option', 'maxDate', _this.attr('data-fecha-fin'));
		  	},
		  	beforeShowDay: function(date)
		  	{	
		  		var dias = _this.attr('data-dias');
		  		var fechas_importantes = _this.attr('data-fechas-importantes');
		  		var day = date.getDay();

		  		var validar_dias = function(dias, festivo, day)
		  		{
		  			var resultado;
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
			  						dias_habiles.push(0);
			  					break;
			  				}
			  			});

			  			if ($.inArray(day, dias_habiles) != -1)
			  			{
			  				resultado = [true, festivo ? 'festivo' : '', festivo ? 'Festivo' : ''];
			  			} else {
			  				resultado = [false, festivo ? 'festivo' : '', festivo ? 'Festivo' : ''];
			  			}
			  		} else {
			  			resultado = [true, festivo ? 'festivo' : '', festivo ? 'Festivo' : ''];	
			  		}

			  		return resultado;
		  		}

		  		if(fechas_importantes)
		  		{
		  			var fechas = fechas_importantes.split(',');
		  			var fecha = date.toISOString().slice(0, 10);

		  			if($.inArray(fecha, fechas) != -1)
		  				return validar_dias(dias, true, day);
		  			else
		  				return validar_dias(dias, false, day);
		  			
		  		} else {
		  			return validar_dias(dias, false, day);
		  		}

		  		return [true, '', ''];
		  	}
		});
    });

	$('input[data-rel="fecha_inicio"]').on('change', function(e)
	{
		$('input[data-rel="fecha_fin"]').datepicker('option', 'minDate', $('input[data-rel="fecha_inicio"]').datepicker('getDate'));
	});

	$('input[data-rel="fecha_fin"]').on('change', function(e)
	{
		$('input[data-rel="fecha_inicio"]').datepicker('option', 'maxDate', $('input[data-rel="fecha_fin"]').datepicker('getDate'));
	});
   
    //utilidades para datetimepicker
    $('input[data-role="clockpicker"]').each(function(i, e)
    {
	    $(this).datetimepicker({
	        useCurrent:false,
	        ignoreReadonly:true,
	        format: 'HH:mm:ss'
	    });

	    if($(this).attr('data-hora-inicio'))
	    {
	    	$(this).data('DateTimePicker').defaultDate($(this).attr('data-hora-inicio'));
	    }

	    if($(this).attr('data-hora-fin'))
	    {
	    	$(this).data('DateTimePicker').defaultDate($(this).attr('data-hora-fin'));
	    }
    });

    $('body').on('focus', 'input[data-role="dynamic-clockpicker"]', function()
    {
    	$(this).datetimepicker({
	        useCurrent:false,
	        ignoreReadonly:true,
	        format: 'HH:mm:ss'
	    });
    });

    if ($('input[data-hora-inicio]').length)
    	$('input[data-hora-inicio]').data('DateTimePicker').minDate($('input[data-hora-inicio]').data('hora-inicio')).maxDate($('input[data-hora-fin]').data('hora-fin'));
    if ($('input[data-hora-fin]').length)
    	$('input[data-hora-fin]').data('DateTimePicker').minDate($('input[data-hora-inicio]').data('hora-inicio')).maxDate($('input[data-hora-fin]').data('hora-fin'));

    $('input[data-rel="hora_inicio"]').on('dp.change', function (e) 
    {
        $('input[data-rel="hora_fin"]').data('DateTimePicker').minDate(e.date);
    });

    $('input[data-rel="hora_fin"]').on('dp.change', function (e) 
    {
        $('input[data-rel="hora_inicio"]').data('DateTimePicker').maxDate(e.date);
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

	$('table.default').DataTable({
		responsive: true,
		columnDefs: [
			{
				targets: 'no-sort', 
				orderable: false
			}
		]
	});
});