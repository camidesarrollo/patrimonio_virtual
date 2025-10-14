
$(document).ready(function($) {
    if (document.body.classList.contains('home')){
        modaljs();
    }
    
})

function modaljs(){
            /// Aqui funcionalidad de modal dinamico
            $(".modal-backdrop").addClass("dynamic-modal")
            const htmlID = $('.modal-content iframe').attr('id');
            window.addEventListener('message', (event) => {
                const allowedOrigins = ['https://ws.biblioredes.gob.cl'];
                if (!allowedOrigins.includes(event.origin)) {
                    return;
                }
                const iframeScrollHeight = event.data;
                $('#'+htmlID).css('height', iframeScrollHeight + 'px');
            });
            const apiUrl =`https://ws.biblioredes.gob.cl/pop/index.php?id=${htmlID}`
            // Realizar la solicitud a la API
            fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.log(data.error);
                } else if (data.activo) {
                    // Si el ID está activo, mostrar el popup con el iframe
                    document.getElementById(htmlID).src = data.url;
                    $(".modal-backdrop, .modal").removeClass("dynamic-modal")
                    $(".modal").addClass("show")
                    $(".modal-backdrop, .modal").show()
                    $(".modal .btn-close").on('click', function() {
                        $(".modal-backdrop, .modal").hide();
                        $("body").removeClass("modal-activo")
                    });
                    $("body").addClass("modal-activo")
                } 
            })
}
