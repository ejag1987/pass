var boton, btn, error, link;

$(function() {
    //activarLink();
    //activarBoton();
    //activarReenvioCorreo();

});

function cargarLogin() {
    $.post(
        urlSitio + 'pagina',
        {
            ajax: true,
            accion: 'cargarPaginaLogin',
            controller: 'login'
        }, function (data) {
            if (!data.success) {
                return false;
            }

            $('.modal-title').html(data.titulo);
            $('.modal-body').html(data.html);

            activarLink();
            activarBoton();

            $("#myModal").modal();

        }, 'json'
    );
}

function activarLink() {
    btn = $('.link-interno');
    btn.on('click', $.debounce(1000, true, accionLink));
}

function activarBoton() {
    boton = $('#boton-accion');
    boton.on('click', $.debounce(2000, true, cargarValidacion));
}

function activarLinkReenvioCorreo() {
    link = $('.reenvio');
    link.on('click', $.debounce(1000, true, activarReenvioCorreo));
}

function accionLink() {
    var pagina, accion;
    pagina = $(this).data('pagina');
    accion = 'cargar' + pagina;

    $.post(
        urlSitio + 'pagina',
        {
            ajax: true,
            accion: accion,
            controller: 'login'
        }, function (data) {
            if (!data.success) {
                activarLink();
                return false;
            }

            $('.modal-title').html(data.titulo);

            var content, link1, link2;
            content = $('#formulario');
            link1 = $('#link-1');
            link2 = $('#link-2');

            link1.empty();
            link2.empty();

            content.empty();
            content.html(data.html);

            switch (pagina) {
                case 'Recordar':
                    link1.html(data.link1Text);
                    link1.data('pagina', data.link1);
                    link2.html(data.link2Text);
                    link2.data('pagina', data.link2);
                    break;
                case 'Registro':
                    link1.html(data.link1Text);
                    link1.data('pagina', data.link1);
                    link2.html(data.link2Text);
                    link2.data('pagina', data.link2);
                    break;
                case 'Login':
                    link1.html(data.link1Text);
                    link1.data('pagina', data.link1);
                    link2.html(data.link2Text);
                    link2.data('pagina', data.link2);
                    break;
            }

            activarBoton();
        }, 'json'
    );
}

function cargarValidacion() {
    var datos, result;

    error = $('#error-lightbox');
    datos = camposValidar('form-lightbox');
    result = $.parseJSON(datos);

    error.empty();
    error.removeClass('text-danger');
    error.removeClass('text-success');

    if (result.error) {
        error.html('Debes todos los campos en rojos para continuar.');
        error.addClass('text-danger');

        return;
    }

    boton.addClass('is-loading');

    switch (boton.data('accion')) {
        case 'registro':
            enviarRegistro(result);
            break;
        case 'recordar':
            enviarRecordar(result);
            break;
        case 'login':
            enviarLogin(result);
            break;
    }
}

function enviarLogin(result) {
    result.datos.ajax       = true;
    result.datos.accion     = 'validarUsuario';
    result.datos.controller = 'login';

    $.post(
        urlSitio + 'pagina', result.datos, function (data) {
            if (!data.success) {
                error.html(data.message);
                error.addClass('text-danger');
                activarLinkReenvioCorreo();

                boton.removeClass('is-loading');
                return;
            }

            var url = window.location.href;

            location.href = url + '/mapa';
        }, 'json'
    );
}

function activarReenvioCorreo() {
    $.post(
        urlSitio + 'pagina',
        {
            ajax: true,
            accion: 'reenvioCorreo',
            controller: 'login',
            email: $('#email').val()
        }, function (data) {
            if (!data.success) {
                var mensaje = error.children('#mensaje');

                mensaje.empty();
                error.removeClass('text-danger');
                error.removeClass('text-success');

                mensaje.html(data.message);
                error.addClass('text-danger');

                return;
            }

            error.empty();
            error.removeClass('text-danger');
            error.removeClass('text-success');

            error.html(data.message);
            error.addClass('text-success');
        }, 'json'
    );
}

function enviarRecordar(result) {
    result.datos.ajax       = true;
    result.datos.accion     = 'recordarUsuario';
    result.datos.controller = 'login';

    $.post(
        urlSitio + 'pagina', result.datos, function (data) {
            if (!data.success) {
                error.html(data.message);
                error.addClass('text-danger');
                activarLinkReenvioCorreo();

                boton.removeClass('is-loading');
                return;
            }

            var modal = $('.modal-body');
            modal.empty();
            modal.html(data.html);
        }, 'json'
    );
}

function enviarRegistro(result) {
    result.datos.ajax       = true;
    result.datos.accion     = 'registrarUsuario';
    result.datos.controller = 'login';

    $.post(
        urlSitio + 'pagina', result.datos, function (data) {
            if (!data.success) {
                error.html(data.message);
                error.addClass('text-danger');
                activarLinkReenvioCorreo();

                boton.removeClass('is-loading');
                return;
            }

            var modal = $('.modal-body');
            modal.empty();
            modal.html(data.html);

        }, 'json'
    );
}
