$(function() {
    desactivarCrear();
    activarGuardar();
});

function desactivarCrear() {
    $('#crear-sitio').off('click');
}

function activarGuardar() {
    var boton = $('#guardar-datos');

    boton.on('click', function() {
        boton.off('click');

        var datos, result, error;
        error = $('#error-datos');
        datos = camposValidar('datos-sitio');
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');
            activarGuardar();
            return;
        }

        result.datos.ajax    = true;
        result.datos.accion  = 'guardarDatos';
        result.datos.idSitio = $('#id-sitio').val();

        $.post(
            urlSitio + 'sitio', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardar();

                    return;
                }

                error.addClass('text-success');
                activarGuardar();

            }, 'json'
        );
    });
}