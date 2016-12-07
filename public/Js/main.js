$(function()
{
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getDate(value) {
    	var date;
    	console.log(value);
		try {
			date = $.datepicker.parseDate('yy-mm-dd', element.value);
		} catch( error ) {
    		date = null;
  		}
  		console.log(date);

      	return date;
    }

	$('input[data-role="datepicker"]').datepicker({
	  	dateFormat: 'yy-mm-dd',
	  	yearRange: "-10:+10",
	  	changeMonth: true,
	  	changeYear: true,
	});

	$('input[data-rel="fecha_inicio"]').on('change', function(e)
	{
		$('input[data-rel="fecha_fin"]').datepicker("option", "minDate", $('input[data-rel="fecha_inicio"]').datepicker('getDate'));
	});

	$('input[data-rel="fecha_fin"]').on('change', function(e)
	{
		$('input[data-rel="fecha_inicio"]').datepicker("option", "maxDate", $('input[data-rel="fecha_fin"]').datepicker('getDate'));
	});
   
    $('input[data-role="clockpicker"]').datetimepicker({
        format: 'HH:mm:ss',
        ignoreReadonly:true,
        useCurrent:false
    });

    $('input[data-rel="hora_inicio"]').on('dp.change', function (e) 
    {
        $('input[data-rel="hora_fin"]').data("DateTimePicker").minDate(e.date);
    });

    $('input[data-rel="hora_fin"]').on('dp.change', function (e) 
    {
        $('input[data-rel="hora_inicio"]').data("DateTimePicker").maxDate(e.date);
    });

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