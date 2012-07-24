<?php	
	class Equipamiento extends ObjetoBD
	{			
		function Equipamiento()
		{				
			$this->id    = "equipamiento_id";
			$this->tabla = "equipamiento";
								
			$this->exitoInsertar   = t("Equip created successfully");
			$this->exitoActualizar = t("Equip updated successfully");
			$this->errorInsertar   = t("Error creating Equip");
			$this->errorActualizar = t("Error updating Equip");
			$this->exitoListar     = t('Equip list obtained successfully');
			$this->errorListar     = t('Error obtaining Equip list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'equipamiento_id'                        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid equip ID'),'valor'=>'','lectura'=>false),
				'equipamiento_nombre'                    => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'equipamiento_detalles'                  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
			);	
		
			$this->relaciones = array(
			
				'profesional' => array (
					'tabla'       => 'equipamientoprofesional',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'equipamientoprofesional_equipamiento_id',
					'claveAjena2' => 'equipamientoprofesional_profesional_id',
					'campos'      => array(
						'equipamientoprofesional_equipamiento_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'equipamientoprofesional_profesional_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'equipamientoprofesional_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				)
			);			
		}
		
		public static function ListarEquipamientosProfesional($id)
		{
			$equipamientos = array();
			$bd             = BD::Instancia();			
			$consulta       = "SELECT equipamiento_nombre,equipamiento_detalles, equipamientoprofesional_detalles FROM equipamientoprofesional 
							   INNER JOIN equipamiento ON equipamiento_id = equipamientoprofesional_equipamiento_id
							   WHERE equipamientoprofesional_profesional_id ='".intval($id)."' ORDER BY equipamiento_nombre";
			
			$datos = $bd->Ejecutar($consulta);
			if( $bd->ObtenerErrores() == '' )
			{	
				// obtenemos una lista con todos los registros
				while($fila = $bd->ObtenerFila($datos))
				{
					array_push($equipamientos, $fila); 
				}
			}		
			return $equipamientos;	
		}
		
		public function Listar($idProfesional,$norelacionadas=false,$filtros=null,$start=null,$limit = null,$sort=null)
		{		
			$res = new Comunicacion();

			if(!$norelacionadas)
			{
				
				$consulta = "SELECT *
				             FROM equipamiento, equipamientoprofesional 
							 WHERE equipamiento_id = equipamientoprofesional_equipamiento_id AND equipamientoprofesional_profesional_id='".intval($idProfesional)."'";
			}
			else
			{
				$consulta = "SELECT equipamiento_id, CASE WHEN equipamiento_id IS NOT NULL THEN '".$idProfesional."' END AS equipamientoprofesional_profesional_id, equipamiento_nombre,equipamiento_detalles 
				             FROM equipamiento WHERE equipamiento_id NOT IN (SELECT equipamientoprofesional_equipamiento_id FROM equipamientoprofesional 
							 WHERE equipamientoprofesional_profesional_id='".intval($idProfesional)."')";
			}
			
			if( $idProfesional == "" && $norelacionadas == false )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}