<?php
//echo "prueba";
if (isset($_GET["url"]) && isset($_GET["token"])) {
    try {
        $token = $_GET["token"];
//$echo $token;        
$url  = 'http://10.83.216.138:8182/cxf/clave-unica/v1/userinfo';
        //$url  = 'https://fuse.patrimoniocultural.gob.cl/cxf/clave-unica/v1/userinfo';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_CAPATH, "/etc/ssl/certs");
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
        $server_output = curl_exec($ch);
        if (curl_error($ch))
	{
	    error_log("Curl: ".$url.", Token: ".$token."Response: ".print_r($server_output, true).", Error:".print_r(curl_error($ch), true));
            //print_r(curl_error($ch));
	}
        else
	{
	    //error_log("Curl OK: ".$url.", Token: ".$token."Response: ".print_r($server_output, true).", Error:".print_r(curl_error($ch), true));
	}
        curl_close($ch);
        $cu_info = json_decode($server_output);
        $identificacionUsuario = $cu_info->{'RolUnico'}->{'numero'} ."-". $cu_info->{'RolUnico'}->{'DV'};
        //echo $rut;
        $url="";
        if(loginUsuarioWordPress($identificacionUsuario, 1, true)){
            $url = site_url() . "/mi-perfil?token=".$token;            
        }else{
            $url =  site_url() . "/centralruc/redirigir/1";
        }
        wp_safe_redirect($url);

        

        // $url = 'http://10.83.216.138:8182/cxf/clave-unica/v1/userinfo';
        // $header = array();
        // $header = array(
        //     'Content-type: application/json',
        //     'Authorization: Bearer ' . "f3439131a5214936896f2da882035099",
        // );

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // //curl_setopt($ch, CURLOPT_POSTFIELDS, "op=update_bor&library=" . $libreria . "&update_flag=Y&xml_full_req=" . $inUrl);
        // $result = curl_exec($ch);
        // curl_close($ch);
        // $xml   = json_decode($result, true);
        // echo $xml;
        //loginUsuarioWordPress($_POST["rut_persona"], $_POST["contrasena_persona"],false);
        //$newURL=site_url()."/inscripcion?rut=".$xml["RolUnico"]["numero"]."-".$xml["RolUnico"]["DV"];
        //header('Location: '.$newURL);
    } catch (\Throwable $th) {
        echo $th;
    }
}
