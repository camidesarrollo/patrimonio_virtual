jQuery(function($) {
    setTimeout(function() {
        $(".loader").fadeOut(1500);
    },2000);

$(window).on('load', function(){
    	
    $(".navbar-nav > .menu-item-has-children > a , .menu-mov > .menu-item-has-children > a " ).wrap( "<div class='sub-menu-wrapper d-flex w-100 justify-content-center align-items-center'></div>" );
    $(".navbar-nav .sub-menu-wrapper").append("<i class='fas fa-angle-down icono-menu-desplegable' aria-hidden='true'></i>")
    $(".menu-mov .sub-menu-wrapper").append("&nbsp;<i class='fas fa-caret-down icono-menu-desplegable-mob' aria-hidden='true'></i>")
    $(".menu-mov .sub-menu").addClass('drop-menu collapse')
    if ($("#main").hasClass("multimedia")) {
        $(".navbar-nav a:contains('Multimedia')").closest("li").addClass("current-menu-item");
      }

    $(".icono-menu-desplegable").on('click', function(){
        $(this).parent().parent().siblings().find('.sub-menu').hide()
        $(this).parent().siblings('.sub-menu').fadeToggle("slow", "linear" )
    })
    $(".icono-menu-desplegable-mob").on('click', function(){
        $(this).parent().parent().siblings().find('.sub-menu').removeClass('show')
        $(this).parent().siblings('.sub-menu').toggleClass('show')
    })
    $(window).on('click', function(event){
        if(!$(event.target).hasClass('sub-menu') && !$(event.target).hasClass('icono-menu-desplegable')){
            $('.navbar-nav .sub-menu').hide()
        }
    })
    
    $('#preguntas-frecuentes-tab .nav-link').on('click', function(){
        console.log('hola')
        newActiveId = $(this).data('pane')
        $('#preguntas-frecuentes-tab .tab-pane').removeClass('show')
        $('#preguntas-frecuentes-tab .tab-pane').removeClass('active')
        $(newActiveId).addClass('show active')
    })

    $('.navbar-nav  .menu-item-has-children .sub-menu').each(function(){
        if($(this).children().length>8){
            $(this).addClass('two-columns')
        }

    })
})

});

window.addEventListener('DOMContentLoaded', () => {
  if (typeof datosUsuarioEncriptado !== 'undefined') {
    metodoAPISeeker(datosUsuarioEncriptado);
  }
});
