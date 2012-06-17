<?php	
	class Centro extends ObjetoBD
	{			
		function Centro()
		{				
			$this->id    = "centro_id";
			$this->tabla = "centro";
								
			$this->exitoInsertar   = t("Award created successfully");
			$this->exitoActualizar = t("Award updated successfully");
			$this->errorInsertar   = t("Error creating Award");
			$this->errorActualizar = t("Error updating Award");
			$this->exitoListar     = t('Award list obtained successfully');
			$this->errorListar     = t('Error obtaining Award list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'centro_id'       => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid award ID'),'valor'=>'','lectura'=>false),
				'centro_nombre'   => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'centro_codigo'   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid cod'),'valor'=>'','lectura'=>false),
				'centro_detalles' => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'centro_tipo'     => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid center type'),'valor'=>'','lectura'=>false)
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
			
			// si el tipo de centro es inválido no dejamos guardar. 
			$objetoBD = new ObjetoBD();
			$res2      = $objetoBD->ObtenerEnum('centro','centro_tipo');
			$valido   = false;
			
			foreach ($res2->datos as $campo => $valor) {
				if($valor['nombre'] === $this->campos['centro_tipo']['valor'])
				{
					$valido = true;
					break;
				}
			}
			if(!$valido)
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Center type invalid");
				return $res;
			}	
			
			
			// itentamos guardar
			$res = parent::Guardar(true,false);
			
			return $res;
		}
		
		
		// devuelve por pantalla 1 si es unico y 0 en caso contrario
		public function ComprobarCodUnico($valor,$idCentro)
		{
			return ObjetoBD::ComprobarUnico("centro_codigo", $valor, $this->tabla,$this->id,$idCentro);	
		}
		
		
		public static function Eliminar($idcentro)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			
			
			if($idcentro=="")
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed");
				return $res;
			}
			
			// si está relacionado con la tabla formacion no le dejamos eliminar
			$consulta = "SELECT COUNT(*) FROM formacion WHERE formacion_centro_id= '". $idcentro ."'";
			if($bd->ContarFilas($consulta) > 0)
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed, it exists relations with formacion");
				return $res;
			}
			
			// si está relacionado con la tabla cargo no le dejamos eliminar
			$consulta = "SELECT COUNT(*) FROM cargo WHERE cargo_centro_id= '". $idcentro ."'";
			if($bd->ContarFilas($consulta) > 0)
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed, it exists relations with cargo");
				return $res;
			}
			
			$consulta = "DELETE FROM centro WHERE centro_id= '". $idcentro ."'";
			$bd->Ejecutar($consulta);
			
			if( $bd->ObtenerErrores() == "" )
			{
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
		
		public function Listar($idCentro="",$filtros=null,$start=null,$limit = null,$sort=null)
		{		
			$res = new Comunicacion();
			$filtroBusqueda = parent::CrearFiltro($filtros);
			$sortBusqueda = "";
			
			// por defecto mostramos el que esté seleccionado el primero en la lista, a menos que el usuario quiera ordenar los resultados de alguna forma
			$sortBusqueda   = ( empty($sort) && empty($filtros) && $idCentro!="" ) ? "ORDER BY (centro_id='".intval($idCentro)."') DESC" : "ORDER BY centro_nombre";
			
			$consulta =" SELECT * FROM centro WHERE 1 ".$sortBusqueda." LIMIT ".$start.",".$limit."";
			
			
			return parent::Listar($consulta);
		}
	}