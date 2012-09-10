<?php	
	class Profesional extends ObjetoBD
	{			
		private static $plantilla      = "profesional.htm";
		private static $plantillaLista = "profesional_lista.htm";
		
		function Profesional()
		{				
			$this->id    = "profesional_id";
			$this->tabla = "profesional";
								
			$this->exitoInsertar   = t("Professional created successfully");
			$this->exitoActualizar = t("Professional updated successfully");
			$this->errorInsertar   = t("Error creating Professional");
			$this->errorActualizar = t("Error updating Professional");
			$this->exitoListar     = t('Professional list obtained successfully');
			$this->errorListar     = t('Error obtaining Professional list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'profesional_id'              => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid professional ID'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_tipo'            => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid type'),'valor'=>null,'lectura'=>false,'opciones'=>array('Restaurador','Colaborador','Especialista')),
				'profesional_nombre'          => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false,'opciones'=>null),
				'profesional_apellido1'       => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid surname1'),'valor'=>null,'lectura'=>false,'opciones'=>null),
				'profesional_apellido2'       => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname2'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_sexo'            => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid sex'),'valor'=>null,'lectura'=>false,'opciones'=>array('Hombre','Mujer')),
				'profesional_observaciones'   => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_paisn'           => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid country'),'valor'=>'','lectura'=>false,'opciones'=>'SELECT pais_nombre FROM pais'),
				'profesional_provincian'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid province'),'valor'=>'','lectura'=>false,'opciones'=>'SELECT provincia_nombre FROM provincia'),
				'profesional_poblacionn'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid city'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_direccionn'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid address'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_cordenadasn'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cordinates'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_fechan'          => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_paisd'           => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid country'),'valor'=>'','lectura'=>false,'opciones'=>'SELECT pais_nombre FROM pais'),
				'profesional_provinciad'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid province'),'valor'=>'','lectura'=>false,'opciones'=>'SELECT provincia_nombre FROM provincia'),
				'profesional_poblaciond'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid city'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_direcciond'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid address'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_cordenadasd'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cordinates'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_fechad'          => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_familia'         => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>false,'opciones'=>null),
				'profesional_directorio'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid tipo'),'valor'=>null,'lectura'=>true),
				'profesional_archivo'         => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>true),
				'profesional_miniatura'       => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>true),
				'profesional_supervisado'     => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid supervised'),'valor'=>null,'lectura'=>false,'opciones'=>null,'nobuscador'=>true),
				'profesional_usuario_id'      => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid creator'),'valor'=>null,'lectura'=>false,'opciones'=>null,'nobuscador'=>true),
				'profesional_ultimamod'       => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid last modification'),'valor'=>'','lectura'=>true,'opciones'=>null,'nobuscador'=>true),
				'profesional_bloqueado'       => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid bloqued'),'valor'=>null,'lectura'=>false,'opciones'=>null,'nobuscador'=>true)
			);
			
			$this->relaciones = array(
			
				'usuario' => array (
					'tabla'       => 'usuario',
					'relacion'    => '1a1',
					'soloLectura' => true,
					'clavePrincipal' => 'profesional_usuario_id',
					'claveAjena1' => 'usuario_id',
					'claveAjena2' => '',
					'campos'      => array(
						'usuario_id'         => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_nombre'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido1'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido2'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_login'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true)
					)
				)
			);

			// a partir de este array construiremos los formularios de búsqueda
			$this->busqueda = array(
				'profesional'                 => array('consulta'=>null,'campos'=>$this->campos),
				'reconocimiento'              => array('consulta'=>'LEFT JOIN reconocimiento ON reconocimiento_profesional_id = profesional_id',
				                                       'campos'  =>array(
														              'reconocimiento_nombre'       => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT reconocimiento_nombre FROM reconocimiento'),
				                                                      'reconocimiento_detalles'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
				                                                      'reconocimiento_fecha'        => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
									)
				),
				'especialidad'                => array('consulta'=>'CROSS JOIN especialidad LEFT JOIN especialidadprofesional ON ( especialidadprofesional_profesional_id = profesional_id AND especialidadprofesional_especialidad_id = especialidad_id)',
				                                       'campos'  =>array(
														              'especialidad_nombre'               => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT especialidad_nombre FROM especialidad'),
				                                                      'especialidad_detalles'             => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
				                                                      'especialidadprofesional_detalles'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
						         		)
				),
				'asociacion'                  => array('consulta'=>'CROSS JOIN asociacion LEFT JOIN asociacionprofesional ON ( asociacionprofesional_profesional_id = profesional_id AND asociacionprofesional_asociacion_id = asociacion_id)',
				                                       'campos'  =>array(
									    					          'asociacion_nombre'                   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT asociacion_nombre FROM asociacion'),
				                                                      'asociacion_detalles'                 => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
				                                                      'asociacionprofesional_detalles'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
														              'asociacionprofesional_fechaentrada'  => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
																      'asociacionprofesional_fechasalida'   => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
								       )
				),
				'tecnica'                     => array('consulta'=>'CROSS JOIN tecnica LEFT JOIN tecnicaprofesional ON ( tecnicaprofesional_profesional_id = profesional_id AND tecnicaprofesional_tecnica_id = tecnica_id)',
				                                       'campos'  =>array(
														              'tecnica_nombre'                   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT tecnica_nombre FROM tecnica'),
				                                                      'tecnica_detalles'                 => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
				                                                      'tecnicaprofesional_detalles'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null)
								       )
				),
				'cargo'                       => array('consulta'=>'LEFT JOIN cargo ON profesional_id = cargo_profesional_id LEFT JOIN centro ON centro_id = cargo_centro_id',
				                                       'campos'  =>array(
														              'cargo_nombre'                    => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT DISTINCT cargo_nombre FROM cargo'),
				                                                      'cargo_detalles'                  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
																      'cargo_principal'                 => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>array(0,1)),
																      'cargo_fechainicio'               => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
																      'cargo_fechafin'                  => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
																      'centro_nombre'                   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT DISTINCT centro_nombre FROM centro'),
																      'centro_codigo'                   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT DISTINCT centro_codigo FROM centro'),
																      'centro_tipo'                     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT DISTINCT centro_tipo FROM centro'),
				                                                      'centro_detalles'                 => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null)
								       )
				),
				'formacion'                       => array('consulta'=>'LEFT JOIN formacion ON profesional_id = formacion_profesional_id LEFT JOIN centro ON centro_id = formacion_centro_id',
				                                       'campos'  =>array(
									            					  'formacion_titulo'                => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT DISTINCT formacion_titulo FROM formacion'),
				                                                      'formacion_detalles'              => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
																      'formacion_fechainicio'           => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
																      'formacion_fechafin'              => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null),
																      'centro_nombre'                   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT DISTINCT centro_nombre FROM centro'),
																      'centro_codigo'                   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT DISTINCT centro_codigo FROM centro'),
																      'centro_tipo'                     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>'SELECT DISTINCT centro_tipo FROM centro'),
				                                                      'centro_detalles'                 => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid text'),'valor'=>'','lectura'=>true,'opciones'=>null)
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
			
			if($this->campos['profesional_tipo']['valor'] == 'Todos')$this->campos['profesional_tipo']['valor'] = 'Colaborador';
			
			// si el tipo de profesional es inválido no dejamos guardar. 
			// (Debe ser Restaurador,Investigador o Colaborador. Esto no tendría que hacerlo si mysql estuviera en modo estricto, pero como no lo está, si le paso un valor incorrecto a un enum, pone la cadena vacía)
			$objetoBD = new ObjetoBD();
			$res2     = $objetoBD->ObtenerEnum('profesional','profesional_tipo');
			$valido   = false;
			
			foreach ($res2->datos as $campo => $valor) {
				if($valor['nombre'] === $this->campos['profesional_tipo']['valor'])
				{
					$valido = true;
					break;
				}
			}
			if(!$valido)
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Professional type invalid");
				return $res;
			}			
			
			// si no es un registro nuevo comprobamos que el usuario tenga permiso para actualizarlo
			if($this->campos['profesional_id']['valor']!="")
			{
				$consulta = "SELECT profesional_bloqueado, profesional_usuario_id FROM profesional WHERE profesional_id = '" . intval($this->campos['profesional_id']['valor']) . "' LIMIT 1";
			
				$datos    = $bd->Ejecutar($consulta);
				$fila     = $bd->ObtenerFila($datos);
			
				// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
				if((!$esAdmin && !$editarRegistrosAjenos) && ($fila['profesional_bloqueado'] == 1 || $fila['profesional_usuario_id'] != Usuario::IdUsuario() ) )
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
				$this->campos['profesional_bloqueado']['valor'] = 0;
				$this->campos['profesional_supervisado']['valor'] = 0;
			}
					
			// si no ha especificado un usuario en concreto, el creador del registro será el usuario identificado
			if($this->campos['profesional_usuario_id']['valor'] == '')
			{
				$this->campos['profesional_usuario_id']['valor'] = Usuario::IdUsuario();
			}
			
			// itentamos guardar
			$res = parent::Guardar(true, false);		
			
			if( $res->exito )
			{
				$template = Plantilla::Smarty();
				
				// si está en caché lo vaciamos
				if($template->isCached(Profesional::$plantilla,$this->campos['profesional_id']['valor'])) {
					$template->clearCache(Profesional::$plantilla,$this->campos['profesional_id']['valor']);	
				}
			}
			
			return $res;
		}
		
		public static function SubirArchivo($profesionalId)
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
			
			if($bd->ContarFilas("SELECT COUNT(*) FROM  profesional WHERE profesional_id='".intval($profesionalId)."' LIMIT 1") == 0 || empty($profesionalId))
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
			else
			{
				$res->exito   = false;
				$res->mensaje = t('Error');
				$res->errores = t('The file you attempted to upload is not allowed.');
				return $res;
			}
 
 			$rutaAbsoluta  = RUTA_ARCHIVOS.DIRECTORY_SEPARATOR.$directorio.DIRECTORY_SEPARATOR;
			$prefijo       = substr(md5(uniqid(rand())),0,6);
			$nombre        = $profesionalId.'_'.$prefijo.'_'.$nombreArchivo;
			
			while(file_exists($rutaAbsoluta.$nombre) ){
				$prefijo       = substr(md5(uniqid(rand())),0,6);
				$nombre        = $profesionalId.'_'.$prefijo.'_'.$nombreArchivo;
			}
 
 
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
 
			if(move_uploaded_file($_FILES['photo-path']['tmp_name'],$rutaAbsoluta.$nombre))
			{					
				// eliminamos el archivo antiguo
				Profesional::EliminarArchivo($profesionalId);
				
				$miniatura = Profesional::ObtenerMiniatura($rutaAbsoluta,$nombre, $ext);
				
				// si todo ha ido bien actualizamos los datos del documento en la bd
				$consulta = "UPDATE profesional SET profesional_archivo='".$nombre."', profesional_miniatura='".$miniatura."', profesional_directorio='".$directorio."' WHERE profesional_id= '". $profesionalId ."'";
				
				$bd->Ejecutar($consulta);			
				if( $bd->ObtenerErrores() == "" )
				{
					$res->exito = true;
					$res->mensaje = t("Success");
					$res->errores = "";
					$res->datos = array('miniatura'=>URL_ARCHIVOS.'/'.$directorio.'/miniaturas/'.$miniatura,'archivo'=>URL_ARCHIVOS.'/'.$directorio.'/'.$nombre);
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
				$res->errores = t('Error moving uploaded file');
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
		private static function EliminarArchivo($profesionalId)
		{
			$bd       = BD::Instancia();
			
			$consulta = "SELECT profesional_miniatura, profesional_archivo, profesional_directorio FROM profesional WHERE profesional_id = '".intval($profesionalId)."' LIMIT 1";		
			$datos    = $bd->Ejecutar($consulta);
			$fila     = $bd->ObtenerFila($datos);
				
			// borramos el archivo antiguo si lo hubiera
			if( $bd->ObtenerNumFilas($datos) > 0 )
			{
				$rutaArchivoAntiguo   = RUTA_ARCHIVOS.DIRECTORY_SEPARATOR.$fila['profesional_directorio'].DIRECTORY_SEPARATOR.$fila['profesional_archivo'];
				$rutaMiniaturaAntigua = RUTA_ARCHIVOS.DIRECTORY_SEPARATOR.$fila['profesional_directorio'].DIRECTORY_SEPARATOR.'miniaturas'.DIRECTORY_SEPARATOR.$fila['profesional_archivo'];
				
				if( $fila['profesional_archivo']!='' && file_exists($rutaArchivoAntiguo) )
				{								
					unlink($rutaArchivoAntiguo);
					$consulta = "UPDATE profesional SET profesional_archivo='', profesional_miniatura='', profesional_directorio='' WHERE profesional_id= '". $profesionalId ."'";				
					$bd->Ejecutar($consulta);
				}
				if($fila['profesional_miniatura'] && $fila['profesional_directorio'] == 'imagen' && file_exists($rutaMiniaturaAntigua))unlink($rutaMiniaturaAntigua);
			}
		}
		
		
		public static function Eliminar($idProfesional, $esAdmin, $editarRegistrosAjenos)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			
			if($idProfesional=="")
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed");
				return $res;
			}


			// comprobamos que el usuario tenga permiso para eliminarlo
			$consulta = "SELECT profesional_bloqueado, profesional_usuario_id FROM profesional WHERE profesional_id = '" . intval($idProfesional) . "' LIMIT 1";
		
			$datos    = $bd->Ejecutar($consulta);
			$fila     = $bd->ObtenerFila($datos);
		
			// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
			if((!$esAdmin && !$editarRegistrosAjenos) && ($fila['profesional_bloqueado'] == 1 || $fila['profesional_usuario_id'] != Usuario::IdUsuario() ) )
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("You dont have permission to edit this record");
				return $res;
			}
			
			// borramos las relaciones entre los documentos y el profesional
			$consulta = "DELETE FROM documentoprofesional WHERE documentoprofesional_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
			
			// borramos las relaciones entre el profesional y los documentos
			$consulta = "DELETE FROM profesionaldocumento WHERE profesionaldocumento_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
			
			// borramos las relaciones entre el profesional y las intervenciones
			$consulta = "DELETE FROM intervencionprofesional WHERE intervencionprofesional_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
			
			// borramos las relaciones entre el profesional y las obras
			$consulta = "DELETE FROM profesionalobra WHERE profesionalobra_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
			
			// borramos las relaciones entre el profesional y los cargos
			$consulta = "DELETE FROM cargo WHERE cargo_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);

			// borramos las relaciones entre el profesional y las formaciones
			$consulta = "DELETE FROM formacion WHERE formacion_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
			
			// borramos las relaciones entre el profesional y las asociaciones
			$consulta = "DELETE FROM asociacionprofesional WHERE asociacionprofesional_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
			
			// borramos las relaciones entre el profesional y las técnicas
			$consulta = "DELETE FROM tecnicaprofesional WHERE tecnicaprofesional_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
			
			// borramos las relaciones entre el profesional y los equipamientos
			$consulta = "DELETE FROM equipamientoprofesional WHERE equipamientoprofesional_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);

			// borramos las relaciones entre el profesional y las especialidades
			$consulta = "DELETE FROM especialidadprofesional WHERE especialidadprofesional_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);			
			
			// borramos las relaciones entre el profesional y los colaboradores
			$consulta = "DELETE FROM colaborador WHERE colaborador_profesional_id= '". $idProfesional ."' OR  colaborador_colaborador_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);			
			
			// borrar reconocimientos
			$consulta = "DELETE FROM reconocimiento WHERE reconocimiento_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);

			// borramos las relaciones entre el profesional y las bibliografias
			$consulta = "DELETE FROM profesionalbibliografia WHERE profesionalbibliografia_profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
			
			// finalmente borramos al profesional
			$consulta = "DELETE FROM profesional WHERE profesional_id= '". $idProfesional ."' ";
			$bd->Ejecutar($consulta);
		
			if( $bd->ObtenerErrores() == "" )
			{
				$res->exito = true;
				$res->mensaje = t("Add relations operation succeed");
				$res->datos = "";
				$res->errores = "";
			}
		
			if( $res->exito )
			{
				// si tiene una foto asociada la borramos
				Profesional::EliminarArchivo($idProfesional);
				
				$template = Plantilla::Smarty();
				
				// si está en caché lo vaciamos
				if($template->isCached(Profesional::$plantilla,$idProfesional)) {
					$template->clearCache(Profesional::$plantilla,$idProfesional);	
				}
			}		
		
			return $res;
		}
		
		public static function GenerarPlantillaLista($letra="todas",$tipo='Restaurador')
		{
			$profesionales = array();
			$template = Plantilla::Smarty();
			
			// comprobamos que es un caracter alfabético
			if(!ctype_alpha ( $letra )) $letra = "todas";
			else $letra = strtolower($letra);
			
			// si no está en caché asignamos los valores
			if(!$template->isCached(Profesional::$plantillaLista,$letra.$tipo)) 
			{
				
				$bd = BD::Instancia();				
				
				if($letra == "todas")
				{
					if($tipo == 'Autor') $consulta = "SELECT * FROM profesional INNER JOIN profesionalobra ON profesionalobra_profesional_id = profesional_id WHERE profesional_supervisado='1'  GROUP BY profesional_id order by profesional_apellido1, profesional_apellido2, profesional_nombre ASC";			
					else $consulta = "SELECT * FROM profesional WHERE profesional_tipo='".$tipo."' AND profesional_supervisado='1' order by profesional_apellido1, profesional_apellido2, profesional_nombre ASC";
				}
				else {
					if($tipo == 'Autor') $consulta = "SELECT * FROM profesional INNER JOIN profesionalobra ON profesionalobra_profesional_id = profesional_id WHERE profesional_supervisado='1' AND profesional_apellido1 REGEXP '^[".$letra."]' GROUP BY profesional_id order by profesional_apellido1, profesional_apellido2, profesional_nombre ASC "; 
					else $consulta = "SELECT * FROM profesional WHERE profesional_tipo='".$tipo."' AND profesional_supervisado='1' AND profesional_apellido1 REGEXP '^[".$letra."]' order by profesional_apellido1, profesional_apellido2, profesional_nombre ASC";
				}
				$profesionales = $bd->ObtenerResultados($consulta);
				
				
				$template->assign('profesionales', $profesionales);
				$template->assign('letra', $letra);
				$template->assign('tipo', $tipo);
				$template->assign('columnas', 4);
			}
			$template->display(Profesional::$plantillaLista,$letra.$tipo);
			
			return $profesionales;
		}
		
		public static function GenerarPlantilla($id=0)
		{
			$template = Plantilla::Smarty(); 

			// si no está en caché asignamos los valores
			if(!$template->isCached(Profesional::$plantilla,$id)) {
				
				$bd = BD::Instancia();
				$resultado = "";
				

				$consulta = "SELECT *, DATE_FORMAT(profesional_fechan, '".FORMATO_FECHA_MYSQL."') AS profesional_fechanacimiento,
				DATE_FORMAT(profesional_fechan, '%d') AS profesional_fechanacimiento_dia,
				DATE_FORMAT(profesional_fechan, '%m') AS profesional_fechanacimiento_mes,
				DATE_FORMAT(profesional_fechan, '%Y') AS profesional_fechanacimiento_anyo,
				DATE_FORMAT(profesional_fechad, '".FORMATO_FECHA_MYSQL."') AS profesional_fechadefuncion,
				DATE_FORMAT(profesional_fechad, '%d') AS profesional_fechadefuncion_dia,
				DATE_FORMAT(profesional_fechad, '%m') AS profesional_fechadefuncion_mes,
				DATE_FORMAT(profesional_fechad, '%Y') AS profesional_fechadefuncion_anyo
				FROM profesional WHERE profesional_id = '".$id."' LIMIT 1";		
				
				$datos = $bd->Ejecutar($consulta);
				$fila  = $bd->ObtenerFila($datos);
				
				// datos personales del profesional
				$template->assign('profesional', $fila);

				//documentos
				$template->assign('documentos',Documento::ListarDocumentosProfesional($id));
				
				//obras
				$template->assign('obras', Obra::ListarObrasProfesional($id) );
				
				//autor de las siguientes obras
				$template->assign('autoriaobras', Obra::ListarAutoriaObrasProfesional($id) );
				
				//colaboradores
				$template->assign('colaboradores',Colaborador::ListarColaboradoresProfesional($id));
				
				//formaciones
				$template->assign('formaciones',Formacion::ListarFormacionesProfesional($id));
				
				//cargos
				$template->assign('cargos',Cargo::ListarCargosProfesional($id));
				
				//reconocimientos
				$template->assign('reconocimientos',Reconocimiento::ListarReconocimientosProfesional($id));
				
				//especialidades
				$template->assign('especialidades',Especialidad::ListarEspecialidadesProfesional($id));
				
				//asociaciones
				$template->assign('asociaciones',Asociacion::ListarAsociacionesProfesional($id));
				
				//tecnicas
				$template->assign('tecnicas',Tecnica::ListarTecnicasProfesional($id));
				
				//equipamientos
				$template->assign('equipamientos',Equipamiento::ListarEquipamientosProfesional($id));
				
			}
			
			$template->display(Profesional::$plantilla,$id);
		
		}
		
		public function Listar($idUsuario, $tipo='Restaurador', $esAdmin=false, $editarRegistrosAjenos = false, $filtros=null,$start=null,$limit = null,$sort=null)
		{		
			$res            = new Comunicacion();
			$filtroBusqueda = "";
			
			$profesionalTipo = ($tipo == 'Todos' ) ? 'WHERE 1' : "WHERE profesional_tipo='".$tipo."'" ;
			
			// si no es administrador y puede ver los registros de otros, todos los que no sean suyos los marcamos como bloqueados para que no pueda editarlos
			$filtroRegistros = (!$esAdmin && !$editarRegistrosAjenos) ? "CASE WHEN profesional_usuario_id!='".$idUsuario."' THEN '1' ELSE profesional_bloqueado END AS profesional_bloqueado" : "profesional_bloqueado";

			$consulta = "SELECT P.*, usuario_login, usuario_nombre, usuario_apellido1, usuario_apellido2, DATE_FORMAT(profesional_fechan, '".FORMATO_FECHA_MYSQL."') AS profesional_fechan,
						 CASE WHEN profesional_archivo!='' THEN CONCAT( '".URL_ARCHIVOS."/',profesional_directorio,'/',profesional_archivo) 
						 ELSE '' END AS profesional_archivo, 
						 CASE WHEN profesional_miniatura!='' THEN CONCAT( '".URL_ARCHIVOS."/',profesional_directorio,'/miniaturas/',profesional_miniatura) 
						 ELSE '".URL_ARCHIVOS."/persona.png' END AS profesional_miniatura,			
			             DATE_FORMAT(profesional_fechad, '".FORMATO_FECHA_MYSQL."') AS profesional_fechad , ".$filtroRegistros." 
						 FROM profesional P LEFT JOIN usuario ON profesional_usuario_id = usuario_id ".$profesionalTipo." ".$filtroBusqueda;
			
			return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}
