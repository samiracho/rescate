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
			
			$tipo           = isset($_REQUEST['tipo'])? $_REQUEST['tipo']:"";
			$filtros        = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit          = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start          = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort           = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$intervencion   = new Intervencion();
			
			$intervencion->Listar($idUsuario,$adminRegistros,$editarRegistrosAjenos, $filtros,$start,$limit,$sort)->ImprimirJson();
			 
		break;
		
		case 'readIntPro':
			
			$idUsuario = Usuario::IdUsuario();
			
			$idIntervencion  = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel        = empty($_REQUEST['norel']) ? false : true;
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit        = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start        = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort         = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$intervencion  = new Intervencion();
			
			$intervencion->ListarIntervencionProfesional($idIntervencion,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			 
		break;
		
		case 'readCargosIntervencionPro':
			
			$objetoBD = new ObjetoBD();
			$res = $objetoBD->ObtenerEnum('intervencionprofesional','intervencionprofesional_cargo');
			$res->ImprimirJson();
			
		break;
		
		case 'add':
			$intervencion   = new Intervencion();

			// guardamos el intervencion e imprimimos la respuesta json del servidor
			$intervencion->Guardar($adminRegistros, $editarRegistrosAjenos)->ImprimirJson();
		break;
		
		case 'addIntPro':
			$intervencion   = new Intervencion();
			$intervencion->Leer();
			// guardamos el intervencion e imprimimos la respuesta json del servidor
			$intervencion->GuardarRelacion('profesional')->ImprimirJson();
		break;
		
		case 'destroy':
			
			$idIntervencion  = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			$intervencion = new Intervencion();
			$intervencion->EliminarDato($idIntervencion, $adminRegistros, $editarRegistrosAjenos)->ImprimirJson();
			
		break;
		
		case 'destroyIntPro':
			$idintervencion = isset($_REQUEST["intervencionprofesional_intervencion_id"])?$_REQUEST["intervencionprofesional_intervencion_id"]:"";
			$idProfesional  = isset($_REQUEST["intervencionprofesional_profesional_id"])?$_REQUEST["intervencionprofesional_profesional_id"]:"";
			$intervencion   = new Intervencion();
			$intervencion->EliminarRelacion('profesional',$idintervencion, $idProfesional)->ImprimirJson();
			
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