$(function() {
    activarNuevoSlide();
    activarNuevaCaluga();
    activarNuevaCalugaSite();
    activarSubirImagen();
    activarGuardar();
    borrarImagen();
    eliminarSlideCaluga();
    activarOrden();
    activarGuardarSlide();
});

function activarNuevoSlide() {
    var boton = $('#nuevo-slide');

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'portada', {
                ajax: true,
                accion: 'nuevoSlide',
                idSitio: $('#id-sitio').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear slide',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo',
                                action: function () {
                                    activarNuevoSlide();
                                }
                            }
                        }
                    });
                    return;
                }

                var bloque = $('#bloque-slide');
                bloque.append(data.html);

                activarNuevoSlide();
                activarSubirImagen();
                activarGuardar();
                eliminarSlideCaluga();
            }, 'json'
        );
    });
}

function activarNuevaCaluga() {
    var boton = $('#nueva-caluga');    

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'portada', {
                ajax: true,
                accion: 'nuevaCaluga',
                idSitio: $('#id-sitio').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear caluga',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo',
                                action: function () {
                                    activarNuevaCaluga();
                                }
                            }
                        }
                    });
                    return;
                }

                var bloque = $('#bloque-caluga');
                bloque.append(data.html);

                activarNuevaCaluga();
                activarSubirImagen();
                activarGuardar();
                eliminarSlideCaluga();
            }, 'json'
        );
    });
}
function activarNuevaCalugaSite() {
    var boton = $('#nueva-calugaSite');    

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'portada', {
                ajax: true,
                accion: 'nuevaCalugaSite',
                idSitio: $('#id-sitio').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear caluga',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo',
                                action: function () {
                                    activarNuevaCalugaSite();
                                }
                            }
                        }
                    });
                    return;
                }

                var bloque = $('#bloque-calugaSite');
                bloque.append(data.html);

                activarNuevaCalugaSite();
                activarSubirImagen();
                activarGuardar();
                eliminarSlideCaluga();
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
        var idImagen, target, bloque;
        $(this).fileupload({
            url: urlSitio + 'portada',
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
            idImagen = e.target.dataset.id;
            target = e.target;
            bloque = $(this).parent().data('bloque');

            console.log($(this).parent());

            $(target).addClass('hide');

            data.context = $( '<div/>' )
                .appendTo('#subir-'+bloque+'-' + idImagen)
                .addClass('file');

            $.each(data.files, function (index, file) {
                var node = addFile(file);
                data.context.append(node);
            });
        }).on('fileuploadprocessalways', function (e, data) {
            data.formData.id      = $(this).data('id');
            data.formData.idSitio = $('#id-sitio').val();
            data.formData.bloque  = $(this).parent().data('bloque');

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
                var bloque = $('#bloque-subir-'+data.result.bloque+'-' + data.result.id);
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
                data.context.find('.file-status').text('Error!!!');
                data.context.find('.progress-bar')
                    .removeClass('is-primary')
                    .addClass('is-danger');
            });

        }).prop( 'disabled', !$.support.fileInput )
            .parent().addClass( $.support.fileInput ? undefined : 'disabled');
    });
}

function activarGuardarSlide() {
    var boton = $('.guardaslide');

    boton.off('click');

    boton.on('click', function() {
        boton.off('click');

        var idSlide, databloque, datos, result, error;

        idSlide    = $(this).data('id');
        databloque = $(this).parents('.botones-slide').first().data('bloque');
        error      = $('#error-'+databloque+'-'+idSlide);
        datos      = camposValidar(databloque+'-'+idSlide);
        result     = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');
            activarGuardarRedSocial();

            return;
        }

        result.datos.ajax       = true;
        result.datos.accion     = 'guardarDatoSlide';
        result.datos.idSlide    = idSlide;
        result.datos.databloque = databloque;

        $.post(
            urlSitio + 'portada', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardarSlide();

                    return;
                }

                error.addClass('text-success');

                setTimeout( function () {
                    error.empty();
                }, 1000 );

                activarGuardarSlide();

            }, 'json'
        );
    });
}


function activarGuardar() {
    var boton = $('.guardar');

    boton.off('click');

    boton.on('click', function() {
        boton.off('click');

        var idSlide, databloque, datos, result, error;

        idSlide    = $(this).data('id');
        databloque = $(this).parents('.botones-slide').first().data('bloque');
        error      = $('#error-'+databloque+'-'+idSlide);
        datos      = camposValidar(databloque+'-'+idSlide);
        result     = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');
            activarGuardarRedSocial();

            return;
        }

        result.datos.ajax       = true;
        result.datos.accion     = 'guardarDatos';
        result.datos.idSlide    = idSlide;
        result.datos.databloque = databloque;

        $.post(
            urlSitio + 'portada', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardar();

                    return;
                }

                error.addClass('text-success');

                setTimeout( function () {
                    error.empty();
                }, 1000 );

                activarGuardar();

            }, 'json'
        );
    });
}

function borrarImagen() {
    var basurero = $('.borrar-imagen');
    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        console.log('hola');

        var id, databloque;

        id = $(this).data('id');
        databloque = $(this).parents('.bloque-subir').first().data('bloque');

        $.confirm( {
            title: 'Borrar la imagen',
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
                        borrarSeguroImagen(id, databloque);
                    }
                }
            }
        } );
    });
}

function borrarImagenSlide() {
    var basurero = $('.borrar-imagen-slide');
    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        console.log('hola slide');

        var id, databloque;

        id = $(this).data('id');
        databloque = $(this).parents('.bloque-subir').first().data('bloque');

        $.confirm( {
            title: 'Borrar la imagen',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar la imagen?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        borrarImagenSlide();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguroSlide(id, databloque);
                    }
                }
            }
        } );
    });
}



function borrarSeguroImagen(id, databloque) {
    $.post(
        urlSitio + 'portada', {
            ajax: true,
            accion: 'borrarImagen',
            id: id,
            bloque: databloque,
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque = $('#bloque-subir-'+databloque+'-'+id);

            if (!data.success) {
                borrarImagen();
                return;
            }

            bloque.empty();
            bloque.html(data.html);
            activarSubirImagen();
            borrarImagen();

        }, 'json'
    );
}

function eliminarSlideCaluga() {
    var basurero = $('.borrar');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var id, databloque;
        id = $(this).data('id');
        databloque = $(this).parents('.botones-slide').first().data('bloque');

        $.confirm( {
            title: 'Borrar '+databloque,
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar esta '+databloque+'?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        eliminarSlideCaluga();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarSeguro(id, databloque);
                    }
                }
            }
        } );
    });
}

function borrarSeguro(id, databloque) {
    $.post(
        urlSitio + 'portada', {
            ajax: true,
            accion: 'borrarSlideCaluga',
            id: id,
            bloque: databloque
        }, function (data) {
            var bloque = $('#'+databloque+'-'+id);

            if (!data.success) {
                $.alert({
                    title: 'Borrar',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                eliminarSlideCaluga();
                            }
                        }
                    }
                });
                return;
            }


            $.alert({
                title: 'Borrar',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            eliminarSlideCaluga();
                            bloque.remove();
                        }
                    }
                }
            });
        }, 'json'
    );
}

function activarOrden() {
    $('.orden').sortable({
        update: function (event, ui) {
            var caja, grupo;
            caja = $(this).sortable('toArray');
            grupo = $(this).data('grupo');

            $.post( urlSitio + 'portada', {
                accion: "guardarOrden",
                ajax: true,
                caja: caja,
                grupo: grupo
            }, function( data ) {
                if (!data.success) {
                    $.alert({
                        title: 'Ordenar',
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