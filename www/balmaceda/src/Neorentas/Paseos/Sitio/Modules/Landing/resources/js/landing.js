$(function(){
    $("#marcas").owlCarousel({
        items: 7,
        margin: 20,
        nav: true,
        navText:  ["<img src='"+urlSitio+"resources/img/flecha-prev.svg'>","<img src='"+urlSitio+"resources/img/flecha-next.svg'>"],
        dots: false,
        autoWidth: false,
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
                items:3,
                nav: true
            },
            600:{
                items:3,
                nav:true
            },
            1000:{
                items:7,
                nav:true
            }
        }
    });
});