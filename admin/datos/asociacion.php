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
			$asociacion    = new Asociacion();
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			
			$asociacion->Listar($idProfesional,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			if(!Usuario::TienePermiso('administrar_asociaciones'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$asociacion = new Asociacion();
				$asociacion->Guardar()->ImprimirJson();
			}
			
		break;
		
		case 'addAsociacionPro':
		
			$asociacion = new Asociacion();
			$asociacion->Leer();
			$asociacion->GuardarRelacion('profesional')->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_asociaciones'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idAsociacion = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$asociacion = new Asociacion();
				$asociacion->EliminarDato($idAsociacion)->ImprimirJson();
			}
			
		break;
		
		case 'destroyAsociacionPro':
		
			$idAsociacion = isset($_REQUEST["asociacion_id"])?$_REQUEST["asociacion_id"]:"";
			$idProfesional  = isset($_REQUEST["asociacionprofesional_profesional_id"])?$_REQUEST["asociacionprofesional_profesional_id"]:"";
			$asociacion = new Asociacion();
			$asociacion->EliminarRelacion('profesional',$idAsociacion, $idProfesional)->ImprimirJson();
			
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