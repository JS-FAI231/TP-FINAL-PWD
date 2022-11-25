<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
$respuesta=false;
$objTrans = new ABMRol();

if (isset($datos['accion'])) {
    $respuesta = $objTrans->abm($datos);
    if($respuesta){
        $mensaje = "La accion ".$datos['accion']." se realizo correctamente.";
    }else {
        $mensaje = "La accion ".$datos['accion']." no pudo concretarse.";
    }
}

$retorno['respuesta'] = $respuesta;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}
 
echo json_encode($retorno);

?>