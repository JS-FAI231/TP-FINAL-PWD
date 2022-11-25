<?php

// function data_submitted() {
//     $_AAux= array();
//     if (!empty($_POST))
//         $_AAux =$_POST;
//     else
//         if(!empty($_GET)) {
//             $_AAux =$_GET;
//         }
//     if (count($_AAux)){
//         foreach ($_AAux as $indice => $valor) {
//             if ($valor=="")
//                 $_AAux[$indice] = 'null'	;
//         }
//     }
//     return $_AAux;

// }


function hoy(){
    return date("Y-m-d H:i:s");
}
function data_submitted()
{
    $_AAux = array();
    if (!empty($_REQUEST))
        $_AAux = $_REQUEST;
    if (count($_AAux)) {
        foreach ($_AAux as $indice => $valor) {
            if ($valor == "")
                $_AAux[$indice] = 'null';
        }
    }
    return $_AAux;
}

function data_submitted_request()
{
    $_AAux = array();
    if (!empty($_REQUEST))
        $_AAux = $_REQUEST;
    if (count($_AAux)) {
        foreach ($_AAux as $indice => $valor) {
            if ($valor == "")
                $_AAux[$indice] = 'null';
        }
    }
    return $_AAux;
}

function verEstructura($e)
{
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}

spl_autoload_register(function ($clase) {
    // echo "Cargamos la clase  ".$clase."<br>" ;
    $directorys = array(
        $GLOBALS['ROOT'] . 'modelo/',
        $GLOBALS['ROOT'] . 'control/',
        $GLOBALS['ROOT'] . 'modelo/conector/',
        $GLOBALS['ROOT'] . 'utiles/',
    );
    // print_r($directorys) ;
    foreach ($directorys as $directory) {
        if (file_exists($directory . $clase . '.php')) {
            // echo "se incluyo".$directory.$class_name . '.php';
            require_once($directory . $clase . '.php');
            return;
        }
    }
});

function encriptardatos($dato = "")
{
    $encrip = false;
    if ($dato <> "") {
        $encrip = MD5($dato);
    }
    return $encrip;
}
