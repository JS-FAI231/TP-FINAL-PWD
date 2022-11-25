<?php 
include_once "../../configuracion.php";

$datos = data_submitted();
$objControl = new ABMRol();

$arrLista = $objControl->buscar($datos);

$arrSalida =  array();
foreach ($arrLista as $elem ){
    $nuevoElem['idrol'] = $elem->getIdrol();
    $nuevoElem["descripcion"]=$elem->getDescripcion();
    array_push($arrSalida,$nuevoElem);
}

echo json_encode($arrSalida,0,2);
?>