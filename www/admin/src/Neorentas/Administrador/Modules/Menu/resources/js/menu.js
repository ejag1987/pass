$(function() {
    activarSelect();
    activarCrear();
});

function activarSelect() {
    $('#paseos').select2({
        language: "es",
        width: '100%',
        allowclear: true,
        placeholder: 'Seleccionar paseo'
    }).on("change", function(e) {
        var idSitio = $(this).val();
        var menu = $('#menu-paseos');
        menu.empty();

        $.post(
            urlSitio + 'menu', {
                ajax: true,
                accion: 'cambiarPaseo',
                idSitio: idSitio
            }, function (data) {

                if (!data.success) {

                    return false;
                }
                menu.html(data.html);
            }, 'json'
        );
    });

}

function activarCrear() {
    var boton = $('#crear-sitio');

    boton.on('click', function() {
        boton.off('click');

        window.location = urlSitio + 'sitio/nuevo';
    });
}