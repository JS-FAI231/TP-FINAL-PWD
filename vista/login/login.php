<?php
include_once("../../configuracion.php");

$datos = data_submitted_request();
$mensajeOperacion = "";
if (isset($datos['mensajeOperacion'])) {
    $mensajeOperacion = $datos['mensajeOperacion'];
}

?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="../bootstrap-5.2.1-dist/css/bootstrap.min.css" title="style" />
    <script src="../bootstrap-5.2.1-dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container-fluid p-5 my-5">
        <div class="row">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">

                <div class="card border-secondary mb-4">
                    <div class="card-header">Ingresar credenciales</div>
                    <div class="card-body text-secondary">
                        <h3 class="card-title"> <?php
                                                echo $mensajeOperacion;
                                                ?></h3>
                        <h1 class="card-title">Login</h1>
                        <p class="card-text">Debe completar con sus datos de usuario y contraseña</p>

                        <form class="row g-3 was-validated" method="post" action="verificarLogin.php">
                            <div class="col-md-12">
                                Usuario: <input id="nombre" name="nombre" type="text" class="form-control" value="" required>
                                <div class="valid-feedback">
                                    ¡Se ve bien!
                                </div>
                                <br>
                                Contraseña: <input id="pass" name="pass" type="password" class="form-control" value="" required>
                                <div class="valid-feedback">
                                    ¡Se ve bien!
                                </div>
                                <br>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-outline-primary" type="submit">Aceptar</button>
                                <a role="button" class="btn btn-outline-primary" href="..\home\index.php">Cancelar</a>
                            </div>
                        </form>
                        <a href="">Recuperar Contraseña</a> <br>
                        <a href="../usuario_registro/index.php">Nuevo Usuario</a>

                    </div>

                </div>
                <div class="card-footer bg-transparent border-success">
                    <?php
                    echo $mensajeOperacion;
                    ?>
                </div>
            </div>
            <div class="col-sm-3">

            </div>
        </div>
    </div>
</body>
<style>
    body {
        background-color: lightgray;
        background-image: url("./../img/vinyl.png");
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;

    }
</style>

</html>