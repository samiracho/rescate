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

if(USAR_COMPRESION)$comp = ".php";
else 	$comp = "";

?>
<html>
	<head>
		<link href="imagenes/favicon.ico" rel="icon" type="image/x-icon" />
		<meta http-equiv="x-ua-compatible" content="chrome=1" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<meta http-equiv="content-type" content="text/css; charset=utf-8"/>
		<meta http-equiv="Content-type" content="text/javascript; charset=utf-8" />
		<title>Panel de control de RESCATE</title>
		<link rel="stylesheet" type="text/css" href="<?php echo EXT_PATH; ?>/resources/css/ext-all.css<?php echo $comp; ?>" />
		<link rel="stylesheet" type="text/css" href="todo.css" />
		<script type="text/javascript" src="<?php echo EXT_PATH; ?>/ext-all.js<?php echo $comp; ?>"></script>
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
		  <script type="text/javascript" src="js/todo.js'.$comp.'"></script>';
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