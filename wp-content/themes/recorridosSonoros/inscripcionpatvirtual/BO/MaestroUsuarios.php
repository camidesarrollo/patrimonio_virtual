<?php

use SoapClient;

class MaestroUsuarios
{
    private $conexion;
    private $validacion;

    public function __construct()
    {
        include_once get_template_directory() . '/inscripcionpatvirtual/models/Conexion.php';
        $this->conexion = new conexion();
        $this->conexion->conectar();
    }
    function verificaMaestro($user)
    {
        try {
            $user = substr($user, 0, -2);
            $consulta = "SELECT * FROM mu.Usuarios AS Usuarios WHERE RUN=?";
            $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user));
            $res = sqlsrv_execute($pstmt);
            //var_dump($res);
            $obj = sqlsrv_fetch_object($pstmt);
            if ($obj != null) {
                return $obj;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return 'error';
        }
    }
    function verificaMaestroCorreo($user)
    {
        try {

            $consulta = "SELECT * FROM mu.Usuarios AS Usuarios WHERE Correo=?";
            $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user));
            $res = sqlsrv_execute($pstmt);
            //var_dump($res);
            $obj = sqlsrv_fetch_object($pstmt);
            if ($obj != null) {
                return $obj;
            } else {
                $consulta = "SELECT * FROM mu.Extranjeros AS Usuarios WHERE Correo=?";
                $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user));
                $res = sqlsrv_execute($pstmt);
                //var_dump($res);
                $obj = sqlsrv_fetch_object($pstmt);
                if ($obj != null) {
                    return $obj;
                } else {
                    return false;
                }
            }
        } catch (\Throwable $th) {
            return 'error';
        }
    }
    function verificaMaestroFecha($identificacion, $fecNacimiento, $esPasaporte)
    {
        try {
            $consulta = "";
            if (!$esPasaporte) {
                $user = substr($identificacion, 0, -2);
                $consulta = "SELECT * FROM mu.Usuarios AS Usuarios WHERE RUN=? AND FechaNacimiento=?";
            } else {
                $consulta = "SELECT * FROM mu.Extranjeros AS Usuarios WHERE NumeroPasaporte=? AND FechaNacimiento=?";
                $user = $identificacion;
            }

            $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user, $fecNacimiento));
            $res = sqlsrv_execute($pstmt);
            //var_dump($res);
            $obj = sqlsrv_fetch_object($pstmt);
            if ($obj != null) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
    function verificaMaestroPasaporte($user)
    {
        try {
            $consulta = "SELECT * FROM mu.Extranjeros AS Usuarios WHERE NumeroPasaporte=?";
            $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user));
            $res = sqlsrv_execute($pstmt);
            //var_dump($res);
            $obj = sqlsrv_fetch_object($pstmt);
            if ($obj != null) {
                return $obj;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return 'error';
        }
    }
    function verificaMaestroClave($identificacion, $clave, $esPasaporte)
    {
        //$wsdl = "http://localhost:9879/Seguridad.svc?wsdl";
        $wsdl = "https://swb.biblioredes.gob.cl:9000/Seguridad.svc?wsdl";
        $datosIP = $this->determinarTipoUsuarioYSistema($esPasaporte);

        try {
            $client = new SoapClient($wsdl, array('trace' => true));
	        $client->__setLocation($wsdl);
            // Verificar si es pasaporte o run
            if ($esPasaporte) {
                // Llamar al método SOAP específico para pasaporte
                $params = array(
                    'pasaporte' => $identificacion,
                    'contrasena' => $clave,
                    'codigoServicio' => $datosIP['CodigoServicio'],
                    'ip' => $datosIP['ip'],
                    'calledstationid' => null,
                );
                $response = $client->__soapCall("IniciarSesionPasaporte", array($params)); // Método específico para pasaporte
                $obj = $response->IniciarSesionPasaporteResult->Codigo;
            } else {
                // Extraer run y dv si no es pasaporte
                list($run, $dv) = explode('-', $identificacion);
                $params = array(
                    'run' => $run,
                    'dv' => $dv,
                    'contrasena' => $clave,
                    'codigoServicio' => $datosIP['CodigoServicio'],
                    'ip' => $datosIP['ip'],
                    'calledstationid' => null,
                );
                $response = $client->__soapCall("IniciarSesionUsuario", array($params)); // Método específico para RUN
                $obj = $response->IniciarSesionUsuarioResult->Codigo;
            }
            if ($obj == "1") {
                return $obj;
            } else {
                return false;
            }


            // Mostrar la respuesta
            echo "Respuesta: ";
        } catch (\Throwable $e) {
            // Manejar errores
            echo "Error: " . $e->getMessage();
        }


        // try {

        //     $consulta = "SELECT CodigoUsuario,ClaveUsuario FROM mu.UsuariosClaves AS Usuarios WHERE CodigoUsuario=? AND ClaveUsuario=?";
        //     $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user, $clave));
        //     $res = sqlsrv_execute($pstmt);
        //     //var_dump($res);
        //     $obj = sqlsrv_fetch_object($pstmt);
        //     if ($obj != null) {
        //         return $obj;
        //     } else {
        //         return false;
        //     }
        // } catch (\Throwable $th) {
        //     return 'error';
        // }
    }
    function verificaMaestroServicioPasaporte($user)
    {
      $wsdl = "https://swb.biblioredes.gob.cl:9000/Usuario.svc?wsdl";
      try {
        $client = new SoapClient($wsdl, array('trace' => true));
        // Llamar al método SOAP específico para pasaporte
        $params = array(
          'pasaporte' => $user,
        );
        $response = $client->__soapCall("ObtenerInformacionPersonalBasicaPasaporte", array($params)); // Método específico para pasaporte
        $codigo = $response->ObtenerInformacionPersonalBasicaPasaporteResult->Codigo;
        $Objeto = $response->ObtenerInformacionPersonalBasicaPasaporteResult->Objeto;
        if ($codigo == "1") {
          return json_decode($Objeto);
        } else if ($codigo == "2") {
          return null;
        } else {
          return false;
        }
      } catch (\Throwable $th) {
        return 'error';
      }
    }
    function verificaMaestroPasaporteServicio($user)
    {
        $wsdl = "https://swb.biblioredes.gob.cl:9000/" . "Usuario.svc?wsdl";
        try {
        $client = new SoapClient($wsdl, array('trace' => true));
        // Llamar al método SOAP específico para pasaporte
        $params = array(
            'pasaporte' => $user,
        );
        $response = $client->__soapCall("ObtenerInformacionPersonalBasicaPasaporte", array($params)); // Método específico para pasaporte
        $codigo = $response->ObtenerInformacionPersonalBasicaPasaporteResult->Codigo;
        $Objeto = $response->ObtenerInformacionPersonalBasicaPasaporteResult->Objeto;
        if ($codigo == "1") {
            return json_decode($Objeto);
        } else if ($codigo == "2") {
            return null;
        } else {
            return false;
        }
        } catch (\Throwable $th) {
        return 'error';
        }
    }
    function obtenerMaestroRun($user)
    {
        $wsdl = $wsdl = "https://swb.biblioredes.gob.cl:9000/Usuario.svc?wsdl";
        try {

        $run = $user;
        // Separar el RUN y el dígito verificador
        list($numeroRun, $dv) = explode('-', $run);

        // El número RUN sin el DV
        $run = $numeroRun;

        // Crear el cliente SOAP
        $client = new SoapClient($wsdl, array('trace' => true));

        // Llamar al método SOAP específico para obtener la información personal
        $params = array(
            'run' => $run,
            'dv' => $dv, // Pasar el dígito verificador
        );
        $response = $client->__soapCall("ObtenerInformacionPersonalBasicaRUN", array($params)); // Método específico para pasaporte
        $codigo = $response->ObtenerInformacionPersonalBasicaRUNResult->Codigo;
        $Objeto = $response->ObtenerInformacionPersonalBasicaRUNResult->Objeto;
        if ($codigo == "1") {
            return json_decode($Objeto);
        } else if ($codigo == "2") {
            return null;
        } else {
            return false;
        }
        } catch (\Throwable $th) {
        return 'error';
        }
    }
    function Modificar_Clave_Usuario($identificacion, $password, $esPasaporte, $oldpassword)
    {

        // $wsdl = "http://localhost:9879/Seguridad.svc?wsdl";
        $wsdl = "https://swb.biblioredes.gob.cl:9000/Seguridad.svc?wsdl";

        try {
          $client = new SoapClient($wsdl, ['trace' => true]);

          if (!empty($oldpassword)) {
              // Caso: Modificar clave con clave antigua
              $params = [
                'claveNueva' => $password,
                'claveConfirmacion' => $password,
                'claveAntigua' => $oldpassword,
                $esPasaporte ? 'codigoOrigenUsuario' : 'CodigoOrigenUsuario' => $esPasaporte ? 2 : 1,
            ];

              if ($esPasaporte) {
                  $params['pasaporte'] = $identificacion;
                  $method = "CambioContrasenaPasaporte";
                  $resultKey = "CambioContrasenaPasaporteResult";
              } else {
                  list($params['run'], $params['dv']) = explode('-', $identificacion);
                  $method = "CambiarContrasenaRUN";
                  $resultKey = "CambiarContrasenaRUNResult";
              }
          } else {
              // Caso: Restablecer clave sin clave antigua
              $usuarioMaestro = $esPasaporte
                  ? $this->verificaMaestroPasaporteServicio($identificacion)
                  : $this->obtenerMaestroRun($identificacion);
          
           
              if (!$usuarioMaestro || $usuarioMaestro === 'error') {
                  return false; // Error al obtener usuario maestro
              }
              
              $fechaCompleta = $usuarioMaestro->FechaNacimiento; // "1997-06-10T00:00:00"
              $soloFecha = (new DateTime($fechaCompleta))->format('Y-m-d'); // "1997-06-10"

              $params = [
                  'runPasaporte' => $esPasaporte ? $usuarioMaestro->NumeroPasaporte : $usuarioMaestro->RUN,
                  'fechaNacimiento' =>$soloFecha,
                  'nuevaclave' => $password,
                  'origen' => $esPasaporte == false ? 1 : 2,

              ];
            
              $method = "RestablecerContrasenaRunPasaporte";
              $resultKey = "RestablecerContrasenaRunPasaporteResult";
          }
          // Llamar al método SOAP
          $response = $client->__soapCall($method, [$params]);
  
          $codigo = $response->$resultKey->Codigo ?? null;
     
          return $codigo === "1";
      } catch (\Throwable $e) {
          // Manejar errores (opcionalmente registrar el error)
          return false;
      }
    }
    // function Insertar_Usuario($user, $esPasaporte)
    // {
    //     try {
    //         // $wsdl = "http://localhost:9879/Seguridad.svc?wsdl";
    //         $wsdl = "https://swb.biblioredes.gob.cl:9000/Seguridad.svc?wsdl";
    //         $client = new SoapClient($wsdl, array('trace' => true));

    //         $run = substr($user["rut_persona"], 0, -2);
    //         $dv = substr($user["rut_persona"], -1);
    //         $consulta = "";
    //         if ($esPasaporte) {
    //             $consulta = "SELECT CodigoExtranjero,NumeroPasaporte FROM mu.Extranjeros AS Usuarios WHERE NumeroPasaporte=?";
    //             $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user["rut_persona"]));
    //         } else {
    //             $consulta = "SELECT CodigoUsuario,RUN FROM mu.Usuarios AS Usuarios WHERE RUN=?";
    //             $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($run));
    //         }
    //         $res = sqlsrv_execute($pstmt);
    //         $obj = sqlsrv_fetch_object($pstmt);
    //         if ($obj != null) {
    //             return false;
    //         } else {
    //             if ($esPasaporte) {
    //                 $params = array(
    //                     'pasaporte' => $user["rut_persona"],
    //                     'nombre' => $user["nombre_persona"],
    //                     'apellidoPaterno' => $user["apellido_paterno"],
    //                     'apellidoMaterno' => $user["apellido_materno"],
    //                     'fechaNacimiento' => $user["fecha_nacimiento"],
    //                     'correo' => $user["mail_persona"],
    //                     'codigoSexo' => $user["sexo_persona"] ?? null,
    //                     'codigoEscolaridad' => null,
    //                     'codigoOcupacion' => null,
    //                     'codigoComuna' => isset($user["comuna_residencia"]) ?? null,
    //                     'confirmarDatos' => 1,
    //                     'comentarioDatos' => null,
    //                     'contraseña' => $user['contrasena_persona'],
    //                     'codigoOrigenUsuario' => 1,
    //                     'servicio' => null,
    //                     'ip' => null,
    //                     'calledstationid' => null,
    //                     'paisOrigen' => $user["pais_persona"],
    //                     'codigoTipoEstancia' => null
    //                 );
    //                 $response = $client->__soapCall("RegistrarUsuarioPasaporte", array($params)); // Método específico para pasaporte
    //                 $obj = $response->RegistrarUsuarioPasaporteResult->Codigo;

    //                 // $consulta = "INSERT INTO mu.Extranjeros (NumeroPasaporte,Nombre,ApellidoPaterno,ApellidoMaterno,FechaNacimiento,Correo,CodigoSexo) VALUES (?,?,?,?,?,?,?)";
    //                 // $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user["rut_persona"], $user["nombre_persona"], $user["apellido_paterno"], $user["apellido_materno"], $user["fecha_nacimiento"], $user["mail_persona"], $user["sexo_persona"]));
    //             } else {
    //                 $params = array(
    //                     'run' => $run,
    //                     'dv' => $dv,
    //                     'nombre' => $user["nombre_persona"],
    //                     'apellidoPaterno' => $user["apellido_paterno"],
    //                     'apellidoMaterno' => $user["apellido_materno"],
    //                     'fechaNacimiento' => $user["fecha_nacimiento"],
    //                     'correo' => $user["mail_persona"],
    //                     'codigoSexo' => $user["sexo_persona"],
    //                     'codigoEscolaridad' => null,
    //                     'codigoOcupacion' => null,
    //                     'codigoComuna' => $user["comuna_persona"],
    //                     'confirmarDatos' => 1,
    //                     'comentarioDatos' => null,
    //                     'codigoNacionalidad' => $user["codigo_nacionalidad"],
    //                     'paisOrigen' => $user["pais_persona"],
    //                     'verificado' => 1,
    //                     'contraseña' => $user['contrasena_persona'],
    //                     'codigoOrigenUsuario' => 1,
    //                     'servicio' => null,
    //                     'ip' => null,
    //                     'calledstationid' => null
    //                 );
    //                 $response = $client->__soapCall("RegistrarUsuarioRUN", array($params)); // Método específico para RUN
    //                 $obj = $response->RegistrarUsuarioRUNResult->Codigo;

    //                 // $consulta = "INSERT INTO mu.Usuarios (RUN,DV,Nombre,ApellidoPaterno,ApellidoMaterno,FechaNacimiento,Correo,CodigoSexo,CodigoNacionalidad,Verificado) VALUES (?,?,?,?,?,?,?,?,?,?)";
    //                 // $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($run, $dv, $user["nombre_persona"], $user["apellido_paterno"], $user["apellido_materno"], $user["fecha_nacimiento"], $user["mail_persona"], $user["sexo_persona"], 'C', 1));
    //             }
    //             //$ejecutar = odbc_exec($this->conexion->conexion_bd, utf8_decode($consulta));
    //             if ($obj == 1) {
    //                 // $user['idUsuario'] = $this->verificaMaestro($user["rut_persona"]);
    //                 $user['idUsuario'] = $user["idUsuario"]->CodigoUsuario;
    //                 return true;
    //             } else {
    //                 return false;
    //                 // $this->Insertar_ClaveUsuario($user, $esPasaporte);
    //                 // $this->CrearUsuarioRegistro($user, $esPasaporte);
    //                 // $this->Insertar_ClaveRadius($user, $esPasaporte);

    //             }
    //         }
    //     } catch (\Throwable $th) {
    //         return false;
    //     }
    // }


    function Insertar_Usuario($user, $esPasaporte)
  {
 
    $wsdl = $wsdl = "https://swb.biblioredes.gob.cl:9000/" . "Seguridad.svc?wsdl";
    try {
      $client = new SoapClient($wsdl, array('trace' => true));
  
      $usuarioMaestro = ($esPasaporte ==  1)
        ? $this->verificaMaestroPasaporte($user["rut_persona"])
        : $this->obtenerMaestroRun($user["rut_persona"]);
 
      if ($esPasaporte != 1) {
        $run = substr($user["rut_persona"], 0, -2);
        $dv = substr($user["rut_persona"], -1);
      }
   
      if ($usuarioMaestro != null && ( $usuarioMaestro === false || $usuarioMaestro == 'error')) {
        return false;
      }

      $obj = $usuarioMaestro;

      if ($obj != null) {
        return false;
      } else {
       
        // SI EL USUARIO NO EXISTE, LO CREA
        $datosIP = $this->determinarTipoUsuarioYSistema($esPasaporte);
     
    
        if ($esPasaporte) {

          $params = array(
            'pasaporte' => $user["rut_persona"],
            'nombre' => $user["nombre_persona"],
            'apellidoPaterno' => $user["apellido_paterno"],
            'apellidoMaterno' => $user["apellido_materno"],
            'fechaNacimiento' => $user["fecha_nacimiento"],
            'correo' => $user["mail_persona"],
            'codigoSexo' => $user["sexo_persona"] ?? null,
            'codigoEscolaridad' => null,
            'codigoOcupacion' => null,
            'codigoComuna' => isset($user["comuna_persona"]) == true ? $user["comuna_persona"] : null,
            'confirmarDatos' => 1,
            'comentarioDatos' => null,
            'contraseña' => $user['contrasena_persona'],
            'codigoOrigenUsuario' => 2,
            'servicio' => $datosIP['CodigoServicio'],
            'ip' => $datosIP['ip'],
            'calledstationid' => null,
            'paisOrigen' => $user["paisorigen_persona"],
            'codigoTipoEstancia' => null,
            'paisResidencia' => $user["pais_persona"] == 0 || $user["pais_persona"] == null ? null :  $user["pais_persona"], //Mi parametro
          );
       
          $response = $client->__soapCall("RegistrarUsuarioPasaporte", array($params)); // Método específico para pasaporte
          $obj = $response->RegistrarUsuarioPasaporteResult->Codigo;
        
          if ($obj != "1") {

            return false;
          } else {

            $params = array(
              'runPasaporte' => $user["rut_persona"],
              'fechaNacimiento' => $user["fecha_nacimiento"],
              'nuevaclave' => $user['contrasena_persona'],
              'origen' => 2
            );
            $response = $client->__soapCall("RestablecerContrasenaRunPasaporte", array($params)); // Método específico para pasaporte
            $obj = $response->RestablecerContrasenaRunPasaporteResult->Codigo;
            if ($obj != "1") {
              return false;
            }
            return true;
          }
        } else {
          if (isset($user["comuna_persona"])) {
            $valor_comuna = $user["comuna_persona"];
            // Hacer algo con $valor_comuna
          } else {
            $valor_comuna = Null;
          }
          $params = array(
            'run' => $run,
            'dv' => $dv,
            'nombre' => $user["nombre_persona"],
            'apellidoPaterno' => $user["apellido_paterno"],
            'apellidoMaterno' => $user["apellido_materno"],
            'fechaNacimiento' => $user["fecha_nacimiento"],
            'correo' => $user["mail_persona"],
            'codigoSexo' => $user["sexo_persona"] ?? null,
            'codigoEscolaridad' => null,
            'codigoOcupacion' => null,
            'codigoComuna' => $valor_comuna,
            'confirmarDatos' => 1,
            'comentarioDatos' => null,
            'codigoNacionalidad' => $user["codigo_nacionalidad"],
            'paisOrigen' => $user["paisorigen_persona"],
            'paisResidencia' => $user["pais_persona"] == 0 || $user["pais_persona"] == null ? null :  $user["pais_persona"], //Mi parametro
            'verificado' => 1,
            'contraseña' => $user['contrasena_persona'],
            'codigoOrigenUsuario' => 1,
            'servicio' => $datosIP['CodigoServicio'],
            'ip' => $datosIP['ip'],
            'calledstationid' => null
          );
     
          $response = $client->__soapCall("RegistrarUsuarioRUN", array($params)); // Método específico para RUN
          $obj = $response->RegistrarUsuarioRUNResult->Codigo;
 
          if ($obj != "1") {

            return false;
          }
        }

        if ($esPasaporte ==  1) {
          $usuarioMaestro = $this->verificaMaestroPasaporte($user["rut_persona"]);
          if ($usuarioMaestro != false && $usuarioMaestro != 'error') {
            $user['idUsuario'] = $user['idUsuario']->CodigoExtranjero;
          } else {
            return false;
          }
        } else {
          $usuarioMaestro = $this->obtenerMaestroRun($user["rut_persona"]);
          if ($usuarioMaestro != false && $usuarioMaestro != 'error') {
            $user['idUsuario'] = $user['idUsuario']->CodigoUsuario;
          } else {
            return false;
          }
        }

        return true;
      }
    } catch (\Throwable $th) {
      return false;
    }
  }

  function determinarTipoUsuarioYSistema($esPasaporte)
  {
    $ip = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] :
    (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

    // Convertir ::1 a 127.0.0.1 si estás en localhost
    if ($ip === '::1') {
        $ip = '127.0.0.1';
    }

    $tipoUsuario = $esPasaporte ? 2 : 1;
    $codSistema = $ip ? $this->esRangoIp($ip) : null;
    return ['tipoUsuario' => $tipoUsuario, 'codSistema' => $codSistema, 'ip' => $ip, 'CodigoServicio' => 49 ];
  }
    function Insertar_ClaveUsuario($user, $esPasaporte)
    {
        try {
            if ($esPasaporte) {
                $run = $user["rut_persona"];
                $tipoUsuario = 2;
            } else {
                $run = substr($user["rut_persona"], 0, -2);
                $tipoUsuario = 1;
            }
            $consulta = "SELECT CodigoUsuario FROM mu.UsuariosClaves WHERE CodigoUsuario=?";
            $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user['idUsuario']));
            $res = sqlsrv_execute($pstmt);
            //var_dump($res);
            $obj = sqlsrv_fetch_object($pstmt);
            if ($obj != null) {
                $consulta = "UPDATE mu.UsuariosClaves SET ClaveUsuario=? WHERE CodigoUsuario=?";
                //$ejecutar = odbc_exec($this->conexion->conexion_bd, utf8_decode($consulta));
                $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user['contrasena_persona'], $user['idUsuario']));
                if (sqlsrv_execute($pstmt) === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                $this->conexion->cerrar();
                return $pstmt;
            } else {
                $consulta = "INSERT INTO mu.UsuariosClaves (CodigoUsuario,CodigoOrigenUsuario,RunPasaporte,ClaveUsuario) VALUES (?,?,?,?)";
                //$ejecutar = odbc_exec($this->conexion->conexion_bd, utf8_decode($consulta));
                $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user['idUsuario'], $tipoUsuario, $run, $user['contrasena_persona']));
                if (sqlsrv_execute($pstmt) === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                return $pstmt;
                $this->conexion->cerrar();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    function Insertar_ClaveRadius($user, $esPasaporte)
    {
        if ($esPasaporte) {
            $run = $user["rut_persona"];
        } else {
            $run = substr($user["rut_persona"], 0, -2);
        }
        try {
            $client = new SoapClient("http://10.0.1.135:7416/Repositorio/BCOM/Seguridad.svc?wsdl");
            $result = $client->InsertarClaveRadius(["usuario" => $run, "contrasenia" => $user['contrasena_persona']]);
            if ($result->InsertarClaveRadiusResult) {
                $data = array(
                    'estado' => 'ok',
                );
            } else {
                $data = array(
                    'estado' => 'error',
                );
            }
            echo json_encode($data, JSON_FORCE_OBJECT);
        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
    }

    function CrearUsuarioRegistro($codUsuario, $esPasaporte)
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        ($esPasaporte) ? $tipoUsuario = 2 : $tipoUsuario = 1;
        $codSistema = null;
        ($ip) ? $codSistema = $this->esRangoIp($ip) : $codSistema = null;
        $consulta = "SELECT CodigoUsuario FROM mu.UsuariosRegistros WHERE CodigoUsuario=?";
        $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($codUsuario));
        $res = sqlsrv_execute($pstmt);
        $obj = sqlsrv_fetch_object($pstmt);
        if ($obj == null) {
            $consulta = "INSERT INTO mu.UsuariosRegistros (CodigoUsuario,CodigoOrigenUsuario,CodigoSistema,CodigoServicio,DireccionIP,FechaRegistro) VALUES (?,?,?,?,?,?)";
            $hoy = date("Y-m-d H:i:s");
            $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($codUsuario, $tipoUsuario, $codSistema, 49, $ip, $hoy));
            if (sqlsrv_execute($pstmt) === false) {
                return false;
            } else {
                return true;
            }
            return $pstmt;
        }
    }
    function esRangoIp($ip)
    {
        $consulta = "SELECT * FROM dbo.RangoIP";
        $ipTransf = ip2long($ip);
        $res = sqLsrv_query($this->conexion->conexion_bd, $consulta);
        $codSistema = null;
        if ($ipTransf) {
            while ($fila = sqLsrv_fetch_array($res)) {
                $min = ip2long($fila["RangoIPDesde"]);
                $max = ip2long($fila["RangoIPHasta"]);
                if ($min <= $ipTransf && $ipTransf <= $max) {
                    $codSistema = $fila["CodigoSistema"];
                    break;
                }
            }
        }
        return $codSistema;
    }

    function Modificar_Mail_Usuario($identificacion, $esPasaporte, $mailUsuario)
    {

        // $wsdl = "http://localhost:9879/Seguridad.svc?wsdl";
        $wsdl = "https://swb.biblioredes.gob.cl:9000/Seguridad.svc?wsdl";
        try {
            $client = new SoapClient($wsdl, array('trace' => true));
            $user = $this->verificaMaestroPasaporte($identificacion);
            if ($user) {

                // Llamar al método SOAP específico para pasaporte
                $params = array(
                    'pasaporte' => $user->NumeroPasaporte,
                    'nombre' => $user->Nombre,
                    'apellidoPaterno' => $user->ApellidoPaterno,
                    'apellidoMaterno' => $user->ApellidoMaterno,
                    'fechaNacimiento' => $user->FechaNacimiento->format('Y-m-d-H-i-s'),
                    'correo' => $mailUsuario,
                    'codigoSexo' => $user->CodigoSexo,
                    'codigoEscolaridad' => $user->CodigoEscolaridad,
                    'codigoOcupacion' => $user->CodigoOcupacion,
                    'codigoComuna' => $user->CodigoComuna,
                    'parm_nacionalidad' => $user->CodigoNacionalidad,
                    'paisOrigen' => $user->CodigoPaisOrigen,
                    'paisResidencia' => $user->CodigoPaisResidencia
                );
                $response = $client->__soapCall("ActualizarUsuarioPasaporte", array($params)); // Método específico para pasaporte
                $obj = $response->ActualizarUsuarioPasaporteResult->Codigo;
                return $obj;
            } else {
                $user = $this->verificaMaestro($identificacion);
                if ($user) {
                    list($run, $dv) = explode('-', $identificacion);
                    $params = array(
                        'run' => $user->RUN,
                        'dv' => $user->DV,
                        'nombre' => $user->Nombre,
                        'apellidoPaterno' => $user->ApellidoPaterno,
                        'apellidoMaterno' => $user->ApellidoMaterno,
                        'fechaNacimiento' => $user->FechaNacimiento->format('Y-m-d'),
                        'correo' => $mailUsuario,
                        'codigoSexo' => $user->CodigoSexo,
                        'codigoEscolaridad' => $user->CodigoEscolaridad,
                        'codigoOcupacion' => $user->CodigoOcupacion,
                        'codigoComuna' => $user->CodigoComuna,
                        'parm_nacionalidad' => $user->CodigoNacionalidad,
                        'paisOrigen' => $user->CodigoPaisOrigen,
                        'paisResidencia' => $user->CodigoPaisResidencia
                    );
                    $response = $client->__soapCall("ActualizarUsuarioRUN", array($params)); // Método específico para RUN
                    $obj = $response->ActualizarUsuarioRUNResult->Codigo;
                    return $obj;
                } else {
                    return false;
                }
            }





            // Mostrar la respuesta
        } catch (\Throwable $e) {
            // Manejar errores
            return false;
        }


        // try {


        //     $consulta = "";
        //     if (!$esPasaporte) {


        //         $consulta = "UPDATE mu.Usuarios SET Correo=? WHERE RUN=?";
        //         $identificacion = substr($identificacion, 0, -2);
        //     } else {
        //         $consulta = "UPDATE mu.Extranjeros SET Correo=? WHERE NumeroPasaporte=?";
        //     }
        //     $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($mailUsuario, $identificacion));
        //     if (sqlsrv_execute($pstmt) === false) {
        //         die(print_r(sqlsrv_errors(), true));
        //         return false;
        //     } else {
        //         return true;
        //     }

        //     return $pstmt;
        // } catch (\Throwable $th) {
        //     return false;
        // }
    }
}
