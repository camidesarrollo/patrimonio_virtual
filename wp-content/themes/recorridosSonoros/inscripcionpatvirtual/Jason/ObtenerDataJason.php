<?php

/*function CalculaEdad($fechanacimiento)
{
    list($ano, $mes, $dia) = explode("-", $fechanacimiento);
    $ano_diferencia  = date("Y") - $ano;
    $mes_diferencia = date("m") - $mes;
    $dia_diferencia   = date("d") - $dia;
    if ($dia_diferencia < 0 || $mes_diferencia < 0)
        $ano_diferencia--;
    return $ano_diferencia;
}*/

class ObtenerDataJason
{
    private $conexion;
    private $validacion;

    public function __construct()
    {
        include get_template_directory() . '/inscripcionpatvirtual/models/Conexion.php';
        $this->conexion = new conexion();
        $this->conexion->conectar();
    }
    function Lista_Paises()
    {
        $consulta = "SELECT * FROM mu.Pais ORDER BY NombrePais ASC";
        $ejecutar = sqLsrv_query($this->conexion->conexion_bd, utf8_decode($consulta));
        return $ejecutar;
        $this->conexion->cerrar();
    }
    function Lista_Regiones()
    {
        $consulta = "SELECT * FROM mu.vRegiones ORDER BY Orden ASC";
        $ejecutar = sqLsrv_query($this->conexion->conexion_bd, $consulta);
        return $ejecutar;
        $this->conexion->cerrar();
    }
    function Lista_Comunas($idRegion)
    {
        $consulta = "SELECT t0.* FROM mu.vComunas t0 INNER JOIN mu.vProvincias t1 ON t0.CodigoProvincia = t1.CodigoProvincia
        WHERE CodigoRegion = ? and Activo = '1' ORDER BY NombreComuna ASC";
        $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($idRegion));
        $res = sqlsrv_execute($pstmt);
        //$ejecutar = odbc_exec($this->conexion->conexion_bd, utf8_decode($consulta));
        return $pstmt;
        $this->conexion->cerrar();
    }
    
    function verificaMaestro($user)
    {
        $user = substr($user, 0, -1);
        $consulta = "SELECT Nombre,ApellidoPaterno,ApellidoMaterno,FechaNacimiento,Correo,Comunas.NombreComuna,Region.NombreRegion,Comunas.CodigoComuna,Region.CodigoRegion
                    FROM ppv.vUsuarios AS Usuarios 
                    LEFT JOIN mu.vComunas AS Comunas ON  Usuarios.CodigoComuna = Comunas.CodigoComuna 
                    LEFT JOIN mu.vProvincias AS Provincia ON Comunas.CodigoProvincia = Provincia.CodigoProvincia
                    LEFT JOIN dbo.Regiones AS Region ON Region.CodigoRegion = Provincia.CodigoRegion
                    WHERE RUNPasaporte=?";
        $pstmt = sqlsrv_prepare($this->conexion->conexion_bd, $consulta, array($user));
        $res = sqlsrv_execute($pstmt);
        //var_dump($res);
        $obj = sqlsrv_fetch_object($pstmt);
        if ($obj == null) {
            return false;
        } else {
            return null;
        }
    }


}