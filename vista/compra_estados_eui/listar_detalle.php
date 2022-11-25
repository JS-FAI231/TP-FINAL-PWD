<?php 
include_once "../../configuracion.php";

$datos = data_submitted_request();
$objABMCompra = new ABMCompra();
$obj =NULL;
$arrSalida =  array();

if (isset($datos['idcompra']) && $datos['idcompra'] <> -1) {
    $listaObjCompraestado= $objABMCompra->listar_compra_estado($datos);
    if (count($listaObjCompraestado) > 0) {
        foreach ($listaObjCompraestado as $objCompraestado) {
            $nuevoElem['idcompraestado'] = $objCompraestado->getIdcompraestado();
            $nuevoElem["idcompra"] = $objCompraestado->getIdcompra();
            $nuevoElem['idcompraestadotipo'] = $objCompraestado->getObjCompraestadotipo()->getIdcompraestadotipo();
            $nuevoElem['descripcion'] = $objCompraestado->getObjCompraestadotipo()->getDescripcion();            
            $nuevoElem["fechaini"] = $objCompraestado->getFechaini();
            $nuevoElem['fechafin'] = $objCompraestado->getFechafin();
            
            array_push($arrSalida,$nuevoElem);
        }
    }
}


echo json_encode($arrSalida,0,2);

?>