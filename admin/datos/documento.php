<?php

require '../inc.php'; 

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
			$limit          = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start          = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort           = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$documento      = new Documento();
			$documento->Listar($idUsuario,$adminRegistros,$editarRegistrosAjenos, $filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'readDocInt':

			$idDocumento  = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel        = empty($_REQUEST['norel']) ? false : true;
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit        = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start        = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort         = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$documento    = new Documento();
			$documento->ListarRelacion('intervencion',$idDocumento,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'readDocPro':
			
			$idDocumento  = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel        = empty($_REQUEST['norel']) ? false : true;
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit        = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start        = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort         = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$documento    = new Documento();
			$documento->ListarRelacion('profesional',$idDocumento,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'readDocObr':
		
			$idDocumento  = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$norel        = empty($_REQUEST['norel']) ? false : true;
			$filtros      = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit        = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start        = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort         = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$documento    = new Documento();
			$documento->ListarRelacion('obra',$idDocumento,$norel,$filtros,$start,$limit,$sort)->ImprimirJson();
			
		break;
		
		case 'addDocInt':
		
			$documento  = new Documento();
			$documento->Leer();
			$documento->GuardarRelacion('intervencion')->ImprimirJson();
			
		break;
		
		case 'addDocPro':
		
			$documento  = new Documento();
			$documento->Leer();
			$documento->GuardarRelacion('profesional')->ImprimirJson();
			
		break;
		
		case 'addDocObr':
		
			$documento  = new Documento();
			$documento->Leer();
			$documento->GuardarRelacion('obra')->ImprimirJson();
			
		break;
		
		case 'add':
		
			$documento  = new Documento();
			$documento->Guardar($adminRegistros, $editarRegistrosAjenos)->ImprimirJson();
			
		break;
		
		case 'upload':
			$documento_id = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			$res = Documento::SubirArchivo($documento_id);
			$res->ImprimirJson();
			
		break;
		
		case 'deleteFile':
			$documento_id = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			print_r(Documento::EliminarArchivo($documento_id));
			
		break;
		
		case 'webcam':
			$documento_id = isset($_REQUEST["documento_id"])?$_REQUEST["documento_id"]:"";
			$res = Documento::SubirImagenWebCam($documento_id);
			
			$res->ImprimirJson();
			
		break;
		
		case 'destroy':
			$idDocumento = isset($_REQUEST["id"])?$_REQUEST["id"]:"";
			$documento = new Documento();
			$documento->EliminarDato($idDocumento, $adminRegistros, $editarRegistrosAjenos)->ImprimirJson();		
		break;
		
		case 'destroyDocInt':
			$iddocumento    = isset($_REQUEST["documentointervencion_documento_id"])?$_REQUEST["documentointervencion_documento_id"]:"";
			$idIntervencion = isset($_REQUEST["documentointervencion_intervencion_id"])?$_REQUEST["documentointervencion_intervencion_id"]:"";
			$documento      = new Documento();
			$documento->EliminarRelacion('intervencion',$iddocumento, $idIntervencion)->ImprimirJson();
			
		break;
		
		case 'destroyDocPro':
			$iddocumento    = isset($_REQUEST["documentoprofesional_documento_id"])?$_REQUEST["documentoprofesional_documento_id"]:"";
			$idProfesional  = isset($_REQUEST["documentoprofesional_profesional_id"])?$_REQUEST["documentoprofesional_profesional_id"]:"";
			$documento      = new Documento();
			$documento->EliminarRelacion('profesional',$iddocumento, $idProfesional)->ImprimirJson();
			
		break;
		
		case 'destroyDocObr':
			$iddocumento = isset($_REQUEST["documentoobra_documento_id"])?$_REQUEST["documentoobra_documento_id"]:"";
			$idObra      = isset($_REQUEST["documentoobra_obra_id"])?$_REQUEST["documentoobra_obra_id"]:"";
			$documento   = new Documento();
			$documento->EliminarRelacion($iddocumento, $idObra)->ImprimirJson();
			
		break;
		
		case 'readProDoc':
			
			$idUsuario = Usuario::IdUsuario();
			
			$idDocumento    = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
			$filtros   = isset($_REQUEST["filtros"])?$_REQUEST["filtros"]:"";
			$limit     = isset($_REQUEST["limit"])?$_REQUEST["limit"]:SELECT_LIMIT;
			$start     = isset($_REQUEST["start"])?$_REQUEST["start"]:0;
			$sort      = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
			$obra      = new Documento();
			
			$obra->ListarProfesionalDocumento($idDocumento,$filtros,$start,$limit,$sort)->ImprimirJson();
			 
		break;
		
		case 'addProDoc':
			$obra   = new Documento();
			$obra->Leer();
			// guardamos la obra e imprimimos la respuesta json del servidor
			$obra->GuardarRelacion('profesionaldocumento')->ImprimirJson();
		break;
		
		case 'destroyProDoc':
			$idDocumento = isset($_REQUEST["profesionaldocumento_documento_id"])?$_REQUEST["profesionaldocumento_documento_id"]:"";
			$idProfesional  = isset($_REQUEST["profesionaldocumento_profesional_id"])?$_REQUEST["profesionaldocumento_profesional_id"]:"";
			$obra = new Documento();
			$obra->EliminarRelacion('profesionaldocumento',$idDocumento, $idProfesional)->ImprimirJson();
		break;
		
		case 'readTiposDocumentos':	
			$objetoBD = new ObjetoBD();
			$res = $objetoBD->ObtenerEnum('documento','documento_tipo');
			$res->ImprimirJson();	
		break;
		
		case 'checkUnique':

			$idDocumento = isset($_REQUEST["idDocumento"])?$_REQUEST["idDocumento"]:'';
			$ref     = isset($_REQUEST["ref"])?$_REQUEST["ref"]:"";
			
			$documento   = new Documento();
			$res = $documento->ComprobarRefUnico($ref,$idDocumento);
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