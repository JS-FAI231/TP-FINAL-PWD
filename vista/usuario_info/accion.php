<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
$respuesta = false;
$objTrans = new ABMUsuario();


if (isset($datos['accion'])) {
    if ($objTrans->existeMail($datos)) {
        $mensaje = "La direccion de mail ya existe en otra cuenta!.";
    } else {
        if (isset($datos['pass'])){
            $datos['pass']=md5($datos['pass']);
        }
        $respuesta = $objTrans->abm($datos);
        if (!$respuesta) {
            $mensaje = "La accion " . $datos['accion'] . " no pudo concretarse.";
        } else {
            $mensaje = "Felicitaciones, " . $datos['nombre'] . "! Su cuentra ha sido actualizada...";
        }
    }
}

echo ("<script>location.href = './index.php?mensajeOperacion=$mensaje&idusuario=" . $datos['idusuario'] . ">';</script>");
?>