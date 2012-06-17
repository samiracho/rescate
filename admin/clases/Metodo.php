<?php	
	class Metodo extends ObjetoBD
	{			
		function Metodo()
		{				
			$this->id    = "metodo_id";
			$this->tabla = "metodo";
								
			$this->exitoInsertar   = t("Tech created successfully");
			$this->exitoActualizar = t("Tech updated successfully");
			$this->errorInsertar   = t("Error creating Tech");
			$this->errorActualizar = t("Error updating Tech");
			$this->exitoListar     = t('Tech list obtained successfully');
			$this->errorListar     = t('Error obtaining Tech list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'metodo_id'           => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid method ID'),'valor'=>'','lectura'=>false),
				'metodo_nombre'       => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'metodo_detalles'     => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'metodo_padre_id'     => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid idProfessional'),'valor'=>0,'lectura'=>false),
				'metodoobra_obra_id'  => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid idObra'),'valor'=>'','lectura'=>true),
				'metodoobra_detalles' => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>true)
			);	
			
			$this->relaciones = array(
			
				'obra' => array (
					'tabla'       => 'metodoobra',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'metodoobra_metodo_id',
					'claveAjena2' => 'metodoobra_obra_id',
					'campos'      => array(
						'metodoobra_metodo_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'metodoobra_obra_id'   => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'metodoobra_detalles'  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				)
			);	
		}
		
		public function Guardar($metodoId,$metodoPadreId, $metodoNombre, $metodoDetalles)
		{			
			// leemos los datos json
			//parent::Leer();
			
			$this->campos["metodo_id"]["valor"] = $metodoId;
			$this->campos["metodo_padre_id"]["valor"] = $metodoPadreId;
			$this->campos["metodo_detalles"]["valor"] = $metodoDetalles;
			$this->campos["metodo_nombre"]["valor"] = $metodoNombre;
			
			// itentamos guardar
			$res = parent::Guardar(true,false);
			
			// si el guardado ha sido correcto eliminamos los archivos de cache
			if($res->exito)
			{
				Cache::Eliminar("listaMetodos");
				Cache::Eliminar("arbolMetodos");
			}
			
			return $res;
		}
		
		public function EliminarDato($idmetodo)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			
			parent::EliminarDato($idmetodo);
			
			// si tenía hijos los ponemos sin padre
			$consulta = "UPDATE metodo SET metodo_padre_id ='0' WHERE metodo_padre_id='".intval($idmetodo)."'";
			$bd->Ejecutar($consulta);
				
			if( $bd->ObtenerErrores() == "" )
			{
				$res->exito = true;
				$res->mensaje = t("Success");
				$res->errores = "";
					
				// si se ha eliminado correctamente eliminamos los archivos de cache
				Cache::Eliminar("listaMetodos");
				Cache::Eliminar("arbolMetodos");
			}
			else
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete relations operation failed ").$$bd->ObtenerErrores();
			}
			return $res;
		}
		
		private static function ConstruirArbol($consulta,$refrescar = false)
		{
			$res = new Comunicacion();
			$resultado = false;
			
			if($refrescar)
			{
				Cache::Eliminar("arbolMetodos");
			}else 
			$resultado = Cache::Obtener("arbolMetodos");
			
			if(!$resultado)
			{
				$bd = BD::Instancia();
				$datos = $bd->Ejecutar($consulta);
				$data = array();
				
				if( $bd->ObtenerErrores() == '' )
				{
					while($row = $bd->ObtenerFila($datos))
					{
						array_push($data,array(
						"id" => $row["metodo_id"],
						"idParent" => $row["metodo_padre_id"],
						"text" => $row["metodo_nombre"],
						"expanded" => true,
					
						"metodoobra_obra_id" => $row["metodoobra_obra_id"],
						"metodo_id" => $row["metodo_id"],
						"metodo_nombre" => $row["metodo_nombre"],
						"metodo_padre_id" => $row["metodo_padre_id"]
						));
					}
					
					// Crear el árbol en formato JSON
					$tree = new TreeExtJS();
					for($i=0;$i<count($data);$i++)
					{
						$category = $data[$i];
						$tree->addChild($category,$category["idParent"]);
					};
					$resultado = $tree->getTree();
					Cache::Guardar("arbolMetodos",$resultado);
				}
				else
				{
					$res->exito = false;
					$res->mensaje = t('Error');
					$res->errores = $bd->ObtenerErrores();
					return $res;
				}			
			}
			
			$res->exito = true;
			$res->mensaje = t('Success');
			$res->errores = '';
			$res->datos = $resultado;
			
			return $res;
		}
		
		public static function ListaIdentada()
		{
			$res = new Comunicacion();
			$resultado = Cache::Obtener("listaMetodos");
			
			if(!$resultado)
			{
				$resultado = TreeExtJS::ListaIdentada("SELECT * FROM metodo ORDER BY metodo_padre_id ASC",'metodo_id', 'metodo_padre_id', 'metodo_nombre');
				Cache::Guardar("listaMetodos",$resultado);
			}
			
			$res->exito = true;
			$res->mensaje = t('Success');
			$res->errores = '';
			$res->datos = $resultado;
			return $res;
		}
		
		public function Listar($idObra,$norelacionadas=false,$filtros=null,$start=null,$limit = null,$sort=null,$refrescar=false)
		{		
			$res = new Comunicacion();
			$filtroBusqueda = parent::CrearFiltro($filtros);
			$sortBusqueda   = $sort!="" ? parent::CrearSort($sort) : "ORDER BY metodo_id DESC";
			
			if($idObra == "") 
			{
				$res->exito = false;
				$res->mensaje = t('Error');
				$res->errores = t('Invalid ID');
				return $res;
			}
			
			if(!$norelacionadas)
			{
				
				$consulta = "SELECT metodo_id, metodoobra_obra_id, metodo_nombre,metodo_detalles, metodoobra_detalles
				             FROM metodo, metodoobra 
							 WHERE metodo_id = metodoobra_metodo_id ".$filtroBusqueda." AND metodoobra_obra_id='".intval($idObra)."'";
				return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
			}
			else
			{
				$consulta = "SELECT metodo_id,metodo_padre_id, CASE WHEN metodo_id IS NOT NULL THEN '".$idObra."' END AS metodoobra_obra_id, metodo_nombre,metodo_detalles, metodo_padre_id FROM metodo WHERE metodo_id!='0' ORDER BY metodo_padre_id ASC";
				return Metodo::ConstruirArbol($consulta,$refrescar);
			}
		}
	}