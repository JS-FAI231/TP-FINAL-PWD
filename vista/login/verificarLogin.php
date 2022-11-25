<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
if (isset($datos['pass'])){
    $datos['pass']=md5($datos['pass']);
}

$objSession = new Session();
$objSession->iniciar($datos['nombre'], $datos['pass']);

if ($objSession->activa()) {
    $idus=$objSession->getIdusuario();
    header('location:..\home\index.php?idus='.$idus);
}else{
    header('location:login.php?mensajeOperacion=Credenciales Invalidas... Intente nuevamente.');
}
?>