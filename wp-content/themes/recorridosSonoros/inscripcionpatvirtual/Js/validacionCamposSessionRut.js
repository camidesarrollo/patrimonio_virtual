var letras = /áéíóúabcdefghijklmnñopqrstuvwxyz-/;
var contacto = /[0-9+]/;
var fecha = /[0-9/-]/;
var varRutValido = true;
var varCorreoValido = true;
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

function rutValido(variable) {
    if (variable) {
        varRutValido = true;
    } else {
        varRutValido = false;
    }
}
(function ($) {

    function tipoIdentidadRut(rutPersona) {
        $(rutPersona).attr('maxlength', '10');
        $(rutPersona).attr('placeholder', 'Ingrese su R.U.T');
        $(rutPersona).keypress(validarRut);
        $(rutPersona).Rut({
            format_on: 'keyup',
            on_success: function () {
                if (rutPersona === '#rut_persona') {
                    $('#rut_persona').removeClass("input-invalido");
                }
            },
            on_error: function () {
                $(rutPersona).addClass("input-invalido");
                rutValido(false);
            }
        });
    }


    /* INICIO DE SESSION RUT */
    function limpiarCamposRut() {
        $('#rut_persona').prop('disabled', false);
        $('#rut_persona').val('');
        $('#contrasena_persona').val('');
        $('#recontrasena_persona').val('');
    }

    function funcionCambioTipoIdentidad($tipo) {
        if ($tipo == 1) {
            $('#rut_persona').attr('maxlength', '10');
            $('#rut_persona').attr('placeholder', 'Ingrese su R.U.T');
            $('#rut_persona').keypress(validarRut);
            $("#rut_persona").Rut({
                format_on: 'keyup',
                on_success: function () {
                    $(".CU_mensaje_identidad").html("");
                    $('#rut_persona').removeClass("input-invalido");
                    rutValido(true);
                },
                on_error: function () {
                    //$("#CU_0_Identidad").val("");
                    $(".CU_mensaje_identidad").html("El RUT ingresado es inválido.");
                    $('#rut_persona').addClass("input-invalido");
                    rutValido(false);
                }
            });
        } else {
            $('#rut_persona').val("");
            $(".CU_mensaje_identidad").html("");
            $('#rut_persona').attr('maxlength', '15');
            $('#rut_persona').attr('placeholder', 'Ingrese su número de pasaporte');
            $('#rut_persona').removeAttr('onkeypress');
            $('#rut_persona').unbind();
        }
    }
    function funcionValidarLogin() {
        var validacion = true;
        var valText = [];

        if (!varRutValido) {
            $('#rut_persona').addClass("input-invalido");
            valText.push(' Se debe ingresar identificación valida');
            validacion = false;
        } else if ($('#rut_persona').val() === '') {
            $('#rut_persona').addClass("input-invalido");
            valText.push(' Se debe ingresar identificación valida');
            validacion = false;
        } else {
            $('#rut_persona').removeClass("input-invalido");
        }

        // VERIFICA CONTRASEÑA
        if (!varPassValido) {
            $('#contrasena_persona').addClass("input-invalido");
            valText.push(' Se debe ingresar contraseña valida');
            validacion = false;
        } else if ($('#contrasena_persona').val() === "") {
            $('#contrasena_persona').addClass("input-invalido");
            valText.push(' Se debe ingresar una contraseña');
            validacion = false;
        } else {
            $('#contrasena_persona').removeClass("input-invalido");
        }

        if (!validacion) {
            document.getElementsByClassName('errorBox')[0].innerHTML = '<i class="fa fa-info-circle" aria-hidden="true"></i> ' + valText.toString() + '.';
            jQuery(".errorBox").css("display", 'block');
        } else {
            jQuery(".errorBox").css("display", 'none');
        }
        return validacion;
    }
    function funcionValidarCambioPass() {
        var validacion = true;
        var valText = [];

        // VERIFICA CONTRASEÑA
        if (!varPassValido) {
            $('#contrasena_persona').addClass("input-invalido");
            valText.push(' Se debe ingresar contraseña valida');
            validacion = false;
        } else if ($('#contrasena_persona').val() === "") {
            $('#contrasena_persona').addClass("input-invalido");
            valText.push(' Se debe ingresar una contraseña');
            validacion = false;
        } else {
            $('#contrasena_persona').removeClass("input-invalido");
        }
        // VERIFICA RE CONTRASEÑA
        if (!varPassValido) {
            $('#recontrasena_persona').addClass("input-invalido");
            valText.push(' Se debe re ingresar contraseña valida');
            validacion = false;
        } else if ($('#recontrasena_persona').val() === "") {
            $('#recontrasena_persona').addClass("input-invalido");
            valText.push(' Se debe re ingresar una contraseña');
            validacion = false;
        } else {
            $('#recontrasena_persona').removeClass("input-invalido");
            if ($('#recontrasena_persona').val() != $('#contrasena_persona').val()) {
                $('#contrasena_persona').addClass("input-invalido");
                $('#recontrasena_persona').addClass("input-invalido");
                valText.push(' Las contraseñas no coinciden');
                validacion = false;
            } else {
                $('#contrasena_persona').removeClass("input-invalido");
                $('#recontrasena_persona').removeClass("input-invalido");
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
    $(document).ready(function ($) {

        //cambiarTipoIdentidad('R', '#rut_persona');  
        funcionCambioTipoIdentidad(1);
        if ($("#field_tipoIdentidad").val() === "1") {
            funcionCambioTipoIdentidad(1);
        } else if ($("#field_tipoIdentidad").val() === "2") {
            funcionCambioTipoIdentidad(2);
        }
        // if ($('#rut_persona').val() != "") {     
        //     tipoIdentidadRut();
        //     $('#rut_persona').prop('disabled', false);
        //     $('#rut_persona').removeClass("input-bloqueado");          
        // }       

        /* INICIO DE SESSION CON RUT */

        $('#btnEnviarRutSession').click(function () {
            //event.preventDefault();
            if (funcionValidarLogin()) {
                $('#rut_persona').prop('disabled', false);
                $('#new_post_inicio_sesion').submit();
                return true;
            } else {
                var body = $("html, body");
                body.stop().animate({ scrollTop: 0 }, 500, 'swing', function () { });
                return false;
            }
        });
        $('#btnEnviarCambioPass').click(function () {
            event.preventDefault();
            if (funcionValidarCambioPass()) {
                $('#rut_persona').prop('disabled', false);
                $('#new_post_inicio_sesion').submit();
                return true;
            } else {
                var body = $("html, body");
                body.stop().animate({ scrollTop: 0 }, 500, 'swing', function () { });
                return false;
            }
        });

        $('.btnBorrarRutSession').click(function () {
            event.preventDefault();
            if (limpiarCamposRut()) {
                return true;
            }
        })

    });
}(jQuery))



