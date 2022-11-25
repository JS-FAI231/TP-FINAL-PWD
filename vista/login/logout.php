<?php
include_once("../../configuracion.php");

$objSession = new Session();
$objSession->cerrar();
header('location:..\home\index.php');

?>