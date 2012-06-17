<?php

require '../inc.php'; 

// permisos
$adminRegistros        = Usuario::TienePermiso('administrar_bibliografias');
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
			$bibliografia   = new Bibliografia();
			
			 $bibliografia->Listar($idUsuario,$adminRegistros,$editarRegistrosAjenos, $filtros,$start,$limit,$sort)->ImprimirJson();
			 
		break;
		
		case 'readProBib':
			
			$idUsuario = Usuario::IdUsuario();
			
			$idBibliografia  = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit        = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start        = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort         = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$bibliografia  = new Bibliografia();
			
			$bibliografia->ListarProfesionalBibliografia($idBibliografia,$filtros,$start,$limit,$sort)->ImprimirJson();
			 
		break;
		
		case 'readTiposProBib':
			
			$objetoBD = new ObjetoBD();
			$res = $objetoBD->ObtenerEnum('profesionalbibliografia','profesionalbibliografia_tiporelacion');
			$res->ImprimirJson();
			
		break;
		
		case 'add':
			$bibliografia   = new Bibliografia();

			// guardamos el bibliografia e imprimimos la respuesta json del servidor
			$r = $bibliografia->Guardar($adminRegistros, $editarRegistrosAjenos);
			$r->ImprimirJson();
		break;
		
		case 'addProBib':
			$bibliografia   = new Bibliografia();
			$bibliografia->Leer();
			// guardamos la bibliografia e imprimimos la respuesta json del servidor
			$bibliografia->GuardarRelacion('profesional')->ImprimirJson();
		break;
		
		case 'destroy':
			
			$idBibliografia  = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			$bibliografia = new Bibliografia();
			$bibliografia->EliminarDato($idBibliografia, $adminRegistros, $editarRegistrosAjenos)->ImprimirJson();
		break;
		
		case 'destroyProBib':

			$idbibliografiaPro = isset($_REQUEST["profesionalbibliografia_id"])?$_REQUEST["profesionalbibliografia_id"]:"";
			$bibliografia      = new Bibliografia();
			$bibliografia->EliminarRelacion('profesional',$idbibliografiaPro)->ImprimirJson();
			
		break;
		
		case 'checkIsbnUnique':

			$idBibliografia = isset($_REQUEST["idBibliografia"])?$_REQUEST["idBibliografia"]:'';
			$isbn     = isset($_REQUEST["isbn"])?$_REQUEST["isbn"]:"";
			
			$usuario   = new Bibliografia();
			$res = $usuario->ComprobarIsbnUnico($isbn,$idBibliografia);
			echo $res->exito ? 1 : 0;
			
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