<?php 
include_once "../../configuracion.php";

$datos = data_submitted_request();
$objControl = new ABMMenu();


$arrLista = $objControl->buscar($datos);

$arrSalida =  array();
foreach ($arrLista as $elem ){
    $nuevoElem['idmenu'] = $elem->getIdmenu();
    $nuevoElem['nombre']=$elem->getNombre();
    $nuevoElem['descripcion']=$elem->getDescripcion();
    if($elem->getObjMenu()!=null){
        $nuevoElem['idpadre']=$elem->getObjMenu()->getIdmenu();
        $nuevoElem['nombrepadre']=$elem->getObjMenu()->getNombre();
    }else{
        $nuevoElem['idpadre']="0";
        $nuevoElem['nombrepadre']="";
    }
    $nuevoElem['deshabilitado']=$elem->getDeshabilitado();
    
    array_push($arrSalida,$nuevoElem);
}
//print_r($arreglo_salida);
echo json_encode($arrSalida,0,2);

?>