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

			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$idCentro       = isset($_REQUEST["idCentro"])?$_REQUEST["idCentro"]:"";
			$limit          = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start          = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort          = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$centro         = new Centro();
			
			$centro->Listar($idCentro,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'add':
		
			$centro  = new Centro();
			$centro->Guardar()->ImprimirJson();
			
		break;
		
		case 'readTiposCentros':
		
			$objetoBD = new ObjetoBD();
			$res = $objetoBD->ObtenerEnum('centro','centro_tipo');
			$res->ImprimirJson();
		
		break;
		
		case 'checkCodUnique':

			$idCentro       = isset($_REQUEST["idCentro"])?$_REQUEST["idCentro"]:"";
			$cod            = isset($_REQUEST["centro_codigo"])?$_REQUEST["centro_codigo"]:"";			
			$centro         = new Centro();
			
			$res = $centro->ComprobarCodUnico($cod,$idCentro);
			echo $res->exito ? 1 : 0;
			
		break;
		
		case 'destroy':
			$idCentro = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			Centro::Eliminar($idCentro)->ImprimirJson();		
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