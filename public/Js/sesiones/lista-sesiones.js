$(function()
{
	$('#sesiones').DataTable({
		responsive: true,
		columnDefs: [
			{
				targets: 7,
        		searchable: false,
        		orderable: false
        	}
      	]
	});
});