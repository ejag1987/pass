$(function() {
    activarNuevaMarca();
    activarSubirImagen();
    borrarLogo();
    activarGuardarMarca();
    eliminarMarca();
    activarOrdenMarcas();
});

function activarNuevaMarca() {
    var boton = $('#nueva-marca');

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'marca', {
                ajax: true,
                accion: 'nuevaMarca',
                idSitio: $('#id-sitio').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear marca',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo',
                                action: function () {
                                    activarNuevaMarca();
                                }
                            }
                        }
                    });
                    return;
                }

                var bloque = $('#bloque-marcas');
                bloque.append(data.html);

                activarNuevaMarca();
                activarSubirImagen();
                activarGuardarMarca();
                eliminarMarca();
            }, 'json'
        );
    });
}

function activarSubirImagen() {
    var upload = $('.uploader');

    if (upload.fileupload().length > 0) {
        upload.fileupload('destroy');
    }

    upload.each(function () {
        var idIcono, target;

        $(this).fileupload({
            url: urlSitio + 'marca',
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
                accion: 'subirLogo'
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
            data.formData.idMarca = $(this).data('id');
            data.formData.idSitio = $('#id-sitio').val();

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
                var bloque = $('#bloque-subir-' + data.result.idMarca);
                data.context.remove();
                bloque.empty();
                bloque.html(data.result.html);
                borrarLogo();
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

function borrarLogo() {
    var basurero = $('.borrar');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idMarca = $(this).data('id');

        $.confirm( {
            title: 'Borrar logo de la marca',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar el logo de esta marca?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        borrarLogo();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroLogo(idMarca);
                    }
                }
            }
        } );
    });
}

function borrarSeguroLogo(idMarca) {
    $.post(
        urlSitio + 'marca', {
            ajax: true,
            accion: 'borrarLogo',
            idMarca: idMarca,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque = $('#bloque-subir-'+idMarca);

            if (!data.success) {
                borrarLogo();
                return;
            }

            bloque.empty();
            bloque.html(data.html);
            activarSubirImagen();
            borrarLogo();

        }, 'json'
    );
}

function activarGuardarMarca() {
    var boton = $('.guardar-marca');

    boton.off('click');

    boton.on('click', function() {
        boton.off('click');

        var idMarca, datos, result, error;

        idMarca = $(this).data('id');
        error = $('#error-datos-'+idMarca);
        datos = camposValidar('marca-'+idMarca);
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');

            return;
        }

        result.datos.ajax    = true;
        result.datos.accion  = 'guardarMarca';
        result.datos.idMarca = idMarca;

        $.post(
            urlSitio + 'marca', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardarMarca();

                    return;
                }

                error.addClass('text-success');
                activarGuardarMarca();

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );
    });
}

function eliminarMarca() {
    var basurero = $('.borrar-marca');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idMarca = $(this).data('id');

        $.confirm( {
            title: 'Borrar marca',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar esta marca?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        eliminarMarca();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroMarca(idMarca);
                    }
                }
            }
        } );
    });
}

function borrarSeguroMarca(idMarca) {
    $.post(
        urlSitio + 'marca', {
            ajax: true,
            accion: 'borrarMarca',
            idMarca: idMarca
        }, function (data) {
            var bloque = $('#marca-'+idMarca);

            if (!data.success) {
                $.alert({
                    title: 'Borrar marca',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                eliminarMarca();
                            }
                        }
                    }
                });
                return;
            }


            $.alert({
                title: 'Borrar Marca',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            eliminarMarca();
                            bloque.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

function activarOrdenMarcas() {
    $('#bloque-marcas').sortable({
        update: function (event, ui) {
            var marcas = $(this).sortable('toArray');

            $.post( urlSitio + 'marca', {
                accion: "guardarOrdenMarca",
                ajax: true,
                marcas: marcas
            }, function( data ) {
                if (!data.success) {
                    $.alert({
                        title: 'Ordenar marcas',
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