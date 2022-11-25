<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
$respuesta=false;
$objTrans = new ABMMenu();

if (isset($datos['accion'])) {
    $respuesta = $objTrans->abm($datos);
    if (!$respuesta) {
        $mensaje = "La accion " . $datos['accion'] . " no pudo concretarse.";
    } else {
        $mensaje = "La accion " . $datos['accion'] . " se concreto con exito.";
    }
}

$retorno['respuesta'] = $respuesta;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}

echo json_encode($retorno);
?>