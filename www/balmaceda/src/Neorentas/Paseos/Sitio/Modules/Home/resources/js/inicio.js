var calugas, x ,url, posicion, tiempo, animacion;

$(function(){
    calugas = [];
    tiempo = 4000;
    animacion = 500;

    cambiaImagen();
	$(window).on("resize", function (e) {
        checkScreenSize();
    });

    checkScreenSize();
	
    $("#slide").owlCarousel({
        items: 1,
        nav: false,
        navText:  ["<img src='"+urlSitio+"resources/img/flecha-prev.svg'>","<img src='"+urlSitio+"resources/img/flecha-next.svg'>"],
        dots: true,
        dotsEach: true,
        autoWidth: false,
        autoplay: true,
        loop: true,
        autoplaySpeed: 1000,
        smartSpeed: 150,
        fluidSpeed: 500,
        responsiveClass: true,
        responsive:{
            0:{
                items:1,
                nav: true,
                dots: false
            },
            600:{
                items:1,
                nav:false
            },
            1000:{
                items:1,
                nav:false
            }
        }
    });

    $("#marcas").owlCarousel({
        items: 7,
        margin: 20,
        nav: true,
        navText:  ["<img src='"+urlSitio+"resources/img/flecha-prev.svg'>","<img src='"+urlSitio+"resources/img/flecha-next.svg'>"],
        dots: false,
        autoWidth: true,
        autoplay: true,
        autoplaySpeed: 1000,
        smartSpeed: 150,
        fluidSpeed: 500,
        /*autoplayTimeout: 100,*/
        loop: true,
        stagePadding: 0,
        responsiveClass: true,
        responsive:{
            0:{
                items:1,
                nav: true,
                autoWidth: true,
                center:true
            },
            600:{
                items:2,
                nav:true
            },
            1000:{
                items:3,
                nav:true
            }
        }
    });
});

function checkScreenSize(){
    var newWindowWidth, item, movil, desktop;

    newWindowWidth = $(window).width();
    item = $('#slide').find('.item').find('img');

    if (newWindowWidth < 769) {
        $.each(item, function(index, value) {
            movil   = $(value).data('movil');
           
            if (movil == '') {
                movil = $(value).data('desktop');
            }

            var altoPagina = document.documentElement.clientHeight-130-52;

            if (newWindowWidth < 480 && altoPagina < 460 && document.documentElement.clientHeight > newWindowWidth) {
                $(value).parents('.item').first().css('height', altoPagina);
            }
            
            $(value).attr('src', movil);
        });
    } else {
        $.each(item, function(index, value) {
            desktop = $(value).data('desktop');

            $(value).attr('src', desktop);
        });
    }
}


function cambiaImagen(){    
          
    $.post( urlSitio + 'home', {
                accion: "mostrarImagen",
                ajax: true
            }, function(data) {
                if (data.success) {
                    var total = data.calugas.length;

                    if (total > 2) {
                        for (var i = 0; i < total; i++) {
                            // var urlImagen = 'http://www.paseos.cl/uploads/paseo_balmaceda/caluga/' + imagenJson.imagen;
                            calugas.push(data.calugas[i]);
                        }
                        url= data.url;
                        x = 2;
                        posicion = '#izquierda';
                        window.setTimeout(animarImagen, tiempo);
                    }
                } 
            }, "json"       
    );    
}


function animarImagen(){
    if (x > calugas.length-1) {
        x=0;
    }


    $(posicion).fadeToggle(animacion, function(){
        $(this).attr('src', url + calugas[x].imagen);            
    }).fadeToggle(animacion, function () {
        if (posicion === '#izquierda') {
            posicion = '#derecha';
        } else {
            posicion = '#izquierda';
        }
        x++;
        window.setTimeout(animarImagen, tiempo);
    });
}



 /*    $('#izquierda').fadeToggle(2000, function(){
        $(this).attr('src', url + calugas[x].imagen);
        
        }).fadeToggle(1400);




/*
function animaIzquierda(){

    if (x > calugas.length -1) {
        x=0;
           }
    
    $('#izquierda').fadeToggle(2000, function(){
        $(this).attr('src', url + calugas[x].imagen);
        x++;
    }).fadeToggle(1000);
    if (x === calugas.length){
        x=0;
    }
    $('#derecha').fadeToggle(3000, function(){
        $('#derecha').attr('src', url + calugas[x].imagen);
        x++;
    }).fadeToggle(1000);
     
 
}
*/