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
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$refrescar     = isset($_REQUEST["refrescar"])?true:false;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$material       = new Material();
			
			$material->Listar($idIntervencion,$norel,$filtros,$start,$limit,"",$refrescar)->ImprimirJson();			

			
		break;
		
		case 'readCats':
		print_r(Material::ListaIdentada()->datos);
		
		break;
		
		case 'add':
			if(!Usuario::TienePermiso('administrar_materiales'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idMaterial = empty($_REQUEST['material_id']) ? '' : $_REQUEST['material_id'];
				$materialNombre = empty($_REQUEST['material_nombre']) ? '' : $_REQUEST['material_nombre'];
				$idMaterialPadre = empty($_REQUEST['material_padre_id']) ? '' : $_REQUEST['material_padre_id'];
				$materialDetalles = empty($_REQUEST['material_detalles']) ? '' : $_REQUEST['material_detalles'];
			
				$material = new Material();
				$material->Guardar($idMaterial,$idMaterialPadre, $materialNombre, $materialDetalles )->ImprimirJson();
			}
			
		break;
		
		case 'addMaterialPro':
		
			$material = new Material();
			$material->Leer();
			$material->GuardarRelacion('intervencion')->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_materiales'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idMaterial = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$material = new Material();
				$material->EliminarDato($idMaterial)->ImprimirJson();
			}
			
		break;
		
		case 'destroyMaterialPro':
		
			$idMaterial = isset($_REQUEST["material_id"])?$_REQUEST["material_id"]:"";
			$idIntervencion  = isset($_REQUEST["materialintervencion_intervencion_id"])?$_REQUEST["materialintervencion_intervencion_id"]:"";
			$material = new Material();
			$material->EliminarRelacion('intervencion',$idMaterial, $idIntervencion)->ImprimirJson();
			
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