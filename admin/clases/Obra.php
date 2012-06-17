<?php	
	class Obra extends ObjetoBD
	{			
		private static $plantilla      = "obra.htm";
		private static $plantillaLista = "obra_lista.htm";
		
		function Obra()
		{				
			$this->id    = "obra_id";
			$this->tabla = "obra";
								
			$this->exitoInsertar   = t("Job created successfully");
			$this->exitoActualizar = t("Job updated successfully");
			$this->errorInsertar   = t("Error creating Job");
			$this->errorActualizar = t("Error updating Job");
			$this->exitoListar     = t('Job list obtained successfully');
			$this->errorListar     = t('Error obtaining Job list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'obra_id'                    => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid award ID'),'valor'=>'','lectura'=>false),
				'obra_dimension_altura'      => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid idCentro'),'valor'=>'','lectura'=>false),
				'obra_dimension_anchura'     => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid idCentro'),'valor'=>'','lectura'=>false),
				'obra_dimension_profundidad' => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid idCentro'),'valor'=>'','lectura'=>false),
				'obra_pais'                  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
				'obra_nombre'                => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
				'obra_provincia'             => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
				'obra_poblacion'             => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
				'obra_direccion'             => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
				'obra_cordenadas'		     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid coords'),'valor'=>'','lectura'=>false),
				'obra_fecha1'                => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false),
				'obra_detalles'              => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'obra_supervisado'           => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid supervised'),'valor'=>null,'lectura'=>false),
				'obra_usuario_id'            => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid creator'),'valor'=>null,'lectura'=>false),
				'obra_ultimamod'             => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid last modification'),'valor'=>'','lectura'=>true),
				'obra_bloqueado'             => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid bloqued'),'valor'=>null,'lectura'=>false)
			);

			$this->relaciones = array(
			
				'profesional' => array (
					'tabla'       => 'profesionalobra',
					'relacion'	  => 'MaN',
					'claveAjena1' => 'profesionalobra_obra_id',
					'claveAjena2' => 'profesionalobra_profesional_id',
					'soloLectura' => false,
					'campos'      => array(		
						'profesionalobra_obra_id' 			=> array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid interv id'),'valor'=>null,'lectura'=>false),
						'profesionalobra_profesional_id'	=> array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid professional ID'),'valor'=>null,'lectura'=>false),
						'profesionalobra_detalles'			=> array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
						'profesional_nombre'				=> array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>true),
						'profesional_apellido1'				=> array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname1'),'valor'=>null,'lectura'=>true),
						'profesional_apellido2'				=> array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname2'),'valor'=>'','lectura'=>true)
					)
				),
				'usuario' => array (
					'tabla'       => 'usuario',
					'relacion'    => '1a1',
					'soloLectura' => true,
					'clavePrincipal' => 'obra_usuario_id',
					'claveAjena1' => 'usuario_id',
					'claveAjena2' => '',
					'campos'      => array(
						'usuario_id'         => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_nombre'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido1'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido2'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_login'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true)
					)
				),
				'ubicacion' => array (
					'tabla'       => 'ubicacion',
					'relacion'    => '1a1',
					'soloLectura' => true,
					'claveAjena1' => 'ubicacion_id',
					'claveAjena2' => '',
					'campos'      => array(
						'ubicacion_nombre'           => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>true),
						'ubicacion_pais'             => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid country'),'valor'=>null,'lectura'=>true),
						'ubicacion_provincia'        => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid province'),'valor'=>null,'lectura'=>true),
						'ubicacion_poblacion'        => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid city'),'valor'=>null,'lectura'=>true),
						'ubicacion_direccion'        => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid address'),'valor'=>null,'lectura'=>true)
					)
				)
			);			
		}
		
		public function Guardar($esAdmin, $editarRegistrosAjenos)
		{		
			$bd 		= BD::Instancia();
			$consulta 	= "";
			$res        = new Comunicacion();

			// leemos los datos json
			parent::Leer();
			
			// si no es un registro nuevo comprobamos que el usuario tenga permiso para actualizarlo
			if($this->campos['obra_id']['valor']!="")
			{
				$consulta = "SELECT obra_bloqueado, obra_usuario_id FROM obra WHERE obra_id = '" . intval($this->campos['obra_id']['valor']) . "' LIMIT 1";
			
				$datos    = $bd->Ejecutar($consulta);
				$fila     = $bd->ObtenerFila($datos);
			
				// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
				if( (!$esAdmin && !$editarRegistrosAjenos) && ($fila['obra_bloqueado'] == 1 || $fila['obra_usuario_id'] != Usuario::IdUsuario() ) )
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
				$this->campos['obra_bloqueado']['valor'] = 0;
				$this->campos['obra_supervisado']['valor'] = 0;
			}
				
			// si no ha especificado un usuario en concreto, el creador del registro será el usuario identificado
			if($this->campos['obra_usuario_id']['valor'] == '')
			{
				$this->campos['obra_usuario_id']['valor'] = Usuario::IdUsuario();
			}
			
			// itentamos guardar
			$res = parent::Guardar(true,false);
			
			return $res;
		}
		
		public static function Eliminar($idobra, $esAdmin, $editarRegistrosAjenos)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			
			
			if($idobra=="")
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed");
				return $res;
			}
			
			// si no es administrador y está intentando eliminar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
			if((!$esAdmin && !$editarRegistrosAjenos) && ($fila['obra_bloqueado'] == 1 || $fila['obra_usuario_id'] != Usuario::IdUsuario() ) )
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("You dont have permission to edit this record");
				return $res;
			}
			
			// si está relacionado con la tabla intervencion no le dejamos eliminar
			$consulta = "SELECT COUNT(*) FROM intervencion WHERE intervencion_obra_id= '". $idobra ."'";
			if($bd->ContarFilas($consulta) > 0)
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed, it exists relations with intervencion");
				return $res;
			}
			
			$consulta = "DELETE FROM obra WHERE obra_id= '". $idobra ."'";
			$bd->Ejecutar($consulta);
			
			if( $bd->ObtenerErrores() == "" )
			{
				$consulta = "DELETE FROM documentoobra WHERE documentoobra_obra_id= '". $idobra ."'";
				$bd->Ejecutar($consulta);
				
				$consulta = "DELETE FROM metodoobra WHERE metodoobra_obra_id= '". $idobra ."'";
				$bd->Ejecutar($consulta);
				
				$consulta = "DELETE FROM ubicacionobra WHERE ubicacionobra_obra_id= '". $idobra ."'";
				$bd->Ejecutar($consulta);
				
				$consulta = "DELETE FROM profesionalobra WHERE profesionalobra_obra_id= '". $idobra ."'";
				$bd->Ejecutar($consulta);
			
			
				$res->exito = true;
				$res->mensaje = t("Success");
				$res->errores = t("Delete operation succeed");
			}
			else
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed");
			}

			return $res;
		}
		
		public static function ListarObrasProfesional($idProfesional=null)
		{
			$obras     = array();
			$bd        = BD::Instancia();			
			$consulta  = "SELECT * FROM obra 
						  INNER JOIN intervencion ON obra_id=intervencion_obra_id 
						  INNER JOIN intervencionprofesional ON intervencion_id = intervencionprofesional_intervencion_id 
						  LEFT JOIN profesionalobra ON profesionalobra_obra_id = obra_id
						  LEFT JOIN profesional ON profesionalobra_profesional_id = profesional_id
						  WHERE intervencionprofesional_profesional_id = '".intval($idProfesional)."' GROUP BY obra_id ORDER BY obra_nombre DESC";
			
			$datos = $bd->Ejecutar($consulta);
			if( $bd->ObtenerErrores() == '' )
			{	
				// obtenemos una lista con todos los registros
				while($fila = $bd->ObtenerFila($datos))
				{
					array_push($obras, $fila); 
				}
			}		
			return $obras;
		}
		
		public static function ListarObrasUbicacion($idUbicacion=null)
		{
			$obras     = array();
			$bd        = BD::Instancia();			
			$consulta  = "SELECT * FROM obra 
						  INNER JOIN ubicacionobra ON ubicacionobra_obra_id = obra_id
						  INNER JOIN ubicacion ON ubicacionobra_ubicacion_id = '".intval($idUbicacion)."'
						  GROUP BY obra_id ORDER BY obra_nombre DESC";
			
			$datos = $bd->Ejecutar($consulta);
			if( $bd->ObtenerErrores() == '' )
			{	
				// obtenemos una lista con todos los registros
				while($fila = $bd->ObtenerFila($datos))
				{
					array_push($obras, $fila); 
				}
			}		
			return $obras;
		}
		
		public static function GenerarPlantillaLista($letra="todas")
		{
			$bd = BD::Instancia();
			$obras = array();
			$template = Plantilla::Smarty();
			
			// comprobamos que es un caracter alfabético
			if(!ctype_alpha ( $letra )) $letra = "todas";
			else $letra = strtolower($letra);
			
			
			if( $letra == "todas" )
			{
				$consulta = "SELECT * FROM obra	
					LEFT JOIN profesionalobra ON profesionalobra_obra_id = obra_id
					LEFT JOIN profesional ON profesionalobra_profesional_id = profesional_id
					order by obra_nombre ASC";
			}else{
				$consulta = "SELECT * FROM obra	
					LEFT JOIN profesionalobra ON profesionalobra_obra_id = obra_id
					LEFT JOIN profesional ON profesionalobra_profesional_id = profesional_id
					WHERE obra_nombre REGEXP '^[".$letra."]' 	
					order by obra_nombre ASC";
			
			}
			
			$datos = $bd->Ejecutar($consulta);
			if( $bd->ObtenerErrores() == '' )
			{	
				// obtenemos una lista con todos los registros
				while($fila = $bd->ObtenerFila($datos))
				{
					array_push($obras, $fila); 
				}
			}
			
			// si no está en caché asignamos los valores
			if(!$template->isCached(Obra::$plantillaLista,$letra)) {
				$template->assign('obras', $obras);
				$template->assign('columnas', 4);
				$template->assign('letra', $letra);
			}
			$template->display(Obra::$plantillaLista,$letra);
		}
		
		public static function GenerarPlantilla($id=0)
		{
			$template = Plantilla::Smarty(); 
			
			$bd = BD::Instancia();
			$resultado = "";

			$consulta = "SELECT *, DATE_FORMAT(obra_fecha1, '".FORMATO_FECHA_MYSQL."') AS obra_fecha1		
			             FROM obra 
						 LEFT JOIN ubicacionobra ON ubicacionobra_obra_id = obra_id
						 LEFT JOIN ubicacion ON ubicacionobra_ubicacion_id = ubicacion_id
						 LEFT JOIN profesionalobra ON profesionalobra_obra_id = obra_id
						 LEFT JOIN profesional ON profesionalobra_profesional_id = profesional_id 
						 where obra_id = '".$id."' LIMIT 1";			
			
			$datos = $bd->Ejecutar($consulta);
			$fila  = $bd->ObtenerFila($datos);

			
			
			$consulta = "SELECT * FROM intervencion 
						 WHERE intervencion_obra_id='".$id."'";			 
			
			$datos = $bd->Ejecutar($consulta);
			
			$intervenciones = array();
			if( $bd->ObtenerErrores() == '' )
			{	
				// obtenemos una lista con todos los registros
				while($row = $bd->ObtenerFila($datos))
				{
					$profesionales = array();
					$consulta = "SELECT * FROM profesional WHERE profesional_id IN (SELECT intervencionprofesional_profesional_id FROM intervencionprofesional WHERE intervencionprofesional_intervencion_id = '".$row['intervencion_id']."' )";
					
					$datos2 = $bd->Ejecutar($consulta);
					while($pros = $bd->ObtenerFila($datos2))
					{
						array_push($profesionales, $pros);
					}
					
					$row['profesionales'] = $profesionales;
					$intervenciones[] = $row;
				}		
			}

			// si no está en caché asignamos los valores
			if(!$template->isCached(Obra::$plantilla,$id)) {
				
				// datos de la obra
				$template->assign('obra', $fila);
				$template->assign('intervenciones', $intervenciones);
			}
			
			$template->display(Obra::$plantilla,$id);	
		}
		
		public function Listar($idUsuario,$idObra="",$esAdmin=false, $editarRegistrosAjenos = false, $filtros=null,$start=null,$limit = null, $sort=null)
		{		
			$res = new Comunicacion();
			
			// si no es administrador y puede ver los registros de otros, todos los que no sean suyos los marcamos como bloqueados para que no pueda editarlos
			$filtroRegistros = (!$esAdmin && !$editarRegistrosAjenos) ? "CASE WHEN obra_usuario_id!='".$idUsuario."' THEN 1 ELSE obra_bloqueado END AS obra_bloqueado" : " obra_bloqueado";
			
			$consulta =" SELECT O.*, UO.*, U.*,PO.*,P.profesional_nombre,P.profesional_apellido1,P.profesional_apellido2, usuario_nombre, usuario_apellido1, usuario_login,usuario_apellido2, ".$filtroRegistros.",DATE_FORMAT(obra_fecha1, '%Y') AS obra_fecha1,obra_fecha1 AS sortobra_fecha1
						FROM obra O LEFT JOIN usuario ON usuario_id=obra_usuario_id
						LEFT JOIN ubicacionobra UO ON obra_id = ubicacionobra_obra_id
						LEFT JOIN ubicacion U ON ubicacionobra_ubicacion_id = ubicacion_id
						LEFT JOIN profesionalobra PO ON obra_id = profesionalobra_obra_id
						LEFT JOIN profesional P ON profesionalobra_profesional_id = profesional_id
						WHERE 1 ";	
			
			return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
		
		public function ListarProfesionalObra($idObra,$filtros=null,$start=null,$limit = null, $sort=null)
		{		
			$res = new Comunicacion();
	
			$consulta = "SELECT * 
				             FROM profesionalobra LEFT JOIN profesional ON profesional_id=profesionalobra_profesional_id 
							 WHERE profesionalobra_obra_id='".intval($idObra)."'";	
			
			if( $idObra == "" )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}