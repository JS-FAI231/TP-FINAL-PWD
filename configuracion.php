<?php header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate ");

$lcSysOp = "windows";

if ($lcSysOp == "windows") {
    /////////////////////////////
    // CONFIGURACION APP FOR WINDOWS//
    /////////////////////////////

    $PROYECTO = ''; //Pone la ubicación de todo el proyecto desde htdocs del XAMP

    //variable que almacena el directorio del proyecto
    $ROOT = $_SERVER['DOCUMENT_ROOT'] . "/$PROYECTO/"; //Agarra la ubicación del servidor donde tiene guardada la carpeta

    include_once($ROOT . 'utiles/funciones.php'); //Trae las funciones del script funciones.php

    // Variable que define la pagina de autenticacion del proyecto
    $INICIO = "Location:http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/login/login.php";

    // variable que define la pagina principal del proyecto (menu principal)
    $PRINCIPAL = "Location:http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/principal.php";

    $_SESSION['ROOT'] = $ROOT;

} elseif ($lcSysOp == "linux") {
    /////////////////////////////
    // CONFIGURACION APP FOR LINUX//
    /////////////////////////////

    $PROYECTO = 'TP_FINAL_SAFE'; //Pone la ubicación de todo el proyecto desde htdocs del XAMP

    //variable que almacena el directorio del proyecto
    //$ROOT =$_SERVER['DOCUMENT_ROOT']."/$PROYECTO/"; //Agarra la ubicación del servidor donde tiene guardada la carpeta
    $ROOT = "/export/home/jorge.segura/public_html_lamptec/$PROYECTO/"; //Agarra la ubicación del servidor donde tiene guardada la carpeta

    include_once($ROOT . 'utiles/funciones.php'); //Trae las funciones del script funciones.php

    // Variable que define la pagina de autenticacion del proyecto
    $INICIO = "Location:http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/login/login.php";

    // variable que define la pagina principal del proyecto (menu principal)
    $PRINCIPAL = "Location:http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/principal.php";

    $_SESSION['ROOT'] = $ROOT;
} else {
}
