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
		responsive: true,
		columnDefs: [
			{
				targets: 5,
        		searchable: false,
        		orderable: false
        	}
      	]
	});

	$('input[data-number]').on('focus', function(e) {
		$(this).select();
	});
});