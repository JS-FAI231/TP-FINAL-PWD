<?php
include_once("../../utiles/funciones.php");
$datos = data_submitted_request();
$form_registro = isset($datos['registro']) ? $datos['registro'] : '';
//VERIFICO LOS DATOS DE LA SESSION
$get_url = "";
$objSession = new Session();
if ($objSession->activa() and $datos['idus'] == $objSession->getIdusuario()) {
    $idusuario = $objSession->getIdusuario();
    $get_url = '?idus=' . $idusuario;

    //Verifico la url segun los roles asignados al menu y a este usuario en esta sesion
    //Metodo de verificacion: Comparo el directorio que figura en la url con los directorios listados en el
    //menu de este usuario en esta session.

    $arr = explode('/', $_SERVER['REQUEST_URI']);  //tomo la el string de la url
    $buscar_url = strtolower($arr[count($arr) - 2]); //extraigo el nombre directorio que figura en la url
    $exito = false;
    if ($buscar_url=='home'){ //si el string del url posee directorio publico
        $exito=true;
    }else{
        $abmMenu = new ABMMenu();
        $arreglo = $abmMenu->arr_menu_usuario($idusuario); //array con info del menu segun el rol del usuario de esta sesion
        foreach ($arreglo as $row) {
            if (str_contains($row['url'], $buscar_url)) {  //si el string del url posee directorios privados de este usario en esta sesion
                $exito = true; 
            }
        }
    }
    if (!$exito) {
        header('Location: ./../home/index.php?idus=-1');
    }
} else {
    header('Location: ./../home/index.php?idus=-1');
}
//FIN VERIFICO LOS DATOS DE LA SESSION
?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="../bootstrap-5.2.1-dist/css/bootstrap.min.css" title="style" />
    <script src="../bootstrap-5.2.1-dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <a id="top"></a>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="..\home\index.php<?php echo $get_url; ?>"><img src=".\..\img\vinyl.png" style="width:auto;height:50px"> JS RECORDS</a>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <?php


                    ///DEFINICION MENU RECURSIVO DINAMICO
                    $abmMenu = new ABMMenu();
                    $arreglo = $abmMenu->arr_menu_usuario($idusuario);
                    $menu = menuRecursivo('0', $arreglo, $get_url);
                    function menuRecursivo($idpadre, $arreglo, $get_url)
                    {
                        foreach ($arreglo as $row) {
                            if ($row['padre'] == $idpadre) {
                                if ($idpadre == '0') {?>
                                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?php echo $row['nombre'] ?></a>
                                        <ul class="dropdown-menu"><?php menuRecursivo($row['idmenu'], $arreglo, $get_url) ?>
                                        </ul>
                                    </li><?php
                                } else { ?>
                                    <li><a class="dropdown-item" href="<?php echo $row['url'] . $get_url ?>"><?php echo $row['nombre'] ?></a>
                                        <?php menuRecursivo($row['idmenu'], $arreglo, $get_url); ?>
                                    </li><?php
                                }
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
            <div>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <?php
                            //OBTENGO LOS ROLES DE ESTE USUARIO

                            $arrRoles = $abmMenu->roles($idusuario);
                            $nombreRol = "Rol: ";
                            foreach ($arrRoles as $rol) {
                                $nombreRol .= $rol . "  ";
                            }
                            ?>
                            <a class="nav-link dropdown"><?php echo $nombreRol; ?></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?php echo 'Conectado como: ' . $objSession->getNombre() ?></a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="..\usuario_info\index.php<?php echo '?idus=' . $idusuario . '&idusuario=' . $idusuario; ?>">Datos de la Cuenta</a></li>

                                <li><a class="dropdown-item" href="..\login\logout.php">Cerrar Sesion</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            . .
            <a href=".\..\carro_eui\index.php?idus=<?php echo $objSession->getIdusuario() ?>"><img src=".\..\img\carrito_blanco.png" style="width:auto;height:50px"></a>
            . .
        </div>
    </nav>
</body>

</html>
<style type="text/css">

</style>
<?php
//include_once("lateral.php");
?>