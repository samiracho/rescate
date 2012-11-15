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
			
			$idIntervencion = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel         = empty($_REQUEST['norel']) ? false : true;
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$refrescar     = isset($_REQUEST["refrescar"])?true:false;
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$procedimiento       = new Procedimiento();
			
			$procedimiento->Listar($idIntervencion,$norel,$filtros,$start,$limit,"",$refrescar)->ImprimirJson();
			
		break;
		
		case 'readCats':
		print_r(Procedimiento::ListaIdentada()->datos);
		
		break;
		
		case 'add':
			if(!Usuario::TienePermiso('administrar_procedimientos'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idProcedimiento = empty($_REQUEST['procedimiento_id']) ? '' : $_REQUEST['procedimiento_id'];
				$procedimientoNombre = empty($_REQUEST['procedimiento_nombre']) ? '' : $_REQUEST['procedimiento_nombre'];
				$idProcedimientoPadre = empty($_REQUEST['procedimiento_padre_id']) ? '' : $_REQUEST['procedimiento_padre_id'];
				$procedimientoDetalles = empty($_REQUEST['procedimiento_detalles']) ? '' : $_REQUEST['procedimiento_detalles'];
			
				$procedimiento = new Procedimiento();
				$procedimiento->Guardar($idProcedimiento,$idProcedimientoPadre, $procedimientoNombre, $procedimientoDetalles )->ImprimirJson();
			}
			
		break;
		
		case 'addProcedimientoPro':
		
			$procedimiento = new Procedimiento();
			$procedimiento->Leer();
			
			$id  = isset($_REQUEST["id"])?$_REQUEST["id"]:"";		
			
			$procedimiento->GuardarRelacion('intervencion',"",$id)->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_procedimientos'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idProcedimiento = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$procedimiento = new Procedimiento();
				$procedimiento->EliminarDato($idProcedimiento)->ImprimirJson();
			}
			
		break;
		
		case 'destroyProcedimientoPro':
		
			$idProcedimiento = isset($_REQUEST["procedimiento_id"])?$_REQUEST["procedimiento_id"]:"";
			$idIntervencion  = isset($_REQUEST["procedimientointervencion_intervencion_id"])?$_REQUEST["procedimientointervencion_intervencion_id"]:"";
			$procedimiento = new Procedimiento();
			$procedimiento->EliminarRelacion('intervencion',$idProcedimiento, $idIntervencion)->ImprimirJson();
			
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
	$res->mensaje = t('No action');
	$res->errores = t('No action');
	$res->ImprimirJson();
}

?>