$(function() {
    $('.disponible').bootstrapSwitch({
        onColor: 'success',
        offColor: 'info',
        offText: 'Disponible',
        onText: 'Arrendado',
        onSwitchChange: function(event, state) {
            cambiarEstado($(event.target).data('id'), state);
        }
    });

    $('.descripcion').hide();

    $('.reservado').on('change', function() {
        var idLocal, check, reservado;
        idLocal = $(this).data('id');
        check = $(this).find('[type=checkbox]');
        reservado = !!check.is( ':checked' );

        cambiarReserva(idLocal, reservado);
    });

    abrirDetalles();
    crearNuevoLocal();
});

function crearNuevoLocal() {
    var boton = $('#nuevo-local');

    boton.on('click', function() {
        boton.off('click');

        $.post(
            urlSitio + 'plano', {
                ajax: true,
                accion: 'nuevoLocal',
                idPlano: $('#id-plano').val()
            }, function (data) {
                if (!data.success) {
                    $.alert({
                        title: 'Crear local',
                        icon: 'fa fa-warning text-danger',
                        content: data.message,
                        boxWidth: '30%',
                        useBootstrap: false,
                        autoClose: 'confirm|4000',
                        buttons: {
                            confirm: {
                                text: 'Listo',
                                action: function () {
                                    crearNuevoLocal();
                                }
                            }
                        }
                    });
                    return;
                }

                var bloque = $('#bloque-locales');
                bloque.prepend(data.html);

                $('.descripcion').hide();

                $('.disponible').bootstrapSwitch({
                    onColor: 'success',
                    offColor: 'info',
                    offText: 'Disponible',
                    onText: 'Arrendado',
                    onSwitchChange: function(event, state) {
                        cambiarEstado($(event.target).data('id'), state);
                    }
                });

                abrirDetalles();
                crearNuevoLocal();

                //eliminarLocal();


            }, 'json'
        );
    });
}

function abrirDetalles() {
    var boton = $('.abrir-descripcion');

    boton.on('click', function() {
        var idLocal, objeto;
        idLocal = $(this).data('id');
        objeto = $(this);

        $("#descripcion-"+idLocal).slideToggle("slow", function() {
            $('#puntos-'+idLocal).hide();

            if (objeto.hasClass('fa-chevron-down')) {
                objeto.removeClass('fa-chevron-down');
                objeto.addClass('fa-chevron-up');

                var disponible = $('#disponible-'+idLocal).bootstrapSwitch('state');

                cargarDatos(idLocal, disponible);

                guardarDetalle(idLocal);
            } else {
                objeto.removeClass('fa-chevron-up');
                objeto.addClass('fa-chevron-down');

                $('#chevron-'+idLocal).off('click');
                $('#guardar-local-'+idLocal).off('click');
            }


        });
    });
}

function cambiarEstado(idLocal, disponible) {
    $.post(
        urlSitio + 'plano', {
            ajax: true,
            accion: 'cambiarEstadoLocal',
            idLocal: idLocal,
            disponible: disponible
        }, function (data) {
            if (!data.success) {
                $.alert({
                    title: 'Cambiar disponibilidad',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                cambiarEstado(idLocal, disponible);
                            }
                        }
                    }
                });
            }


            // cargar nuevos datos
            cargarDatos(idLocal, disponible);
        }, 'json'
    );
}

function activarPuntos(botonChevron) {
    var boton = $(botonChevron);

    boton.on('click', function() {
        var idLocal, objeto;
        idLocal = $(this).data('id');
        objeto = $(this);

        $("#puntos-"+idLocal).slideToggle("slow", function() {
            if (objeto.hasClass('fa-chevron-down')) {
                objeto.removeClass('fa-chevron-down');
                objeto.addClass('fa-chevron-up');
            } else {
                objeto.removeClass('fa-chevron-up');
                objeto.addClass('fa-chevron-down');
            }

            activarPuntos();
        });
    });
}

function guardarDetalle(idLocal) {
    var boton = $('#guardar-local-'+idLocal);

    boton.on('click', function() {
        //boton.off('click');
        var formulario, terminaciones, datos, result, error;

        formulario = $('#local-'+idLocal);
        terminaciones = formulario.find('.terminacion');

        var valTerminaciones = [];

        terminaciones.each(function(){
            if ($(this).val() != '') {
                var valores = [];
                valores.push($(this).data('id'));
                valores.push($(this).val());

                valTerminaciones.push(valores);
            }
        });

        error = $('#error-datos-'+idLocal);
        datos = camposValidar('local-'+idLocal);
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
        result.datos.accion   = 'guardarDatos';
        result.datos.idLocal  = idLocal;
        result.datos.terminaciones = valTerminaciones;

        $.post(
            urlSitio + 'plano', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    //guardarDetalle(idLocal);

                    return;
                }

                error.addClass('text-success');
                //guardarDetalle(idLocal);

            }, 'json'
        );
    });
}

function subirImagen(idLocal) {
    var upload = $('#tipo-local-'+idLocal).find('.uploader');

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
                accion: 'subirLocal'
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
            data.formData.tipo = $(this).data('tipo');
            data.formData.idLocal = $(this).data('id');
            data.formData.idPlano = $('#id-plano').val();

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
                var bloque = $('#bloque-subir-'+ data.result.tipo+'-'+ data.result.idLocal);
                data.context.remove();
                bloque.empty();
                bloque.html(data.result.html);
                borrarImagenLocal();
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

function borrarImagenLocal() {
    var basurero = $('.borrar');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var idLocal = $(this).data('id');

        $.confirm( {
            title: 'Borrar plano local',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar el plano?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        borrarImagenLocal();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarImagenLocalSeguro(idLocal);
                    }
                }
            }
        } );
    });
}

function borrarImagenLocalSeguro(idLocal) {
    $.post(
        urlSitio + 'plano', {
            ajax: true,
            accion: 'borrarPlanoLocal',
            idLocal: idLocal,
            idPlano: $('#id-plano').val()
        }, function (data) {
            var bloque = $('#bloque-subir-'+idLocal);

            if (!data.success) {
                borrarImagenLocal();
                return;
            }

            bloque.empty();
            bloque.html(data.html);
            subirImagen(idLocal);
        }, 'json'
    );
}

function cargarDatos(idLocal, disponible) {
    $.post(
        urlSitio + 'plano', {
            ajax: true,
            accion: 'cargarContenido',
            idLocal: idLocal,
            disponible: disponible
        }, function (data) {
            if (!data.success) {

            }

            if (!disponible) {
                $('#switch-reservado-'+idLocal).removeClass('hide');
            } else {
                $('#switch-reservado-'+idLocal).addClass('hide');
            }

            var bloque = $('#tipo-local-'+idLocal);
            bloque.empty();
            bloque.html(data.html);

            subirImagen(idLocal);
            borrarImagenLocal();
        }, 'json'
    );
}

function cambiarReserva(idLocal, reservado) {
    $.post(
        urlSitio + 'plano', {
            ajax: true,
            accion: 'cambiarReservaLocal',
            idLocal: idLocal,
            reservado: reservado
        }, function (data) {
            if (!data.success) {
                $.alert({
                    title: 'Cambiar reserva',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                cambiarReserva(idLocal, reservado);
                            }
                        }
                    }
                });
            }
        }, 'json'
    );
}

