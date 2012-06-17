<?php

require '../inc.php'; 

// comprobamos permisos
if (!Usuario::EstaIdentificado())
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
			
			$idProfesional = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel         = empty($_REQUEST['norel']) ? false : true;
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			
		break;
		
		case 'readPais':
			
			$objetoBD = new ObjetoBD();
			$objetoBD->Listar("SELECT pais_id AS id, pais_nombre AS nombre FROM pais",true)->ImprimirJson();	
		
		break;
		
		case 'readProvincia':
			$objetoBD = new ObjetoBD();
			$objetoBD->Listar("SELECT provincia_id AS id, provincia_nombre AS nombre FROM provincia",true)->ImprimirJson();	
		
		break;
		
		case 'readPoblacion':
			$provincia = empty($_REQUEST['provincia']) ? '' : $_REQUEST['provincia'];
			if($provincia!='')
			{
				$objetoBD = new ObjetoBD();
				$objetoBD->Listar("SELECT poblacion_nombre AS nombre, poblacion_id AS id FROM poblacion LEFT JOIN provincia on poblacion_provincia_id = provincia_id WHERE provincia_nombre = '".$provincia."'",true)->ImprimirJson();
			}
			else
			{
				$res = new Comunicacion();
				$res->exito   = true;
				$res->ImprimirJson();
			}
		
		break;
		
		case 'readCreador':
			
			$objetoBD = new ObjetoBD();		
			$objetoBD->Listar("SELECT usuario_id AS id, CONCAT_WS(' ,', usuario_nombre, usuario_apellido1, usuario_apellido2, usuario_login ) AS nombre FROM usuario")->ImprimirJson();	
			
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