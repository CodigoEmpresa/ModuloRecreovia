$(function()
{
	$('#sesiones').DataTable({
		responsive: true,
		columnDefs: [
			{
				targets: 6,
        		searchable: false,
        		orderable: false
        	}
      	]
	});
});