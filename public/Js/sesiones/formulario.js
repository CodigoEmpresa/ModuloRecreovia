$(function(e)
{
	var latitud = parseFloat($('input[id="latitud"]').val());
	var longitud = parseFloat($('input[id="longitud"]').val());
	var zoom = 13;

	var map = new google.maps.Map($("#map").get(0), {
		center: {lat: latitud, lng: longitud},
		zoom: zoom
	});

	var marker = new google.maps.Marker({
		map: map,
		draggable: true,
		animation: google.maps.Animation.DROP,
		position: {lat: latitud, lng: longitud}
	});

	$('#sesiones').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json"
		}
	});
});