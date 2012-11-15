<?php

require '../inc.php'; 

// permisos
$adminRegistros        = Usuario::TienePermiso('administrar_registros');
$adminRegistrosPropios = Usuario::TienePermiso('agregareditar_registros_propios');
$editarRegistrosAjenos = Usuario::TienePermiso('editar_registros_ajenos');

// comprobamos permisos
if(!$adminRegistros && !$adminRegistrosPropios && !$editarRegistrosAjenos)
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
			
			$idUsuario = Usuario::IdUsuario();
			
			$tipo          = isset($_REQUEST['tipo'])? $_REQUEST['tipo']:"";
			$filtros       = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$profesional   = new Profesional();
			
			$profesional->Listar($idUsuario,$tipo, $adminRegistros, $editarRegistrosAjenos,$filtros,$start,$limit,$sort)->ImprimirJson();	
			
		break;
		
		case 'add':
			$profesional   = new Profesional();

			// guardamos el profesional e imprimimos la respuesta json del servidor
			$profesional->Guardar($adminRegistros, $editarRegistrosAjenos)->ImprimirJson();
		break;
		
		case 'destroy':
			$idProfesional  = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			Profesional::Eliminar($idProfesional, $adminRegistros, $editarRegistrosAjenos)->ImprimirJson();
		break;
		
		case 'upload':
			$profesional_id = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			$res = Profesional::SubirArchivo($profesional_id);
			$res->ImprimirJson();
			
		break;
		
		case 'deleteFile':
			$profesional_id = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			print_r(Profesional::EliminarArchivo($profesional_id));
			
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