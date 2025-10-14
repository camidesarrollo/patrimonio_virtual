<?php
$resultado = false;
if (isset($_GET["cval"]) && isset($_GET["ciden"]) && isset($_GET["cPas"] )) {
    $identificacionUsuario = $_GET["ciden"];
    $user = get_user_by('login', $identificacionUsuario);
    if ($user) {
        $all_meta_for_user = get_user_meta($user->ID);
        $persona = [];
        $md5Cod = md5($all_meta_for_user["mail_persona_temp"][0]);
        if ($md5Cod == $_GET["cval"]) {            
            $persona['mail_persona'] = $all_meta_for_user["mail_persona_temp"][0];
            $esPasaporte = $persona['tipo_identificacion'] == "P" ? true : false;
            if(cambiaMailUsuario($identificacionUsuario, $esPasaporte,$persona['mail_persona'])){
                $resultado = true;
            } 
        }
    }
}
if ($resultado) {
    if (is_user_logged_in()) {
        $url = site_url() . "/mi-perfil?acC=mailupdate";
      }else{
        $url = site_url() . "/registro-msge?correoActualizado=1";
      }    
} else {
    $url = site_url() . "/registro-msge?errorCorreoActualizado=1";
}
wp_safe_redirect($url);
