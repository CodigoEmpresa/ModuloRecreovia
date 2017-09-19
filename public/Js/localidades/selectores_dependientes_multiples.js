$(function()
{
    var LOCALIDADADES = JSON.parse(JSON.stringify($('select[name="id_localidad"]').data('json')));

    $('select[name="id_localidad"]').on('changed.bs.select', function(e, clickedIndex, newValue, oldValue){
       var val = $('select[name="id_localidad"]').selectpicker('val');

       var filtro_localidades = LOCALIDADADES.filter(function(localidad) {
                                    return $.inArray(localidad.Id_Localidad.toString(), val) > -1;
                               });

       $('select[name="id_upz"] optgroup').remove();
       $('select[name="id_upz"] option').remove();

       $('select[name="id_upz"]').append('<option value="todos">Todos</option>');

       $.each(filtro_localidades, function(i, localidad) {
           $('select[name="id_upz"]').append('<optgroup id="optgroup_'+localidad.Id_Localidad+'" label="'+localidad.Localidad+'"></optgroup>')
           $.each(localidad.upz, function(i, upz) {
               $('select[name="id_upz"] #optgroup_'+localidad.Id_Localidad).append('<option value="'+upz.Id_Upz+'">'+upz.Upz+'</option>');
           });

           $('select[name="id_upz"]').selectpicker('refresh');
       });
    });

    $('select[name="id_upz"]').on('changed.bs.select', function(e, clickedIndex, newValue, oldValue){
        var localidades = $('select[name="id_localidad"]').selectpicker('val');
        var val = $('select[name="id_upz"]').selectpicker('val');

        $('select[name="id_punto"] optgroup').remove();
        $('select[name="id_punto"] option').remove();

        $('select[name="id_punto"]').append('<option value="todos">Todos</option>');

        $.each(LOCALIDADADES, function(i, localidad) {
            $('select[name="id_upz"]').append('<optgroup id="optgroup_'+localidad.Id_Localidad+'" label="'+localidad.Localidad+'"></optgroup>')
            $.each(localidad.upz, function(i, upz) {
                $('select[name="id_upz"] #optgroup_'+localidad.Id_Localidad).append('<option value="'+upz.Id_Upz+'">'+upz.Upz+'</option>');
            });

            $('select[name="id_upz"]').selectpicker('refresh');
        });
    });
});