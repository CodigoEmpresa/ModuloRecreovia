$(function()
{
	var grupos_impacto_participantes = $.parseJSON(JSON.stringify($('input[name="grupos_impacto_participantes"]').data('json')));
	var grupos_impacto_asistentes = $.parseJSON(JSON.stringify($('input[name="grupos_impacto_asistentes"]').data('json')));

	(function grafica_participantes()
	{
		var categorias = [];
		var masculino = [];
		var femenino = [];

		$.each(grupos_impacto_participantes, function(k, v)
		{
			categorias.push(k);
			masculino.push(v.Participantes.M);
			femenino.push(v.Participantes.F);
		});

		Highcharts.chart('grupos_impacto_participantes', {
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'Participantes'
	        },
	        xAxis: {
	            categories: categorias,
	            crosshair: true
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Total personas'
	            }
	        },
	        tooltip: {
	            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	                '<td style="padding:0"><b>{point.y:.1f} personas</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0
	            }
	        },
	        series: [{
	            name: 'Masculino',
	            data: masculino

	        }, {
	            name: 'Femenino',
	            data: femenino

	        }]
	    });
	})();

    (function grafica_asistentes(){
		var categorias = [];
		var masculino = [];
		var femenino = [];
		$.each(grupos_impacto_asistentes, function(k, v)
		{
			categorias.push(k);
			masculino.push(v.Asistentes.M);
			femenino.push(v.Asistentes.F);
		});

		Highcharts.chart('grupos_impacto_asistentes', {
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'Asistentes'
	        },
	        xAxis: {
	            categories: categorias,
	            crosshair: true
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Total personas'
	            }
	        },
	        tooltip: {
	            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	                '<td style="padding:0"><b>{point.y:.1f} personas</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0
	            }
	        },
	        series: [{
	            name: 'Masculino',
	            data: masculino

	        }, {
	            name: 'Femenino',
	            data: femenino

	        }]
	    });
    })();

});