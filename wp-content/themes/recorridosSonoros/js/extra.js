jQuery(function($) {
$( document ).ready(function() {
    addEvents()
    shareUrls()
});

function addEvents(){
    //Selector de idioma
    $(".language-selector, .container-select-language").click(function(){
        $('#language-list').toggleClass('d-none')
    });

    //Menu recorridos virtuales
    $(document).on("click",function(e) {
      var container = $(".opc-menu-desk");
      var container2 = $(".icon-menu");

      if (!container.is(e.target) && container.has(e.target).length === 0) { 
        if (!container2.is(e.target) && container2.has(e.target).length === 0) {            
          $('.opc-menu-desk').css('display','none')        
        }else{
          if(container.css("display")=="block")   {
              $('.opc-menu-desk').css('display','none') 
          }
          else{  
            $('.opc-menu-desk').css('display','block') 
          } 
        } 
      }      
    });

    //Boton ver todos los recorridos 
        $(document).on("click",function(e) {
            var container = $(".opc-menu-desk2");
            var container2 = $(".icon-menu2");
      
            if (!container.is(e.target) && container.has(e.target).length === 0) { 
              if (!container2.is(e.target) && container2.has(e.target).length === 0) {            
                $('.opc-menu-desk2').css('display','none')        
              }else{
                if(container.css("display")=="block")   {
                    $('.opc-menu-desk2').css('display','none') 
                }
                else{  
                  $('.opc-menu-desk2').css('display','block') 
                } 
              } 
            }      
          });


}

function buscar(valor){
}

function shareUrls(name){
    titulo = encodeURI(document.title.split("|",1))
    permalink = encodeURI(window.location.href)
    urlTwitter="https://twitter.com/intent/tweet?text=¡Disfruta de Patrimonio Virtual y recorre el "+titulo+"desde cualquier lugar! - Patrimonio Virtual- "+permalink
    $('.shareX-twitter').attr('href',urlTwitter)
    $('.shareX-twitter').click(function(){
        genericSocialShare(urlTwitter)
    })

    urlFacebook="https://www.facebook.com/sharer/sharer.php?u="+permalink
    $('#share-facebook').attr('href','javascript:void(0)')
    $('#share-facebook').click(function(){
        genericSocialShare(urlFacebook)
    })
}

function genericSocialShare(url){
    window.open(url,'sharer','toolbar=0,status=0,width=648,height=395');
    return true;
}
});

