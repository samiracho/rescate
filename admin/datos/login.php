<?php

require '../inc.php';

$login    = isset($_POST["loginUsername"]) ? $_POST["loginUsername"] : "";
$password = isset($_POST["loginPassword"]) ? $_POST["loginPassword"] : "";


if(Usuario::Login($login,$password))
{	
	// enviamos el resultado en JSON
	echo "{success: true}";
} 
else 
{
	// en caso contrario enviamos un error para informar
	echo "{success: false, errors: { reason: 'Identificación incorrecta. Inténtelo de nuevo.' }}";
}

?>