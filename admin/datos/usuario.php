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
			
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit        = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start        = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$usuario      = new Usuario();
			$usuario->Listar($filtros,$start,$limit,$sort)->ImprimirJson();
		
		break;
		
		case 'add':		
			$usuario = new Usuario();
			// guardamos el usuario e imprimimos la respuesta json del servidor
			$usuario->Guardar()->ImprimirJson();
		break;
		
		case 'destroy':	
			$idUsuario = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			Usuario::Eliminar($idUsuario)->ImprimirJson();
		break;
		
		case 'checkUnique':

			$idUsuario = isset($_REQUEST["idUsuario"])?$_REQUEST["idUsuario"]:'';
			$login     = isset($_REQUEST["login"])?$_REQUEST["login"]:"";
			
			$usuario   = new Usuario();
			$res = $usuario->ComprobarLoginUnico($login,$idUsuario);
			echo $res->exito ? 1 : 0;
			
		break;
		
		case 'saveState':
			$datos      = isset($_REQUEST["data"])?$_REQUEST["data"]:"";
			Usuario::GuardarEstado($datos)->ImprimirJson();
		break;
		
		case 'readState':
			print_r(Usuario::ObtenerEstado());
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