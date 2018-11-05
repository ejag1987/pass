var clock;

$(function(){
    // Grab the current date
    var currentDate = new Date();
    var reloj = $('.clock');


    // Set some date in the future. In this case, it's always Jan 1
    //var futureDate  = new Date(currentDate.getFullYear() + 1, 0, 1);
    var futureDate  = new Date(reloj.data('apertura'));

    // Calculate the difference in seconds between the future and current date
    var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;

    // Instantiate a coutdown FlipClock
    clock = reloj.FlipClock(diff, {
        clockFace: 'DailyCounter',
        countdown: true,
        language: 'spanish'
    });

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