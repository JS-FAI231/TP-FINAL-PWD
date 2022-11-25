<?php
include_once "../../configuracion.php";

$datos = data_submitted_request();
$objControl = new ABMProducto();

$arrLista = $objControl->buscar($datos);   //buscar(datos) devuelve un array con 2 elementos, el total de filas del where y los resultados where limit
$totalFilas = $arrLista[0];         //recupero el total de filas de la tabla que viene en la posicion 0;
$arrObjsProducto = $arrLista[1];    //recupero el array con los objsProducto que viene en la posicion 1;
$arrSalida =  array();

foreach ($arrObjsProducto as $elem) {
    $nuevoElem['idproducto'] = $elem->getIdproducto();
    $nuevoElem["nombre"] = $elem->getNombre();
    $nuevoElem["detalle"] = $elem->getDetalle();
    $nuevoElem["cantstock"] = $elem->getCantstock();
    $nuevoElem["artista"] = $elem->getArtista();
    $nuevoElem["album"] = $elem->getAlbum();
    $nuevoElem['foto'] = $elem->getFoto();
    array_push($arrSalida, $nuevoElem);
}

$result["rows"] = $arrSalida;
$result["total"] = $totalFilas;
echo json_encode($result);

?>