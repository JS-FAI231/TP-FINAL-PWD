<?php
include_once("../../configuracion.php");
include_once("../estructura/encabezado_publico.php");


$datos = data_submitted_request();
$mensajeOperacion = "";
if (isset($datos['mensajeOperacion'])) {
	$mensajeOperacion = $datos['mensajeOperacion'];
}

?>


<html>

<head>
	<title>Registro de un nuevo usuario</title>
	<link rel="stylesheet" type="text/css" href="../bootstrap-5.2.1-dist/css/bootstrap.min.css" title="style" />
	<script src="../bootstrap-5.2.1-dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	<div id="header">
	</div>
	<div id="body" class="container-fluid p-5">
		<div class="row">
			<div class="col-sm-2">
			</div>
			<div class="col-sm-8">
				<div class="card border-secondary mb-4">
					<div class="card-header">REGISTRO NUEVO USUARIO</div>
					<div class="card-body text-secondary">
						<h1 class="card-title"><?php
					echo $mensajeOperacion;
					?></h1>
						<p class="card-text">Complete el formulario con sus datos.</p>

						<form class="was-validated" method="post" action="accion.php" onSubmit="return validar();">

							<div class="col-md-12">
								Nombre Usuario: <input id="nombre" name="nombre" type="text" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();" required>
								<div class="valid-feedback">
									¡Se ve bien!
								</div>
								<br>
								Contraseña: <input id="pass" name="pass" type="password" class="form-control" value="" required>
								<div class="valid-feedback">
									¡Se ve bien!
								</div>
								Repita su Contraseña: <input id="pass2" name="pass2" type="password" class="form-control" value="" required>
								<div class="valid-feedback">
									¡Se ve bien!
								</div>
								<br>
								e-Mail:
								<div class="input-group">
									<span class="input-group-text" id="inputGroupPrepend2">@</span>
									<input id="mail" name="mail" type="text" class="form-control" value="" required>
									<div class="valid-feedback">
										¡Se ve bien!
									</div>
								</div>
								<br>
								
							</div>

							<div class="col-12">
								<!-- <input id="idusuario" name="idusuario"  value="-1" type="hidden"> -->
								<input id="accion" name="accion" value="nuevo" type="hidden">
								
								<input type="submit" class="btn btn-outline-primary" value="Crear">
								<a role="button" class="btn btn-outline-primary" href="..\home\index.php">Cerrar</a>

							</div>
						</form>
						
					</div>

				</div>
				<div class="card-footer bg-transparent border-success">
					<?php
					echo $mensajeOperacion;
					?>
				</div>
			</div>
			<div class="col-sm-2">

			</div>
		</div>
	</div>
	<div id="footer">
	</div>

</body>

<script type="text/javascript">
	function validar(){
		resp=true;
		var pass = document.getElementById('pass').value;
		var pass2 = document.getElementById('pass2').value;
		if (pass!=pass2){
			alert("ERROR: Las constraseñas no coinciden");
			resp=false;
		}else{
			//alert("las claves son iguales");
		}
		return resp;
	}
</script>

<style type="text/css">
	body {
		background-color: lightgray;
        background-image: url("./../img/vinyl.png");
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
	}
</style>

</html>