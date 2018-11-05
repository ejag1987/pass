var urlSitio, map, popup, images, activeImage, bounds, locales, primerMapa;

$(function() {
    var idPlano;

    activarBotonesNivel();

    map = L.map('mapa', {
        crs: L.CRS.Simple,
        zoomControl: true,
        trackResize: true,
        dragging: true,
        boxZoom: true,
        doubleClickZoom: false,
        scrollWheelZoom: false,
        attributionControl: true
    });
	
	map.options.minZoom = setInitialMapZoom();

    primerMapa = true;
    popup      = L.popup();

    bounds   = [[0,0], [690, 710]];
    //bounds   = [[0,0], [296, 305]];
    urlSitio = $('#site-url').data('url');

    idPlano = $('#id-plano-defecto').val();
    cargarMapa(parseInt(idPlano));
    map.fitBounds(bounds);
    // map.on('click', onMapClick);
});

function setInitialMapZoom() {

    var viewportWidth = window.innerWidth;
    var mapZoom;

    if (viewportWidth < 480) {
        mapZoom = -1;
    }    else if (viewportWidth >= 769 && viewportWidth < 1000) {
        mapZoom = 0;
    }    else {
        mapZoom = 0;
    }

    return mapZoom;
}

function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("You clicked the map at " + e.latlng.toString())
        .openOn(map);

    console.log(e.latlng);
}

function activarBotonesNivel()
{
    $('.btn-nivel').on('click', function () {
        var idPlano;

        $('.btn-nivel').removeClass('is-active');
        $(this).addClass('is-active');

        idPlano = parseInt($(this).data('id-plano'));
        cargarMapa(idPlano);
    });
}

function cargarMapa(idPlano)
{
    var datosMapa;

    if (!primerMapa) {
        removerPoligonos();
        activeImage.remove();
    } else {
        primerMapa = false;
    }

    // simulacion obtencion de plano desde base de datos
    // datosMapa   = obtenerDatosPlano(idPlano);

    $.post(
        urlSitio + 'pagina',
        {
            ajax: true,
            accion: 'obtenerDatosPlano',
            idPlano: idPlano
        }, function (data) {
            activarLocales(data);
            activeImage = L.imageOverlay(urlSitio + 'uploads/' + data.carpeta + '/mapa/' + data.imagen, bounds).addTo(map);

            var pie = $('.pie','#referencia-plano');
            var fecha = $('.fecha','#referencia-plano');

            pie.empty();
            fecha.empty();

            pie.html(data.pie_imagen);
            fecha.html(data.fecha_actualizacion);

        }, 'json'
    );

}

function activarLocales(datosMapa)
{
    var totalLocales;

    totalLocales = datosMapa.locales.length;
    locales      = [];

    for (var i = 0; i < totalLocales; i++) {
        if (typeof datosMapa.locales[i].poligono != 'undefined') {
            locales[i] = L.polygon(
                datosMapa.locales[i].poligono,
                {stroke: false, fillOpacity: 0, color: 'red', idLocal: datosMapa.locales[i].id_local}
            ).addTo(map);

            locales[i].on('mouseover', function() {
                this.setStyle({color: '#003E44', weight: 2, stroke: true, fillOpacity: 0.1});
            });

            locales[i].on('mouseout', function() {
                this.setStyle({stroke: false, fillOpacity: 0});
            });

            locales[i].on('click', function(event) {
                var idLocal, btnDescargar;
                idLocal = event.target.options.idLocal;

                var click = $(this);

                $.post(
                    urlSitio + 'pagina',
                    {
                        ajax: true,
                        accion: 'cargarFichaLocal',
                        idLocal: idLocal
                    }, function (data) {
                        $("#myModal").modal();

                        if (data.html.length > 0) {
                            if (data.disponible == 0) {

                                $('.modal-body').html(data.html);
                                $('.modal-dialog').css('min-width', 'auto');
                            } else {
                                $('.modal-body').html(data.html);
                                $('.modal-dialog').css('min-width', '80%');

                                btnDescargar = $('#descargar-pdf');
                                btnDescargar.off('click');
                                btnDescargar.on('click', function () {
                                    window.open(urlSitio + 'ficha/' + idLocal);
                                });
                            }
                        }

                    }, 'json'
                );
            });
        }
    }
}

function removerPoligonos()
{
    var totalLocales;

    if (typeof locales != 'undefined') {
        totalLocales = locales.length;

        for (var i = 0; i < totalLocales; i++) {
            locales[i].remove();
        }
    }
}
