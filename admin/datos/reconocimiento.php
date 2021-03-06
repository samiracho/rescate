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
			
			$idProfesional  = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$filtros        = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit          = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start          = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$reconocimiento = new Reconocimiento();
			
			$reconocimiento->Listar($idProfesional,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			$reconocimiento  = new Reconocimiento();
			$reconocimiento->Guardar()->ImprimirJson();
			
		break;
		
		case 'checkReconocimientoUnique':

			$idBibliografia = isset($_REQUEST["idBibliografia"])?$_REQUEST["idBibliografia"]:"";
			$isbn           = isset($_REQUEST["bibliografia_isbn"])?$_REQUEST["bibliografia_isbn"]:"";			
			$bibliografia   = new Bibliografia();
			
			$bibliografia->ComprobarIsbnUnico($isbn,$idBibliografia);
			
		break;
		
		case 'destroy':
			$idReconocimiento = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			$reconocimiento = new Reconocimiento();
			$reconocimiento->EliminarDato($idReconocimiento)->ImprimirJson();		
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