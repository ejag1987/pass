$(function() {
    $('#pagina-nueva').select2({
        placeholder: 'Seleccionar página',
        allowClear: true,
        language: "es",
        width: '100%'
    });
    agregarMenu();

    $('.select-option').select2({
        allowClear: true,
        language: "es",
        width: '100%'
    });

    $('.sortable').nestedSortable({
        forcePlaceholderSize: true,
        handle: 'div.mover-menu',
        helper:	'clone',
        items: 'li',
        opacity: .6,
        placeholder: 'placeholder',
        revert: 250,
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div',
        maxLevels: 4,
        isTree: true,
        expandOnHover: 700,
        startCollapsed: false,
        excludeRoot: true
    });

    guardarMenu();
    guardarOrdenMenu();

    eliminarMenu();
});

function guardarMenu() {
    var boton = $('.guardar-menu');

    boton.on('click', function() {
        boton.off('click');

        var idSitio, idMenu, pagina, link, activo, datos, result, error;

        idSitio = $('#id-sitio').val();
        idMenu  = $(this).data('id');

        datos  = camposValidar('menu-'+idMenu);
        result = $.parseJSON(datos);

        pagina = $('#pagina-'+idMenu);
        link   = $('#link-'+idMenu);
        activo = $('#cmn-toggle-'+idMenu).is(':checked');

        error = $('#error-'+idMenu);
        error.empty();
        error.removeClassExcept("error");

        if (pagina.val() === '' && link.val() === '') {
            pagina.parents('.form-group').first().addClass('has-error');
            link.parents('.form-group').first().addClass('has-error');

            result.error = true;
        } else {
            pagina.parents('.form-group').first().removeClass('has-error');
            link.parents('.form-group').first().removeClass('has-error');
        }

        if (result.error) {
            error.addClass('text-danger');
            error.html('Debes llenar los campos en rojo para guardar.');

            return;
        }

        result.datos.ajax    = true;
        result.datos.accion  = 'guardarMenu';
        result.datos.activo  = activo;
        result.datos.idMenu  = idMenu;
        result.datos.idSitio = idSitio;

        $.post(
            urlSitio + 'menusitio', result.datos, function(data) {
                if (!data.success) {
                    error.addClass('text-danger');
                    error.html(data.message);

                    guardarMenu();

                    return;
                }

                error.addClass('text-success');
                error.html(data.message);

                guardarMenu();

            }, 'json'
        );
    });
}

function guardarOrdenMenu() {
    $('.guardar-orden').click(function(e){
        var arraied = $('ol.sortable').nestedSortable('toArray', {
            startDepthCount: 0
        });

        var error = $('.error-orden');
        error.empty();
        error.removeClassExcept("error");

        $.post(
            urlSitio + 'menusitio',
            {
                ajax: true,
                accion: 'guardarOrden',
                menus: arraied
            }, function (data) {
                if (!data.success) {
                    error.addClass('text-danger');
                    error.html(data.message);

                    return;
                }
                error.addClass('text-success');
                error.html(data.message);

            }, 'json'
        );
    });
}

function agregarMenu() {
    var boton = $('#agregar-menu');

    boton.on('click', function() {
        boton.off('click');

        var idSitio, pagina, link, nombre, error, errorNombre = false, errorLink = false, errorEnvio = false, mensaje,
            errorURL;

        idSitio = $('#id-sitio').val();

        error = $('#error-agregar');
        error.empty();
        error.removeClassExcept("error");

        nombre = $('#nombre');
        pagina = $('#pagina-nueva');
        link   = $('#link-nueva');

        nombre.parents('.form-group').first().removeClass('has-error');
        pagina.parents('.form-group').first().removeClass('has-error');
        link.parents('.form-group').first().removeClass('has-error');

        if (nombre.val().length == 0) {
            nombre.parents('.form-group').first().addClass('has-error');

            errorEnvio = true;
            errorNombre = true;
        }

        if (pagina.val() === '' && link.val() === '') {
            pagina.parents('.form-group').first().addClass('has-error');
            link.parents('.form-group').first().addClass('has-error');

            errorEnvio = true;
            errorLink = true;
        }

        if (link.val().length > 0) {

            if (!validarCampo(link.data('validation'), link.val())) {
                errorEnvio = true;
                errorURL = true;
            }
        }

        if (errorEnvio) {
            mensaje = '';

            if (errorNombre) {
                mensaje += 'Debes poner un nombre.<br>';
            }

            if (errorLink) {
                mensaje += 'Debes seleccionar una página o poner un link.<br>';
            }

            if (errorURL) {
                mensaje += 'Debes poner una url válida. <small>(ej: http://www.google.com)</small>';
            }

            error.addClass('text-danger');
            error.html(mensaje);

            return;
        }

        $.post(
            urlSitio + 'menusitio', {
                ajax: true,
                accion: 'crearMenu',
                idSitio: idSitio,
                nombre: nombre.val(),
                pagina: pagina.val(),
                link: link.val()
            }, function(data) {
                if (!data.success) {
                    error.addClass('text-danger');
                    error.html(data.message);

                    agregarMenu();
                    return;
                }

                $('#nombre').val('');
                $('#link-nueva').val('');
                $("#pagina-nueva").val('').trigger('change');

                error.addClass('text-success');
                error.html(data.message);

                var bloque = $('#bloque-menu');
                bloque.append(data.html);

                $('.select-option').select2({
                    allowClear: true,
                    language: "es",
                    width: '100%'
                });


                agregarMenu();
            }, 'json'
        );
    });
}

function eliminarMenu() {
    var basurero = $('.borrar-menu');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idMenu = $(this).data('id');

        $.confirm( {
            title: 'Borrar menu',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar este menú?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        eliminarMenu();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroMenu(idMenu);
                    }
                }
            }
        } );
    });
}

function borrarSeguroMenu(idMenu) {
    $.post(
        urlSitio + 'menusitio', {
            ajax: true,
            accion: 'borrarMenu',
            idMenu: idMenu
        }, function (data) {
            var bloque = $('#menu-'+idMenu);

            if (!data.success) {
                $.alert({
                    title: 'Borrar menú',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                eliminarMenu();
                            }
                        }
                    }
                });
                return;
            }


            $.alert({
                title: 'Borrar menú',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            eliminarMenu();
                            bloque.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

