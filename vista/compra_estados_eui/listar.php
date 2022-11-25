<?php
include_once "../../configuracion.php";

$datos = data_submitted_request();
$objControl = new ABMCompra();

$arrLista = $objControl->buscar($datos);

$arrSalida =  array();
foreach ($arrLista as $elem) {
    $nuevoElem['idcompra'] = $elem->getIdcompra();
    $nuevoElem["fecha"] = $elem->getFecha();
    $nuevoElem["idusuario"] = $elem->getObjusuario()->getIdusuario();
    $nuevoElem["us_nombre"] = $elem->getObjusuario()->getNombre();
    
    $nuevoElem['estado']=$objControl->estado($nuevoElem);
    array_push($arrSalida, $nuevoElem);
}

echo json_encode($arrSalida, 0, 2);

?>