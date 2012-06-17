<?php
require '../inc.php'; 

// comprobamos permisos
if(!Usuario::TienePermiso('administrar_registros') && !Usuario::TienePermiso('agregareditar_registros_propios'))
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
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$colaborador    = new Colaborador();
			
			$colaborador->Listar($idProfesional,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			$colaborador = new Colaborador();
			$colaborador->Guardar()->ImprimirJson();
			
		break;
		
		case 'destroy':
			$idColaborador = isset($_REQUEST["colaborador_colaborador_id"])?$_REQUEST["colaborador_colaborador_id"]:"";
			$idProfesional  = isset($_REQUEST["colaborador_profesional_id"])?$_REQUEST["colaborador_profesional_id"]:"";
			Colaborador::Eliminar($idColaborador, $idProfesional)->ImprimirJson();
			
		break;
		
		case 'readTiposColaboradores':
		
			$objetoBD = new ObjetoBD();
			$res = $objetoBD->ObtenerEnum('colaborador','colaborador_tipo');
			$res->ImprimirJson();
		
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