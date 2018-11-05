var urlSitio, urlSitioPaseos, map, popup, paseo, images, activeImage, bounds, locales, primerMapa;

$(function() {
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

    paseo      = "paseo_balmaceda";
    primerMapa = true;
    popup      = L.popup();

    bounds         = [[0,0], [690, 710]];
    urlSitio       = $('#site-url').data('url');
    urlSitioPaseos = $('#sitio-paseos').data('url');

    cargarMapa(1);
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
}

function activarCerrar() {
    var btn = $('#close');

    btn.on('click', function() {
        var  modal   = $('#modal');

        modal.hide();
    });
}

function activarCerrarLogo() {
    var btn = $('#close-logo');

    btn.on('click', function() {
        var  modal   = $('#modal-logo');

        modal.hide();
    });
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

    $.post(
        urlSitio + 'pagina',
        {
            ajax: true,
            accion: 'obtenerDatosPlano',
            idPlano: idPlano
        }, function (data) {
            activarLocales(data);
            activeImage = L.imageOverlay(urlSitioPaseos + 'uploads/' + data.carpeta + '/mapa/' + data.imagen, bounds).addTo(map);
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


            if (parseInt(datosMapa.locales[i].disponible) === 0) {
                locales[i].on('mouseover', function() {
                    this.setStyle({color: '#003E44', weight: 2, stroke: true, fillOpacity: 0.1});
                });

                locales[i].on('mouseout', function() {
                    this.setStyle({stroke: false, fillOpacity: 0});
                });

                locales[i].on('click', function(event) {
                    var idLocal;
                    idLocal = event.target.options.idLocal;

                    $.post(
                        urlSitio + 'pagina',
                        {
                            ajax: true,
                            accion: 'cargarDatosTienda',
                            idLocal: idLocal
                        }, function (data) {
                            $('#caja-mapa-tienda').html(data.html);
                        }, 'json'
                    );
                });
            }
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
