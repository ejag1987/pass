$(function() {
    activarGuardarDatos();
    CKEDITOR.replace('editor');

    activarSubirImagen();
    borrarImagen();

    activarGuardarInformacion();
});

function activarGuardarDatos() {
    var boton = $('#guardar-datos');

    boton.on('click', function(){
        boton.off('click');
        var datos, result, error;

        error = $('#error-datos');
        datos = camposValidar('datos-sitio');
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');

            return;
        }

        result.datos.ajax   = true;
        result.datos.accion = 'guardarDatos';
        result.datos.idSitio = $('#id-sitio').val();

        $.post(
            urlSitio + 'configuracion', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardarDatos();

                    return;
                }

                error.addClass('text-success');
                activarGuardarDatos();

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
        var idTipo, target;

        $(this).fileupload({
            url: urlSitio + 'configuracion',
            dataType: 'json',
            autoUpload: true,
            dropZone: $(this),
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
        } ).on( 'fileuploadadd', function (e, data) {
            idTipo = e.target.dataset.id;
            target = e.target;

            $( target ).addClass( 'hide' );

            data.context = $( '<div/>' )
                .appendTo( '#subir-'+idTipo )
                .addClass( 'file' );

            $.each( data.files, function (index, file) {
                var node = addFile( file );
                data.context.append( node );
            } );
        } ).on( 'fileuploadprocessalways', function (e, data) {
            data.formData.tipo     = $(this).data('id');
            data.formData.idPagina = $('#id-pagina').val();
            data.formData.idSitio  = $('#id-sitio').val();

            var index = data.index,
                file = data.files[index],
                node = $( data.context.children()[index] );

            if (file.preview) {
                node.prepend( file.preview );
            }

            if (file.error) {
                node.append( $( '<span class="text-danger"/>' ).text( file.error ) );
            }

            if (index + 1 === data.files.length) {
                data.context.find( '.file-status' )
                    .text( 'En cola...' )
                    .prop( 'disabled', !!data.files.error );
            }
        } ).on( 'fileuploadprogress', function (e, data) {
            var progress = parseInt( data.loaded / data.total * 100, 10 );

            data.context.find( '.progress-bar' ).val( progress );
            data.context.find( '.file-status' ).text( progress + '%' );
        } ).on( 'fileuploaddone', function (e, data) {
            if (!data.result.success) {
                data.context.find( '.file-status' ).text( 'Error!!!' );
                data.context.find( '.progress-bar' )
                    .removeClass( 'is-primary' )
                    .addClass( 'is-danger' );
            } else {
                data.context.find( '.file-status' ).text( 'OK!!!' );
                data.context.find( '.progress-bar' )
                    .removeClass( 'is-primary' )
                    .addClass( 'is-success' );
            }

            setTimeout( function () {
                var bloque = $( '#bloque-'+ data.result.tipo);
                data.context.remove();
                bloque.empty();
                bloque.html( data.result.html );

                if (!data.result.success) {
                    activarSubirImagen();
                } else {
                    borrarImagen();

                    $('#id-pagina').val(data.result.idPagina);
                }
            }, 2000 );
        } ).on( 'fileuploadfail', function (e, data) {
            //console.log(data.errorThrown);
            //console.log(data.textStatus);
            //console.log(data.jqXHR);

            $.each( data.files, function (index) {
                data.context.find( '.file-status' ).text( 'Error!!!' );
                data.context.find( '.progress-bar' )
                    .removeClass( 'is-primary' )
                    .addClass( 'is-danger' );
            } );

        } ).prop( 'disabled', !$.support.fileInput )
            .parent().addClass( $.support.fileInput ? undefined : 'disabled' );
    });
}

function borrarImagen() {
    var basurero = $('.borrar');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        var tipo = $(this).data('tipo');

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
                        borrarImagenSeguro(tipo);
                    }
                }
            }
        } );
    });
}

function borrarImagenSeguro(tipo) {
    $.post(
        urlSitio + 'configuracion', {
            ajax: true,
            accion: 'borrarImagen',
            idTipo: tipo,
            idPagina: $('#id-pagina').val(),
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque = $('#bloque-'+tipo);

            if (!data.success) {
                borrarIcono();
                return;
            }

            bloque.empty();
            bloque.html(data.html);
            activarSubirImagen();
            borrarImagen();

        }, 'json'
    );
}

function activarGuardarInformacion() {
    var boton = $('#guardar-acerca');

    boton.on('click', function() {
        boton.off('click');

        var texto, error, datos, result;
        error = $('#error-acerca');
        datos = camposValidar('contenido-texto');
        result = $.parseJSON(datos);
        texto = CKEDITOR.instances['editor'].getData();

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no válidos y/o vacíos.');
            error.addClass('text-danger');

            return;
        }

        if (texto.length == 0) {
            error.html('Desbes llenar el texto para guardar.');
            error.addClass('text-danger');

            return false;
        }

        result.datos.ajax     = true;
        result.datos.accion   = 'guardarTexto';
        result.datos.texto    = texto;
        result.datos.idPagina = $('#id-pagina').val();
        result.datos.idSitio  = $('#id-sitio').val();

        $.post(
            urlSitio + 'configuracion', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardarInformacion();

                    return;
                }

                $('#id-pagina').val(data.idPagina);
                error.addClass('text-success');
                activarGuardarInformacion();

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );
    });
}
