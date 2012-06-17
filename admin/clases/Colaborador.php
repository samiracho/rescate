<?php	
	class Colaborador extends ObjetoBD
	{			
		function Colaborador()
		{				
			$this->id    = "";
			$this->tabla = "colaborador";
								
			$this->exitoInsertar   = t("Club created successfully");
			$this->exitoActualizar = t("Club updated successfully");
			$this->errorInsertar   = t("Error creating Club");
			$this->errorActualizar = t("Error updating Club");
			$this->exitoListar     = t('Club list obtained successfully');
			$this->errorListar     = t('Error obtaining Club list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'colaborador_profesional_id'      => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid bibliography ID'),'valor'=>'','lectura'=>false),
				'colaborador_colaborador_id'      => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>null,'lectura'=>false),
				'colaborador_tipo'                => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid type'),'valor'=>'','lectura'=>false),
				'colaborador_detalles'            => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'profesional_nombre'              => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>true),
				'profesional_apellido1'           => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid surname1'),'valor'=>null,'lectura'=>true),
				'profesional_apellido2'           => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname2'),'valor'=>'','lectura'=>true),
				'profesional_tipo'                => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid pr type'),'valor'=>'','lectura'=>true),
				
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
			

			// primero comprobamos que no existe ya
			$consulta = "SELECT COUNT(*) FROM colaborador WHERE colaborador_colaborador_id= '". $this->campos['colaborador_colaborador_id']['valor'] ."' AND colaborador_profesional_id='". $this->campos['colaborador_profesional_id']['valor'] ."' LIMIT 1";

			// si no existe lo creamos			
			if($bd->ContarFilas($consulta) == 0)
			{
				$consulta = "INSERT INTO colaborador (colaborador_colaborador_id, colaborador_profesional_id,colaborador_tipo,colaborador_detalles) VALUES ('". $this->campos['colaborador_colaborador_id']['valor'] ."','". $this->campos['colaborador_profesional_id']['valor'] ."','". $this->campos['colaborador_tipo']['valor'] ."','". $this->campos['colaborador_detalles']['valor'] ."')";
			}
			else
			{
				$consulta = "UPDATE colaborador  SET colaborador_detalles = '". $this->campos['colaborador_detalles']['valor'] ."',colaborador_tipo = '". $this->campos['colaborador_tipo']['valor'] ."'
					             WHERE colaborador_colaborador_id = '". $this->campos['colaborador_colaborador_id']['valor'] ."' AND colaborador_profesional_id = '". $this->campos['colaborador_profesional_id']['valor'] ."'";
			}	
				
			$bd->Ejecutar($consulta);
				
			if( $bd->ObtenerErrores() == "" )
			{
				// referencias cruzadas colaborador discipulo maestro
				$tipoColaborador = '';
				if ($this->campos['colaborador_tipo']['valor'] == 'Maestro') $tipoColaborador = 'Discipulo';
				else if ($this->campos['colaborador_tipo']['valor'] == 'Discipulo') $tipoColaborador = 'Maestro';
				else if ($this->campos['colaborador_tipo']['valor'] == 'Colaborador') $tipoColaborador = 'Colaborador';
				
				if($this->campos['colaborador_tipo']['valor']!= '' && $tipoColaborador != '')
				{
					// primero comprobamos que no existe ya
					$consulta = "SELECT COUNT(*) FROM colaborador WHERE colaborador_colaborador_id= '". $this->campos['colaborador_profesional_id']['valor'] ."' AND colaborador_profesional_id='". $this->campos['colaborador_colaborador_id']['valor'] ."' LIMIT 1";
					// si no existe lo creamos			
					if($bd->ContarFilas($consulta) == 0)
					{
						$consulta = "INSERT INTO colaborador (colaborador_colaborador_id, colaborador_profesional_id,colaborador_tipo) VALUES ('". $this->campos['colaborador_profesional_id']['valor'] ."','". $this->campos['colaborador_colaborador_id']['valor'] ."','".$tipoColaborador."')";			
					}
					else
					{
						$consulta = "UPDATE colaborador SET colaborador_tipo = '".$tipoColaborador."'
									WHERE colaborador_colaborador_id = '". $this->campos['colaborador_profesional_id']['valor'] ."' AND colaborador_profesional_id = '". $this->campos['colaborador_colaborador_id']['valor'] ."'";
					}
					
					$bd->Ejecutar($consulta);
				
					if( $bd->ObtenerErrores() == "" )
					{
						$res->exito = true;
						$res->mensaje = t("Add crossed relations operation succeed");
						$res->errores = "";
					}
					else
					{
						$res->exito = false;
						$res->mensaje = t("Error");
						$res->errores = t("Add crossed relations operation failed");
					}
				}	
			}
			else
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Add relations operation failed");
			}
			
			return $res;
		}
		
		public static function Eliminar($idColaborador, $idProfesional)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			
			
			if($idColaborador=="" || $idProfesional=="")
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete operation failed");
				return $res;
			}
			
			$consulta = "DELETE FROM colaborador WHERE colaborador_colaborador_id= '". $idColaborador ."' AND colaborador_profesional_id= '". $idProfesional ."'";
			$bd->Ejecutar($consulta);
			
			// la referencia cruzada
			$consulta = "DELETE FROM colaborador WHERE colaborador_colaborador_id= '". $idProfesional ."' AND colaborador_profesional_id= '". $idColaborador ."'";
			$bd->Ejecutar($consulta);
			
			// si todo ha ido bien borramos la relación de este colaborador con el profesional
			if( $bd->ObtenerErrores() == "" )
			{
				$res->exito = true;
				$res->mensaje = t("Delete operation succeed");
				$res->errores = "";
			}
			else
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("Delete relations operation failed");
			}
			
			return $res;
		}
		
		public static function ListarColaboradoresProfesional($id)
		{
			$colaboradores     = array();
			$bd                = BD::Instancia();			
			$consulta          = "SELECT profesional_id,profesional_nombre, profesional_apellido1, profesional_apellido2,colaborador_tipo, colaborador_detalles
								  FROM colaborador INNER JOIN profesional ON profesional_id=colaborador_colaborador_id 
								  WHERE colaborador_profesional_id='".intval($id)."' ORDER BY profesional_apellido1 DESC";	
				
			return $bd->ObtenerResultados($consulta);	
		}
		
		public function Listar($idProfesional,$norelacionadas=false,$filtros=null,$start=null,$limit = null,$sort=null)
		{		
			$res = new Comunicacion();
			
			if($limit != 0) $limites = "LIMIT ".$start.",".$limit;
			
			if(!$norelacionadas)
			{				
				$consulta = "SELECT *
				             FROM colaborador LEFT JOIN profesional ON profesional_id=colaborador_colaborador_id 
							 WHERE colaborador_profesional_id='".intval($idProfesional)."'";	
			}
			else
			{
				$consulta = "SELECT *, profesional_id AS colaborador_colaborador_id, CASE WHEN profesional_id IS NOT NULL THEN '".$idProfesional."' END AS colaborador_profesional_id 
				             FROM profesional 
							 WHERE profesional_id NOT IN (SELECT colaborador_colaborador_id FROM colaborador WHERE colaborador_profesional_id ='".intval($idProfesional)."' ) AND profesional_id!='".intval($idProfesional)."'";				 
			}
			
			if( $idProfesional == "" )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}