$(function()
{
    var URL = $('#main').data('url');

    $('#informes').delegate('a[data-role="estado"]', 'click', function(e)
    {
        var tr = $(this).closest('tr');
        var _this = $(this);

        $.post(
            URL+'/actualizar_estado',
            {
                id: tr.data('id'),
                estado: _this.text()
            },
            'json'
        ).done(function(data)
        {
            var clase = "default";
            switch (_this.text())
            {
                case 'Pendiente':
                    clase = 'default';
                    break;
                case 'Diligenciado':
                case 'Corregir':
                    clase = 'warning';
                    break;
                case 'Aprobado':
                    clase = 'success';
                    break;
                case 'Finalizado':
                    clase = 'info';
                    break;
                case 'Rechazado':
                case 'Cancelado':
                    clase = 'danger';
                    break;
                default:
                    clase= 'default';
                    break;
            }
            tr.removeClass('default warning success info danger').addClass(clase);
            tr.find('td[data-role="Estado"]').text(_this.text());
        });

        e.preventDefault();
    });
});
