<?php
include_once "../../configuracion.php";

$datos = data_submitted_request();
$objControl = new ABMUsuario();

$arrLista = $objControl->buscar($datos);

$arrSalida =  array();
foreach ($arrLista as $elem) {
    $nuevoElem['idusuario'] = $elem->getIdusuario();
    $nuevoElem["nombre"] = $elem->getNombre();
    $nuevoElem["pass"] = $elem->getPass();
    $nuevoElem["mail"] = $elem->getMail();
    $nuevoElem["deshabilitado"] = $elem->getDeshabilitado();

    array_push($arrSalida, $nuevoElem);
}

echo json_encode($arrSalida, 0, 2);

?>