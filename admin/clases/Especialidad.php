<?php	
	class Especialidad extends ObjetoBD
	{			
		function Especialidad()
		{				
			$this->id    = "especialidad_id";
			$this->tabla = "especialidad";
								
			$this->exitoInsertar   = t("Tech created successfully");
			$this->exitoActualizar = t("Tech updated successfully");
			$this->errorInsertar   = t("Error creating Tech");
			$this->errorActualizar = t("Error updating Tech");
			$this->exitoListar     = t('Tech list obtained successfully');
			$this->errorListar     = t('Error obtaining Tech list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'especialidad_id'                        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid bibliography ID'),'valor'=>'','lectura'=>false),
				'especialidad_nombre'                    => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'especialidad_detalles'                  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
			);
			
			$this->relaciones = array(
			
				'profesional' => array (
					'tabla'       => 'especialidadprofesional',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'especialidadprofesional_especialidad_id',
					'claveAjena2' => 'especialidadprofesional_profesional_id',
					'campos'      => array(
						'especialidadprofesional_especialidad_id'  => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'especialidadprofesional_profesional_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'especialidadprofesional_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
						'especialidadprofesional_valor'          => array('tipo'=>'int','nulo'=>true,'msg'=>t('Invalid value'),'valor'=>'','lectura'=>false)
					)
				)
			);
		}
		
		public static function ListarEspecialidadesProfesional($id)
		{
			$especialidades = array();
			$bd             = BD::Instancia();			
			$consulta       = "SELECT especialidad_nombre,especialidad_detalles, especialidadprofesional_detalles, especialidadprofesional_valor FROM especialidadprofesional 
							   INNER JOIN especialidad ON especialidad_id = especialidadprofesional_especialidad_id
							   WHERE especialidadprofesional_profesional_id ='".intval($id)."' ORDER BY especialidad_nombre";
				
			return $bd->ObtenerResultados($consulta);	
		}
		
		public function Listar($idProfesional,$norelacionadas=false,$filtros=null,$start=null,$limit = null, $sort = null)
		{		
			$res = new Comunicacion();
			
			if(!$norelacionadas)
			{
				
				$consulta = "SELECT *
				             FROM especialidad, especialidadprofesional 
							 WHERE especialidad_id = especialidadprofesional_especialidad_id AND especialidadprofesional_profesional_id='".intval($idProfesional)."'";
			}
			else
			{
				$consulta = "SELECT especialidad_id, CASE WHEN especialidad_id IS NOT NULL THEN '".$idProfesional."' END AS especialidadprofesional_profesional_id, especialidad_nombre,especialidad_detalles 
				             FROM especialidad 
							 WHERE especialidad_id NOT IN (SELECT especialidadprofesional_especialidad_id FROM especialidadprofesional WHERE especialidadprofesional_profesional_id='".intval($idProfesional)."')";
							 
			}
			
			if( $idProfesional == "" && $norelacionadas == false )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}