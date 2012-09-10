<?php	
	class Intervencion extends ObjetoBD
	{			
		function Intervencion()
		{				
			$this->id    = "intervencion_id";
			$this->tabla = "intervencion";
								
			$this->exitoInsertar   = t("Intervencion created successfully");
			$this->exitoActualizar = t("Intervencion updated successfully");
			$this->errorInsertar   = t("Error creating Intervencion");
			$this->errorActualizar = t("Error updating Intervencion");
			$this->exitoListar     = t('Intervencion list obtained successfully');
			$this->errorListar     = t('Error obtaining Intervencion list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'intervencion_id'          => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid intervencion ID'),'valor'=>'','lectura'=>false),
				'intervencion_obra_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid intervencion ID'),'valor'=>null,'lectura'=>false),
				'obra_nombre'              => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid nombreObra'),'valor'=>'','lectura'=>true),
				'intervencion_nombre'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid intervencion name'),'valor'=>'','lectura'=>false),
				'intervencion_estadoconservacion' => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid intervencion name'),'valor'=>'','lectura'=>false),
				'intervencion_descprocedimiento' => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid intervencion name'),'valor'=>'','lectura'=>false),
				'intervencion_principiosteoricos' => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid intervencion name'),'valor'=>'','lectura'=>false),
				'intervencion_fechainicio' => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false),
				'intervencion_actualmente' => array('tipo'=>'checkbox','nulo'=>true,'msg'=>t('Invalid actualmente'),'valor'=>'','lectura'=>false),
				'intervencion_fechafin'    => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false),
				'intervencion_detalles'    => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'intervencion_ultimamod'   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid last modification'),'valor'=>'','lectura'=>true),
				'intervencion_supervisado' => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid supervised'),'valor'=>null,'lectura'=>false),
				'intervencion_usuario_id'  => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid creator'),'valor'=>null,'lectura'=>false),
				'intervencion_ultimamod'   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid last modification'),'valor'=>'','lectura'=>true),
				'intervencion_bloqueado'   => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid bloqued'),'valor'=>null,'lectura'=>false)
			);
			
			$this->relaciones = array(
			
				'profesional' => array (
					'tabla'       => 'intervencionprofesional',
					'relacion'	  => 'MaN',
					'claveAjena1' => 'intervencionprofesional_intervencion_id',
					'claveAjena2' => 'intervencionprofesional_profesional_id',
					'soloLectura' => false,
					'campos'      => array(		
						'intervencionprofesional_intervencion_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid interv id'),'valor'=>null,'lectura'=>false),
						'intervencionprofesional_profesional_id'  => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid professional ID'),'valor'=>null,'lectura'=>false),
						'intervencionprofesional_detalles'        => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
						'intervencionprofesional_cargo'           => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid job'),'valor'=>'','lectura'=>false),
						'profesional_nombre'       				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>true),
						'profesional_apellido1'    				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname1'),'valor'=>null,'lectura'=>true),
						'profesional_apellido2'    				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname2'),'valor'=>'','lectura'=>true),
						'profesional_tipo'         				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid pr type'),'valor'=>'','lectura'=>true)
					)
				),
				'documento' => array (
					'tabla'       => 'documentointervencion',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'documentointervencion_intervencion_id',
					'claveAjena2' => 'documentointervencion_documento_id',
					'campos'      => array(
						'documentointervencion_documento_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid documentointervencion_documento_id'),'valor'=>'','lectura'=>false),
						'documentointervencion_intervencion_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'documentointervencion_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				),
				'material' => array (
					'tabla'       => 'materialintervencion',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'materialintervencion_intervencion_id',
					'claveAjena2' => 'materialintervencion_documento_id',
					'campos'      => array(
						'materialintervencion_documento_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid materialintervencion_documento_id'),'valor'=>'','lectura'=>false),
						'materialintervencion_intervencion_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idintervencion'),'valor'=>'','lectura'=>false),
						'materialintervencion_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				),
				'procedimiento' => array (
					'tabla'       => 'procedimientointervencion',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'procedimientointervencion_intervencion_id',
					'claveAjena2' => 'procedimientointervencion_documento_id',
					'campos'      => array(
						'procedimientointervencion_documento_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid materialintervencion_documento_id'),'valor'=>'','lectura'=>false),
						'procedimientointervencion_intervencion_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idintervencion'),'valor'=>'','lectura'=>false),
						'procedimientointervencion_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				),
				'usuario' => array (
					'tabla'       => 'usuario',
					'relacion'    => '1a1',
					'clavePrincipal' => 'intervencion_usuario_id',
					'claveAjena1' => 'usuario_id',
					'claveAjena2' => '',
					'soloLectura' => true,
					'campos'      => array(
						'usuario_id'         => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_nombre'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido1'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido2'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_login'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true)
					)
				)
			);	
		}
		
		public function EliminarDato($idIntervencion, $esAdmin, $editarRegistrosAjenos )
		{			
			$bd       = BD::Instancia();
			$res      = new Comunicacion();
			// comprobamos que el usuario tenga permiso para eliminarlo
			$consulta = "SELECT intervencion_bloqueado, intervencion_usuario_id FROM intervencion WHERE intervencion_id = '" . intval($idIntervencion) . "' LIMIT 1";
		
			$datos    = $bd->Ejecutar($consulta);
			$fila     = $bd->ObtenerFila($datos);
		
			// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
			if((!$esAdmin && !$editarRegistrosAjenos) && ($fila['intervencion_bloqueado'] == 1 || $fila['intervencion_usuario_id'] != Usuario::IdUsuario() ) )
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("You dont have permission to edit this record");
				return $res;
			}
			return parent::EliminarDato($idIntervencion);
		}
		
		public function Guardar($esAdmin, $editarRegistrosAjenos)
		{		
			$bd 		= BD::Instancia();
			$consulta 	= "";
			$res        = new Comunicacion();
			$relaciones = "";
			
			// leemos los datos json
			parent::Leer();
			
			if ( !is_numeric($this->campos['intervencion_fechafin']['valor'])  && $this->campos['intervencion_fechafin']['valor']!= "" )
			{
				$this->campos['intervencion_actualmente']['valor'] = 1;
				$this->campos['intervencion_fechafin']['valor'] = null;
			}
			else
			{
				$this->campos['intervencion_actualmente']['valor'] = 0;
			}
			
			// si no es un registro nuevo comprobamos que el usuario tenga permiso para actualizarlo
			if($this->campos['intervencion_id']['valor']!="")
			{
				$consulta = "SELECT intervencion_bloqueado, intervencion_usuario_id FROM intervencion WHERE intervencion_id = '" . intval($this->campos['intervencion_id']['valor']) . "' LIMIT 1";
			
				$datos    = $bd->Ejecutar($consulta);
				$fila     = $bd->ObtenerFila($datos);
			
				// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
				if( (!$esAdmin  && !$editarRegistrosAjenos) && ($fila['intervencion_bloqueado'] == 1 || $fila['intervencion_usuario_id'] != Usuario::IdUsuario() ) )
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
				$this->campos['intervencion_bloqueado']['valor'] = 0;
				$this->campos['intervencion_supervisado']['valor'] = 0;
			}
					
			// si no ha especificado un usuario en concreto, el creador del registro será el usuario identificado
			if($this->campos['intervencion_usuario_id']['valor'] == '')
			{
				$this->campos['intervencion_usuario_id']['valor'] = Usuario::IdUsuario();
			}
			
			
			// itentamos guardar
			$res = parent::Guardar(true,false);
			
			return $res;
		}
		
		public function Listar($idUsuario, $esAdmin=false, $editarRegistrosAjenos = false, $filtros=null,$start=null,$limit = null, $sort = null)
		{		
			$res = new Comunicacion();
			$filtroBusqueda = "";
			
			// si no es administrador y puede ver los registros de otros, todos los que no sean suyos los marcamos como bloqueados para que no pueda editarlos
			$filtroRegistros = (!$editarRegistrosAjenos && !$esAdmin) ? "CASE WHEN intervencion_usuario_id!='".$idUsuario."' THEN 1 ELSE intervencion_bloqueado END AS intervencion_bloqueado" : " intervencion_bloqueado";
			
			if($limit != 0) $limites = "LIMIT ".$start.",".$limit;
			
			$consulta = " SELECT I.*, O.*, usuario_nombre, usuario_apellido1, usuario_login,usuario_apellido2, intervencion_usuario_id, 
					    ".$filtroRegistros.", DATE_FORMAT(intervencion_fechainicio, '".FORMATO_FECHA_MYSQL_ANYO."') AS intervencion_fechainicio,DATE_FORMAT(intervencion_fechafin, '".FORMATO_FECHA_MYSQL_ANYO."') AS intervencion_fechafin, intervencion_fechafin AS sortintervencion_fechafin,intervencion_actualmente, intervencion_fechainicio AS sortintervencion_fechainicio  
						  FROM intervencion I LEFT JOIN usuario ON usuario_id=intervencion_usuario_id  LEFT JOIN obra O ON obra_id = intervencion_obra_id WHERE 1 ".$filtroBusqueda;	
				
			return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
		
		public function ListarIntervencionProfesional($idIntervencion,$norelacionadas=false,$filtros=null,$start=null,$limit = null, $sort=null)
		{		
			$res = new Comunicacion();

			
			if(!$norelacionadas)
			{				
				$consulta = "SELECT IP.*, P.* 
				             FROM intervencionprofesional IP LEFT JOIN profesional P ON profesional_id=intervencionprofesional_profesional_id 
							 WHERE intervencionprofesional_intervencion_id='".intval($idIntervencion)."'";	
			}
			else
			{
				$consulta = "SELECT P.*, profesional_id AS intervencionprofesional_profesional_id, CASE WHEN profesional_id IS NOT NULL THEN '".$idIntervencion."' END AS intervencionprofesional_intervencion_id 
				             FROM profesional P 
				             WHERE  profesional_id 
							 NOT IN (SELECT intervencionprofesional_profesional_id FROM intervencionprofesional WHERE intervencionprofesional_intervencion_id ='".intval($idIntervencion)."' )";
			}
			
			if( $idIntervencion == "" && $norelacionadas == false )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}
