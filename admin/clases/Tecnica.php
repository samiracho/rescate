<?php	
	class Tecnica extends ObjetoBD
	{			
		function Tecnica()
		{				
			$this->id    = "tecnica_id";
			$this->tabla = "tecnica";
								
			$this->exitoInsertar   = t("Tech created successfully");
			$this->exitoActualizar = t("Tech updated successfully");
			$this->errorInsertar   = t("Error creating Tech");
			$this->errorActualizar = t("Error updating Tech");
			$this->exitoListar     = t('Tech list obtained successfully');
			$this->errorListar     = t('Error obtaining Tech list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'tecnica_id'                        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid bibliography ID'),'valor'=>'','lectura'=>false),
				'tecnica_nombre'                    => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'tecnica_detalles'                  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
			);	
		
			$this->relaciones = array(
			
				'profesional' => array (
					'tabla'       => 'tecnicaprofesional',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'tecnicaprofesional_tecnica_id',
					'claveAjena2' => 'tecnicaprofesional_profesional_id',
					'campos'      => array(
						'tecnicaprofesional_tecnica_id'     => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'tecnicaprofesional_profesional_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'tecnicaprofesional_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				)
			);			
		}
		
		public static function ListarTecnicasProfesional($id)
		{
			$tecnicas = array();
			$bd             = BD::Instancia();			
			$consulta       = "SELECT tecnica_nombre,tecnica_detalles, tecnicaprofesional_detalles FROM tecnicaprofesional 
							   INNER JOIN tecnica ON tecnica_id = tecnicaprofesional_tecnica_id
							   WHERE tecnicaprofesional_profesional_id ='".intval($id)."' ORDER BY tecnica_nombre";
			
			$datos = $bd->Ejecutar($consulta);
			if( $bd->ObtenerErrores() == '' )
			{	
				// obtenemos una lista con todos los registros
				while($fila = $bd->ObtenerFila($datos))
				{
					array_push($tecnicas, $fila); 
				}
			}		
			return $tecnicas;	
		}
		
		public function Listar($idProfesional,$norelacionadas=false,$filtros=null,$start=null,$limit = null,$sort=null)
		{		
			$res = new Comunicacion();

			if(!$norelacionadas)
			{
				
				$consulta = "SELECT *
				             FROM tecnica, tecnicaprofesional 
							 WHERE tecnica_id = tecnicaprofesional_tecnica_id AND tecnicaprofesional_profesional_id='".intval($idProfesional)."'";
			}
			else
			{
				$consulta = "SELECT tecnica_id, CASE WHEN tecnica_id IS NOT NULL THEN '".$idProfesional."' END AS tecnicaprofesional_profesional_id, tecnica_nombre,tecnica_detalles 
				             FROM tecnica WHERE tecnica_id NOT IN (SELECT tecnicaprofesional_tecnica_id FROM tecnicaprofesional 
							 WHERE tecnicaprofesional_profesional_id='".intval($idProfesional)."')";
			}
			
			if( $idProfesional == "" && $norelacionadas == false )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}