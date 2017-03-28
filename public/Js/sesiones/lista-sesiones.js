$(function()
{
	$('#sesiones').DataTable({
		responsive: true,
		columnDefs: [
			{
				targets: 8,
        		searchable: false,
        		orderable: false
        	}
      	]
	});
});
