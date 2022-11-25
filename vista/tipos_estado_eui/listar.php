<?php 
include_once "../../configuracion.php";

$datos = data_submitted();
$objControl = new ABMCompraestadotipo();

$arrLista = $objControl->buscar($datos);

$arrSalida =  array();
foreach ($arrLista as $elem ){
    $nuevoElem['idcompraestadotipo'] = $elem->getidcompraestadotipo();
    $nuevoElem["descripcion"]=$elem->getDescripcion();
    $nuevoElem["detalle"]=$elem->getDetalle();
    array_push($arrSalida,$nuevoElem);
}

echo json_encode($arrSalida,0,2);
?>