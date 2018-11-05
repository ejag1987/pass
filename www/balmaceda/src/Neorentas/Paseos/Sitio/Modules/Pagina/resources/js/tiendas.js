$(function() {
    $('.item-tienda').off('click');

    cargarBuscador();
    cargarTienda();
});

function cargarBuscador() {
    $("#buscador").keyup(function(event) {
        var buscar = $(this).val();

        $.post(
            urlSitio + 'pagina',
            {
                ajax: true,
                accion: 'buscarTienda',
                buscar: buscar
            }, function (data) {
                if (!data.success) {

                }

                var contenedor = $('#contenedor-tiendas');
                contenedor.empty();
                contenedor.html(data.html);

                $('.item-tienda').off('click');
                cargarTienda();
            }, 'json'
        );
    });
}

function cargarTienda() {
    $('.item-tienda').on('click', function() {
        var idLocal = $(this).data('id');

        $.post(
            urlSitio + 'pagina',
            {
                ajax: true,
                accion: 'mostrarTienda',
                idLocal: idLocal
            }, function (data) {
                if (!data.success) {

                }

                var contenedor = $('#pagina-tiendas');
                contenedor.empty();
                contenedor.html(data.html);
            }, 'json'
        );
    });
}
