<?php 
include_once "../../configuracion.php";

$datos = data_submitted_request();
$objControl = new ABMCompraitem();

$arrLista = $objControl->buscar($datos);

$arrSalida =  array();
foreach ($arrLista as $elem) {
    $nuevoElem['foto']=$elem->getObjproducto()->getFoto();
    $nuevoElem['artista']=$elem->getObjproducto()->getArtista();
    $nuevoElem['album']=$elem->getObjproducto()->getAlbum();
    $nuevoElem['idcompraitem'] = $elem->getIdcompraitem();
    $nuevoElem["idproducto"] = $elem->getObjproducto()->getIdproducto();
    $nuevoElem["idcompra"] = $elem->getObjcompra()->getIdcompra();
    $nuevoElem["cantidad"] = $elem->getCantidad();
    
    
    array_push($arrSalida, $nuevoElem);
}

echo json_encode($arrSalida, 0, 2);

?>