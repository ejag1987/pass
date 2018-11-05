$(function () {
    var codigo = getUrlVars()["c"];
    if (codigo) {
        $.post(
            urlSitio + 'pagina',
            {
                ajax: true,
                accion: 'validarCorreo',
                controller: 'login',
                codigo: codigo
            }, function (data) {
                $('.modal-title').html(data.titulo);
                $('.modal-body').html(data.html);

                $("#myModal").modal('show');

            }, 'json'
        );
    }

    var token = getUrlVars()["p"];

    if (token) {
        $.post(
            urlSitio + 'pagina',
            {
                ajax: true,
                accion: 'cambiarPass',
                controller: 'login',
                token: token
            }, function (data) {
                if (!data.success) {

                }

                $('.modal-title').html(data.titulo);
                $('.modal-body').html(data.html);

                $("#myModal").modal('show');

                $('#confirm-password').on('blur', function() {
                    activarPassConfirm();
                });

                activarCambiarPass(token);
            }, 'json'
        );
    }

    //$('.title','section.pagina').firstWord();

    var verGaleria = $('.ver-fotos');
    verGaleria.on('click', function() {
        var idGaleria = $(this).data('id');

        $.post(
            urlSitio + 'pagina',
            {
                ajax: true,
                accion: 'cargarGaleria',
                idGaleria : idGaleria
            }, function (data) {
                if (!data.success) {
                    return false;
                }

                $('.modal-body').empty();
                $('.modal-body').html(data.html);

                $("#galeriaModal").modal();

                $('#galeriaModal').on('shown.bs.modal', function () {
                    crearCarousel();
                });

            }, 'json'
        );
    });

    activarEnvioCoreo();

    borrarUrl(codigo, token);

});

function borrarUrl(codigo, token) {
    $('#myModal').on('hidden.bs.modal', function (e) {
        if (codigo || token) {
            var url = window.location.pathname;

            window.location.href = url;
        }
    });
}

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}


function crearCarousel() {
    $("#carousel-foto-principal").owlCarousel({
        thumbs: true,
        lazyLoad: true,
        thumbsPrerendered: true,
        //autoHeight:true,
        autoWidth: false,
        items: 1,
        nav: true,
        navText:  ["<img src='"+urlSitio+"resources/img/flecha-prev.svg'>","<img src='"+urlSitio+"resources/img/flecha-next.svg'>"],
        dots: false,
        responsiveClass: true,
        responsive:{
            0:{
                items:1,
                nav: true,
                dots: false
            },
            600:{
                items:1,
                nav:true
            },
            1000:{
                items:1,
                nav:true
            }
        }
    });
}

function activarCambiarPass(token) {
    var boton = $('#boton-cambiar');

    boton.on('click', function() {
        boton.off('click');

        var datos, result, error;

        error = $('#error-login');
        datos = camposValidar('form-pass');
        result = $.parseJSON(datos);

        error.empty();
        error.removeClass('text-danger');
        error.removeClass('text-success');

        if (result.error) {
            error.html('Debes ingresar una contraseña válida.');
            error.addClass('text-danger');

            activarCambiarPass(token);

            setTimeout(function(){
                error.empty();
            }, 4000);
            return;
        }

        if (!$('#confirm-password').data('confirm')) {
            error.html('Las contraseñas no coinciden.');
            error.addClass('text-danger');

            activarCambiarPass(token);

            setTimeout(function(){
                error.empty();
            }, 4000);
            return;
        }

        result.datos.ajax       = true;
        result.datos.accion     = 'cambiarContrasena';
        result.datos.controller = 'login';
        result.datos.token      = token;
        result.datos.idUsuario  = $('#id-usuario').val();

        $.post(
            urlSitio + 'pagina', result.datos, function (data) {
                var modal = $('.modal-body');

                if (!data.success) {
                    error.html(data.message);
                    error.addClass('text-danger');

                    setTimeout(function(){
                        error.empty();
                    }, 4000);

                    activarCambiarPass(token);
                }

                modal.empty();
                modal.html(data.html);

            }, 'json'
        );
    });

}

function activarPassConfirm() {
    // validar confirmar pass igual a password
    var pass = $('#contrasena').val(),
        confirmacion = $('#confirm-password'),
        confirm = confirmacion.val(),
        campo = confirmacion.siblings('.confirm');

    campo.addClass('hide');

    var error = $('#error-login');
    error.empty();
    error.removeClass('text-danger');
    error.removeClass('text-success');

    if ($.trim(pass).length > 0) {
        if (pass === confirm) {
            campo
                .removeClass('hide')
                .removeClass('fa-times')
                .removeClass('text-danger')
                .addClass('text-success')
                .addClass('fa-check');

            confirmacion.data('confirm', true);

            return;
        }

        campo
            .removeClass('hide')
            .removeClass('fa-check')
            .removeClass('text-success')
            .addClass('text-danger')
            .addClass('fa-times');

        confirmacion.data('confirm', false);

        error.html('Las contraseñas no coinciden.');
        error.addClass('text-danger');

        return true;
    }

    error.html('Se debe ingresar una constraseña.');
    error.addClass('text-danger');

    setTimeout(function(){
        error.empty();
    }, 4000);
    return false;
}


function activarEnvioCoreo() {
    var url = getUrlVars()[0].split('/');
    var pagina = url[url.length-1];

    if (pagina.indexOf('contacto') > -1) {
        $('#envio-contacto').on('click', $.debounce(2000, true, cargarEnvioCorreo));
    }
}

function cargarEnvioCorreo() {
    var boton = $('#envio-contacto');

    //boton.off('click');
    boton.addClass('is-loading');

    var datos, result, error;

    error = $('#error-envio');
    datos = camposValidar('form-contacto');
    result = $.parseJSON(datos);

    error.empty();
    error.removeClass('text-danger');
    error.removeClass('text-success');

    if (result.error) {
        error.html('Debes llenar todos los campos para enviar el correo.');
        error.addClass('text-danger');
        boton.removeClass('is-loading');

        //cargarEnvioCorreo();
        return;
    }

    result.datos.ajax   = true;
    result.datos.accion = 'envioCorreo';

    $.post(
        urlSitio + 'pagina', result.datos, function (data) {
            error.html(data.message);

            if (!data.success) {
                error.addClass('text-danger');
                if (!data.enviado || !isset(data.enviado)) {
                    //cargarEnvioCorreo();
                    boton.removeClass('is-loading');
                }
                return;
            }

            $('form').find('p.control').remove();

            //cargarEnvioCorreo();

        }, 'json'
    );
}