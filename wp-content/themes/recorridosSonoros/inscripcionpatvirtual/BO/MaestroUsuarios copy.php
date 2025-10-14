<?php
class conexion
{
    private $servidor;
    private $datos;
    public  $conexion_bd;

    public function __construct()
    {
        $this->servidor   = '10.0.1.225';
        $this->datos      = array("database" => "Biblioredes", "uid" => "dba", "pwd" => "P0o9i8u7y6", "TrustServerCertificate" => "yes");
    }

    function conectar()
    {
        $this->conexion_bd = sqlsrv_connect($this->servidor, $this->datos);
        if ($this->conexion_bd) {
        } else {
            echo "Conexión no se pudo establecer.";
            error_log("Conexión no se pudo establecer.", 0);
            $errorTxt = print_r(sqlsrv_errors(), true);
            error_log($errorTxt, 0);
        }
    }

    function cerrar()
    {
        $this->conexion_bd->close();
    }
}

class MaestroUsuarios
{
    function __construct()
    {
        $this->conexion = new conexion();
        $this->conexion->conectar();
    }


    function Lista_Regiones()
    {
        $consulta = "SELECT * FROM mu.vRegiones ORDER BY Orden ASC";
        $ejecutar = sqLsrv_query($this->conexion->conexion_bd, $consulta);
        return $ejecutar;
        $this->conexion->cerrar();
    }
}

$instancia_lfiltro = new MaestroUsuarios();
$resultados = $instancia_lfiltro->Lista_Regiones();
echo "<option value='0'>Seleccione su región</option>";
        while ($fila = sqLsrv_fetch_array($resultados)) {
          $fila = mb_convert_encoding($fila, "UTF-8", "iso-8859-1");
          $CodigoRegion = $fila["CodigoRegion"];
          $NombreRegion = $fila["NombreRegion"];
          echo "<option value='" . $CodigoRegion . "'>" . utf8_decode($NombreRegion) . "</option>";
        }
        echo "</optgroup>";


