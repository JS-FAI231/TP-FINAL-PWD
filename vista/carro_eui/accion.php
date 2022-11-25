<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
$respuesta = false;
if ($datos['accion'] == 'finalizar_pedido') {
    if (isset($datos['idcompra'])) {
        $objTrans = new ABMCompra();
        if (isset($datos['accion'])) {
            $respuesta = $objTrans->abm($datos);
            if (!$respuesta) {
                $mensaje = "La accion " . $datos['accion'] . " no pudo concretarse. ";
                $mensaje = "OPPS!!, hubo algun inconveniente con la operacion.";
            } else {
                $mensaje = "La accion " . $datos['accion'] . " se concreto con exito.";
                $mensaje = "Muchas gracias por su compra!!, en breve estaremos en contacto con usted.";
            }
        }
    } else {
        // $mensaje = "FALTA IDCOMPRA";
    }
    echo ("<script>location.href = './../home/index.php?msg=$mensaje&inicarro=1&idus=" . $datos['idus'] . "';</script>");
} else {
    $objTrans = new ABMCompraitem();
    if (isset($datos['accion'])) {
        $respuesta = $objTrans->abm($datos);
        if (!$respuesta) {
            $mensaje = "La accion " . $datos['accion'] . " no pudo concretarse.";
        } else {
            $mensaje = "La accion " . $datos['accion'] . " se concreto con exito.";
            $auxSession = new Session();
            if ($auxSession->activa()) {
                if ($datos['accion']=='borrar'){
                    $cantidad=intval($auxSession->getCantidaditems());
                    $cantidad--;
                    $auxSession->setCantidaditems(strval($cantidad));
                }
            }
        }
    }

    $retorno['respuesta'] = $respuesta;
    if (isset($mensaje)) {
        $retorno['errorMsg'] = $mensaje;
    }

    echo json_encode($retorno);
}
