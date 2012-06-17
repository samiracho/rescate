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
			
			$idUsuario      = Usuario::IdUsuario();
			$filtros        = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$idObra         = isset($_REQUEST["idObra"])?$_REQUEST["idObra"]:"";
			$limit          = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start          = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$obra           = new Obra();
			
			$obra->Listar($idUsuario,$idObra,$adminRegistros,$editarRegistrosAjenos,$filtros,$start,$limit,$sort)->ImprimirJson();
		break;
		
		case 'add':
			$obra   = new Obra();

			// guardamos el obra e imprimimos la respuesta json del servidor
			$obra->Guardar($adminRegistros, $editarRegistrosAjenos)->ImprimirJson();
		break;
		
		case 'destroy':
			$idObra  = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			Obra::Eliminar($idObra, $adminRegistros, $editarRegistrosAjenos)->ImprimirJson();
		break;
		
		case 'readProObra':
			
			$idUsuario = Usuario::IdUsuario();
			
			$idObra    = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$filtros   = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit     = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start     = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort      = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$obra      = new Obra();
			
			$obra->ListarProfesionalObra($idObra,$filtros,$start,$limit,$sort)->ImprimirJson();
			 
		break;
		
		case 'addProObra':
			$obra   = new Obra();
			$obra->Leer();
			// guardamos la obra e imprimimos la respuesta json del servidor
			$obra->GuardarRelacion('profesional')->ImprimirJson();
		break;
		
		case 'destroyProObra':
			$idObra = isset($_REQUEST["profesionalobra_obra_id"])?$_REQUEST["profesionalobra_obra_id"]:"";
			$idProfesional  = isset($_REQUEST["profesionalobra_profesional_id"])?$_REQUEST["profesionalobra_profesional_id"]:"";
			$obra = new Obra();
			$obra->EliminarRelacion('profesional',$idObra, $idProfesional)->ImprimirJson();
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