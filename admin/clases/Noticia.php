<?php	
	class Noticia extends ObjetoBD
	{			
		private static $plantilla = "noticias.htm";
		
		function Noticia()
		{				
			$this->id        = "noticia_id";
			$this->tabla     = "noticia";
								
			$this->exitoInsertar   = t("Article created successfully");
			$this->exitoActualizar = t("Article updated successfully");
			$this->errorInsertar   = t("Error creating Article");
			$this->errorActualizar = t("Error updating Article");
			$this->exitoListar     = t('Article list obtained successfully');
			$this->errorListar     = t('Error obtaining Article list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'noticia_id'         => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid award ID'),'valor'=>'','lectura'=>false),
				'noticia_usuario_id' => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid id User'),'valor'=>null,'lectura'=>false),
				'noticia_titulo'     => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'noticia_cuerpo'     => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'noticia_publicada'  => array('tipo'=>'checkbox','nulo'=>true,'msg'=>t('Invalid published'),'valor'=>'','lectura'=>false),
				'noticia_portada'    => array('tipo'=>'checkbox','nulo'=>true,'msg'=>t('Invalid front'),'valor'=>'','lectura'=>false),
				'noticia_fecha'      => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false),
				'noticia_ultimamod'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true)
			);

			$this->relaciones = array(
			
				'usuario' => array (
					'tabla'         => 'usuario',
					'relacion'      => '1a1',
					'clavePrimaria' => 'noticia_usuario_id',
					'claveAjena1'   => 'usuario_id',
					'claveAjena2'   => '',
					'soloLectura' => true,
					'campos'        => array(
						'usuario_id'         => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_nombre'     => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido1'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_apellido2'  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true),
						'usuario_login'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>true)
					)
				)
			);	
		}
		
		public function Guardar()
		{		
			$bd 		= BD::Instancia();
			$consulta 	= "";
			$res        = new Comunicacion();
			$relaciones = "";
			
			// leemos los datos json
			parent::Leer();
			
			// si no ha especificado un usuario en concreto, el creador del registro será el usuario identificado
			if($this->campos['noticia_usuario_id']['valor'] == '')
			{
				$this->campos['noticia_usuario_id']['valor'] = Usuario::IdUsuario();
			}
			
			// itentamos guardar
			$res = parent::Guardar(true,false,false);
			
			// si ha ido bien borramos el cache
			if( $res->exito )
			{
				$template = Plantilla::Smarty();
				$template->clearCache(Noticia::$plantilla);
			}
			
			return $res->Json();
		}
		
		public function EliminarDato($idnoticia)
		{
			$res = parent::EliminarDato($idnoticia);

			if($res->exito)
			{		
				// si ha ido bien borramos el cache
				$template = Plantilla::Smarty();
				$template->clearCache(Noticia::$plantilla, null);
			}
			else
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed");
			}

			return $res;
		}
		
		public static function ListarNoticias($numPagina=1, $resultadosPorPag = 2,$url='?')
		{
			$template = Plantilla::Smarty(); 
			
			$bd = BD::Instancia();
			$noticias = array();
			
			$start = 0;
			$limit = $resultadosPorPag;
			
			if($numPagina > 1)
			{
				$start = ( ($numPagina -1)*$resultadosPorPag );
			}
			
			$consulta = "SELECT noticia_titulo, DATE_FORMAT(noticia_fecha, '".FORMATO_FECHA_MYSQL."') AS noticia_fechapub, noticia_cuerpo FROM noticia where noticia_publicada = '1' ORDER BY noticia_fecha DESC LIMIT ".$start.",".$limit."";			
			
			$datos = $bd->Ejecutar($consulta);
			if( $bd->ObtenerErrores() == '' )
			{	
				// obtenemos una lista con todos los registros
				while($fila = $bd->ObtenerFila($datos))
				{
					array_push($noticias, $fila); 
				}
			}
			
			// agregamos el paginador
			$paginador = parent::ConstruirPaginador("SELECT COUNT(*) FROM noticia WHERE noticia_publicada='1'", $numPagina, $resultadosPorPag, $url );
			
			// si no está en caché asignamos los valores
			if(!$template->isCached(Noticia::$plantilla,$numPagina)) {
				$template->assign('noticias', $noticias);
				$template->assign('paginador', $paginador);		
			}
			
			$template->display(Noticia::$plantilla,$numPagina);
		}
		
		public function Listar($filtros=null,$start=null,$limit = null,$sort=null)
		{		
			$res = new Comunicacion();
			
			$consulta = "SELECT N.*, DATE_FORMAT(noticia_fecha, '".FORMATO_FECHA_MYSQL."') AS noticia_fecha, usuario_nombre, usuario_apellido1, usuario_apellido2, usuario_login, noticia_fecha AS sortnoticia_fecha 
			             FROM noticia N LEFT JOIN usuario ON noticia_usuario_id = usuario_id WHERE 1 ";
			
			return parent::Listar($consulta, false, $filtros,$start,$limit,$sort);
		}
	}
