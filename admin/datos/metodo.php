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
			
			$idObra        = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel         = empty($_REQUEST['norel']) ? false : true;
			$filtros       = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$refrescar     = isset($_REQUEST["refrescar"])?true:false;
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$metodo        = new Metodo();
			
			$metodo->Listar($idObra,$norel,$filtros,$start,$limit,$sort,$refrescar)->ImprimirJson();

			
		break;
		
		case 'readCats':
		print_r(Metodo::ListaIdentada()->datos);
		
		break;
		
		case 'add':
		
			if(!Usuario::TienePermiso('administrar_metodos'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idMetodo = empty($_REQUEST['metodo_id']) ? '' : $_REQUEST['metodo_id'];
				$metodoNombre = empty($_REQUEST['metodo_nombre']) ? '' : $_REQUEST['metodo_nombre'];
				$idMetodoPadre = empty($_REQUEST['metodo_padre_id']) ? '' : $_REQUEST['metodo_padre_id'];
				$metodoDetalles = empty($_REQUEST['metodo_detalles']) ? '' : $_REQUEST['metodo_detalles'];
			
				$metodo = new Metodo();
				$metodo->Guardar($idMetodo,$idMetodoPadre, $metodoNombre, $metodoDetalles )->ImprimirJson();
			}
			
		break;
		
		case 'addMetodoObra':
			$metodo = new Metodo();
			$metodo->Leer();
			
			$id  = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			$metodo->GuardarRelacion('obra',"",$id)->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_metodos'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idMetodo = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$metodo = new Metodo();
				$metodo->EliminarDato($idMetodo)->ImprimirJson();
			}
			
		break;
		
		case 'destroyMetodoObra':
		
			$idMetodo = isset($_REQUEST["metodo_id"])?$_REQUEST["metodo_id"]:"";
			$idObra  = isset($_REQUEST["metodoobra_obra_id"])?$_REQUEST["metodoobra_obra_id"]:"";
			$metodo = new Metodo();
			$metodo->EliminarRelacion('obra',$idMetodo, $idObra)->ImprimirJson();
			
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