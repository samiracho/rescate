<?php	
	class Documento extends ObjetoBD
	{			
		function Documento()
		{				
			$this->id    = "documento_id";
			$this->tabla = "documento";
								
			$this->exitoInsertar   = t("Award created successfully");
			$this->exitoActualizar = t("Award updated successfully");
			$this->errorInsertar   = t("Error creating Award");
			$this->errorActualizar = t("Error updating Award");
			$this->exitoListar     = t('Award list obtained successfully');
			$this->errorListar     = t('Error obtaining Award list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'documento_id'             => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid award ID'),'valor'=>'','lectura'=>false),
				'documento_titulo'         => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'documento_ref'            => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'documento_directorio'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid tipo'),'valor'=>null,'lectura'=>true),
				'documento_archivo'        => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>true),
				'documento_miniatura'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>true),
				'documento_enlace'         => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_pais'           => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_provincia'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_poblacion'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_direccion'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_cordenadas'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_autornombre'    => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_autorapellido1' => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_autorapellido2' => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_fechainicial'   => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_fechafinal'     => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'documento_descripcion'    => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'documento_supervisado'    => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid supervised'),'valor'=>null,'lectura'=>false),
				'documento_usuario_id'     => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid creator'),'valor'=>null,'lectura'=>false),
				'documento_ultimamod'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid last modification'),'valor'=>'','lectura'=>true),
				'documento_bloqueado'      => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid bloqued'),'valor'=>null,'lectura'=>false)
			);
			
			$this->relaciones = array(
			
				'usuario' => array (
					'tabla'          => 'usuario',
					'relacion'       => '1a1',
					'soloLectura'	 => true,
					'clavePrincipal' => 'documento_usuario_id',
					'claveAjena1'    => 'usuario_id',
					'claveAjena2'    => '',
					'campos'         => array(
						'usuario_id'         => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_nombre'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido1'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido2'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_login'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true)
					)
				),
				'profesional' => array (
					'tabla'       => 'documentoprofesional',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'documentoprofesional_documento_id',
					'claveAjena2' => 'documentoprofesional_profesional_id',
					'campos'      => array(
						'documentoprofesional_documento_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'documentoprofesional_profesional_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'documentoprofesional_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				),
				'profesionaldocumento' => array (
					'tabla'       => 'profesionaldocumento',
					'relacion'	  => 'MaN',
					'claveAjena1' => 'profesionaldocumento_documento_id',
					'claveAjena2' => 'profesionaldocumento_profesional_id',
					'soloLectura' => false,
					'campos'      => array(		
						'profesionaldocumento_documento_id'   => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid doc id'),'valor'=>null,'lectura'=>false),
						'profesionaldocumento_profesional_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid professional ID'),'valor'=>null,'lectura'=>false),
						'profesionaldocumento_detalles'		  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
						'profesional_nombre'				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>true),
						'profesional_apellido1'				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname1'),'valor'=>null,'lectura'=>true),
						'profesional_apellido2'				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname2'),'valor'=>'','lectura'=>true)
					)
				),
				'intervencion' => array (
					'tabla'       => 'documentointervencion',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'documentointervencion_documento_id',
					'claveAjena2' => 'documentointervencion_intervencion_id',
					'campos'      => array(
						'documentointervencion_documento_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid documentointervencion_documento_id'),'valor'=>'','lectura'=>false),
						'documentointervencion_intervencion_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'documentointervencion_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				),
				'obra' => array (
					'tabla'       => 'documentoobra',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'documentoobra_documento_id',
					'claveAjena2' => 'documentoobra_obra_id',
					'campos'      => array(
						'documentoobra_documento_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'documentoobra_obra_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'documentoobra_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				)
			);	
		}
		
		public function Guardar($esAdmin, $editarRegistrosAjenos)
		{		
			$bd 		= BD::Instancia();
			$consulta 	= "";
			$res        = new Comunicacion();
			$relaciones = "";
			
			// leemos los datos json
			parent::Leer();
			
			// comprobamos que el num de refrerencia sea unico
			$res = $this->ComprobarRefUnico($this->campos['documento_ref']['valor'],$this->campos['documento_id']['valor']);
			if(!$res->exito)return $res;
			
			// si no es un registro nuevo comprobamos que el usuario tenga permiso para actualizarlo
			if($this->campos['documento_id']['valor']!="")
			{
				$consulta = "SELECT documento_bloqueado, documento_usuario_id FROM documento WHERE documento_id = '" . intval($this->campos['documento_id']['valor']) . "' LIMIT 1";
			
				$datos    = $bd->Ejecutar($consulta);
				$fila     = $bd->ObtenerFila($datos);
			
				// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
				if((!$esAdmin && !$editarRegistrosAjenos) && ($fila['documento_bloqueado'] == 1 || $fila['documento_usuario_id'] != Usuario::IdUsuario() ) )
				{
					$res->exito = false;
					$res->mensaje = t("Error");
					$res->errores = t("You dont have permission to edit this record");
					return $res;
				}
			}			
			
			// si no es administrador, no puede bloquear el registro y tenemos que marcarlo como no supervisado
			if(!$esAdmin)
			{
				$this->campos['documento_bloqueado']['valor'] = 0;
				$this->campos['documento_supervisado']['valor'] = 0;
			}
			
			// si no ha especificado un usuario en concreto, el creador del registro será el usuario identificado
			if($this->campos['documento_usuario_id']['valor'] == '')
			{
				$this->campos['documento_usuario_id']['valor'] = Usuario::IdUsuario();
			}
			
			// itentamos guardar
			$res = parent::Guardar(true,false);
			
			
			return $res;
		}
		
		// comprueba que el login sea unico
		public function ComprobarRefUnico($ref, $idDocumento)
		{
			return ObjetoBD::ComprobarUnico("documento_ref", $ref, $this->tabla,$this->id,$idDocumento);			
		}
		
		public static function SubirImagenWebCam($documentoId)
		{
			$bd  = BD::Instancia();
			$res = new Comunicacion();
			
			if(empty($documentoId))
			{
				$res->exito   = false;
				$res->mensaje = t('Error');
				$res->errores = t('Invalid id');
				return $res;
			}
			
			$rutaAbsoluta  = RUTA_ARCHIVOS.DIRECTORY_SEPARATOR."imagen".DIRECTORY_SEPARATOR;
			$prefijo       = substr(md5(uniqid(rand())),0,6);
			$nombreArchivo = $documentoId.'_'.$prefijo.'_captura.jpg';
			
			$result = file_put_contents($rutaAbsoluta.$nombreArchivo, file_get_contents('php://input'));
			if(!$result)
			{
				$res->exito   = false;
				$res->mensaje = t('Error');
				$res->errores = t('Error Uploading File');
				return $res;
			
			}
			else
			{
				// eliminamos el archivo antiguo
				Documento::EliminarArchivo($documentoId);
				
				$miniatura = Documento::ObtenerMiniatura($rutaAbsoluta,$nombreArchivo, '.jpg');
				
				// si todo ha ido bien actualizamos los datos del documento en la bd
				$consulta = "UPDATE documento SET documento_archivo='".$nombreArchivo."', documento_miniatura='".$miniatura."', documento_directorio='imagen' WHERE documento_id= '". $documentoId ."'";
				
				$bd->Ejecutar($consulta);			
				if( $bd->ObtenerErrores() == "" )
				{
					$res->exito = true;
					$res->mensaje = t("Success");
					$res->errores = "";
					$res->datos = array('miniatura'=>URL_ARCHIVOS.'/imagen/miniaturas/'.$miniatura,'archivo'=>URL_ARCHIVOS.'/imagen/'.$nombreArchivo);
				}
				else
				{
					$res->exito = false;
					$res->mensaje = t("Error");
					$res->errores = t("Update operation failed");
				}
				return $res;
			}
		
		}
		
		public static function SubirArchivo($documentoId)
		{
			global $_FILES;
			
			$bd            = BD::Instancia();
			$res           = new Comunicacion();
			$directorio    = "";
			$tipo          = "";
			
			// quitamos caracteres extraños
			$nombreArchivo = preg_replace('/[^(\x20-\x7F)]*/','', $_FILES['photo-path']['name']);
			
			// extensión del archivo
			$ext           =  strtolower( substr($nombreArchivo, strpos($nombreArchivo,'.'), strlen($nombreArchivo)-1) );
			
			if($bd->ContarFilas("SELECT COUNT(*) FROM documento WHERE documento_id='".intval($documentoId)."' LIMIT 1") == 0 || empty($documentoId))
			{
				$res->exito   = false;
				$res->mensaje = t('Error');
				$res->errores = t('Invalid id');
				return $res;
			}
			
			if(in_array($ext,explode(',', IMAGENES_PERMITIDAS)))
			{
				$directorio = "imagen";
			}
			else if(in_array($ext,explode(',', DOCUMENTOS_PERMITIDOS)))
			{
				$directorio = "documento";
			}
			
			else if(in_array($ext,explode(',', SONIDOS_PERMITIDOS)))
			{
				$directorio = "sonido";
			}
 			else if(in_array($ext,explode(',', VIDEOS_PERMITIDOS)))
			{
				$directorio = "video";
			}
			else
			{
				$res->exito   = false;
				$res->mensaje = t('Error');
				$res->errores = t('The file you attempted to upload is not allowed.');
				return $res;
			}
 
 			$rutaAbsoluta  = RUTA_ARCHIVOS.DIRECTORY_SEPARATOR.$directorio.DIRECTORY_SEPARATOR;
			$prefijo       = substr(md5(uniqid(rand())),0,6);
			$nombreArchivo = $documentoId.'_'.$prefijo.'_'.$nombreArchivo;
 
 
			if(filesize($_FILES['photo-path']['tmp_name']) > TAM_MAX)
			{
				$res->exito   = false;
				$res->mensaje = t('Error');
				$res->errores = t('The file you attempted to upload is too large.');
				return $res;
			}
			
			// Check if we can upload to the specified path, if not DIE and inform the user.
			if(!is_writable($rutaAbsoluta))
			{
				$res->exito   = false;
				$res->mensaje = t('Error');
				$res->errores = t('You cannot upload to the specified directory, please CHMOD it to 777.');
				return $res;
			}
 
			if(move_uploaded_file($_FILES['photo-path']['tmp_name'],$rutaAbsoluta.$nombreArchivo))
			{		
				// eliminamos el archivo antiguo
				Documento::EliminarArchivo($documentoId);
				
				$miniatura = Documento::ObtenerMiniatura($rutaAbsoluta,$nombreArchivo, $ext);
				
				// si todo ha ido bien actualizamos los datos del documento en la bd
				$consulta = "UPDATE documento SET documento_archivo='".$nombreArchivo."', documento_miniatura='".$miniatura."', documento_directorio='".$directorio."' WHERE documento_id= '". $documentoId ."'";
				
				$bd->Ejecutar($consulta);			
				if( $bd->ObtenerErrores() == "" )
				{
					$res->exito = true;
					$res->mensaje = t("Success");
					$res->errores = t("Operation succeed");
					$res->datos = array('miniatura'=>URL_ARCHIVOS.'/'.$directorio.'/miniaturas/'.$miniatura,'archivo'=>URL_ARCHIVOS.'/'.$directorio.'/'.$nombreArchivo);
				}
				else
				{
					$res->exito = false;
					$res->mensaje = t("Error");
					$res->errores = t("Update operation failed");
				}
			}
			else
			{
				$res->exito   = false;
				$res->mensaje = t('Error');
				$res->errores = t('Error.');
			}
			return $res;
		}
		
		private static function ObtenerMiniatura($ruta,$nombreArchivo, $extension)
		{
			require_once '../lib/thumbnailer/ThumbLib.inc.php';
			
			if($extension == '.gif' || $extension == '.jpg' || $extension == '.jpeg' || $extension == '.png')
			{
				
				// crear la miniatura
				$thumb = PhpThumbFactory::create($ruta.$nombreArchivo);
				$thumb->adaptiveResize(TAM_MINIATURAS, TAM_MINIATURAS);
				$thumb->save($ruta.'miniaturas'.DIRECTORY_SEPARATOR.$nombreArchivo);
				return $nombreArchivo;
			}
			else
			{
				$thumb = substr($extension, 1).".png";
				return $thumb;
			}
		}
		
		// elimina un archivo y su miniatura si la hubiera
		private static function EliminarArchivo($documentoId)
		{
			$bd       = BD::Instancia();
			
			$consulta = "SELECT documento_miniatura, documento_archivo, documento_directorio FROM documento WHERE documento_id = '".intval($documentoId)."' LIMIT 1";		
			$datos    = $bd->Ejecutar($consulta);
			$fila     = $bd->ObtenerFila($datos);
				
			// borramos el archivo antiguo si lo hubiera
			if( $bd->ObtenerNumFilas($datos) > 0 )
			{
				$rutaArchivoAntiguo   = RUTA_ARCHIVOS.DIRECTORY_SEPARATOR.$fila['documento_directorio'].DIRECTORY_SEPARATOR.$fila['documento_archivo'];
				$rutaMiniaturaAntigua = RUTA_ARCHIVOS.DIRECTORY_SEPARATOR.$fila['documento_directorio'].DIRECTORY_SEPARATOR.'miniaturas'.DIRECTORY_SEPARATOR.$fila['documento_archivo'];
				
				if( $fila['documento_archivo']!='' && file_exists($rutaArchivoAntiguo) )
				{								
					unlink($rutaArchivoAntiguo);
					$consulta = "UPDATE documento SET documento_archivo='', documento_miniatura='', documento_directorio='' WHERE documento_id= '". $documentoId ."'";				
					$bd->Ejecutar($consulta);
				}
				if($fila['documento_miniatura'] && $fila['documento_directorio'] == 'imagen' && file_exists($rutaMiniaturaAntigua))unlink($rutaMiniaturaAntigua);
			}
		}
		
		public function EliminarDato($idDocumento, $esAdmin, $editarRegistrosAjenos )
		{	
			$bd       = BD::Instancia();
			$res      = new Comunicacion();
		
			// comprobamos que el usuario tenga permiso para eliminarlo
			$consulta = "SELECT documento_bloqueado, documento_usuario_id FROM documento WHERE documento_id = '" . intval($idDocumento) . "' LIMIT 1";
		
			$datos    = $bd->Ejecutar($consulta);
			$fila     = $bd->ObtenerFila($datos);
		
			// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
			if((!$esAdmin && !$editarRegistrosAjenos) && ($fila['documento_bloqueado'] == 1 || $fila['documento_usuario_id'] != Usuario::IdUsuario() ) )
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("You dont have permission to edit this record");
				return $res;
			}
			
			
			Documento::EliminarArchivo($idDocumento);
			return parent::EliminarDato($idDocumento);
		}
		
		public function Listar($idUsuario, $esAdmin=false, $editarRegistrosAjenos = false, $verRegistrosAjenos=false, $filtros=null,$start=null,$limit = null,$sort = null)
		{		
			$res = new Comunicacion();
			$filtroBusqueda = "";
			
			// si no es admin entonces le mostramos solo los registros creados por él mismo
			// si no es admin y no puede ver registros de otros, entonces le mostramos solo los registros creados por él mismo
			if(!$esAdmin && !$verRegistrosAjenos )
			{
				$filtroBusqueda.= " AND documento_usuario_id = '".$idUsuario."'";
			}
			
			$filtroRegistros = ($verRegistrosAjenos && (!$esAdmin && !$editarRegistrosAjenos) ) ? "CASE WHEN documento_usuario_id!='".$idUsuario."' THEN '1' ELSE documento_bloqueado END AS documento_bloqueado" : "documento_bloqueado";
			
			$consulta =" SELECT P.profesional_nombre, P.profesional_apellido1, P.profesional_apellido2, usuario_login, usuario_nombre, usuario_apellido1, usuario_apellido2, documento_supervisado, ".$filtroRegistros.",documento_cordenadas, documento_id, documento_titulo,documento_enlace, documento_ref,
			             DATE_FORMAT(documento_fechainicial, '".FORMATO_FECHA_MYSQL_ANYO."') AS documento_fechainicial, DATE_FORMAT(documento_fechafinal, '".FORMATO_FECHA_MYSQL_ANYO."') AS documento_fechafinal, documento_descripcion, 
			             documento_pais, documento_ultimamod, documento_provincia, documento_poblacion, documento_direccion, documento_fechainicial AS sordocumento_fechainicial, documento_fechafinal AS sortdocumento_fechafinal,  
						 CASE WHEN documento_archivo!='' THEN CONCAT( '".URL_ARCHIVOS."/',documento_directorio,'/',documento_archivo) 
						 ELSE '' END AS documento_archivo, 
						 CASE WHEN documento_miniatura!='' THEN CONCAT( '".URL_ARCHIVOS."/',documento_directorio,'/miniaturas/',documento_miniatura) 
						 ELSE '".URL_ARCHIVOS."/desconocido.png' END AS documento_miniatura		 
						 FROM documento 
						 LEFT JOIN usuario ON documento_usuario_id = usuario_id 
						 LEFT JOIN profesionaldocumento PD ON documento_id = profesionaldocumento_documento_id
						 LEFT JOIN profesional P ON profesionaldocumento_profesional_id = profesional_id
						 WHERE 1 ".$filtroBusqueda;
			
			
			return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
		
		public static function ListarDocumentosProfesional($id)
		{
			$documentos = array();
			$bd             = BD::Instancia();			
			$consulta       = "SELECT * FROM documentoprofesional 
							   LEFT JOIN documento ON documento_id = documentoprofesional_documento_id
							   LEFT JOIN profesionaldocumento ON profesionaldocumento_documento_id = documento_id
						       LEFT JOIN profesional ON profesionaldocumento_profesional_id = profesional_id
							   WHERE documentoprofesional_profesional_id ='".intval($id)."' ORDER BY documento_titulo";
				
			return $bd->ObtenerResultados($consulta);	
		}
		
		public function ListarRelacion($nombreRelacion,$id,$norelacionadas=false,$filtros="",$start=0,$limit = SELECT_LIMIT, $sort)
		{		
			$res = new Comunicacion();
			$filtroBusqueda = parent::CrearFiltro($filtros);
			$sortBusqueda   = $sort!="" ? parent::CrearSort($sort) : "ORDER BY documento_id DESC";
			
			if(!$norelacionadas)
			{				
				$consulta      =  "SELECT documento_titulo,
				                  CASE WHEN documento_miniatura!='' THEN CONCAT( '".URL_ARCHIVOS."/',documento_directorio,'/miniaturas/',documento_miniatura) 
				                  ELSE '".URL_ARCHIVOS."/desconocido.png' END AS documento_miniatura,
				                  documento_archivo, documento_autorapellido1, documento_cordenadas, documento_autorapellido2, ".$this->relaciones[$nombreRelacion]['claveAjena1'].", ".$this->relaciones[$nombreRelacion]['claveAjena2'].", ".$this->relaciones[$nombreRelacion]['tabla']."_detalles 
								  FROM ".$this->relaciones[$nombreRelacion]['tabla']." LEFT JOIN documento ON documento_id=".$this->relaciones[$nombreRelacion]['claveAjena1']." WHERE ".$this->relaciones[$nombreRelacion]['claveAjena2']."='".intval($id)."' ".$filtroBusqueda." ".$sortBusqueda." LIMIT ".$start.",".$limit;	
			}
			else
			{
				$consulta       = "SELECT documento_titulo,  
				                   CASE WHEN documento_miniatura!='' THEN CONCAT( '".URL_ARCHIVOS."/',documento_directorio,'/miniaturas/',documento_miniatura) 
				                   ELSE '".URL_ARCHIVOS."/desconocido.png' END AS documento_miniatura, 
								   documento_archivo, documento_autorapellido1, documento_cordenadas, documento_autorapellido2,documento_id AS ".$this->relaciones[$nombreRelacion]['claveAjena1'].", CASE WHEN documento_id IS NOT NULL THEN '".$id."' END AS ".$this->relaciones[$nombreRelacion]['claveAjena2']." FROM documento 
								   WHERE documento_id NOT IN (SELECT ".$this->relaciones[$nombreRelacion]['claveAjena1']." FROM ".$this->relaciones[$nombreRelacion]['tabla']." WHERE ".$this->relaciones[$nombreRelacion]['claveAjena2']." ='".intval($id)."' )  ".$filtroBusqueda." ".$sortBusqueda." LIMIT ".$start.",".$limit;
			}	
			
			if( $id == "" && $norelacionadas == false )return Comunicacion::Error(); 
			else return parent::Listar($consulta);
		}
		
		public function ListarProfesionalDocumento($idDocumento,$filtros=null,$start=null,$limit = null, $sort=null)
		{		
			$res = new Comunicacion();
	
				
			$consulta = "SELECT * 
				             FROM profesionaldocumento LEFT JOIN profesional ON profesional_id=profesionaldocumento_profesional_id 
							 WHERE profesionaldocumento_documento_id='".intval($idDocumento)."'";	

			if( $idDocumento == "" )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}