let validacionRecaptcha = false;
function ValidarRecaptcha(response) {
    jQuery.ajax({
        type: 'POST',
        url: '../wp-content/themes/recorridosSonoros/inscripcionpatvirtual/Jason/Recaptcha.php',
        data: { identificacion: response },
        content: "application/json; charset=utf-8",
        dataType: "json"
    })
        .done(function (r) {
            console.log(r);
            if(r== true){
                HabilitarCampos();
            }else{
                DesahabilitarCampos();
                
            }
            contadorAfk = 0;
            validacionRecaptcha = r;
        }).fail(function (data) {
            console.log(data)
        })
}

//Limpiar Recaptcha
function resetRecaptcha() {
    grecaptcha.reset();
}



//Recaptcha tiene una duracion de 2 minutos por lo tanto pasados esos dos minutos se limpia automaticamente y debes limpiar la variable de validacion 
function RecaptchaExpired() {
    console.log("Metodo expirar Recaptcha");
    InputExpired();
}

function DesahabilitarCampos(){
    jQuery(".contenedor-recaptcha").removeClass('d-none');
    jQuery(".contenedor-recaptcha").css("display", "block");
    
    jQuery(".contenedor-formulario").addClass('d-none');

    document.getElementById('rut_persona').disabled = true;
    document.getElementById('nombre_persona').disabled = true;
    document.getElementById('apellido_paterno').disabled = true;
    document.getElementById('apellido_materno').disabled = true;
    document.getElementById('fecha_nacimiento').disabled = true;
    


}

function HabilitarCampos(){
    jQuery(".contenedor-recaptcha").addClass('d-none');
    jQuery(".contenedor-recaptcha").css("display", "none");

    jQuery(".contenedor-formulario").removeClass('d-none');

    document.getElementById('rut_persona').disabled = false;
    document.getElementById('nombre_persona').disabled = false;
    document.getElementById('apellido_paterno').disabled = false;
    document.getElementById('apellido_materno').disabled = false;
    document.getElementById('fecha_nacimiento').disabled = false;
    
}


var contadorAfk = 0;
jQuery(document).ready(function () {
    //Cada minuto se lanza la función ctrlTiempo /*Cada minuto */
    setInterval(ctrlTiempo, 60000);

    //Si el usuario mueve el ratón cambiamos la variable a 0.
    jQuery(this).mousemove(function (e) {
        contadorAfk = 0;
    });
    //Si el usuario presiona alguna tecla cambiamos la variable a 0.
    jQuery(this).keypress(function (e) {
        contadorAfk = 0;
    });
});


function ctrlTiempo() {

    if (validacionRecaptcha == true) {
        //Se aumenta en 1 la variable.
        contadorAfk++;
        //Se comprueba si ha pasado del tiempo que designemos.
        InputExpired();
    }

 
}

function InputExpired() {
    //Pregunta si ha pasado mas de 2 min inactivo
    if (contadorAfk >= 2) {
        DesahabilitarCampos();
        validacionRecaptcha = false;
    }
    
}





