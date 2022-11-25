<?php
include_once("../../utiles/funciones.php");
$datos_sesion = data_submitted_request();
$objSession = new Session();

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
            <a class="navbar-brand" href="..\home\index.php"><img src=".\..\img\vinyl.png" style="width:auto;height:50px"> JS RECORDS</a>
                <div>
                    <a role="button" class="btn btn-outline-info btn-sm" href="..\usuario_registro\index.php">Registrase</a>
                    . .
                    <a role="button" class="btn btn-outline-warning btn-sm" href="..\login\login.php">Log In</a>
                    . .
                </div>
        </div>
    </nav>
</body>

</html>
<style type="text/css">

</style>
<?php
//include_once("lateral.php");
?>