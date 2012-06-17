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
			$tecnica       = new Tecnica();
			
			$tecnica->Listar($idProfesional,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			if(!Usuario::TienePermiso('administrar_tecnicas'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$tecnica = new Tecnica();
				$tecnica->Guardar()->ImprimirJson();
			}
			
		break;
		
		case 'addTecnicaPro':
		
			$tecnica = new Tecnica();
			$tecnica->Leer();
			$tecnica->GuardarRelacion('profesional')->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_tecnicas'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idTecnica = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$tecnica = new Tecnica();
				$tecnica->EliminarDato($idTecnica)->ImprimirJson();
			}
			
		break;
		
		case 'destroyTecnicaPro':
		
			$idTecnica = isset($_REQUEST["tecnica_id"])?$_REQUEST["tecnica_id"]:"";
			$idProfesional  = isset($_REQUEST["tecnicaprofesional_profesional_id"])?$_REQUEST["tecnicaprofesional_profesional_id"]:"";
			$tecnica = new Tecnica();
			$tecnica->EliminarRelacion('profesional',$idTecnica, $idProfesional)->ImprimirJson();
			
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