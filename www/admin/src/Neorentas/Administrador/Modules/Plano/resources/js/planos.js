$(function() {
    activarSubirPlano();
    borrarPlano();
    nuevoPlano();
    guardarPlano();
    eliminarPlano();
    activarOrdenPlano()
});

function borrarPlano() {
    var basurero = $('.borrar');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idPlano = $(this).data('id');

        $.confirm( {
            title: 'Borrar plano',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar el plano?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        borrarPlano();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarImagenPlanoSeguro(idPlano);
                    }
                }
            }
        } );
    });
}

function borrarImagenPlanoSeguro(idPlano) {
    $.post(
        urlSitio + 'plano', {
            ajax: true,
            accion: 'borrarImagenPlano',
            idPlano: idPlano,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque = $('#bloque-subir-'+idPlano);

            if (!data.success) {
                borrarPlano();
                return;
            }

            bloque.empty();
            bloque.html(data.html);
            activarSubirPlano();
        }, 'json'
    );
}

function activarSubirPlano() {
    var upload = $('.uploader');

    if (upload.fileupload().length > 0) {
        upload.fileupload('destroy');
    }

    upload.each(function () {
        var idIcono, target;

        $(this).fileupload({
            url: urlSitio + 'plano',
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
                accion: 'subirPlano'
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
            data.formData.idPlano = $(this).data('id');
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
                var bloque = $('#bloque-subir-' + data.result.idPlano);
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
                data.context.find('.file-status').text('Error!!!');
                data.context.find('.progress-bar')
                    .removeClass('is-primary')
                    .addClass('is-danger');
            });

        }).prop( 'disabled', !$.support.fileInput )
            .parent().addClass( $.support.fileInput ? undefined : 'disabled');
    });
}

function nuevoPlano() {
    var boton = $('#nuevo-plano');

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'plano', {
                ajax: true,
                accion: 'nuevoPlano',
                idSitio: $('#id-sitio').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear plano',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo',
                                action: function () {
                                    nuevoPlano();
                                }
                            }
                        }
                    });
                    return;
                }

                var bloque = $('#bloque-planos');
                bloque.append(data.html);

                nuevoPlano();
                activarSubirPlano();
                guardarPlano();
                eliminarPlano();
            }, 'json'
        );
    });
}

function guardarPlano() {
    var boton = $('.guardar');

    boton.off('click');

    boton.on('click', function() {
        boton.off('click');

        var idPlano, datos, result, error;

        idPlano = $(this).data('id');
        error = $('#error-datos-'+idPlano);
        datos = camposValidar('plano-'+idPlano);
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
        result.datos.accion  = 'guardarPlano';
        result.datos.idPlano = idPlano;

        $.post(
            urlSitio + 'plano', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardarMarca();

                    return;
                }

                error.addClass('text-success');
                guardarPlano();

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );
    });
}

function eliminarPlano() {
    var basurero = $('.borrar-plano');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idPlano = $(this).data('id');

        $.confirm( {
            title: 'Borrar plano',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar este plano?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        eliminarPlano();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroPlano(idPlano);
                    }
                }
            }
        } );
    });
}

function borrarSeguroPlano(idPlano) {
    $.post(
        urlSitio + 'plano', {
            ajax: true,
            accion: 'borrarPlano',
            idPlano: idPlano
        }, function (data) {
            var bloque = $('#plano-'+idPlano);

            if (!data.success) {
                $.alert({
                    title: 'Borrar plano',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                eliminarPlano();
                            }
                        }
                    }
                });
                return;
            }

            $.alert({
                title: 'Borrar Plano',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            eliminarPlano();
                            bloque.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

function activarOrdenPlano() {
    $('#bloque-planos').sortable({
        update: function (event, ui) {
            var planos = $(this).sortable('toArray');

            $.post( urlSitio + 'plano', {
                accion: "guardarOrdenPlano",
                ajax: true,
                planos: planos
            }, function( data ) {
                if (!data.success) {
                    $.alert({
                        title: 'Ordenar planos',
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