<?php

require '../inc.php'; 

// comprobamos permisos
if(!Usuario::TienePermiso('administrar_usuarios'))
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
		
			$filtros       = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit         = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start         = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$rol = new Rol();
			print_r($rol->ListaRoles($filtros));
		break;
		
		case 'add':
			$rol = new Rol();
			$rol->LeerRolJson();
			// guardamos el rol e imprimimos la respuesta json del servidor
			print_r($rol->Guardar());
		break;
		
		case 'checkUnique':

			$idRol = isset($_REQUEST["idRol"])?$_REQUEST["idRol"]:'';
			$nombre     = isset($_REQUEST["nombreRol"])?$_REQUEST["nombreRol"]:"";
			
			$rol   = new Rol();
			$res = $rol->ComprobarRolUnico($nombre,$idRol);
			echo $res->exito ? 1 : 0;
			
		break;
		
		case 'readPerms':
		
			$idRol = isset($_REQUEST["id"])?$_REQUEST["id"]:'';	
			// obtenemos la lista de permisos
			print_r(Rol::ObtenerPermisosJson($idRol));

		break;
		
		case 'destroy':
			$idRol = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			print_r(Rol::Eliminar($idRol));
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