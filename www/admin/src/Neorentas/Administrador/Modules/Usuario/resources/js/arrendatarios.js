var boton;
$(function() {
    boton = $('#descarga-excel');
    activarBoton();
});

function activarBoton() {
    boton.on('click', $.debounce(3000, true, descarga));
}

function descarga() {
    //boton.off('click');
    boton.addClass('is-loading');

    var url = urlSitio + 'usuario/excel';
    window.open(url);

    //activarBoton();
    boton.removeClass('is-loading');
}