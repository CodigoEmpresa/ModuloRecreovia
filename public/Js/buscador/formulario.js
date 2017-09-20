var ctrlPressed = false;
function cacheIt(e) {
    if (e.ctrlKey || e.metaKey)
        ctrlPressed = true;
    else
        ctrlPressed = false;
}
document.onkeydown = cacheIt;
document.onkeyup = cacheIt;

$(function()
{
    var latitud = 4.65308;
    var longitud = -74.244232;
    var zoom = 12;
    var puntos = [];
    var markers = [];
    var marcadores_seleccionados = [];
    var table = null;

    var reloadDatatable = function() {
         table = $('table.sesiones').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            lengthChange: false,
            scrollY: '38vh',
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
    }

    var map = new google.maps.Map($('#mapa-buscador').get(0), {
        center: {lat: latitud, lng: longitud},
        zoom: zoom
    });

    function showLoader() {
        $('.ajaxloader').show();
    }

    function hideLoader() {
        $('.ajaxloader').hide();
    }

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    function renderSesiones() {
        table.clear().draw();
        var puntos = '';
        var $tr = '';
        table.destroy();

        $.each (marcadores_seleccionados, function(i, marcador){
            marcador.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
            var punto = marcador.punto;

            $.each(punto.cronogramas, function(i1, cronograma)
            {
                $.each(cronograma.sesiones, function(i2, sesion)
                {
                    $tr += '<tr data-id="'+sesion.Id+'"><td>'+sesion.Code+'</td><td>'+punto.Escenario+'</td><td>'+punto.Direccion+'</td><td>'+sesion.Objetivo_General+'</td><td>'+(sesion.profesor.persona['Primer_Nombre']+' '+sesion.profesor.persona['Primer_Apellido'])+'</td><td>'+sesion.Objetivos_Especificos+'</td><td>'+sesion.Inicio+' / '+sesion.Fin+'</td></tr>';
                });
            });
        });

        $('table.sesiones tbody').html($tr);
        reloadDatatable();
    }

    function showDetails(e) {
        var _this = this;
        if (!ctrlPressed)
        {
            $.each(marcadores_seleccionados, function (i, marcador) {
                marcador.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');
            });

            marcadores_seleccionados = [];
        }

        var exists = false;
        $.each(marcadores_seleccionados, function(i, marcador)
        {
            if (marcador === _this) {
                exists = true;
            }
        });

        if (!exists) {
            marcadores_seleccionados.push(this);
        } else {
            this.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');
            marcadores_seleccionados = marcadores_seleccionados.filter(function(marcador) {
                return _this.punto.Id_Punto !== marcador.punto.Id_Punto;
            });
        }

        renderSesiones();
    }

    function addMarkers(puntos) {
        for (var i = 0; i < puntos.length; i++) {

            var marker = new google.maps.Marker({
                position: {lat: parseFloat(puntos[i].Latitud), lng: parseFloat(puntos[i].Longitud)},
                map: map,
                icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
                title: puntos[i].Escenario+' '+puntos[i].Direccion,
                punto: puntos[i]
            });

            google.maps.event.addListener(marker, 'click', showDetails);

            markers.push(marker);
        }
    }

    $('#seleccionar-todos').on('click', function(e)
    {
        if (marcadores_seleccionados.length >= markers.length)
        {
            $.each(marcadores_seleccionados, function(i, marcador) {
                marcador.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');
            });

            marcadores_seleccionados = [];
            renderSesiones();
        } else {
            $.each(markers, function(i, marcador) {
                marcadores_seleccionados.push(marcador);
                renderSesiones();
            });
        }
    });

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
                marcadores_seleccionados = [];
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

    reloadDatatable();
});