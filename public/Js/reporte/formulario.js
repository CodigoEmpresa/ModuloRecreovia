$(function()
{
    var URL = $('#main').data('url');

    var reindexarServicios = function()
    {
        $('#servicios tbody tr[id != "plantilla_servicio"]').each(function(i, tr)
        {
            $(tr).find('input, select').each(function(i2, formElement)
            {
                $(formElement).attr('name', $(formElement).data('name')+'_'+i);
            });

            $(tr).find('td[data-role="item"]').text(i+1);
        });

        $('input[name="Total_Servicios"]').val($('#servicios tbody tr[id != "plantilla_servicio"]').length);
    }

    $('#participaciones').DataTable({
        paging: false
    });

    $('#asistencias').DataTable({
        paging: false
    });

    var tbl_sesiones = $('#sesiones').DataTable();

    $('input[name="Dias"]').selectize({
        delimiter: ',',
        persist: true,
        create:  true
    });

    $('select[name="Id_Punto"]').on('changed.bs.select', function(e)
    {
    	var id_punto = $(this).val();
        var id_gestor = $('input[name="id_gestor"]').val()
        var request = $.get(URL+'/gestor/'+id_gestor+'/punto/'+id_punto+'/cronogramas', {}, 'json');
        var recargar_sesiones = false;

        request.done(function(punto)
        {
            if(punto.cronogramas)
            {
                $('select[name="Id_Cronograma"]').html('');
                $.each(punto.cronogramas, function(i, cronograma)
                {
                    if(!recargar_sesiones) recargar_sesiones = +$('select[name="Id_Cronograma"]').data('value') == cronograma.Id;

                    $('select[name="Id_Cronograma"]').append('<option data-desde="'+cronograma.Desde+'" data-dias="'+cronograma.jornada.Dias+'" data-hasta="'+cronograma.Hasta+'" value="'+cronograma.Id+'">'+('Del '+cronograma.Desde+' al '+cronograma.Hasta+' / '+cronograma.jornada.Code+' - '+cronograma.jornada.Label)+'</option>');
                });

                $('select[name="Id_Cronograma"]').selectpicker('refresh');
            }


            if (recargar_sesiones)
            {
                $('select[name="Id_Cronograma"]').val($('select[name="Id_Cronograma"]').data('value')).trigger('change');
            } else {
                $('select[name="Id_Cronograma"]').selectpicker('val', '');
                tbl_sesiones.clear().draw();
            }
        });
    });

    $('select[name="Id_Cronograma"]').on('change', function(e)
    {
        var id_cronograma = $(this).val();
        var request = $.get(URL+'/cronograma/'+id_cronograma+'/sesiones', {}, 'json');

        tbl_sesiones.clear().draw();

        request.done(function(cronograma)
        {
            var sesiones = cronograma.sesiones;
            var sesiones_seleccionadas = $.map($('input[name="sesiones"]').val().split(','), function(v) { return +v; });

            $.each(sesiones, function(i, e){
                var checked = $.inArray(e.Id, sesiones_seleccionadas) > -1 ? 'checked="checked"' : '';
                var enEsteInforme = $.inArray(e.Id, sesiones_seleccionadas) > -1 ? 'Si' : 'No';
                var profesor = e.profesor ? e.profesor.persona['Primer_Nombre']+' '+e.profesor.persona['Primer_Apellido'] : 'Sin profesor asignado';
                tbl_sesiones.row.add($('<tr data-id="'+e.Id+'">'+
                    '<td>'+e.Fecha+'</td>'+
                    '<td>'+e.Objetivo_General+'<br>'+profesor+'</td>'+
                    '<td>'+enEsteInforme+'</td>'+
                    '<td>'+e.reportes.length+' informe(s)</td>'+
                    '<td><input type="checkbox" name="sesion[]" value="'+e.Id+'" '+checked+'/></td>'+
                    '</tr>')).draw(false);
            });

            tbl_sesiones.order([2, 'desc']).draw();
        });
    });

    $('#check_all').on('click', function(e){
        var state = $(this).is(':checked');
        tbl_sesiones.rows({filter: 'applied'}).every(function (rowIdx, tableLoop, rowLoop) {
            $tr = $(this.node());
            $tr.find('input[type="checkbox"]').prop('checked', state);
        });
    });

    if ($('select[name="Id_Punto"]').data('value') !== '')
    {
        $('select[name="Id_Punto"]').val($('select[name="Id_Punto"]').data('value')).trigger('change');
    }

    $('#actualizar_reporte').on('click', function(e)
    {
        $('#formularios_complementarios form').each(function(i, e)
        {
            var data = $(this).serialize();

            $.post(
                URL+'/actualizar',
                data,
                function(data){},
                'json'
            ).done(function(e)
            {
                $('html, body').animate({
                    scrollTop: 10
                }, 100);
            });
        });
    });

    $('#agregar_servicio').on('click', function(e)
    {
        var $plantilla = $('#plantilla_servicio').clone(true, true);
        $plantilla.removeAttr('id');

        $('#servicios').append($plantilla);
        $plantilla.fadeIn();
        reindexarServicios();
    });

    $('table#servicios').delegate('a[data-role="eliminar"]', 'click', function(e)
    {
        $(this).closest('tr').remove();
        reindexarServicios();
        e.preventDefault();
    });

    $('#principal').on('submit', function(e)
    {
        var selected = [];
        tbl_sesiones.rows().every(function (rowIdx, tableLoop, rowLoop) {
            $tr = $(this.node());
            if($tr.find('input[type="checkbox"]').is(':checked'))
            {
                selected.push($tr.data('id'));
            }
        });

        $('input[name="sesiones"]').val(selected.join());
    });
});
