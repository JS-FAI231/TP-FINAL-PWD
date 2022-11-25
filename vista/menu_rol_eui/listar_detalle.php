<?php 
include_once "../../configuracion.php";

$datos = data_submitted();
$objABMMenu = new ABMMenu();
$obj =NULL;
$arrSalida =  array();

if (isset($datos['idmenu']) && $datos['idmenu'] <> -1) {
    $listaObjMenuRol = $objABMMenu->listar_roles($datos);
    if (count($listaObjMenuRol) > 0) {
        foreach ($listaObjMenuRol as $objMenuRol) {
            $nuevoElem['idrol'] = $objMenuRol->getObjrol()->getIdrol();
            $nuevoElem["nombre"] = $objMenuRol->getObjrol()->getDescripcion();
            array_push($arrSalida,$nuevoElem);
        }
    }
}

echo json_encode($arrSalida,0,2);

?>