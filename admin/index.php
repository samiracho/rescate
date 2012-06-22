<?php
session_start();
require 'inc.php';
require 'idiomas/ES_es/ES_es.php';

// si tenemos activada la opción FORZAR_SSL redirigiremos la pag a la dirección https
if(isset($_SERVER['HTTPS'])){
	if ($_SERVER['HTTPS'] != "on" && FORZAR_SSL) { 
		$url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; 
		header("Location: $url"); 
		exit();
	}
}

// si le envio la orden por get de cerrar la sesion, la destruimos y mostramos el formulario de login
if( isset($_GET["logout"]) ){session_start();session_destroy();header( 'Location: index.php' );}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link href="imagenes/favicon.ico" rel="icon" type="image/x-icon" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<meta http-equiv="content-type" content="text/css; charset=utf-8"/>
		<meta http-equiv="Content-type" content="text/javascript; charset=utf-8" />
		<title>Panel de control de RESCATE</title>
		<link rel="stylesheet" type="text/css" href="<?php echo EXT_PATH; ?>/resources/css/ext-all.css" />
		<link rel="stylesheet" type="text/css" href="comun.css" />
		<script type="text/javascript" src="<?php echo EXT_PATH; ?>/ext-all-debug.js"></script>
		<?php echo $LOCALE_scripts ?>
<?php 
	//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO 
	if (!isset($_SESSION["usuario"]))
	{	
		echo '<script type="text/javascript" src="js/login.js"></script>';	
	}
    else
	{	
				
	echo '<script type="text/javascript" src="https://maps-api-ssl.google.com/maps/api/js?sensor=false"></script>
			  <script type="text/javascript" src="js/1config.js.php"></script>
			  <script type="text/javascript" src="js/2aplicacion.js"></script>
			  <script type="text/javascript" src="js/3modelos.js"></script>
			  <script type="text/javascript" src="js/4stores.js"></script>
			  <script type="text/javascript" src="js/5profesional.js"></script>
			  <script type="text/javascript" src="js/overrides.js"></script>
			  <script type="text/javascript" src="js/dashboard.js"></script>
			  <script type="text/javascript" src="js/BarraHerramientas.js"></script>
			  <script type="text/javascript" src="js/MultiSort.js"></script>
			  <script type="text/javascript" src="js/ubicacion.js"></script>
			  <script type="text/javascript" src="js/usuario.js"></script>
			  <script type="text/javascript" src="js/reconocimiento.js"></script>
			  <script type="text/javascript" src="js/asociacion.js"></script>
			  <script type="text/javascript" src="js/tecnica.js"></script>
			  <script type="text/javascript" src="js/equipamiento.js"></script>
			  <script type="text/javascript" src="js/especialidad.js"></script>
			  <script type="text/javascript" src="js/bibliografia.js"></script>
			  <script type="text/javascript" src="js/centro.js"></script>
			  <script type="text/javascript" src="js/formacion.js"></script>
			  <script type="text/javascript" src="js/cargo.js"></script>
			  <script type="text/javascript" src="js/noticia.js"></script>
			  <script type="text/javascript" src="js/colaborador.js"></script>
			  <script type="text/javascript" src="js/obra.js"></script>
			  <script type="text/javascript" src="js/intervencion.js"></script>
			  <script type="text/javascript" src="js/documento.js"></script>
			  <script type="text/javascript" src="js/metodo.js"></script>
			  <script type="text/javascript" src="js/tipo.js"></script>
			  <script type="text/javascript" src="js/material.js"></script>
			  <script type="text/javascript" src="js/procedimiento.js"></script>
			  <script type="text/javascript" src="js/ClearButton.js"></script>
			  <script type="text/javascript" src="js/webcam.js"></script>
			  <script type="text/javascript" src="js/GMapPanel3.js"></script>
			  <script type="text/javascript" src="js/PagingToolbarResizer.js"></script>
			  <script type="text/javascript" src="js/HtmlEditorImage.js"></script>
			  <script type="text/javascript" src="js/stateProvider.js"></script>';
	}
?>	
	</head>
	<body>
		<div id="loading-mask" style=""></div>
			<div id="loading">
			<div class="loading-indicator">
				<img alt="Cargando" src="imagenes/anim_extanim32.gif" width="32" height="32" style="margin-right:8px;float:left;vertical-align:top;"/>Panel de Control de R.E.S.C.A.T.E
				<br /><span id="loading-msg">Cargando...</span>
			</div>
		</div>
	</body>
</html>