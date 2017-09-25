$(function()
{
    var LOCALIDADADES = JSON.parse(JSON.stringify($('select[name="id_localidad[]"]').data('json')));

    $('select[name="id_localidad[]"]').on('changed.bs.select', function(e, clickedIndex, newValue, oldValue){
       var localidades = $('select[name="id_localidad[]"]').selectpicker('val');

       var filtro_localidades = LOCALIDADADES.filter(function(localidad) {
                                    return $.inArray(localidad.Id_Localidad.toString(), localidades) > -1;
                               });

       $('select[name="id_upz[]"] optgroup').remove();
       $('select[name="id_upz[]"] option').remove();

       $.each(filtro_localidades, function(i, localidad) {
           $('select[name="id_upz[]"]').append('<optgroup id="opt_loc_group_'+localidad.Id_Localidad+'" label="'+localidad.Localidad+'"></optgroup>');
           $.each(localidad.upz, function(i, upz) {
               $('select[name="id_upz[]"] #opt_loc_group_'+localidad.Id_Localidad).append('<option value="'+upz.Id_Upz+'">'+upz.Upz+'</option>');
           });

           $('select[name="id_upz[]"]').selectpicker('refresh');
           $('select[name="id_upz[]"]').selectpicker('val', $('select[name="id_upz[]"]').data('value').split(',')).trigger('change');
       });
    });

    $('select[name="id_upz[]"]').on('changed.bs.select', function(e, clickedIndex, newValue, oldValue){
        var localidades = $('select[name="id_localidad[]"]').selectpicker('val');
        var upzs = $('select[name="id_upz[]"]').selectpicker('val');

        $('select[name="id_punto[]"] optgroup').remove();
        $('select[name="id_punto[]"] option').remove();

        var filtro_localidades = LOCALIDADADES.filter(function(localidad) {
            return $.inArray(localidad.Id_Localidad.toString(), localidades) > -1;
        });

        $.each(filtro_localidades, function(i, localidad) {
            var filtro_upz = localidad.upz.filter(function(upz) {
                return $.inArray(upz.Id_Upz.toString(), upzs) > -1;
            });

            $.each(filtro_upz, function(i, upz)
            {
                $('select[name="id_punto[]"]').append('<optgroup id="opt_upz_group_'+upz.Id_Upz+'" label="'+upz.Upz+'"></optgroup>');
                $.each(upz.puntos, function(i, punto) {
                    $('select[name="id_punto[]"] #opt_upz_group_'+upz.Id_Upz).append('<option value="'+punto.Id_Punto+'">'+punto.Escenario+'</option>');
                });
            });
        });

        $('select[name="id_punto[]"]').selectpicker('refresh');
        $('select[name="id_punto[]"]').selectpicker('val', $('select[name="id_punto[]"]').data('value').split(',')).trigger('change');
    });

    $('select[name="id_jornada[]"]').selectpicker('val', $('select[name="id_jornada[]"]').data('value').split(','));
    $('select[name="id_localidad[]"]').selectpicker('val', $('select[name="id_localidad[]"]').data('value').split(',')).trigger('change');
});