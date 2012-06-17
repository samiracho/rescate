<?php

require '../inc.php'; 

// comprobamos permisos
if(!Usuario::TienePermiso('administrar_noticias'))
{
	$res = new Comunicacion();
	$res->exito   = false;
	$res->mensaje = t('You dont have the required permissions');
	$res->errores = t('Permission error');
	$res->ImprimirJson();
}
else if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'read':
			
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit          = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start          = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$noticia        = new Noticia();

			$noticia->Listar($filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			$noticia  = new Noticia();
			print_r($noticia->Guardar());
			
		break;
		
		case 'destroy':
			$idNoticia = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			$noticia = new Noticia();
			$noticia->EliminarDato($idNoticia)->ImprimirJson();		
		break;
		
		default:
			$res = new Comunicacion();
			$res->exito   = false;
			$res->mensaje = t('Invalid action');
			$res->errores = t('Invalid action');
			$res->ImprimirJson();
		break;
	}
}
else
{
	$res = new Comunicacion();
	$res->exito   = false;
	$res->mensaje = t('Invalid action');
	$res->errores = t('Invalid action');
	$res->ImprimirJson();
}

?>