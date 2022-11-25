<?php
include_once("../../configuracion.php");
include_once("../estructura/encabezado.php");


$datos = data_submitted_request();
$objABM = new ABMUsuario();
$obj = NULL;

if (isset($datos['idusuario']) && $datos['idusuario'] <> -1) {
    $listaTabla = $objABM->buscar($datos);

    if (count($listaTabla) == 1) {
        $obj = $listaTabla[0];
    }
}

$mensajeOperacion = "";
if (isset($datos['mensajeOperacion'])) {
	$mensajeOperacion = $datos['mensajeOperacion'];
}

?>


<html>

<head>
	<title>Datos del usuario</title>
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
					<div class="card-header">DATOS USUARIO</div>
					<div class="card-body text-secondary">
						<h1 class="card-title"></h1>
						<p class="card-text">Informacion de su cuenta. <?php echo $mensajeOperacion;?></p>

						<form id="cambiar_datos" class="was-validated" method="post" action="accion.php">

							<div class="col-md-12">
								Nombre Usuario: <input id="nombre" name="nombre" type="text" class="form-control" 
								                value="<?php echo ($obj != null) ? $obj->getNombre() : "" ?>" onkeyup="this.value = this.value.toUpperCase();" required>
								<div class="valid-feedback">
									¡Se ve bien!
								</div>
								<br>
								e-Mail:
								<div class="input-group">
									<span class="input-group-text" id="inputGroupPrepend2">@</span>
									<input id="mail" name="mail" type="text" class="form-control" value="<?php echo ($obj != null) ? $obj->getMail() : "" ?>" required>
									<div class="valid-feedback">
										¡Se ve bien!
									</div>
								</div>
								<br>
								<input id="idusuario" name="idusuario" type="hidden" value="<?php echo ($obj != null) ? $obj->getIdusuario() : "-1" ?>" readonly required>
								<input id="accion" name="accion" value="editar" type="hidden">
								<input type="submit" class="btn btn-outline-primary" value="Grabar Cambios">
								<hr>
								
							</div>
						</form>

						<p class="card-text">Cambiar su contraseña.</p>
						<form id="cambiar_pass" class="was-validated" method="post" action="accion.php" onSubmit="return validar();">
							<div class="col-md-12">
								Contraseña: <input id="pass" name="pass" type="password" class="form-control" value="" required>
								<div class="valid-feedback">
									¡Se ve bien!
								</div>
								Repita su Contraseña: <input id="pass2" name="pass2" type="password" class="form-control" required>
								<div class="valid-feedback">
									¡Se ve bien!
								</div>
								<br>

								<div class="col-12">
									<input id="idusuario" name="idusuario" type="hidden" value="<?php echo ($obj != null) ? $obj->getIdusuario() : "-1" ?>" readonly required>
									<input id="accion" name="accion" value="editar" type="hidden">
								<input type="submit" class="btn btn-outline-primary" value="Grabar Cambios">
									

								</div>
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
	function validar() {
		resp = true;
		var pass = document.getElementById('pass').value;
		var pass2 = document.getElementById('pass2').value;
		if (pass != pass2) {
			alert("ERROR: Las constraseñas no coinciden");
			resp = false;
		} else {
			alert("las claves son iguales");
		}
		return resp;
	}
	function habilitar_edicion(){
		document.getElementById('nombre').setAttribute('readonly',false);
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