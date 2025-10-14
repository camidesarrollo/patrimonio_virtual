<?php
$resultado = false;
if (isset($_GET["cval"]) && isset($_GET["ciden"])) {
    $identificacionUsuario = $_GET["ciden"];
    $user = get_user_by('login', $identificacionUsuario);
    if ($user) {
       
        $all_meta_for_user = get_user_meta($user->ID);
        $persona = [];
        $md5Cod = md5($all_meta_for_user["validado_persona"][0]);
        if ($all_meta_for_user["validado_persona"][0] != 0 && $md5Cod == $_GET["cval"]) {
            $persona['rut_persona'] = $all_meta_for_user["rut_persona"][0];
            $persona['pais_persona'] = $all_meta_for_user["pais_persona"][0];
            $persona['paisorigen_persona'] = $all_meta_for_user["paisorigen_persona"][0];
            $persona['codigo_nacionalidad'] = $all_meta_for_user["codigo_nacionalidad"][0];
            $persona['tipo_identificacion'] = $all_meta_for_user["tipo_identificacion"][0];
            $persona['nombre_persona'] = $all_meta_for_user["nombre_persona"][0];
            $persona['apellido_paterno'] = $all_meta_for_user["apellido_paterno"][0];
            $persona['apellido_materno'] = $all_meta_for_user["apellido_materno"][0];
            $persona['sexo_persona'] = $all_meta_for_user["sexo_persona"][0];
            $persona['fecha_nacimiento'] = $all_meta_for_user["fecha_nacimiento"][0];
            $persona['region_persona'] = $all_meta_for_user["region_persona"][0];
            $persona['comuna_persona'] = $all_meta_for_user["comuna_persona"][0];
            $persona['mail_persona'] = $all_meta_for_user["mail_persona"][0];
            $persona['contrasena_persona'] = $all_meta_for_user["contrasena_persona"][0];
            $esPasaporte = $persona['tipo_identificacion'] == "P" ? true : false;

            if(registroMaestro($persona, $esPasaporte)){
                update_user_with_metadata($persona);
                $resultado = true;
            } 
        }
    }
}
if ($resultado) {
    $url = site_url() . "/registro-msge?successRegistro=1";
} else {
    $url = site_url() . "/registro-msge?errorValidacion=1";
}
wp_safe_redirect($url);
