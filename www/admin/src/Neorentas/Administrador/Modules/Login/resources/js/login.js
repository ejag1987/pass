$(function() {
    $('#boton-ingresar').on('click', function (e) {
        var usuario, password, error, loginBox;

        e.preventDefault();

        usuario = $('#usuario').val();
        password = $('#password').val();

        error = $('#error');
        loginBox = $('#login-box');

        if (usuario.length === 0 || password.length === 0) {
            loginBox.addClass('shake-error-login');

            setTimeout(function(){
                loginBox.removeClass('shake-error-login');
            }, 1000);

            error.removeClass('hide');
            error.html('Debes ingresar el usuario y/o la contraseña.');

            return;
        }

        $.post(urlSitio + 'login', {
            accion: 'validarUsuario',
            ajax: true,
            usuario: usuario,
            password: password
        }, function(data) {
            if (!data.success) {
                // crear efecto de shake
                loginBox.addClass('shake-error-login');

                setTimeout(function(){
                    loginBox.removeClass('shake-error-login');
                }, 1000);

                error.removeClass('hide');

                error.html(data.message);

                return;
            }

            window.location.href = urlSitio;
        }, 'json');
    });

});