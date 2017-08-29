$(function()
{
    var URL = $('#main').data('url');

    $('.buscador-cronogramas').on('blur', function(e) {
        var $this = $(this);
        var $target = $($(this).data('target')).DataTable();

        var request = $.post(
                URL+'/buscar',
                {
                    'codigos': $this.val()
                },
                'json'
            );

        request.done(function(data)
        {
            $target.clear().draw();
            if(data)
            {
                $.each(data, function(i, cronograma)
                {
                    var $tr = $('<tr>' +
                            '<td>'+cronograma.Code+'</td>' +
                            '<td>'+cronograma.punto.Label+'<br>'+cronograma.Label+'<br>'+cronograma.gestor.persona.Label+'</td>' +
                            '<td>'+cronograma.jornada.Label+'</td>'+
                            '<td>'+cronograma.sesiones.length+'</td>'+
                        '</tr>');

                    $target.rows.add($tr).draw(false);
                });
            }
        });

        request.fail(function(jqXHR, textStatus, error) {
            console.log(jqXHR.status);
            switch(jqXHR.status){
                case 422:
                break;
                case 500:
                break;
            }
        });
    });


});