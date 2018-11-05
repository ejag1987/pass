$(function() {
    activarNuevaRedSocial();
    activarSubirImagen();
    borrarIcono();
    activarGuardarRedSocial();
    eliminarRedSocial();
    activarOrdenRed();
});

function activarNuevaRedSocial() {
    var boton = $('#nueva-red');

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'rrss', {
                ajax: true,
                accion: 'nuevaRed',
                idSitio: $('#id-sitio').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear red social',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo',
                                action: function () {
                                    activarNuevaRedSocial();
                                }
                            }
                        }
                    });
                    return;
                }

                var bloque = $('#bloque-redes');
                bloque.append(data.html);

                activarNuevaRedSocial();
                activarSubirImagen();
                activarGuardarRedSocial();
                eliminarRedSocial();
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
            url: urlSitio + 'rrss',
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
                accion: 'subirIcono'
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
            data.formData.idRed = $(this).data('id');
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
                var bloque = $( '#bloque-subir-' + data.result.idRed );
                data.context.remove();
                bloque.empty();
                bloque.html(data.result.html);
                borrarIcono();
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

function borrarIcono() {
    var basurero = $('.borrar');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idRed = $(this).data('id');

        $.confirm( {
            title: 'Borrar ícono de red social',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar el ícono de esta red social?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        borrarIcono();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroIcono(idRed);
                    }
                }
            }
        } );
    });
}

function borrarSeguroIcono(idRed) {
    $.post(
        urlSitio + 'rrss', {
            ajax: true,
            accion: 'borrarIcono',
            idRed: idRed,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque = $('#bloque-subir-'+idRed);

            if (!data.success) {
                borrarIcono();
                return;
            }

            bloque.empty();
            bloque.html(data.html);
            activarSubirImagen();
            borrarIcono();

        }, 'json'
    );
}

function activarGuardarRedSocial() {
    var boton = $('.guardar-red');

    boton.off('click');

    boton.on('click', function() {
        boton.off('click');

        var idRed, datos, result, error;

        idRed = $(this).data('id');
        error = $('#error-datos-'+idRed);
        datos = camposValidar('red-social-'+idRed);
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');
            activarGuardarRedSocial();

            return;
        }

        result.datos.ajax   = true;
        result.datos.accion = 'guardarRed';
        result.datos.idRed  = idRed;

        $.post(
            urlSitio + 'rrss', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardarRedSocial();

                    return;
                }

                error.addClass('text-success');

                setTimeout( function () {
                    error.empty();
                }, 1000 );

                activarGuardarRedSocial();

            }, 'json'
        );
    });
}

function eliminarRedSocial() {
    var basurero = $('.borrar-red');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idRed = $(this).data('id');

        $.confirm( {
            title: 'Borrar red social',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar esta red social?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        eliminarRedSocial();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroRedSocial(idRed);
                    }
                }
            }
        } );
    });
}

function borrarSeguroRedSocial(idRed) {
    $.post(
        urlSitio + 'rrss', {
            ajax: true,
            accion: 'borrarRed',
            idRed: idRed
        }, function (data) {
            var bloque = $('#red-social-'+idRed);

            if (!data.success) {
                $.alert({
                    title: 'Borrar red social',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                eliminarRedSocial();
                            }
                        }
                    }
                });
                return;
            }


            $.alert({
                title: 'Borrar red social',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            eliminarRedSocial();
                            bloque.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

function activarOrdenRed() {
    $('#bloque-redes').sortable({
        update: function (event, ui) {
            var redesSociales = $(this).sortable('toArray');

            $.post( urlSitio + 'rrss', {
                accion: "guardarOrdenRedesSociales",
                ajax: true,
                redesSociales: redesSociales
            }, function( data ) {
                if (!data.success) {
                    $.alert({
                        title: 'Ordenar redes sociales',
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