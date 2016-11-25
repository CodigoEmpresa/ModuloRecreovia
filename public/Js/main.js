$(function()
{
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	$('input[data-role="datepicker"]').datepicker({
	  dateFormat: 'yy-mm-dd',
	  yearRange: "-100:+0",
	  changeMonth: true,
	  changeYear: true,
	});
   
    $('input[data-role="clockpicker"]').datetimepicker({
        format: 'HH:mm:ss',
        ignoreReadonly:true,
        useCurrent:false
    });

    $('input[data-rel="hora_inicio"]').on("dp.change", function (e) {
        $('input[data-rel="hora_fin"]').data("DateTimePicker").minDate(e.date);
    });

    $('input[data-rel="hora_fin"]').on("dp.change", function (e) {
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
	});
});