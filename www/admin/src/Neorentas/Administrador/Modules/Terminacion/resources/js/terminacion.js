$(function() {
    cargarEliminar();
    cargarGuardar();
    crearNuevo();
});

function cargarEliminar() {
    var boton = $('.borrar');

    boton.on('click', function() {
        boton.off('click');

        var idTerminacion, item, numero, descripcion;

        idTerminacion = $(this).data('id');
        item = $(this).parents('.terminacion').first();

        numero = $(item).find('.numero').val();
        descripcion = $(item).find('.descripcion').val();

        if (idTerminacion > 0 || numero != '' || descripcion != '') {
            $.confirm( {
                title: 'Borrar terminación',
                icon: 'fa fa-exclamation-triangle text-danger',
                content: '¿Está seguro que desea borrar esta terminación?',
                boxWidth: '30%',
                useBootstrap: false,
                buttons: {
                    cancel: {
                        text: 'No borrar',
                        action: function () {
                            cargarEliminar();
                        }
                    },
                    confirm: {
                        text: 'Borrar',
                        action: function () {
                            eliminarTerminacion(idTerminacion, item);
                        }
                    }
                }
            } );
        } else {
            item.remove();
        }
    })
}

function eliminarTerminacion(idTerminacion, item) {
    $.post(
        urlSitio + 'terminacion', {
            ajax: true,
            accion: 'eliminarTerminacion',
            idTerminacion: idTerminacion,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            if (!data.success) {
                $.alert({
                    title: 'Borrar terminación',
                    icon: 'fa fa-exclamation-triangle text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'cancelAction|4000',
                    buttons: {
                        cancel: {
                            text: 'OK!',
                            action: function () {
                                cargarEliminar();
                            }
                        }
                    }
                });

                return;
            }

            $.alert({
                title: 'Borrar terminación',
                icon: 'fa fa-check text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'cancel|4000',
                buttons: {
                    cancel: {
                        text: 'OK!',
                        action: function () {
                            //location.reload();
                            item.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

function cargarGuardar() {
    var boton = $('#guardar-info');

    boton.on('click', function() {
        boton.off('click');

        var grupo, inputs, input, datos, result, error;

        //validación
        error = $('#error');
        datos = camposValidar('form-terminaciones');
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no válidos y/o vacíos.');
            error.addClass('text-danger');

            cargarGuardar();
            return;
        }

        grupo = $('.terminacion');
        inputs = [];


        grupo.each(function(index, element) {
            input = $(element).find('input');

            var valor = $.map(input, function(e, i) {
                return $(e).val();
            });

            valor.push($(element).data('id'));

            inputs.push(valor);
        });

        $.post(
            urlSitio + 'terminacion', {
                ajax: true,
                accion: 'guardarTerminacion',
                valores: inputs,
                idSitio: $('#id-sitio').val()
            }, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    cargarGuardar();
                    return;
                }

                error.addClass('text-success');
                cargarGuardar();

                setTimeout( function () {
                    location.reload();
                }, 1000 );
            }, 'json'
        );
    });
}

function crearNuevo() {
    var boton = $('#nueva-terminacion');

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'terminacion', {
                ajax: true,
                accion: 'nuevaTerminacion'
            }, function (data) {
                $('.borrar').off('click');
                var botonAgregar = $('#nueva-terminacion').parent();
                console.log(botonAgregar);

                $(data.html).insertBefore(botonAgregar);

                cargarEliminar();
                crearNuevo();
            }, 'json'
        );
    })
}

