$(function(){
	$(window).on("resize", function (e) {
        checkScreenSize();
    });

    checkScreenSize();

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