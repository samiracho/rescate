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
			
			$idDocumento   = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel         = empty($_REQUEST['norel']) ? false : true;
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$tipo          = new Tipo();
			
			$tipo->Listar($idDocumento,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			if(!Usuario::TienePermiso('administrar_tipos'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$tipo = new Tipo();
				$tipo->Guardar()->ImprimirJson();
			}
			
		break;
		
		case 'addTipoDocumento':
		
			$tipo = new Tipo();
			$tipo->Leer();
			$tipo->GuardarRelacion('documento')->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_tipos'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idTipo = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$tipo = new Tipo();
				$tipo->EliminarDato($idTipo)->ImprimirJson();
			}
			
		break;
		
		case 'destroyTipoDocumento':
		
			$idTipo = isset($_REQUEST["tipo_id"])?$_REQUEST["tipo_id"]:"";
			$idDocumento  = isset($_REQUEST["tipodocumento_documento_id"])?$_REQUEST["tipodocumento_documento_id"]:"";
			$tipo = new Tipo();
			$tipo->EliminarRelacion('documento',$idTipo, $idDocumento)->ImprimirJson();
			
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