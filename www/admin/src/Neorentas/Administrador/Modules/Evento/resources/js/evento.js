$(function() {
    activarMaskFecha();

    accionGuardarPagina();
    accionSubirImagen();
    borrarImagen();

    accionNuevo();
    guardarEvento();
    eliminarEvento();

    ordenarEventos();
});

function activarMaskFecha() {
    $('.fecha').inputmask("99-99-9999", {
        placeholder: 'dd-mm-yyyy'
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
            urlSitio + 'evento', result.datos, function (data) {
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

function accionSubirImagen() {
    var upload = $('.uploader');

    if (upload.fileupload().length > 0) {
        upload.fileupload('destroy');
    }

    upload.each(function () {
        var idIcono, target;

        $(this).fileupload({
            url: urlSitio + 'evento',
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
            data.formData.idSitio     = $('#id-sitio').val();
            data.formData.imagenSubir = $(this).data('imagen');
            data.formData.idItem      = $(this).data('id');

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
                bloque = $('#bloque-subir-' + data.result.campo + '-' +  data.result.idItem);

                if (data.result.campo == 'fondo') {
                    bloque = $('#bloque-subir-' + data.result.campo);

                    $('#id-pagina').val(data.result.idItem);
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

        var idItem, campo;
        idItem = $(this).data('id');
        campo  = $(this).data('imagen');

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
                        borrarSeguroImagen(idItem, campo);
                    }
                }
            }
        } );
    });
}

function borrarSeguroImagen(idItem, campo) {
    $.post(
        urlSitio + 'evento', {
            ajax: 'true',
            accion: 'borrarImagen',
            idItem: idItem,
            campo: campo,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque;

            bloque = $('#bloque-subir-' + campo + '-' +  idItem);

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

function accionNuevo() {
    var boton = $('#nuevo-evento');

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'evento', {
                ajax: true,
                accion: 'nuevoEvento',
                idSitio: $('#id-sitio').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear evento',
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
                var bloque = $('#bloque-eventos');
                bloque.append(data.html);

                activarMaskFecha();

                accionNuevo();
                accionSubirImagen();
                guardarEvento();
                eliminarEvento();
            }, 'json'
        );
    });
}

function guardarEvento() {
    var boton = $('.guardar-evento');

    boton.off('click');

    boton.on('click', function() {
        boton.off('click');

        var idEvento, datos, result, error;

        idEvento = $(this).data('id');
        error = $('#error-datos-'+idEvento);
        datos = camposValidar('evento-'+idEvento);
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');

            guardarEvento();

            return;
        }

        result.datos.ajax     = true;
        result.datos.accion   = 'guardarEvento';
        result.datos.idEvento = idEvento;

        $.post(
            urlSitio + 'evento', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    guardarEvento();

                    return;
                }

                error.addClass('text-success');
                guardarEvento();

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );

    });
}

function eliminarEvento() {
    var boton = $('.borrar-evento');

    boton.off('click');
    boton.on('click', function() {
        boton.off('click');

        var idEvento = $(this).data('id');

        $.confirm( {
            title: 'Borrar evento',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar este evento?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        eliminarEvento();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroEvento(idEvento);
                    }
                }
            }
        } );
    });
}

function borrarSeguroEvento(idEvento) {
    $.post(
        urlSitio + 'evento', {
            ajax: true,
            accion: 'borrarEvento',
            idEvento: idEvento,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque = $('#evento-'+idEvento);

            if (!data.success) {
                $.alert({
                    title: 'Borrar evento',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                eliminarEvento();
                            }
                        }
                    }
                });
                return;
            }

            $.alert({
                title: 'Borrar evento',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            eliminarEvento();
                            bloque.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

function ordenarEventos() {
    $('#bloque-eventos').sortable({
        update: function (event, ui) {
            var eventos = $(this).sortable('toArray');

            $.post( urlSitio + 'evento', {
                accion: "guardarOrden",
                ajax: true,
                eventos: eventos
            }, function( data ) {
                if (!data.success) {
                    $.alert({
                        title: 'Ordenar eventos',
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
