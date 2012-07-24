<?php
require '../inc.php'; 

// comprobamos permisos
if( !Usuario::TienePermiso('agregareditar_registros_propios') )
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
			$filtros       = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$equipamiento       = new Equipamiento();
			
			$equipamiento->Listar($idProfesional,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			if(!Usuario::TienePermiso('administrar_equipamientos'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$equipamiento = new Equipamiento();
				$equipamiento->Guardar()->ImprimirJson();
			}
			
		break;
		
		case 'addEquipamientoPro':
		
			$equipamiento = new Equipamiento();
			$equipamiento->Leer();
			$equipamiento->GuardarRelacion('profesional')->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_equipamientos'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idEquipamiento = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$equipamiento = new Equipamiento();
				$equipamiento->EliminarDato($idEquipamiento)->ImprimirJson();
			}
			
		break;
		
		case 'destroyEquipamientoPro':
		
			$idEquipamiento = isset($_REQUEST["equipamiento_id"])?$_REQUEST["equipamiento_id"]:"";
			$idProfesional  = isset($_REQUEST["equipamientoprofesional_profesional_id"])?$_REQUEST["equipamientoprofesional_profesional_id"]:"";
			$equipamiento = new Equipamiento();
			$equipamiento->EliminarRelacion('profesional',$idEquipamiento, $idProfesional)->ImprimirJson();
			
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