<?php	
	class Procedimiento extends ObjetoBD
	{			
		function Procedimiento()
		{				
			$this->id    = "procedimiento_id";
			$this->tabla = "procedimiento";
								
			$this->exitoInsertar   = t("Tech created successfully");
			$this->exitoActualizar = t("Tech updated successfully");
			$this->errorInsertar   = t("Error creating Tech");
			$this->errorActualizar = t("Error updating Tech");
			$this->exitoListar     = t('Tech list obtained successfully');
			$this->errorListar     = t('Error obtaining Tech list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'procedimiento_id'                        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid bibliography ID'),'valor'=>'','lectura'=>false),
				'procedimiento_nombre'                    => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'procedimiento_detalles'                  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'procedimiento_padre_id'                  => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid idProfessional'),'valor'=>0,'lectura'=>false)
			);

			$this->relaciones = array(
			
				'intervencion' => array (
					'tabla'         => 'procedimientointervencion',
					'relacion'	    => 'MaN',
					'soloLectura'   => false,
					'clavePrimaria' => 'procedimiento_id',
					'claveAjena1'   => 'procedimientointervencion_procedimiento_id',
					'claveAjena2'   => 'procedimientointervencion_intervencion_id',
					'campos'        => array(
						'procedimientointervencion_procedimiento_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'procedimientointervencion_intervencion_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'procedimientointervencion_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				)
			);				
		}
		
		public function Guardar($procedimientoId,$procedimientoPadreId, $procedimientoNombre, $procedimientoDetalles)
		{			
			// leemos los datos json
			//parent::Leer();
			
			$this->campos["procedimiento_id"]["valor"] = $procedimientoId;
			$this->campos["procedimiento_padre_id"]["valor"] = $procedimientoPadreId;
			$this->campos["procedimiento_detalles"]["valor"] = $procedimientoDetalles;
			$this->campos["procedimiento_nombre"]["valor"] = $procedimientoNombre;
			
			// itentamos guardar
			$res = parent::Guardar(true,false);
			
			// si el guardado ha sido correcto eliminamos los archivos de cache
			if($res->exito)
			{
				Cache::Eliminar("listaProcedimientos");
				Cache::Eliminar("arbolProcedimientos");
			}
			
			return $res;
		}
		
		public function EliminarDato($idprocedimiento)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			
			parent::EliminarDato($idprocedimiento);
			
			// si tenía hijos los ponemos sin padre
			$consulta = "UPDATE procedimiento SET procedimiento_padre_id ='0' WHERE procedimiento_padre_id='".intval($idprocedimiento)."'";
			$bd->Ejecutar($consulta);
				
			if( $bd->ObtenerErrores() == "" )
			{
				$res->exito = true;
				$res->mensaje = t("Success");
				$res->errores = "";
					
				// si se ha eliminado correctamente eliminamos los archivos de cache
				Cache::Eliminar("listaProcedimientos");
				Cache::Eliminar("arbolProcedimientos");
			}
			else
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete relations operation failed ").$bd->ObtenerErrores();
			}
			return $res;
		}
		
		private static function ConstruirArbol($consulta,$refrescar = false)
		{
			$res = new Comunicacion();
			
			$resultado = false;
			if($refrescar)
			{
				Cache::Eliminar("arbolProcedimientos");
			}else 
			$resultado = Cache::Obtener("arbolProcedimientos");
			
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
						"id" => $row["procedimiento_id"],
						"idParent" => $row["procedimiento_padre_id"],
						"text" => $row["procedimiento_nombre"],
						"expanded" => true,
						"procedimiento_id" => $row["procedimiento_id"],
						"procedimiento_nombre" => $row["procedimiento_nombre"],
						"procedimiento_padre_id" => $row["procedimiento_padre_id"]
						));
					}
					
					// Crear el árbol en formato JSON
					$tree = new TreeExtJS();
					for($i=0;$i<count($data);$i++)
					{
						$category = $data[$i];
						$tree->addChild($category,$category["idParent"]);
					}
					$resultado = $tree->getTree();
					Cache::Guardar("arbolProcedimientos",$resultado);
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
			$resultado = Cache::Obtener("listaProcedimientoes");
			
			if(!$resultado)
			{
				$resultado = TreeExtJS::ListaIdentada("SELECT * FROM procedimiento ORDER BY procedimiento_padre_id ASC",'procedimiento_id', 'procedimiento_padre_id', 'procedimiento_nombre');
				Cache::Guardar("listaProcedimientos",$resultado);
			}
			
			$res->exito = true;
			$res->mensaje = t('Success');
			$res->datos = $resultado;
			$res->errores = '';
			return $res;
		}
		
		public function Listar($idIntervencion,$norelacionadas=false,$filtros="",$start=0,$limit = SELECT_LIMIT, $sort="",$refrescar=false)
		{		
			$res = new Comunicacion();
			$filtroBusqueda = parent::CrearFiltro($filtros);
			$sortBusqueda   = $sort!="" ? parent::CrearSort($sort) : "ORDER BY procedimiento_id DESC";
			
			if($idIntervencion == "") 
			{
				$res->exito = false;
				$res->mensaje = t('Error');
				$res->errores = t('Invalid ID');
				return $res;
			}
			
			if(!$norelacionadas)
			{
				
				$consulta = "SELECT procedimiento_id, procedimientointervencion_intervencion_id, procedimiento_nombre,procedimiento_detalles, procedimientointervencion_detalles
				             FROM procedimiento, procedimientointervencion 
							 WHERE procedimiento_id = procedimientointervencion_procedimiento_id ".$filtroBusqueda." AND procedimientointervencion_intervencion_id='".intval($idIntervencion)."' ".$sortBusqueda." LIMIT ".$start.",".$limit."";

				return parent::Listar($consulta);
			}
			else
			{
				$consulta = "SELECT procedimiento_id,procedimiento_padre_id, CASE WHEN procedimiento_id IS NOT NULL THEN '".$idIntervencion."' END AS procedimientointervencion_intervencion_id, procedimiento_nombre,procedimiento_detalles, procedimiento_padre_id FROM procedimiento WHERE procedimiento_id!='0' ORDER BY procedimiento_padre_id ASC";
				$consultaContar = "SELECT COUNT(*) FROM procedimiento WHERE procedimiento_id NOT IN (SELECT procedimientointervencion_procedimiento_id FROM procedimientointervencion WHERE procedimientointervencion_intervencion_id='".intval($idIntervencion)."') ".$filtroBusqueda." ";
				
				return Procedimiento::ConstruirArbol($consulta,$refrescar);
			}
		}
	}