$(function()
{
    var latitud = 4.666575;
    var longitud = -74.125786;
    var zoom = 13;
    var puntos = [];
    var markers = [];

    var table = $('table.sesiones').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        scrollY: '50vh',
        scrollCollapse: true,
        columnDefs: [
            {
                targets: 'no-sort',
                orderable: false
            }
        ]
    });

    var map = new google.maps.Map($('#mapa-buscador').get(0), {
        center: {lat: latitud, lng: longitud},
        zoom: zoom
    });

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    function showDetails() {
        table.clear().draw();
        var punto = this.punto;
        var index = 0;

        $('#punto').html(punto.Escenario+'<br><small>'+punto.Direccion+'</small>');
        $.each(punto.cronogramas, function(i, cronograma)
        {
            $.each(cronograma.sesiones, function(i2, sesion)
            {
                index ++;
                var $tr = $('<tr><td>'+index+'</td><td>'+sesion.Objetivo_General+'</td><td>'+sesion.Inicio+' / '+sesion.Fin+'</td></tr>');

                table.row.add($tr).draw(false);
            });
        });
    }

    function addMarkers(puntos) {
        for (var i = 0; i < puntos.length; i++) {

            var marker = new google.maps.Marker({
                position: {lat: parseFloat(puntos[i].Latitud), lng: parseFloat(puntos[i].Longitud)},
                map: map,
                title: puntos[i].Escenario+' '+puntos[i].Direccion,
                punto: puntos[i]
            });

            google.maps.event.addListener(marker, 'click', showDetails);

            markers.push(marker);
        }
    }

    $('form#form-buscador').on('submit', function(e)
    {
        $.post(
            $(this).attr('action'),
            $(this).serialize(),
            'json'
        ).done(function(data)
        {
            if(puntos)
            {
                setMapOnAll(null);
                markers = [];
                puntos = [];
            }

            if(data)
            {
                puntos = data;
                addMarkers(puntos);
            }
        }).fail(function(xhr, status, error)
        {
            alert(error);
        });

        e.preventDefault();
    });
});