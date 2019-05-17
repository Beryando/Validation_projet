$(function(){
    $('nav a[href^="#"]').click(function(){
        var the_id = $(this).attr("href");
        if (the_id === '#') {
            return;
        }
        var posCible = $(the_id).offset().top - $("nav").height();
        $('html, body').animate({
            scrollTop:posCible
        }, 'slow');
        return false;
    });
});