<?php	
	class Bibliografia extends ObjetoBD
	{			
		function Bibliografia()
		{				
			$this->id    = "bibliografia_id";
			$this->tabla = "bibliografia";
								
			$this->exitoInsertar   = t("Bibliografia created successfully");
			$this->exitoActualizar = t("Bibliografia updated successfully");
			$this->errorInsertar   = t("Error creating Bibliografia");
			$this->errorActualizar = t("Error updating Bibliografia");
			$this->exitoListar     = t('Bibliografia list obtained successfully');
			$this->errorListar     = t('Error obtaining Bibliografia list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'bibliografia_id'          => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid bibliografia ID'),'valor'=>'','lectura'=>false),
				'bibliografia_id'           => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid bibliography ID'),'valor'=>'','lectura'=>false),
				'bibliografia_titulo'       => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid title'),'valor'=>null,'lectura'=>false),
				'bibliografia_fechaedicion' => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid edition date'),'valor'=>'','lectura'=>false),
				'bibliografia_editorial'    => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid editorial'),'valor'=>'','lectura'=>false),
				'bibliografia_isbn'         => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid isbn'),'valor'=>null,'lectura'=>false),
				'bibliografia_detalles'     => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'bibliografia_categorias'   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid category'),'valor'=>'','lectura'=>false),
				'bibliografia_ultimamod'   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid last modification'),'valor'=>'','lectura'=>true),
				'bibliografia_supervisado' => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid supervised'),'valor'=>null,'lectura'=>false),
				'bibliografia_usuario_id'  => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid creator'),'valor'=>null,'lectura'=>false),
				'bibliografia_ultimamod'   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid last modification'),'valor'=>'','lectura'=>true),
				'bibliografia_bloqueado'   => array('tipo'=>'checkbox','nulo'=>false,'msg'=>t('Invalid bloqued'),'valor'=>null,'lectura'=>false)
			);
			
			$this->relaciones = array(
			
				'profesional' => array (
					'tabla'       => 'profesionalbibliografia',
					'relacion'	  => 'MaM',
					'claveAjena1' => 'profesionalbibliografia_id',
					'claveBorrado'=> 'profesionalbibliografia_bibliografia_id',
					'soloLectura' => false,
					'campos'      => array(		
						'profesionalbibliografia_id'              => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid profesionalbibliografia_id'),'valor'=>null,'lectura'=>false),
						'profesionalbibliografia_bibliografia_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid interv id'),'valor'=>null,'lectura'=>false),
						'profesionalbibliografia_profesional_id'  => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid professional ID'),'valor'=>null,'lectura'=>false),
						'profesionalbibliografia_detalles'        => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
						'profesionalbibliografia_tiporelacion'    => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid job'),'valor'=>'','lectura'=>false),
						'profesional_nombre'       				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>true),
						'profesional_apellido1'    				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname1'),'valor'=>null,'lectura'=>true),
						'profesional_apellido2'    				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname2'),'valor'=>'','lectura'=>true),
						'profesional_tipo'         				  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid pr type'),'valor'=>'','lectura'=>true)
					)
				),
				'usuario' => array (
					'tabla'       => 'usuario',
					'relacion'    => '1a1',
					'clavePrincipal' => 'obra_usuario_id',
					'claveAjena1' => 'usuario_id',
					'soloLectura' => true,
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
		}
		
		public function EliminarDato($idBibliografia, $esAdmin, $editarRegistrosAjenos )
		{			
			$bd       = BD::Instancia();
			$res      = new Comunicacion();
			
			// comprobamos que el usuario tenga permiso para eliminarlo
			$consulta = "SELECT bibliografia_bloqueado, bibliografia_usuario_id FROM bibliografia WHERE bibliografia_id = '" . intval($idBibliografia) . "' LIMIT 1";
		
			$datos    = $bd->Ejecutar($consulta);
			$fila     = $bd->ObtenerFila($datos);
		
			// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
			if((!$esAdmin && !$editarRegistrosAjenos) && ($fila['bibliografia_bloqueado'] == 1 || $fila['bibliografia_usuario_id'] != Usuario::IdUsuario() ) )
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("You dont have permission to edit this record");
				return $res;
			}
			return parent::EliminarDato($idBibliografia);
		}
		
		public function Guardar($esAdmin, $editarRegistrosAjenos)
		{		
			$bd 		= BD::Instancia();
			$consulta 	= "";
			$res        = new Comunicacion();
			$relaciones = "";
			
			// leemos los datos json
			parent::Leer();
			
			// comprobamos que el login no esté repetido
			$res = $this->ComprobarIsbnUnico($this->campos['bibliografia_isbn']['valor'],$this->campos['bibliografia_id']['valor']);
			if(!$res->exito)return $res;
			
			// si no es un registro nuevo comprobamos que el usuario tenga permiso para actualizarlo
			if($this->campos['bibliografia_id']['valor']!="")
			{
				$consulta = "SELECT bibliografia_bloqueado, bibliografia_usuario_id FROM bibliografia WHERE bibliografia_id = '" . intval($this->campos['bibliografia_id']['valor']) . "' LIMIT 1";
			
				$datos    = $bd->Ejecutar($consulta);
				$fila     = $bd->ObtenerFila($datos);
			
				// si no es administrador y está intentando editar un registro que se encontraba bloqueado o que no ha sido creado por él, se lo impedimos
				if((!$esAdmin && !$editarRegistrosAjenos ) && ($fila['bibliografia_bloqueado'] == 1 || $fila['bibliografia_usuario_id'] != Usuario::IdUsuario() ) )
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
				$this->campos['bibliografia_bloqueado']['valor'] = 0;
				$this->campos['bibliografia_supervisado']['valor'] = 0;
			}
					
			// si no ha especificado un usuario en concreto, el creador del registro será el usuario identificado
			if($this->campos['bibliografia_usuario_id']['valor'] == '')
			{
				$this->campos['bibliografia_usuario_id']['valor'] = Usuario::IdUsuario();
			}
			
			
			// itentamos guardar
			$res = parent::Guardar(true, false);
			
			return $res;
		}
		
		// comprueba que el login sea unico
		public function ComprobarIsbnUnico($isbn, $idBibliografia)
		{
			return ObjetoBD::ComprobarUnico("bibliografia_isbn", $isbn, $this->tabla,$this->id,$idBibliografia);			
		}
		
		public function Listar($idUsuario, $esAdmin=false,$editarRegistrosAjenos, $filtros="",$start=0,$limit = SELECT_LIMIT, $sort)
		{		
			$res = new Comunicacion();
			$bd 		= BD::Instancia();
			$filtroBusqueda = "";
			
			// si no es administrador y puede ver los registros de otros, todos los que no sean suyos los marcamos como bloqueados para que no pueda editarlos
			$filtroRegistros = (!$esAdmin && !$editarRegistrosAjenos) ? "CASE WHEN bibliografia_usuario_id!='".$idUsuario."' THEN 1 ELSE bibliografia_bloqueado END AS bibliografia_bloqueado" : " bibliografia_bloqueado";
			
			if($limit != 0) $limites = "LIMIT ".$start.",".$limit;
			
			$consulta = " SELECT b.*, usuario_nombre, usuario_apellido1, usuario_login,usuario_apellido2, 
					    ".$filtroRegistros.", DATE_FORMAT(bibliografia_fechaedicion, '".FORMATO_FECHA_MYSQL_ANYO."') AS bibliografia_fechaedicion FROM bibliografia b 
						  LEFT JOIN usuario ON usuario_id=bibliografia_usuario_id 
						  LEFT JOIN profesionalbibliografia ON bibliografia_id = profesionalbibliografia_bibliografia_id
						  LEFT JOIN profesional P ON profesionalbibliografia_profesional_id = profesional_id WHERE 1 ".$filtroBusqueda;	
			
					
			//return parent::Listar($consulta,false, $filtros, $start, $limit, $sort);
			$res = parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
			
			$numFilas = count($res->datos);
			
			for($i = 0; $i< $numFilas ; $i++)
			{
				$consulta = "SELECT * FROM profesional LEFT JOIN profesionalbibliografia ON profesionalbibliografia_profesional_id = profesional_id WHERE profesionalbibliografia_bibliografia_id = ".$res->datos[$i]['bibliografia_id']." ORDER BY profesional_nombre, profesional_apellido1";
				$res->datos[$i]['profesionales']= $bd->ObtenerResultados($consulta);
			}
			return $res;
		}
		
		public function ListarProfesionalBibliografia($idBibliografia,$filtros=null,$start=null,$limit = null, $sort=null)
		{		
			$res = new Comunicacion();
	
			$consulta = "SELECT * 
				             FROM profesionalbibliografia LEFT JOIN profesional ON profesional_id=profesionalbibliografia_profesional_id 
							 WHERE profesionalbibliografia_bibliografia_id='".intval($idBibliografia)."'";		
			
			if($idBibliografia == "") 
			{
				$res->exito = false;
				$res->mensaje = t('Error');
				$res->errores = t('Invalid ID');
				return $res;
			}
			else
			{
				return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
			}
		}
	}
