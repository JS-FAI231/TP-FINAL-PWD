<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
$respuesta = false;
$mensaje="";
$objTrans = new ABMUsuario();
// if (isset($datos['accion'])) {
//     if (!isset($datos['idusuario'])){
//         $datos['idusuario']='0';
//     }
//     if ($objTrans->existeMail($datos)) {
//         $mensaje = "ERROR! La direccion de mail ya existe en otra cuenta!.";

//     } else {
//         $respuesta = $objTrans->abm($datos);
//         if (!$respuesta) {
//             $mensaje = "La accion " . $datos['accion'] . " no pudo concretarse.";
//         } else {
//             $mensaje = "La accion " . $datos['accion'] . " se concreto con exito.";
//         }
//     }
// }
if (isset($datos['accion'])) {
    if (!isset($datos['idusuario'])){
        $datos['idusuario']='0';
    }
    if ($objTrans->existeMail($datos)) {
        $mensaje = "ERROR! La direccion de mail ya existe en otra cuenta!.";
       
    } else {
        if ($objTrans->existeUsuario($datos)) {
            $mensaje = "ERROR! El nombre de usuario ya existe en otra cuenta!.";
       
        } else {
            if (isset($datos['pass'])) {
                $datos['pass'] = md5($datos['pass']);
            }
            $respuesta = $objTrans->abm($datos);
            if (!$respuesta) {
                $mensaje = "La accion " . $datos['accion'] . " no pudo concretarse.";
        
            } else {

                $mensaje = "La accion " . $datos['accion'] . " se pudo concretarse exitosamente.";
          
            }
        }
    }
}

$retorno['respuesta'] = $respuesta;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}

echo json_encode($retorno);
?>
