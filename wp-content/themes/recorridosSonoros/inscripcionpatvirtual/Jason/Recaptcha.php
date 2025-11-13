<?php 

# Aquí pon la clave secreta que obtuviste en la página de developers de Google
#KEY LOCAL
define("CLAVE_SECRETA", "6Lf2pdciAAAAABh_Y1OmzUohNIVHgCtJ7bEpGpjb");
#Sitio web 6Lf2pdciAAAAACpnSb6pgWr1KExKtqa2cVJA1EtP
#KEY DESARROLLO

#define("CLAVE_SECRETA", "6LfBnA8jAAAAAJW7xDVbkec32gVcu-MN8rUt_aeb");
#Sitio web 6LfBnA8jAAAAAN3sxTm2OWCElIZ0jbAOecKQsIop

#KEY PRODUCCION

#define("CLAVE_SECRETA", "6Lcho6AjAAAAAGQNOeo0Z_ioAdQhauY-SaU7kM2S");
#Sitio web 6LcPGzUjAAAAAJbeEbqTYIYACS3O9bDiAJIp_rv0


# Comprobamos si enviaron el dato
if (!isset($_POST["identificacion"]) || empty($_POST["identificacion"])) {
    exit("Debes completar el captcha");
}

# Antes de comprobar usuario y contraseña, vemos si resolvieron el captcha
$token = $_POST["identificacion"];
$verificado = verificarToken($token, CLAVE_SECRETA);
# Si no ha pasado la prueba
echo json_encode($verificado, JSON_FORCE_OBJECT);

function verificarToken($token, $claveSecreta)
{
    # La API en donde verificamos el token
    $url = "https://www.google.com/recaptcha/api/siteverify";
    # Los datos que enviamos a Google
    $datos = [
        "secret" => $claveSecreta,
        "response" => $token,
    ];
    // Crear opciones de la petición HTTP
    $opciones = array(
        "http" => array(
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($datos), # Agregar el contenido definido antes
        ),
    );
    # Preparar petición
    $contexto = stream_context_create($opciones);
    # Hacerla
    $resultado = file_get_contents($url, false, $contexto);
    # Si hay problemas con la petición (por ejemplo, que no hay internet o algo así)
    # entonces se regresa false. Este NO es un problema con el captcha, sino con la conexión
    # al servidor de Google
    if ($resultado === false) {
        # Error haciendo petición
        return false;
    }

    # En caso de que no haya regresado false, decodificamos con JSON

    $resultado = json_decode($resultado);
    # La variable que nos interesa para saber si el usuario pasó o no la prueba
    # está en success
    $pruebaPasada = $resultado->success;
    # Regresamos ese valor, y listo (sí, ya sé que se podría regresar $resultado->success)
    return $pruebaPasada;
}



?>