<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
$respuesta = false;
$objTrans = new ABMCompra();

$auxSession = new Session();
if ($auxSession->activa()) {
    if (isset($datos['accion'])) {
        $respuesta = $objTrans->abm($datos);
        if ($respuesta) {
            $mensaje = "Se agrego un item al carro.";
            if ($datos['accion']=='add_to_basket'){
                $cantidad = intval($auxSession->getCantidaditems());
                $cantidad++;
                $auxSession->setCantidaditems(strval($cantidad));
            }
        } else {
            $mensaje = "No se pudo agregar el item";
        }
    } else {
        $mensaje = "ERROR EN datos['accion']";
    }
} else {
    $mensaje = "Debe iniciar session.";
}

$retorno['respuesta'] = $respuesta;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}

echo json_encode($retorno);
