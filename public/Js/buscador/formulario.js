$(function()
{
    var latitud = 4.65308;
    var longitud = -74.244232;
    var zoom = 12;
    var puntos = [];
    var markers = [];

    var table = $('table.sesiones').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        lengthChange: false,
        paging: false,
        scrollY: '50vh',
        scrollCollapse: true,
        buttons: [
            'copy', 'excel', {extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL'}
        ],
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

        $('#punto').html('<strong>'+punto.Escenario+'</strong><br><small>'+punto.Direccion+'</small>');
        $.each(punto.cronogramas, function(i, cronograma)
        {
            $.each(cronograma.sesiones, function(i2, sesion)
            {
                index ++;
                var $tr = $('<tr data-id="'+sesion.Id+'"><td>'+index+'</td><td>'+sesion.Objetivo_General+'</td><td>'+(sesion.profesor.persona['Primer_Nombre']+' '+sesion.profesor.persona['Primer_Apellido'])+'</td><td>'+sesion.Objetivos_Especificos+'</td><td>'+sesion.Inicio+' / '+sesion.Fin+'</td></tr>');

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