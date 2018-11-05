$(function() {
    guardarGaleria();
    borrarGaleria();

    accionSubirImagen();
    borrarImagen();

    ordenarImagenes();
});

function guardarGaleria() {
    var boton = $('#guardar-galeria');

    boton.on('click', function() {
        boton.off('click');

        var idSitio, idGaleria, datos, result, error;
        idSitio = $('#id-sitio').val();
        idGaleria = $('#id-galeria').val();

        error = $('#error-datos-'+idGaleria);
        datos = camposValidar('galeria-'+idGaleria);
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');

            guardarGaleria();

            return;
        }

        result.datos.ajax      = true;
        result.datos.accion    = 'guardarGaleria';
        result.datos.idGaleria = idGaleria;
        result.datos.idSitio   = idSitio;

        $.post(
            urlSitio + 'galeria', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    guardarGaleria();

                    return;
                }

                $('#id-galeria').val(data.idGaleria);

                error.addClass('text-success');
                guardarGaleria();

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );
    });
}

function borrarGaleria() {
    var boton = $('#borrar-galeria');

    boton.on('click', function() {
        boton.off('click');

        var idGaleria = $('#id-galeria').val();

        $.confirm( {
            title: 'Borrar galería',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar esta galería?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        borrarGaleria();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        eliminarGaleria(idGaleria);
                    }
                }
            }
        } );
    });
}

function eliminarGaleria(idGaleria) {
    var idSitio = $('#id-sitio').val();

    $.post(
        urlSitio + 'galeria', {
            ajax: true,
            accion: 'borrarGaleria',
            idGaleria: idGaleria,
            idSitio: idSitio
        }, function (data) {
            if (!data.success) {
                $.alert({
                    title: 'Borrar galeria',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                borrarGaleria();
                            }
                        }
                    }
                });
                return;
            }

            window.location.href = urlSitio + 'galeria/'+ idSitio;
        }, 'json'
    );
}

function accionSubirImagen() {
    $('.uploader').fileupload({
        url: urlSitio + 'galeria',
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png|svg)$/i,
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test( window.navigator && navigator.userAgent ),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
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
        var idGaleria = $('#id-galeria').val();

        if (!(idGaleria > 0)) {
            $.alert({
                title: 'Subir imagen galeria',
                icon: 'fa fa-warning text-danger',
                content: 'Debes crear la galería primero.',
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo'
                    }
                }
            });
            return false;
        }

        data.context = $( '<div/>' )
            .appendTo('#subir')
            .addClass('file');

        $.each(data.files, function (index, file) {
            var node = addFile(file);
            data.context.append(node);
        });

    }).on('fileuploadprocessalways', function (e, data) {
        var idGaleria = $('#id-galeria').val();

        if (!(idGaleria > 0)) {
            return false;
        }

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
        setTimeout( function () {
            data.context.remove();
        }, 3000 );

        if (!data.result.success) {
            data.context.find('.file-status').text('ERROR!!!');
            data.context.find('.progress-bar')
                .removeClass('is-primary')
                .addClass('is-danger');
            return false;
        }

        data.context.find('.file-status').text('OK!!!');
        data.context.find('.progress-bar')
            .removeClass('is-primary')
            .addClass('is-success');

        var bloque = $('#bloque-items');
        bloque.append(data.result.html);
        borrarImagen();

    }).on('fileuploadfail', function (e, data) {
        //console.log(data.errorThrown);
        //console.log(data.textStatus);
        //console.log(data.jqXHR);

        $.each( data.files, function (index) {
            data.context.find('.file-status').text('Error!!!');
            data.context.find('.progress-bar')
                .removeClass('is-primary')
                .addClass('is-danger');
        });
    }).prop( 'disabled', !$.support.fileInput )
        .parent().addClass( $.support.fileInput ? undefined : 'disabled');
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
        urlSitio + 'galeria', {
            ajax: 'true',
            accion: 'borrarImagen',
            idItem: idItem,
            campo: campo,
            idSitio: $('#id-galeria').val()
        }, function (data) {
            if (!data.success) {
                $.alert({
                    title: 'Borrar imagen galeria',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                borrarImagen();
                            }
                        }
                    }
                });

                return;
            }

            var bloque;
            bloque = $('#imagen-' +  idItem);

            bloque.remove();
            bloque.html(data.html);

            borrarImagen();
        }
    );
}

function ordenarImagenes() {
    $('#bloque-items').sortable({
        cursor: 'move',
        update: function (event, ui) {
            var items = $(this).sortable('toArray');

            $.post( urlSitio + 'galeria', {
                accion: "guardarOrdenImagenes",
                ajax: true,
                imagenes: items
            }, function( data ) {
                if (!data.success) {
                    $.alert({
                        title: 'Ordenar imagenes',
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