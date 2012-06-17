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
			$especialidad  = new Especialidad();
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			
			$especialidad->Listar($idProfesional,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			if(!Usuario::TienePermiso('administrar_especialidades'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$especialidad = new Especialidad();
				$especialidad->Guardar()->ImprimirJson();
			}
			
		break;
		
		case 'addEspecialidadPro':
		
			$especialidad = new Especialidad();
			$especialidad->Leer();
			$especialidad->GuardarRelacion('profesional')->ImprimirJson();
			
		break;
		
		case 'destroy':
		
			if(!Usuario::TienePermiso('administrar_especialidades'))
			{
				$res = new Comunicacion();
				$res->exito   = false;
				$res->mensaje = t('You dont have the required permissions');
				$res->errores = t('Permission error');
				$res->ImprimirJson();		
			}
			else
			{
				$idEspecialidad = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
				$especialidad = new Especialidad();
				$especialidad->EliminarDato($idEspecialidad)->ImprimirJson();
			}
			
		break;
		
		case 'destroyEspecialidadPro':
		
			$idEspecialidad = isset($_REQUEST["especialidad_id"])?$_REQUEST["especialidad_id"]:"";
			$idProfesional  = isset($_REQUEST["especialidadprofesional_profesional_id"])?$_REQUEST["especialidadprofesional_profesional_id"]:"";
			$especialidad = new Especialidad();
			$especialidad->EliminarRelacion('profesional',$idEspecialidad, $idProfesional)->ImprimirJson();
			
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