var letras = /áéíóúabcdefghijklmnñopqrstuvwxyz-/;
var contacto = /[0-9+]/;
var fecha = /[0-9/-]/;
var varRutValido = true;
var varCorreoValido = true;
var varCorreoExiste = 'noExiste';
var varPassValido = true;
var varFechaValido = true;

function validarRut(e) {
    tecla = document.all ? e.keyCode : e.which;
    //Tecla de retroceso para borrar, siempre la permite
    if (tecla === 8) {
        return true;
    }
    // Patron de entrada, en este caso solo acepta numeros y el caracter :

    patron = /[0-9kK]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
function validarPass(pass) {
    var format = /[?]+/;

    if (format.test(pass)) {
        return true;
    } else {
        return false;
    }
}


function rutValido(variable) {
    if (variable) {
        varRutValido = true;
    } else {
        varRutValido = false;
    }
}
function correoValido(variable) {
    if (variable) {
        varCorreoValido = true;
    } else {
        varCorreoValido = false;
    }
}
function correoExistente(variable) {
    if (variable) {
        varCorreoValido = true;
    } else {
        varCorreoValido = false;
    }
}

function calcularEdad(fecha) {

    var hoy = new Date();
    hoy.setDate(hoy.getDate() - 1);

    var cumpleanos = new Date(fecha);
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();
    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
    }

    return edad;
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
let validacionRecaptcha = false;
function ValidarRecaptcha(response) {
    HabilitarCampos();
    // jQuery.ajax({
    //     type: 'POST',
    //     url: '../wp-content/themes/recorridosSonoros/inscripcionpatvirtual/Jason/Recaptcha.php',
    //     data: { identificacion: response },
    //     content: "application/json; charset=utf-8",
    //     dataType: "json"
    // })
    //     .done(function (r) {
    //         console.log(r);
    //         if (r == true) {
    //             HabilitarCampos();
    //         } else {
    //             DesahabilitarCampos();

    //         }
    //         contadorAfk = 0;
    //         validacionRecaptcha = r;
    //     }).fail(function (data) {
    //         console.log(data)
    //     })
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

function DesahabilitarCampos() {
    jQuery(".contenedor-recaptcha").removeClass('d-none');
    jQuery(".contenedor-recaptcha").css("display", "block");

    jQuery(".contenedor-formulario").addClass('d-none');
    jQuery("#formContainer").html("");
    // document.getElementById('rut_persona').disabled = true;
    // document.getElementById('nombre_persona').disabled = true;
    // document.getElementById('apellido_paterno').disabled = true;
    // document.getElementById('apellido_materno').disabled = true;
    // document.getElementById('fecha_nacimiento').disabled = true;



}

function HabilitarCampos() {
    jQuery.ajax({
        type: 'POST',
        url: valCampos_vars.ajaxurl,
        data: {
            action: 'inscripciones_ajax',
            option: 6,
        }
    }).done(function (data) {
        jQuery(".contenedor-recaptcha").addClass('d-none');
        jQuery(".contenedor-recaptcha").css("display", "none");

        jQuery(".contenedor-formulario").removeClass('d-none');
        jQuery("#formContainer").html(data);
        activarAlljQUery();
        console.log("Trae formulario");
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("Error formulario");
    })

}


var contadorAfk = 0;



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



function funcionObtenerRegiones() {
    jQuery.ajax({
        type: 'POST',
        url: valCampos_vars.ajaxurl,
        data: {
            action: 'inscripciones_ajax',
            option: 1,
        }
    }).done(function (data) {
        jQuery("#region_persona").html(data);
        console.log("Trae region");
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("Error region");
    })
};
function funcionObtenerPaises() {
    jQuery.ajax({
        type: 'POST',
        url: valCampos_vars.ajaxurl,
        data: {
            action: 'inscripciones_ajax',
            option: 5,
        }
    }).done(function (data) {
        jQuery("#pais_persona").html(data);
     
        jQuery("#paisorigen_persona").html(data);
        jQuery("#paisorigen_persona option[value='67']").remove();
    }).fail(function (jqXHR, textStatus, errorThrown) {
    })
};
function funcionObtenerComunas() {
    var idRegion = jQuery('#region_persona').val();
    jQuery.ajax({
        type: 'POST',
        url: valCampos_vars.ajaxurl,
        data: {
            action: 'inscripciones_ajax',
            idRegion: idRegion,
            option: 2,
        }
    }).done(function (data) {
        if (idRegion == '0') {
            jQuery("#divComuna").hide();
            jQuery('.select2-selection').css("border", "3px solid red");
            console.log("La region esta en 0");
        }
        jQuery("#comuna_persona").html(data);
        console.log("Trae comuna");
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("Error comuna");
    })
};
function funcionComprobarUsuario() {
    var validacion = true;
    var valText = [];
    var ident = jQuery('#rut_persona').val();
    var tipoIdent = jQuery('#tipoIden_persona').val();
    jQuery.ajax({
        type: 'POST',
        async: true,
        url: valCampos_vars.ajaxurl,
        data: {
            action: 'inscripciones_ajax',
            ident: ident,
            tipoIdent: tipoIdent,
            option: 3,
        }
    }).done(function (data) {
        if (data == 'true') {
            jQuery('#rut_persona').addClass("input-invalido");
            validacion = false;
            rutValido(false);
            if (jQuery('#tipoIden_persona').val() === 'R') {
                valText.push('Ya existe una cuenta asociada al rut ingresado, <a href="#">inicia sesión aquí</a>');
            } else {
                valText.push('Ya existe una cuenta asociada al Pasaporte / DNI ingresado, <a href="#">inicia sesión aquí</a>');
            }

        } else if (data == 'valida') {
            jQuery('#rut_persona').addClass("input-invalido");
            validacion = false;
            rutValido(false);
            valText.push('La identificación ya se encuentra registrada, favor valida tu cuenta e <a href="#">inicia sesión</a>');
        } else {
            if (jQuery('#tipoIden_persona').val() === 'R') {
                jQuery('#rut_persona').removeClass("input-invalido");
                obtenerPisee();
            }

        }
        if (!validacion) {
            document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> ' + valText.toString() + '.';
            jQuery(".errorBox").css("display", 'block');
        } else {
            jQuery(".errorBox").css("display", 'none');
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        jQuery('#rut_persona').addClass("input-invalido");
        validacion = false;
        valText.push('No se puede validar la identificación, favor intente mas tarde');
    })
    return validacion;
};
function funcionComprobarCorreo(campoCorreo) {
    var validacion = 'noExiste';
    var valText = [];
    var correo = jQuery(campoCorreo).val();
    jQuery.ajax({
        type: 'POST',
        url: valCampos_vars.ajaxurl,
        cache: true,
        async: false, // to set local variable
        data: {
            action: 'inscripciones_ajax',
            correo: correo,
            option: 4,
        }
    }).done(function (data) {
        if (data == 'true') {
            jQuery(campoCorreo).addClass("input-invalido");
            varCorreoExiste = 'existe';
            validacion = 'existe';
            //correoValido(false);
            valText.push('El correo ya se encuentra registrado, favor utiliza otro');
        } else {
            jQuery(campoCorreo).removeClass("input-invalido");
        }
        if (!validacion) {
            document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> ' + valText.toString() + '.';
            //jQuery(".errorBox").css("display", 'block');
        } else {
            //jQuery(".errorBox").css("display", 'none');
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        jQuery(campoCorreo).addClass("input-invalido");
        varCorreoExiste = 'error';
        validacion = 'error';
        valText.push('No se puede validar el correo, favor intente mas tarde');
    })
    return validacion;
};



/* CORREGIR SEGUN */
function obtenerPisee() {
    jQuery.ajax({
        type: 'POST',
        url: valCampos_vars.ajaxurl,
        content: "application/json; charset=utf-8",
        dataType: "json",
        data: {
            action: 'inscripciones_ajax',
            rut: jQuery('#rut_persona').val(),
            option: 0,
        }
    })
        .done(function (data) {
            if (data.estado == 'ok') {
                console.log(data.nombres);
                if (data.nombres !== '') {
                    jQuery('#nombre_persona').val(data.nombres);
                }

                if (data.apellidoPaterno !== '') {
                    jQuery('#apellido_paterno').val(data.apellidoPaterno);
                }
                if (data.sexo !== '') {
                    jQuery('#genero_persona').val(data.sexo);
                }

                if (data.apellidoMaterno !== '') {
                    jQuery('#apellido_materno').val(data.apellidoMaterno);
                }

                if (data.fecha !== '') {
                    jQuery('#fecha_nacimiento').val(data.fecha);
                }

                // if (data.nacionalidad == 'E' || data.nacionalidad == 'N') {
                //     funcionObtenerPaises();
                //     jQuery('.divPaises label').html('País de origen <span style="color: red;">&nbsp; *');
                //     jQuery('.divPaises').css("display", 'block');
                // }

                jQuery('.divPaises').css("display", 'block');

                if (data.nacionalidad == "E" ||  data.nacionalidad == "N"){
                    document.getElementById('paisesOrigen-div').style.display = "block";
                    jQuery('#paisorigen_persona').removeClass("input-invalido");
                }else{
                document.getElementById('paisesOrigen-div').style.display = "none";
                jQuery('#paisorigen_persona').removeClass("input-invalido");
                }
        
                if (data.nacionalidad == "E" ||  data.nacionalidad == "N"){
                document.getElementById('codNacionalidad').disabled = true;
                jQuery('#codNacionalidad').val(data.nacionalidad);
                jQuery('#codNacionalidad').removeClass("input-invalido");
                }
                if (data.nacionalidad == "C"){
                document.getElementById('codNacionalidad').disabled = true;
                jQuery('#codNacionalidad').val(data.nacionalidad);
                jQuery('#codNacionalidad').removeClass("input-invalido");
                }

                document.getElementById('rut_persona').disabled = true;
                document.getElementById('nombre_persona').disabled = true;
                document.getElementById('apellido_paterno').disabled = true;
                document.getElementById('apellido_materno').disabled = true;
                document.getElementById('fecha_nacimiento').disabled = true;
                document.getElementById('genero_persona').disabled = true;
                jQuery('#field_validado').val('true');

                rutValido(true);
                document.getElementsByClassName('errorBox')[0].innerHTML = '';
                jQuery(".errorBox").css("display", 'none');

            } else if (data.estado == 'nok1') {
                jQuery(".CU_mensaje_identidad").html("El RUT ingresado es inválido, La edad debe ser mayor a 7 años.");
                jQuery('#field_identidad').addClass("input-invalido");
                rutValido(false);
                document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> El RUT ingresado es inválido, La edad debe ser mayor a 7 años.';
                jQuery(".errorBox").css("display", 'block');
            } else if (data.estado == 'nok2') {
                jQuery(".CU_mensaje_identidad").html("El RUT ingresado es inválido, La edad debe ser menor a 120 años.");
                jQuery('#field_identidad').addClass("input-invalido");
                rutValido(false);
                document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> El RUT ingresado es inválido, La edad debe ser menor a 120 años.';
                jQuery(".errorBox").css("display", 'block');
            } else if (data.estado == 'nok3') {
                jQuery(".CU_mensaje_identidad").html("El RUT ingresado es inválido, Persona Fallecida.");
                jQuery('#field_identidad').addClass("input-invalido");
                rutValido(false);
                document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> El RUT ingresado es inválido, Persona Fallecida.';
                jQuery(".errorBox").css("display", 'block');
            } else if (data.estado == 'nok4') {
                jQuery(".CU_mensaje_identidad").html("No se pueden obtener los datos del registro civil, favor completar los campos manualmente");
                jQuery("#contenedorNombre").show();
                jQuery("#contenedorAPaterno").show();
                jQuery("#contenedorAMaterno").show();
                jQuery("#contenedorFNacimiento").show();
                jQuery("#contenedorSexo").show();
                jQuery(".contenedor-codNacionalidad-div").removeClass("d-none");
                jQuery("#contenedorPMF").show();
                funcionObtenerPaises();
                // jQuery('.divPaises label').html('País de origen <span style="color: red;">&nbsp; *');
                jQuery('.divPaises').css("display", 'block');
                rutValido(true);
                document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> No se pueden obtener los datos del registro civil, favor completar los campos manualmente.';
                jQuery(".errorBox").css("display", 'block');

                jQuery(".contenedor-codNacionalidad-div").removeClass("d-none");
                jQuery('#codNacionalidad').val("");
                jQuery('#codNacionalidad').prop('disabled', false);
            }

        })
        // SI PASA ACA SIGNIFICA Q ESTA BIEN //
        .fail(function (data) {
            jQuery(".CU_mensaje_identidad").html("No se pueden obtener los datos del registro civil, favor completar los campos manualmente");
            jQuery("#contenedorNombre").show();
            jQuery("#contenedorAPaterno").show();
            jQuery("#contenedorAMaterno").show();
            jQuery("#contenedorFNacimiento").show();
            jQuery("#contenedorSexo").show();
            jQuery("#contenedorPMF").show();
            funcionObtenerPaises();
            // jQuery('.divPaises label').html('País de origen <span style="color: red;">&nbsp; *');
            jQuery('.divPaises').css("display", 'block');
            rutValido(true);
            document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> No se pueden obtener los datos del registro civil, favor completar los campos manualmente.';
            jQuery(".errorBox").css("display", 'block');
            jQuery(".contenedor-codNacionalidad-div").removeClass("d-none");
            jQuery('#codNacionalidad').val("");
            jQuery('#codNacionalidad').prop('disabled', false);
        })
}


function llenarDatosPisee(data, option) {

    if (data.nombres !== '') {
        jQuery('#rut_persona').addClass("input-bloqueado");
        jQuery('#nombre_persona').val(data.nombres);
        jQuery('#nombre_persona').prop('disabled', true);
        jQuery('#nombre_persona').addClass("input-bloqueado");
        jQuery('#nombre_persona').removeClass("input-invalido");
        jQuery('#rut_persona').removeClass("input-invalido");
    }
    if (data.apellidoPaterno !== '') {
        jQuery('#apellido_paterno').val(data.apellidoPaterno);
        jQuery('#apellido_paterno').prop('disabled', true);
        jQuery('#apellido_paterno').addClass("input-bloqueado");
        jQuery('#apellido_paterno').removeClass("input-invalido");
    }
    if (data.apellidoMaterno !== '') {
        jQuery('#apellido_materno').val(data.apellidoMaterno);
        jQuery('#apellido_materno').prop('disabled', true);
        jQuery('#apellido_materno').addClass("input-bloqueado");
        jQuery('#apellido_materno').removeClass("input-invalido");
    }
};

function mostrarDatosPersona(option) {
    if (option) {
        jQuery('#rut_persona').prop('disabled', true);
        jQuery('#nombre_persona').prop('disabled', true);
        jQuery('#apellido_paterno').prop('disabled', true);
        jQuery('#apellido_materno').prop('disabled', true);
    }
}


function tipoIdentidadRut(rutPersona) {
    jQuery(rutPersona).attr('maxlength', '10');
    jQuery(rutPersona).attr('placeholder', 'Ingrese su R.U.T');
    jQuery(rutPersona).keypress(validarRut);
    jQuery(rutPersona).Rut({
        format_on: 'keyup',
        on_success: function () {
            if (rutPersona === '#rut_persona') {
                jQuery('#rut_persona').removeClass("input-invalido");
                if (jQuery('#rut_persona').val() != "") {
                    if (funcionComprobarUsuario()) {
                        // obtenerPisee();
                    } else {
                        jQuery(rutPersona).addClass("input-invalido");
                        rutValido(false);
                    }
                }
            }
        },
        on_error: function () {
            var valText = [];
            jQuery(rutPersona).addClass("input-invalido");
            valText.push(' Se debe ingresar rut valido');
            document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> ' + valText.toString() + '.';
            jQuery(".errorBox").css("display", 'block');
            rutValido(false);
        }
    });
}
function tipoIdentidadPasaporte(rutPersona) {
    jQuery(rutPersona).attr('maxlength', '10');
    jQuery(rutPersona).attr('placeholder', 'Ingrese su R.U.T');
    jQuery(rutPersona).keypress(validarRut);
    jQuery(rutPersona).Rut({
        format_on: 'keyup',
        on_success: function () {
            jQuery('#rut_persona').removeClass("input-invalido");
            if (rutPersona === '#rut_persona') {
                if (funcionComprobarUsuario()) {
                    obtenerPisee();
                } else {
                    jQuery(rutPersona).addClass("input-invalido");
                    rutValido(false);
                }
            }
        },
        on_error: function () {
            jQuery(rutPersona).addClass("input-invalido");
            rutValido(false);
        }
    });
}

function limpiarCampos() {

    jQuery('#rut_persona').prop('disabled', false);
    jQuery('#nombre_persona').prop('disabled', false);
    jQuery('#apellido_paterno').prop('disabled', false);
    jQuery('#apellido_materno').prop('disabled', false);
    jQuery('#sexo_persona').prop('disabled', false);
    jQuery('#fecha_nacimiento').prop('disabled', false);

    jQuery('#rut_persona').val('');
    jQuery('#nombre_persona').val('');
    jQuery('#apellido_paterno').val('');
    jQuery('#apellido_materno').val('');
    jQuery('#fecha_nacimiento').val('');
    jQuery('#region_persona').val('0');
    jQuery('#comuna_persona').val('0');
    jQuery('#pais_persona').val('0');
    jQuery('#mail_persona').val('');
    jQuery('#mail_persona_repite').val('');
    jQuery('#contrasena_persona').val('');
    jQuery('#recontrasena_persona').val('');
    jQuery('#paisorigen_persona').val('0');
    jQuery('#paisorigen_persona').removeClass("input-invalido");
    jQuery('#genero_persona').val('0');
    jQuery('#genero_persona').removeClass("input-invalido");
    jQuery('#rut_persona').removeClass("input-invalido");
    jQuery('#nombre_persona').removeClass("input-invalido");
    jQuery('#apellido_paterno').removeClass("input-invalido");
    jQuery('#apellido_materno').removeClass("input-invalido");
    jQuery('#fecha_nacimiento').removeClass("input-invalido");
    jQuery('#region_persona').removeClass("input-invalido");
    jQuery('#comuna_persona').removeClass("input-invalido");
    jQuery('#mail_persona').removeClass("input-invalido");
    jQuery('#mail_persona_repite').removeClass("input-invalido");
    jQuery('#contrasena_persona').removeClass("input-invalido");
    jQuery('#recontrasena_persona').removeClass("input-invalido");

    jQuery("#CU_mensaje_Pass").html("");
    jQuery("#CU_mensaje_Mail").html("");
    jQuery('#paisorigen_persona').val('0');
    jQuery('#paisorigen_persona').removeClass("input-invalido");

    jQuery(".contenedor-codNacionalidad-div").addClass("d-none");
    jQuery('#codNacionalidad').val("");
    jQuery('#codNacionalidad').prop('disabled', true);

    jQuery(".errorBox").css("display", 'none');


}

function cambiarTipoIdentidad(tipoIden, rutPersona) {

    if (jQuery(tipoIden).val() === 'R') {
        jQuery(".errorBox").css("display", 'none');
        jQuery('.divPaises').css("display", 'none');
        jQuery('.divRegiones').css("display", 'block');
        jQuery(rutPersona).val("");
        jQuery(rutPersona).attr('maxlength', '10');
        jQuery(rutPersona).attr('placeholder', 'Ingrese su R.U.T');
        jQuery(rutPersona).prop('disabled', false);
        jQuery(rutPersona).removeClass("input-bloqueado");
        tipoIdentidadRut(rutPersona);
        if (rutPersona === '#rut_persona') {
            limpiarCampos();
        }
        
    } else if (jQuery(tipoIden).val() === 'P') {
        jQuery(".errorBox").css("display", 'none');
        //jQuery('.divPaises label').html('País de residencia <span style="color: red;">&nbsp; *');
        jQuery('.paisesOrigen-div').css("display", 'block');
        jQuery('.divPaises').css("display", 'none');
        jQuery('.divRegiones').css("display", 'none');
        jQuery('.divComuna').css("display", 'none');
        jQuery(rutPersona).val("");
        jQuery(rutPersona).attr('maxlength', '15');
        jQuery(rutPersona).attr('placeholder', 'Ingrese su número de pasaporte o DNI');
        jQuery(rutPersona).removeClass("input-invalido");
        jQuery(rutPersona).removeAttr('onkeypress');
        jQuery(rutPersona).unbind();
        jQuery(rutPersona).prop('disabled', false);
        jQuery(rutPersona).removeClass("input-bloqueado");
        if (rutPersona === '#rut_persona') {
            limpiarCampos();
        }
        jQuery(rutPersona).blur(function () {

            if (jQuery(rutPersona).val() != "") {
                if (funcionComprobarUsuario()) {

                } else {
                    jQuery(rutPersona).addClass("input-invalido");
                    rutValido(false);
                }
            }
        });

        jQuery(".contenedor-codNacionalidad-div").addClass("d-none");
        jQuery('#codNacionalidad').val("E");
        jQuery('#codNacionalidad').prop('disabled', true);
    }
  
}
function funcionValidarInscripcion() {
    var validacion = true;
    var valText = [];
    // INFORMACIÖN DE PERSONA

    if (!varRutValido) {
        jQuery('#rut_persona').addClass("input-invalido");
        validacion = false;
        if (jQuery('#tipoIden_persona').val() === 'R') {
            valText.push(' Se debe ingresar rut valido');
        } else {
            valText.push(' Se debe ingresar Pasaporte / DNI valido');
        }

    } else if (jQuery('#rut_persona').val() === '') {
        jQuery('#rut_persona').addClass("input-invalido");
        validacion = false;

        if (jQuery('#tipoIden_persona').val() === 'R') {
            valText.push(' Se debe ingresar rut');
        } else {
            valText.push(' Se debe ingresar Pasaporte / DNI');
        }
    } else {
        jQuery('#rut_persona').removeClass("input-invalido");
    }


    if (jQuery('#nombre_persona').val() === '') {
        jQuery('#nombre_persona').addClass("input-invalido");
        validacion = false;
        valText.push(' Se debe ingresar nombre');
    } else {
        jQuery('#nombre_persona').removeClass("input-invalido");
    }

    if (jQuery('#apellido_paterno').val() === '') {
        jQuery('#apellido_paterno').addClass("input-invalido");
        validacion = false;
        valText.push(' Se debe ingresar apellido paterno');
    } else {
        jQuery('#apellido_paterno').removeClass("input-invalido");
    }

    /*if (jQuery('#apellido_materno').val() === '') {
        jQuery('#apellido_materno').addClass("input-invalido");
        validacion = false;
        valText.push(' Se debe ingresar apellido materno');
    } else {
        jQuery('#apellido_materno').removeClass("input-invalido");
    }*/
    if (jQuery('#genero_persona').val() === '0') {
        jQuery('#genero_persona').addClass("input-invalido");
        validacion = false;
        valText.push(' Se debe ingresar el sexo');
    } else {
        jQuery('#genero_persona').removeClass("input-invalido");
    }
    
        

    var edad = calcularEdad(jQuery('#fecha_nacimiento').val());
    if (jQuery('#fecha_nacimiento').val() === '') {
        jQuery('#fecha_nacimiento').addClass("input-invalido");
        valText.push(' Debe ingresar una fecha de nacimiento valida');
        validacion = false;
    } else if (edad < 6) {
        jQuery('#fecha_nacimiento').addClass("input-invalido");
        valText.push(' La persona no puede ser menor a 7 años');
        validacion = false;
    } else if (edad > 120) {
        jQuery('#fecha_nacimiento').addClass("input-invalido");
        valText.push(' La persona no puede ser mayor a 120 años');
        validacion = false;
    } else {
        jQuery('#fecha_nacimiento').removeClass("input-invalido");
    }

    if(jQuery('#codNacionalidad').val() === '' || jQuery('#codNacionalidad').val() === '0' ){
        jQuery('#codNacionalidad').addClass("input-invalido");
        valText.push(' La persona debe ingresar la nacionalidad');
        validacion = false;
    } else {
        jQuery('#codNacionalidad').removeClass("input-invalido");
    }

    if (jQuery('#tipoIden_persona').val() === 'R') {
        if ($('.divPaises').css('display') == 'block') {
            if (jQuery('#pais_persona').val() === '0') {
                jQuery('#pais_persona').addClass("input-invalido");
                jQuery('.select2-selection').css("border", "3px solid red");
                valText.push(' Debe ingresar su país');
                validacion = false;
            } else {
                jQuery('#pais_persona').removeClass("input-invalido");
                jQuery('.select2-selection').css("border", "");
            }
        }
        if(jQuery("#pais_persona").val() == "67"){
            if (jQuery('#region_persona').val() === '0') {
                jQuery('#region_persona').addClass("input-invalido");
                jQuery('.select2-selection').css("border", "3px solid red");
                valText.push(' Debe ingresar su región');
                validacion = false;
            } else {
                jQuery('#region_persona').removeClass("input-invalido");
                jQuery('.select2-selection').css("border", "");
            }
    
            if (jQuery('#comuna_persona').val() === '0') {
                jQuery('#comuna_persona').addClass("input-invalido");
                jQuery('.select2-selection').css("border", "3px solid red");
                valText.push(' Debe ingresar su comuna');
                validacion = false;
            } else {
                jQuery('#comuna_persona').removeClass("input-invalido");
                jQuery('.select2-selection').css("border", "");
            }
        }
        

    }

    if(jQuery('#codNacionalidad').val() === 'N' || jQuery('#codNacionalidad').val() === 'E' || jQuery('#tipoIden_persona').val() === 'P'){
        if (jQuery('#paisorigen_persona').val() == "0") {
          jQuery('#paisorigen_persona').addClass("input-invalido");
          validacion = false;
          valText.push(' Se debe seleccionar país de origen');
        } else {
          jQuery('#paisorigen_persona').removeClass("input-invalido");
        }
    }

    var varCorreoExis = funcionComprobarCorreo('#mail_persona');
    if (varCorreoExis === 'existe') {
        valText.push(' El correo ingresado está registrado con otra cuenta. Por favor utilice otro correo electrónico.');
        validacion = false;
    } else if (varCorreoExis === 'error') {
        valText.push(' No se puede validar el correo, favor intente mas tarde');
        validacion = false;
    }
    if (!varCorreoValido) {
        jQuery('#mail_persona').addClass("input-invalido");
        validacion = false;
        valText.push(' Se debe ingresar correo valido');
    } else {
        if (jQuery('#mail_persona').val() != jQuery('#mail_persona_repite').val()) {
            jQuery('#mail_persona').addClass("input-invalido");
            jQuery('#mail_persona_repite').addClass("input-invalido");
            validacion = false;
            valText.push(' El correo ingresado no coincide, por favor verificar.');
        } else {
            jQuery('#mail_persona').removeClass("input-invalido");
            jQuery('#mail_persona_repite').removeClass("input-invalido");
        }

        if (jQuery('#mail_persona').val() === '') {
            jQuery('#mail_persona').addClass("input-invalido");
            validacion = false;
            valText.push(' Se debe ingresar correo');
        } else if (!validateEmail(jQuery('#mail_persona').val())) {
            jQuery('#mail_persona').addClass("input-invalido");
            validacion = false;
            valText.push(' Se debe ingresar correo valido');
        } else {
            jQuery('#mail_persona').removeClass("input-invalido");
        }

        if (jQuery('#mail_persona_repite').val() === '') {
            jQuery('#mail_persona_repite').addClass("input-invalido");
            validacion = false;
            valText.push(' Se debe re ingresar correo');
        } else if (!validateEmail(jQuery('#mail_persona_repite').val())) {
            jQuery('#mail_persona_repite').addClass("input-invalido");
            validacion = false;
            valText.push(' Se debe re ingresar correo valido');
        } else {
            jQuery('#mail_persona_repite').removeClass("input-invalido");
        }
    }

    if (validarPass(jQuery('#contrasena_persona').val())) {
        jQuery('#contrasena_persona').addClass("input-invalido");
        validacion = false;
        valText.push(' Favor no incluir símbolos de puntuación en la contraseña');
    } else {
        jQuery('#contrasena_persona').removeClass("input-invalido");
    }

    if (jQuery('#contrasena_persona').val() != jQuery('#recontrasena_persona').val()) {
        jQuery('#contrasena_persona').addClass("input-invalido");
        jQuery('#recontrasena_persona').addClass("input-invalido");
        validacion = false;
        valText.push(' La contraseña ingresada no coincide, por favor reingresar.');
    } else {
        jQuery('#contrasena_persona').removeClass("input-invalido");
        jQuery('#recontrasena_persona').removeClass("input-invalido");
    }

    if (jQuery('#contrasena_persona').val() === '') {
        jQuery('#contrasena_persona').addClass("input-invalido");
        validacion = false;
        valText.push(' Debe ingresar una contraseña');
    } else {
        if (jQuery('#contrasena_persona').val().length <= 4 || jQuery('#contrasena_persona').val().length > 12) {
            jQuery('#contrasena_persona').addClass("input-invalido");
            validacion = false;
            valText.push(' La contraseña debe tener entre 4 y 12 caracteres');
        } else {
            jQuery('#contrasena_persona').removeClass("input-invalido");
        }
    }

    if (jQuery('#recontrasena_persona').val() === '') {
        jQuery('#recontrasena_persona').addClass("input-invalido");
        validacion = false;
        valText.push(' Debe re ingresar su contraseña');
    } else {
        jQuery('#recontrasena_persona').removeClass("input-invalido");
    }


    if (jQuery('#checkacepto').prop('checked')) {
        jQuery('#checkacepto').removeClass("input-invalido");
    } else {
        jQuery('#checkacepto').addClass("input-invalido");
        validacion = false;
        valText.push(' Debe aceptar terminos y condiciones');
    }
    // if (grecaptcha.getResponse() == "") {
    //     valText.push(' Debe marcar casilla reCAPTCHA');
    //     jQuery('.g-recaptcha').css("border", "solid 2px #ff5E5E");
    //     validacion = false;
    // }


    if (!validacion) {
        document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> ' + valText.toString() + '.';
        jQuery(".errorBox").css("display", 'block');
    } else {
        jQuery(".errorBox").css("display", 'none');
    }
    return validacion;
}
function funcionValidarEdit() {
    var validacion = true;
    var valText = [];
    // INFORMACIÖN DE PERSONA  
    if (jQuery('#mail_persona_edit').val() != jQuery('#mail_persona_edit_old').val()) {
        var varCorreoExis = funcionComprobarCorreo('#mail_persona_edit');
        if (varCorreoExis === 'existe') {
            valText.push(' El correo ingresado está registrado con otra cuenta. Por favor utilice otro correo electrónico.');
            validacion = false;
        } else if (varCorreoExis === 'error') {
            valText.push(' No se puede validar el correo, favor intente mas tarde');
            validacion = false;
        }
    }
    if (!varCorreoValido) {
        jQuery('#mail_persona_edit').addClass("input-invalido");
        validacion = false;
        valText.push(' Se debe ingresar correo valido');
    } else {
        if (jQuery('#mail_persona_edit').val() != jQuery('#mail_persona_repite_edit').val()) {
            jQuery('#mail_persona_edit').addClass("input-invalido");
            jQuery('#mail_persona_repite_edit').addClass("input-invalido");
            validacion = false;
            valText.push(' Los correos no coinciden');
        } else {
            jQuery('#mail_persona_edit').removeClass("input-invalido");
            jQuery('#mail_persona_repite_edit').removeClass("input-invalido");
        }

        if (jQuery('#mail_persona_edit').val() === '') {
            jQuery('#mail_persona_edit').addClass("input-invalido");
            validacion = false;
            valText.push(' Se debe ingresar correo');
        } else if (!validateEmail(jQuery('#mail_persona_edit').val())) {
            jQuery('#mail_persona_edit').addClass("input-invalido");
            validacion = false;
            valText.push(' Se debe ingresar correo valido');
        } else {
            jQuery('#mail_persona_edit').removeClass("input-invalido");
        }

        if (jQuery('#mail_persona_repite_edit').val() === '') {
            jQuery('#mail_persona_repite_edit').addClass("input-invalido");
            validacion = false;
            valText.push(' Se debe re ingresar correo');
        } else if (!validateEmail(jQuery('#mail_persona_repite_edit').val())) {
            jQuery('#mail_persona_repite_edit').addClass("input-invalido");
            validacion = false;
            valText.push(' Se debe re ingresar correo valido');
        } else {
            jQuery('#mail_persona_repite_edit').removeClass("input-invalido");
        }
    }
    if (jQuery('#contrasena_persona_edit').val() != jQuery('#recontrasena_persona_edit').val()) {
        jQuery('#contrasena_persona_edit').addClass("input-invalido");
        jQuery('#recontrasena_persona_edit').addClass("input-invalido");
        validacion = false;
        valText.push('Las contraseñas no coinciden');
    } else {
        jQuery('#contrasena_persona_edit').removeClass("input-invalido");
        jQuery('#recontrasena_persona_edit').removeClass("input-invalido");
    }

    if (jQuery('#contrasena_persona_edit').val() === '') {
        // jQuery('#contrasena_persona_edit').addClass("input-invalido");
        // validacion = false;
        // valText.push(' Debe ingresar una contraseña');
    } else {
        // Validación de longitud de la contraseña
        if (jQuery('#contrasena_persona_edit').val().length < 4 || jQuery('#contrasena_persona_edit').val().length > 12) {
            jQuery('#contrasena_persona_edit').addClass("input-invalido");
            validacion = false;
            valText.push(' La contraseña debe tener entre 4 y 12 caracteres');
        } else {
            jQuery('#contrasena_persona_edit').removeClass("input-invalido");

            if (jQuery('#contrasena_old_persona_edit').val() === '') {
                jQuery('#contrasena_old_persona_edit').addClass("input-invalido");
                validacion = false;
                valText.push(' Debe ingresar su antigua contraseña');
            } else {
                jQuery('#contrasena_old_persona_edit').removeClass("input-invalido");

                if (jQuery('#recontrasena_persona_edit').val() === '') {
                    jQuery('#recontrasena_persona_edit').addClass("input-invalido");
                    validacion = false;
                    valText.push(' Debe reingresar su contraseña');
                } else {
                    jQuery('#recontrasena_persona_edit').removeClass("input-invalido");
                }
            }
        }
    }



    if (!validacion) {
        document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> ' + valText.toString() + '.';
        jQuery(".errorBox").css("display", 'block');
    } else {
        jQuery(".errorBox").css("display", 'none');
    }
    return validacion;
}
function funcionValidarCorreoRepite(opcion) {
    // Opcion es para saber si viene desde el registro o edición de perfil
    if (opcion == 1) {
        if (jQuery('#mail_persona').val() != jQuery('#mail_persona_repite').val()) {
            jQuery('#mail_persona').addClass("input-invalido");
            jQuery('#mail_persona_repite').addClass("input-invalido");
            jQuery("#CU_mensaje_Mail").html("El correo ingresado no coincide, por favor verificar. ");
        } else {
            jQuery('#mail_persona').removeClass("input-invalido");
            jQuery('#mail_persona_repite').removeClass("input-invalido");
            jQuery("#CU_mensaje_Mail").html("");
        }
    } else {
        if (jQuery('#mail_persona_edit').val() != jQuery('#mail_persona_repite_edit').val()) {
            jQuery('#mail_persona_edit').addClass("input-invalido");
            jQuery('#mail_persona_repite_edit').addClass("input-invalido");
            jQuery("#CU_mensaje_Mail").html("El correo ingresado no coincide, por favor verificar. ");
        } else {
            jQuery('#mail_persona_edit').removeClass("input-invalido");
            jQuery('#mail_persona_repite_edit').removeClass("input-invalido");
            jQuery("#CU_mensaje_Mail").html("");
        }
    }

}
function funcionValidarPassRepite(opcion) {
    // Opcion es para saber si viene desde el registro o edición de perfil
    if (opcion == 1) {
        if (jQuery('#contrasena_persona').val() != jQuery('#recontrasena_persona').val()) {
            jQuery('#contrasena_persona').addClass("input-invalido");
            jQuery('#recontrasena_persona').addClass("input-invalido");
            jQuery("#CU_mensaje_Pass").html("La contraseña ingresada no coincide, por favor reingresar. ");
        } else {
            jQuery('#contrasena_persona').removeClass("input-invalido");
            jQuery('#recontrasena_persona').removeClass("input-invalido");
            jQuery("#CU_mensaje_Pass").html("");
        }
    } else {
        if (jQuery('#contrasena_persona_edit').val() != jQuery('#recontrasena_persona_edit').val()) {
            jQuery('#contrasena_persona_edit').addClass("input-invalido");
            jQuery('#recontrasena_persona_edit').addClass("input-invalido");
            jQuery("#CU_mensaje_Pass").html("La contraseña ingresada no coincide, por favor reingresar. ");
        } else {
            jQuery('#contrasena_persona_edit').removeClass("input-invalido");
            jQuery('#recontrasena_persona_edit').removeClass("input-invalido");
            jQuery("#CU_mensaje_Pass").html("");
        }
    }

}

function activarAlljQUery() {
    cambiarTipoIdentidad('#tipoIden_persona', '#rut_persona');

    if (jQuery('#rut_persona').val() != "") {
        tipoIdentidadRut();
        jQuery('#rut_persona').prop('disabled', false);
        jQuery('#rut_persona').removeClass("input-bloqueado");
    }

    jQuery('#tipoIden_persona').on('change', function () {
        cambiarTipoIdentidad('#tipoIden_persona', '#rut_persona');
    });
    jQuery('#mail_persona_repite').blur(function () {
        funcionValidarCorreoRepite(1);
    });
    jQuery('#recontrasena_persona').blur(function () {
        funcionValidarPassRepite(1);
    });

    jQuery('#mail_persona_repite_edit').blur(function () {
        funcionValidarCorreoRepite(2);
    });
    jQuery('#recontrasena_persona_edit').blur(function () {
        funcionValidarPassRepite(2);
    });
    // if (varCorreoValido) {
    //     jQuery('#mail_persona_edit').blur(function () {

    //     });
    // }

    jQuery('#divComuna').hide();
    jQuery('#comuna_persona').prop('disabled', true);
    jQuery('#comuna_persona').addClass("input-bloqueado");
    funcionObtenerRegiones();
    funcionObtenerPaises();
    jQuery('#region_persona').on('change', function () {
        funcionObtenerComunas();
        jQuery('#divComuna').show();
        jQuery('#comuna_persona').prop('disabled', false);
        jQuery('#comuna_persona').removeClass("input-bloqueado");
    });

    jQuery('#codNacionalidad').on('change', function () {
    const nacionalidad = jQuery('#codNacionalidad').val();
    const tipoIden = jQuery('#tipoIden_persona').val();

    if (nacionalidad === 'N' || nacionalidad === 'E' || tipoIden === 'P') {
        document.getElementById('paisesOrigen-div').style.display = "block";
        jQuery('#paisorigen_persona').removeClass("input-invalido");
    } else {
        document.getElementById('paisesOrigen-div').style.display = "none";
        jQuery('#paisorigen_persona').removeClass("input-invalido");
    }

    jQuery('#paisorigen_persona').val("0");
    });

    if (jQuery('#pais_persona')) {
        jQuery('#pais_persona').on('change', function () {
          if (jQuery('#pais_persona').val() === '67') {
            document.getElementById('divRegiones').style.display = "block";
            if (jQuery('#region_persona').val() != null) {
              document.getElementById('divComuna').style.display = "block";
            }
          } else {
            document.getElementById('divComuna').style.display = "none";
            document.getElementById('divRegiones').style.display = "none";
          }
        });
      }
    
    jQuery('#fecha_nacimiento').blur(function () {
        var edad = calcularEdad(jQuery('#fecha_nacimiento').val());
        if (edad < 7) {
            document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> La persona no puede ser menor a 7 años o mayor a 120 años .'
            jQuery(".errorBox").css("display", 'block');
            mostrarDatosPersona(true);

            var body = jQuery("html, body");
            body.stop().animate({ scrollTop: 0 }, 500, 'swing', function () { });
            return false;
        } else {
            mostrarDatosPersona(false);
        }
    });

    jQuery('#btnEnviarTest').click(function () {
        event.preventDefault();
        if (funcionValidarInscripcion()) {
            jQuery('#rut_persona').prop('disabled', false);
            jQuery('#nombre_persona').prop('disabled', false);
            jQuery('#apellido_paterno').prop('disabled', false);
            jQuery('#apellido_materno').prop('disabled', false);
            jQuery('#fecha_nacimiento').prop('disabled', false);
            jQuery('#codNacionalidad').prop('disabled', false);
            jQuery('#genero_persona').prop('disabled', false);
            jQuery('#actionFormSubmit').val('new_post');
            jQuery('#new_post').submit();
            return true;
        } else {
            var body = jQuery("html, body");
            body.stop().animate({ scrollTop: 0 }, 500, 'swing', function () { });
            return false;
        }
    });
    jQuery('#btnEnviarEdit').click(function () {
        event.preventDefault();
        if (funcionValidarEdit()) {
            jQuery('#new_post').submit();
            return true;
        } else {
            var body = jQuery("html, body");
            body.stop().animate({ scrollTop: 0 }, 500, 'swing', function () { });
            return false;
        }
    });

    jQuery('#btnBorrarRegistro').click(function () {
        event.preventDefault();
        if (limpiarCampos()) {
            return true;
        }
    })
    jQuery('#btnBorrarEdit').click(function () {
        event.preventDefault();
        jQuery('#mail_persona_edit').val('');
        jQuery('#mail_persona_repite_edit').val('');
        jQuery('#contrasena_persona_edit').val('');
        jQuery('#recontrasena_persona_edit').val('');

        jQuery('#mail_persona_edit').removeClass('input-invalido');
        jQuery('#mail_persona_repite_edit').removeClass('input-invalido');
        jQuery('#contrasena_persona_edit').removeClass('input-invalido');
        jQuery('#recontrasena_persona_edit').removeClass('input-invalido');

        jQuery("#CU_mensaje_Pass").html("");
        jQuery("#CU_mensaje_Mail").html("");

        jQuery(".errorBox").css("display", 'none');
    })
}
jQuery(document).ready(function ($) {
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
    activarAlljQUery();
});




