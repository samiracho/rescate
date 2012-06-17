<?php	
	class Material extends ObjetoBD
	{			
		function Material()
		{				
			$this->id    = "material_id";
			$this->tabla = "material";
								
			$this->exitoInsertar   = t("Tech created successfully");
			$this->exitoActualizar = t("Tech updated successfully");
			$this->errorInsertar   = t("Error creating Tech");
			$this->errorActualizar = t("Error updating Tech");
			$this->exitoListar     = t('Tech list obtained successfully');
			$this->errorListar     = t('Error obtaining Tech list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'material_id'                        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid bibliography ID'),'valor'=>'','lectura'=>false),
				'material_nombre'                    => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'material_detalles'                  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'material_padre_id'                  => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid idProfessional'),'valor'=>0,'lectura'=>false)
			);

			$this->relaciones = array(
			
				'intervencion' => array (
					'tabla'         => 'materialintervencion',
					'relacion'	=> 'MaN',
					'clavePrimaria' => 'material_id',
					'claveAjena1'   => 'materialintervencion_material_id',
					'claveAjena2'   => 'materialintervencion_intervencion_id',
					'soloLectura' => false,
					'campos'        => array(
						'materialintervencion_material_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'materialintervencion_intervencion_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'materialintervencion_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				)
			);				
		}
		
		public function Guardar($materialId,$materialPadreId, $materialNombre, $materialDetalles)
		{					
			$this->campos["material_id"]["valor"] = $materialId;
			$this->campos["material_padre_id"]["valor"] = $materialPadreId;
			$this->campos["material_detalles"]["valor"] = $materialDetalles;
			$this->campos["material_nombre"]["valor"] = $materialNombre;
			
			// itentamos guardar
			$res = parent::Guardar(true,false);
			
			// si el guardado ha sido correcto eliminamos los archivos de cache
			if($res->exito)
			{
				Cache::Eliminar("listaMateriales");
				Cache::Eliminar("arbolMateriales");
			}
			
			return $res;
		}
		
		public function EliminarDato($idmaterial)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			
			parent::EliminarDato($idmaterial);
			
			// si tenía hijos los ponemos sin padre
			$consulta = "UPDATE material SET material_padre_id ='0' WHERE material_padre_id='".intval($idmaterial)."'";
			$bd->Ejecutar($consulta);
				
			if( $bd->ObtenerErrores() == "" )
			{
				$res->exito = true;
				$res->mensaje = t("Success");
				$res->errores = "";
					
				// si se ha eliminado correctamente eliminamos los archivos de cache
				Cache::Eliminar("listaMateriales");
				Cache::Eliminar("arbolMateriales");
			}
			else
			{
				$res->exito = false;
				$res->mensaje = $bd->ObtenerErrores();
				$res->errores = t("Delete relations operation failed");
			}
			return $res;
		}
		
		private static function ConstruirArbol($consulta,$refrescar = false)
		{		
			$resultado = false;
			$res = new Comunicacion();
			
			if($refrescar)
			{
				Cache::Eliminar("arbolMateriales");
			}else $resultado = Cache::Obtener("arbolMateriales");
			
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
						"id" => $row["material_id"],
						"idParent" => $row["material_padre_id"],
						"text" => $row["material_nombre"],
						"expanded" => true,
					
						"materialintervencion_intervencion_id" => $row["materialintervencion_intervencion_id"],
						"material_id" => $row["material_id"],
						"material_nombre" => $row["material_nombre"],
						"material_padre_id" => $row["material_padre_id"]
						));
					}
					
					// Crear el árbol en formato JSON
					$tree = new TreeExtJS();
					for($i=0;$i<count($data);$i++)
					{
						$category = $data[$i];
						$tree->addChild($category,$category["idParent"]);
					}

					$resultado = $tree->GetTree();
					Cache::Guardar("arbolMateriales",$resultado);
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
			$resultado = Cache::Obtener("listaMateriales");
			
			if(!$resultado)
			{
				$resultado = TreeExtJS::ListaIdentada("SELECT * FROM material ORDER BY material_padre_id ASC",'material_id', 'material_padre_id', 'material_nombre');
				Cache::Guardar("listaMateriales",$resultado);
			}
			
			$res->exito = true;
			$res->mensaje = t('Success');
			$res->errores = '';
			$res->datos = $resultado;
			return $res;
		}
		
		public function Listar($idIntervencion,$norelacionadas=false,$filtros=null,$start=null,$limit = null, $sort=null,$refrescar=false)
		{		
			$res = new Comunicacion();
			
			if($idIntervencion == "") 
			{
				$res->exito = false;
				$res->mensaje = t('Error');
				$res->errores = t('Invalid ID');
				return $res;
			}
			
			if(!$norelacionadas)
			{
				
				$consulta = "SELECT material_id, materialintervencion_intervencion_id, material_nombre,material_detalles, materialintervencion_detalles
				             FROM material, materialintervencion 
							 WHERE material_id = materialintervencion_material_id AND materialintervencion_intervencion_id='".intval($idIntervencion)."'";
			
				return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
			}
			else
			{
				$consulta = "SELECT material_id,material_padre_id, CASE WHEN material_id IS NOT NULL THEN '".$idIntervencion."' END AS materialintervencion_intervencion_id, material_nombre,material_detalles, material_padre_id FROM material WHERE material_id!='0' ORDER BY material_padre_id ASC";
				return Material::ConstruirArbol($consulta,$refrescar);
			}
		}
	}