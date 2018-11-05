$(function() {
    accionGuardarPagina();
    accionSubirImagen();
    borrarImagen();

    accionEdicion();
    accionBorrar();

    ordenarGalerias();
});

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
            urlSitio + 'galeria', result.datos, function (data) {
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
            url: urlSitio + 'galeria',
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
        urlSitio + 'galeria', {
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

function accionEdicion() {
    var boton = $('.editar-galeria');

    boton.on('click', function() {
        console.log('editar');
        var idSitio, idGaleria;
        idSitio = $('#id-sitio').val();
        idGaleria = $(this).data('id');

        window.location.href = urlSitio + 'galeria/'+ idSitio + '/editar/' + idGaleria;
    });
}

function accionBorrar() {
    var boton = $('.borrar-galeria');

    boton.on('click', function() {
        boton.off('click');


        var idGaleria = $(this).data('id');

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
                        accionBorrar();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarGaleria(idGaleria);
                    }
                }
            }
        } );
        
    });
}

function borrarGaleria(idGaleria) {
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

            var bloque = $('#galeria-'+idGaleria);

            $.alert({
                title: 'Borrar galeria',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            accionBorrar();
                            bloque.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

function ordenarGalerias() {
    $('#bloque-galerias').sortable({
        update: function (event, ui) {
            var galerias = $(this).sortable('toArray');

            $.post( urlSitio + 'galeria', {
                accion: "guardarOrden",
                ajax: true,
                galerias: galerias
            }, function( data ) {
                if (!data.success) {
                    $.alert({
                        title: 'Ordenar galerias',
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
