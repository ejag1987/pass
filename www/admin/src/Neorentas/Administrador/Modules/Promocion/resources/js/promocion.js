$(function() {
    accionNuevo();
    accionSubirImagen();
    borrarImagen();
    guardarPromocion();
    eliminarPromocion();
    accionOrdenar();

    accionGuardarPagina();
});

function accionNuevo() {
    var boton = $('#nueva-promocion');

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'promocion', {
                ajax: true,
                accion: 'nuevaPromocion',
                idSitio: $('#id-sitio').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear promocion',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo',
                                action: function () {
                                    accionNuevo();
                                }
                            }
                        }
                    });
                    return;
                }
                var bloque = $('#bloque-promocion');
                bloque.append(data.html);

                accionNuevo();
                accionSubirImagen();
                guardarPromocion();
                eliminarPromocion();
            }, 'json'
        );
    });
}

function accionSubirImagen() {
    var upload = $('.uploader');

    if (upload.fileupload().length > 0) {
        upload.fileupload('destroy');
    }

    upload.each(function () {
        var idIcono, target;

        $(this).fileupload({
            url: urlSitio + 'promocion',
            dataType: 'json',
            dropZone: $(this),
            autoUpload: true,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png|svg)$/i,
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test( window.navigator && navigator.userAgent ),
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true,
            singleFileUploads: false,
            limitConcurrentUploads: 1,
            limitMultiFileUploads: 1,
            formData: {
                ajax: true,
                accion: 'subirImagen'
            },
            messages: {
                acceptFileTypes: 'Archivo no aceptado',
                maxNumberOfFiles: 'Número máximo de archivos excedido',
                maxFileSize: 'Archivo es muy grande',
                minFileSize: 'Archivo es muy pequeño'
            }
        }).on('fileuploadadd', function (e, data) {
            idIcono = e.target.dataset.id;
            target = e.target;

            $(target).addClass('hide');

            data.context = $( '<div/>' )
                .appendTo('#subir-' + idIcono)
                .addClass('file');

            $.each(data.files, function (index, file) {
                var node = addFile(file);
                data.context.append(node);
            });
        }).on('fileuploadprocessalways', function (e, data) {
            data.formData.idPromocion = $(this).data('id');
            data.formData.idSitio = $('#id-sitio').val();
            data.formData.imagenSubir = $(this).data('imagen');

            var index = data.index,
                file = data.files[index],
                node = $( data.context.children()[index] );

            if (file.preview) {
                node.prepend(file.preview);
            }

            if (file.error) {
                node.append($('<span class="text-danger"/>' ).text(file.error));
            }

            if (index + 1 === data.files.length) {
                data.context.find( '.file-status')
                    .text('En cola...' )
                    .prop('disabled', !!data.files.error);
            }
        }).on('fileuploadprogress', function (e, data) {
            var progress = parseInt( data.loaded / data.total * 100, 10 );

            data.context.find('.progress-bar').val(progress);
            data.context.find('.file-status').text(progress + '%');
        }).on('fileuploaddone', function (e, data) {
            data.context.find('.file-status').text('OK!!!');
            data.context.find('.progress-bar')
                .removeClass('is-primary')
                .addClass('is-success');

            setTimeout( function () {
                var bloque;
                bloque = $('#bloque-subir-' + data.result.campo + '-' +  data.result.idPromocion);

                if (data.result.campo == 'fondo') {
                    bloque = $('#bloque-subir-' + data.result.campo);

                    $('#id-pagina').val(data.result.idPromocion);
                }

                data.context.remove();
                bloque.empty();
                bloque.html(data.result.html);
                borrarImagen();

            }, 1000 );
        }).on('fileuploadfail', function (e, data) {
            //console.log(data.errorThrown);
            //console.log(data.textStatus);
            //console.log(data.jqXHR);

            $.each( data.files, function (index) {
                data.context.find( '.file-status' ).text( 'Error!!!' );
                data.context.find( '.progress-bar' )
                    .removeClass( 'is-primary' )
                    .addClass( 'is-danger' );
            });

        }).prop( 'disabled', !$.support.fileInput )
            .parent().addClass( $.support.fileInput ? undefined : 'disabled');
    });
}

function borrarImagen() {
    var basurero = $('.borrar');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idPromocion, campo;
        idPromocion = $(this).data('id');
        campo       = $(this).data('imagen');

        $.confirm( {
            title: 'Borrar imagen',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar la imagen?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        borrarImagen();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroImagen(idPromocion, campo);
                    }
                }
            }
        } );
    });
}

function borrarSeguroImagen(idPromocion, campo) {
    $.post(
        urlSitio + 'promocion', {
            ajax: 'true',
            accion: 'borrarImagen',
            idPromocion: idPromocion,
            campo: campo,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque;

            bloque = $('#bloque-subir-' + campo + '-' +  idPromocion);

            if (campo == 'fondo') {
                bloque = $('#bloque-subir-' + campo);
            }

            if (!data.success) {
                borrarImagen();
                return;
            }

            bloque.empty();
            bloque.html(data.html);
            accionSubirImagen();
            borrarImagen();
        }
    );
}

function guardarPromocion() {
    var boton = $('.guardar-promocion');

    boton.off('click');

    boton.on('click', function() {
        boton.off('click');

        var idPromocion, datos, result, error;

        idPromocion = $(this).data('id');
        error = $('#error-datos-'+idPromocion);
        datos = camposValidar('promocion-'+idPromocion);
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');

            return;
        }

        result.datos.ajax        = true;
        result.datos.accion      = 'guardarPromocion';
        result.datos.idPromocion = idPromocion;

        $.post(
            urlSitio + 'promocion', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    guardarPromocion();

                    return;
                }

                error.addClass('text-success');
                guardarPromocion();

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );
    });
}

function eliminarPromocion() {
    var boton = $('.borrar-promocion');

    boton.off('click');
    boton.on('click', function() {
        boton.off('click');

        var idPromocion = $(this).data('id');

        $.confirm( {
            title: 'Borrar promoción',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar esta promoción?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        eliminarPromocion();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroPromocion(idPromocion);
                    }
                }
            }
        } );
    });
}

function borrarSeguroPromocion(idPromocion) {
    $.post(
        urlSitio + 'promocion', {
            ajax: true,
            accion: 'borrarPromocion',
            idPromocion: idPromocion,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque = $('#promocion-'+idPromocion);

            if (!data.success) {
                $.alert({
                    title: 'Borrar promoción',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                eliminarPromocion();
                            }
                        }
                    }
                });
                return;
            }

            $.alert({
                title: 'Borrar promoción',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            eliminarPromocion();
                            bloque.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

function accionOrdenar() {
    $('#bloque-promocion').sortable({
        update: function (event, ui) {
            var promociones = $(this).sortable('toArray');

            $.post( urlSitio + 'promocion', {
                accion: "guardarOrden",
                ajax: true,
                promociones: promociones
            }, function( data ) {
                if (!data.success) {
                    $.alert({
                        title: 'Ordenar promociones',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo'
                            }
                        }
                    });
                }
            }, "json");
        }
    });
}

function accionGuardarPagina() {
    var boton = $('#guardar-datos');

    boton.on('click', function() {
        boton.off('click');

        var idSitio, idPagina, datos, result, error;

        idSitio = $('#id-sitio').val();
        idPagina = $('#id-pagina').val();
        error = $('#error-datos');
        datos = camposValidar('pagina');
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');

            return;
        }

        result.datos.ajax     = true;
        result.datos.accion   = 'guardarPagina';
        result.datos.idSitio  = idSitio;
        result.datos.idPagina = idPagina;

        $.post(
            urlSitio + 'promocion', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    accionGuardarPagina();

                    return;
                }

                $('#id-pagina').val(data.idPagina);
                error.addClass('text-success');
                accionGuardarPagina();

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );
    });
}