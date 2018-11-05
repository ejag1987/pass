$(function() {
    activarEditar();
    activarBorrar();
    activarNuevo();
});

function activarEditar() {
    var boton = $('.editar');

    boton.on('click', function() {
        desactivarBotones();

        var id = $(this).data('id');

        $.post(
            urlSitio + 'usuario',
            {
                ajax: true,
                accion: 'editarUsuario',
                idUsuario: id
            }, function(data) {
                if (!data.success) {

                }

                var bloque = $('#bloque-usuario');
                bloque.empty();
                bloque.html(data.html);

                activarFunciones();

            }, 'json'
        );
    });
}

function desactivarBotones() {
    $('.editar').off('click');
    $('#nuevo-usuario').off('click');
}

function activarBotones() {
    activarNuevo();
    activarEditar();
}

function activarNuevo() {
    var boton = $('#nuevo-usuario');

    boton.on('click', function() {
        desactivarBotones();

        $.post(
            urlSitio + 'usuario',
            {
                ajax: true,
                accion: 'editarUsuario',
                idUsuario: 0
            }, function(data) {
                if (!data.success) {

                }

                var bloque = $('#bloque-usuario');
                bloque.empty();
                bloque.html(data.html);

                activarFunciones();

            }, 'json'
        );
    });
}

function activarFunciones() {
    $('#telefono').inputmask('(99 9) 9999 9999');

    $('.select2').select2({
        allowClear: true,
        language: "es",
        width: '100%',
        minimumResultsForSearch: Infinity
    });

    $('#rut').on('blur', function(){
        formatRut($(this).val(), $(this).attr('id'));
    });

    validarPassword();

    activarGuardar();
    activarCancelar();
    activarBorrar();

    activarVerificar();
}

function activarGuardar() {
    var boton = $('#guardar');

    boton.on('click', function() {
        boton.off('click');

        var datos, result, error, idUsuario;

        error = $('#error-agregar');
        datos = camposValidar('form-usuario');
        result = $.parseJSON(datos);
        idUsuario = $('#id-usuario').val();

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Existen campos no validos y/o vacíos.');
            error.addClass('text-danger');

            activarGuardar();
            return;
        }

        if (idUsuario == 0) {
            var contrasena = $('#contrasena');

            if (contrasena.val() == '') {
                error.html('Debe registrar una contraseña para el usuario.');
                error.addClass('text-danger');

                contrasena.parents('.form-group').first().addClass('has-error');
            }
        }

        result.datos.ajax      = true;
        result.datos.accion    = 'guardarDatos';
        result.datos.idUsuario = idUsuario;

        $.post(
            urlSitio + 'usuario', result.datos, function (data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarGuardar();

                    return;
                }

                $('#id-usuario').val(data.idUsuario);
                error.addClass('text-success');
                activarGuardar();

                if (idUsuario > 0) {
                    $('#item-'+idUsuario).replaceWith(data.html);
                } else {
                    $('#listado-usuarios').append(data.html);
                }

                setTimeout( function () {
                    error.empty();
                }, 3000 );

            }, 'json'
        );
    });
}

function activarCancelar() {
    $('.cerrar-usuario').on('click', function() {
        $('#bloque-usuario').empty();

        activarBotones();
    });
}

function activarBorrar() {
    var boton = $('.borrar');

    boton.on('click', function() {
        var id, botonId;
        id = $(this).data('id');
        botonId = $(this).attr('id');

        $.confirm( {
            title: 'Borrar usuario',
            icon: 'fa fa-exclamation-triangle text-danger',
            content: '¿Está seguro que desea borrar este usuario?',
            boxWidth: '30%',
            useBootstrap: false,
            buttons: {
                cancel: {
                    text: 'No borrar',
                    action: function () {
                        activarBorrar();
                    }
                },
                confirm: {
                    text: 'Borrar',
                    action: function () {
                        borrarUsuario(id, botonId);
                    }
                }
            }
        } );
    });
}

function borrarUsuario(idUsuario, botonId) {
    $.post(
        urlSitio + 'usuario',
        {
            ajax: true,
            accion: 'borrarUsuario',
            idUsuario: idUsuario
        }, function(data) {
            if (!data.success) {
                $.alert({
                    title: 'Borrar usuario',
                    icon: 'fa fa-warning text-danger',
                    content: data.message,
                    boxWidth: '30%',
                    useBootstrap: false,
                    autoClose: 'confirm|4000',
                    buttons: {
                        confirm: {
                            text: 'Listo',
                            action: function () {
                                activarBorrar();
                            }
                        }
                    }
                });
                return;
            }

            $.alert({
                title: 'Borrar usuario',
                icon: 'fa fa-check-circle text-success',
                content: data.message,
                boxWidth: '30%',
                useBootstrap: false,
                autoClose: 'confirm|4000',
                buttons: {
                    confirm: {
                        text: 'Listo',
                        action: function () {
                            activarBorrar();
                            $('#item-'+idUsuario).remove();

                            if (botonId == 'eliminar') {
                                $('#bloque-usuario').empty();

                                activarBotones();
                            }
                        }
                    }
                }
            });

        }, 'json'
    );
}

function activarVerificar() {
    var boton = $('#verificar');

    boton.on('click', function() {
        boton.off('click');

        var idUsuario, error;

        idUsuario = $('#id-usuario').val();
        error = $('#error-agregar');

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        $.post(
            urlSitio + 'usuario',
            {
                ajax: true,
                accion: 'verificarUsuario',
                idUsuario: idUsuario
            }, function(data) {
                error.html(data.message);

                if (!data.success) {
                    error.addClass('text-danger');
                    activarVerificar();

                    return;
                }

                error.addClass('text-success');

                var bloque = $('#bloque-usuario');

                $('#item-'+idUsuario).replaceWith(data.htmlLista);
                bloque.empty();
                bloque.html(data.htmlEditar);

                activarFunciones();

            }, 'json'
        );
    });
}

function validarPassword() {
    var input = $('#contrasena');

    input.keyup(function() {
        var pswd = $(this).val();

        if ( pswd.length < 8 ) {
            $('#length').removeClass('valid').addClass('invalid');
        } else {
            $('#length').removeClass('invalid').addClass('valid');
        }

        //validate letter
        if ( pswd.match(/[A-z]/) ) {
            $('#letter').removeClass('invalid').addClass('valid');
        } else {
            $('#letter').removeClass('valid').addClass('invalid');
        }

        //validate capital letter
        if ( pswd.match(/[A-Z]/) ) {
            $('#capital').removeClass('invalid').addClass('valid');
        } else {
            $('#capital').removeClass('valid').addClass('invalid');
        }

        //validate number
        if ( pswd.match(/\d/) ) {
            $('#number').removeClass('invalid').addClass('valid');
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }

        if (pswd.match(/[!,%,&,@,#,$,^,*,?,_,~]/)) {
            $('#special').removeClass('invalid').addClass('valid');
        } else {
            $('#special').removeClass('valid').addClass('invalid');
        }

    }).focus(function() {
        $('#pswd_info').show();
    }).blur(function() {
        $('#pswd_info').hide();
    });
}

