$(function() {
    $('#telefono').inputmask('(99 9) 9999 9999');

    activarGuardarDatos();
    activarSubirImagen();
    borrarImagen();
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

            activarGuardarDatos();
            return;
        }

        result.datos.ajax     = true;
        result.datos.accion   = 'guardarDatos';
        result.datos.idPagina = $('#id-pagina').val();
        result.datos.idSitio  = $('#id-sitio').val();

        $.post(
            urlSitio + 'contacto', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardarDatos();

                    return;
                }

                $('#id-pagina').val(data.idPagina);
                error.addClass('text-success');
                activarGuardarDatos();

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );
    });
}

function activarSubirImagen() {
    var target;

    $('.uploader').fileupload({
        url: urlSitio + 'contacto',
        dataType: 'json',
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
    } ).on( 'fileuploadadd', function (e, data) {
        target = e.target;

        $(target).addClass('hide');

        data.context = $( '<div/>' )
            .appendTo('#subir')
            .addClass('file');

        $.each( data.files, function (index, file) {
            var node = addFile( file );
            data.context.append( node );
        } );
    } ).on( 'fileuploadprocessalways', function (e, data) {
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
            activarSubirImagen();
        } else {
            data.context.find( '.file-status' ).text( 'OK!!!' );
            data.context.find( '.progress-bar' )
                .removeClass( 'is-primary' )
                .addClass( 'is-success' );
        }


        $('#id-pagina').val(data.result.idPagina);
        setTimeout( function () {
            var bloque = $( '#bloque-fondo');
            data.context.remove();
            bloque.empty();
            bloque.html( data.result.html );
            borrarImagen();
        }, 1000 );

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
}

function borrarImagen() {
    var basurero = $('.borrar');

    basurero.off('click');

    basurero.on('click', function() {
        basurero.off('click');

        $.confirm( {
            title: 'Borrar fondo',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar el fondo?',
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
                        borrarImagenSeguro();
                    }
                }
            }
        } );
    });
}

function borrarImagenSeguro() {
    $.post(
        urlSitio + 'contacto', {
            ajax: true,
            accion: 'borrarImagen',
            idPagina: $('#id-pagina').val(),
            idSitio: $('#id-sitio').val()
        }, function (data) {
            var bloque = $('#bloque-fondo');

            if (!data.success) {
                borrarImagen();
                return;
            }

            bloque.empty();
            bloque.html(data.html);
            activarSubirImagen();
        }, 'json'
    );
}