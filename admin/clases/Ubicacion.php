<?php	
	class Ubicacion extends ObjetoBD
	{			
		private static $plantilla      = "ubicacion.htm";
		private static $plantillaLista = "ubicacion_lista.htm";
		
		function Ubicacion()
		{				
			$this->id    = "ubicacion_id";
			$this->tabla = "ubicacion";
								
			$this->exitoInsertar   = t("Location created successfully");
			$this->exitoActualizar = t("Location updated successfully");
			$this->errorInsertar   = t("Error creating Location");
			$this->errorActualizar = t("Error updating Location");
			$this->exitoListar     = t('Location list obtained successfully');
			$this->errorListar     = t('Error obtaining Location list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'ubicacion_id'                        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid Location ID'),'valor'=>'','lectura'=>false),
				'ubicacion_nombre'                    => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'ubicacion_pais'                 	  => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid country'),'valor'=>null,'lectura'=>false),
				'ubicacion_provincia'                 => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid province'),'valor'=>null,'lectura'=>false),
				'ubicacion_poblacion'                 => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid city'),'valor'=>null,'lectura'=>false),
				'ubicacion_direccion'                 => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid address'),'valor'=>null,'lectura'=>false),
				'ubicacion_cordenadas'                => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cords'),'valor'=>null,'lectura'=>false),
				'ubicacion_detalles'                  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
			);
			
			$this->relaciones = array(
			
				'obra' => array (
					'tabla'       => 'ubicacionobra',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'ubicacionobra_ubicacion_id',
					'claveAjena2' => 'ubicacionobra_obra_id',
					'campos'      => array(
						'ubicacionobra_ubicacion_id'  => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid ubicacionobra_ubicacion_id'),'valor'=>'','lectura'=>false),
						'ubicacionobra_obra_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid ubicacionobra_obra_id'),'valor'=>'','lectura'=>false),
						'ubicacionobra_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				)
			);
		}
		
		public static function ListarUbicacionsProfesional($id)
		{
			$ubicaciones = array();
			$bd             = BD::Instancia();			
			$consulta       = "SELECT ubicacion_nombre, ubicacion_pais, ubicacion_provincia, ubicacion_poblacion, ubicacion_direccion, ubicacion_cordenadas, ubicacionobra_detalles
							   FROM ubicacionobra 
							   INNER JOIN ubicacion ON ubicacion_id = ubicacionobra_ubicacion_id
							   WHERE ubicacionobra_obra_id ='".intval($id)."' ORDER BY ubicacion_nombre";
			
			return $bd->ObtenerResultados($consulta);	
		}
		
		public static function GenerarPlantillaLista($letra="todas")
		{
			$ubicaciones = array();
			$template = Plantilla::Smarty();
			
			// comprobamos que es un caracter alfabético
			if(!ctype_alpha ( $letra )) $letra = "todas";
			else $letra = strtolower($letra);
			
			// si no está en caché asignamos los valores
			if(!$template->isCached(Ubicacion::$plantillaLista,$letra)) 
			{
				
				$bd = BD::Instancia();				
				$consulta = $letra == "todas" ? "SELECT * FROM ubicacion ORDER BY ubicacion_nombre ASC" : "SELECT * FROM ubicacion WHERE ubicacion_nombre REGEXP '^[".$letra."]' order by ubicacion_nombre ASC";
				
				$ubicaciones = $bd->ObtenerResultados($consulta);			
				
				$template->assign('ubicaciones', $ubicaciones);
				$template->assign('letra', $letra);
				$template->assign('columnas', 4);
			}
			
			$template->display(Ubicacion::$plantillaLista,$letra);
			
			return $ubicaciones;
		}
		
		public static function GenerarPlantilla($id=0)
		{
			$template = Plantilla::Smarty(); 

			// si no está en caché asignamos los valores
			if(!$template->isCached(Ubicacion::$plantilla,$id)) {
				
				$bd = BD::Instancia();
				$resultado = "";
				
				$consulta = "SELECT * FROM  ubicacion WHERE ubicacion_id = '".$id."' LIMIT 1";			
				
				$datos = $bd->Ejecutar($consulta);
				$fila  = $bd->ObtenerFila($datos);
				
				// datos personales de la ubicacion
				$template->assign('ubicacion', $fila);				
				
				//obras
				$template->assign('obras', Obra::ListarObrasUbicacion($id) );
			}
			
			$template->display(Ubicacion::$plantilla,$id);
		}
		
		public function Listar($idObra,$norelacionadas=false,$filtros=null,$start=null,$limit = null, $sort=null)
		{		
			$res = new Comunicacion();
			
			if($norelacionadas)
			{
				$consulta = "SELECT *, CASE WHEN ubicacion_id IS NOT NULL THEN '".$idObra."' END AS ubicacionobra_obra_id FROM ubicacion 
				             WHERE ubicacion_id NOT IN (SELECT ubicacionobra_ubicacion_id FROM ubicacionobra WHERE ubicacionobra_obra_id='".intval($idObra)."')";
			}
			else
			{
				$consulta = "SELECT *
				             FROM ubicacion, ubicacionobra 
							 WHERE ubicacion_id = ubicacionobra_ubicacion_id AND ubicacionobra_obra_id='".intval($idObra)."'";
			}
			
			if( $idObra == "" && $norelacionadas == false )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}