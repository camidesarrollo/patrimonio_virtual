<?php
class SRVEncrypt{

  function _cl_encrypt_api_data($data, $secret_key) {
    // Convertir datos a JSON
    $json_data = json_encode($data);
    
    // Generar IV
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($iv_length);
    
    // Encriptar
    $encrypted = openssl_encrypt(
      $json_data,
      'aes-256-cbc',
      $secret_key,
      OPENSSL_RAW_DATA,
      $iv
    );
    
    // Combinar IV + datos encriptados
    return base64_encode($iv . $encrypted);
  }
}
