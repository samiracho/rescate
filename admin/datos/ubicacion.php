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
			
			$idObra  = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel   = empty($_REQUEST['norel']) ? false : true;
			$filtros = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit   = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start   = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$ubicacion = new Ubicacion();
			$sort    = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			
			$ubicacion->Listar($idObra,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			if(!Usuario::TienePermiso('administrar_ubicaciones'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$ubicacion = new Ubicacion();
				$ubicacion->Guardar()->ImprimirJson();
			}
			
		break;
		
		case 'addUbicacionObra':
		
			$ubicacion = new Ubicacion();
			$ubicacion->Leer();
			$ubicacion->GuardarRelacion('obra')->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_ubicaciones'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idUbicacion = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$ubicacion = new Ubicacion();
				$ubicacion->EliminarDato($idUbicacion)->ImprimirJson();
			}
			
		break;
		
		case 'destroyUbicacionObra':
		
			$idUbicacion = isset($_REQUEST["ubicacion_id"])?$_REQUEST["ubicacion_id"]:"";
			$idObra  = isset($_REQUEST["ubicacionobra_obra_id"])?$_REQUEST["ubicacionobra_obra_id"]:"";
			$ubicacion = new Ubicacion();
			$ubicacion->EliminarRelacion('obra',$idUbicacion, $idObra)->ImprimirJson();
			
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