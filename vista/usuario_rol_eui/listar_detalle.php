<?php 
include_once "../../configuracion.php";

$datos = data_submitted_request();
$objABMUsuario = new ABMUsuario();
$obj =NULL;
$arrSalida =  array();

if (isset($datos['idusuario']) && $datos['idusuario'] <> -1) {
    $listaObjUsuarioRol = $objABMUsuario->listar_roles($datos);
    if (count($listaObjUsuarioRol) > 0) {
        foreach ($listaObjUsuarioRol as $objUsuarioRol) {
            $nuevoElem['idrol'] = $objUsuarioRol->getObjrol()->getIdrol();
            $nuevoElem["nombre"] = $objUsuarioRol->getObjrol()->getDescripcion();
            array_push($arrSalida,$nuevoElem);
        }
    }
}

echo json_encode($arrSalida,0,2);

?>