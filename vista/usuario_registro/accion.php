<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
$respuesta = false;
$objTrans = new ABMUsuario();


if (isset($datos['accion'])) {
    if ($objTrans->existeMail($datos)) {
        $mensaje = "ERROR! La direccion de mail ya existe en otra cuenta!.";
        echo ("<script>location.href = './index.php?mensajeOperacion=$mensaje';</script>");
    } else {
        if ($objTrans->existeUsuario($datos)) {
            $mensaje = "ERROR! El nombre de usuario ya existe en otra cuenta!.";
            echo ("<script>location.href = './index.php?mensajeOperacion=$mensaje';</script>");
        } else {
            if (isset($datos['pass'])) {
                $datos['pass'] = md5($datos['pass']);
            }
            $respuesta = $objTrans->abm($datos);
            if (!$respuesta) {
                $mensaje = "La accion " . $datos['accion'] . " no pudo concretarse.";
                echo ("<script>location.href = './index.php?mensajeOperacion=$mensaje';</script>");
            } else {

                $mensaje = "Bienvenido, " . $datos['nombre'] . "! Su cuentra ha sido creada con exito...";
                echo ("<script>location.href = './../login/login.php?mensajeOperacion=$mensaje';</script>");
            }
        }
    }
}
