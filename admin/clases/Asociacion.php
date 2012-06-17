<?php	
	class Asociacion extends ObjetoBD
	{			
		function Asociacion()
		{				
			$this->id    = "asociacion_id";
			$this->tabla = "asociacion";
								
			$this->exitoInsertar   = t("Club created successfully");
			$this->exitoActualizar = t("Club updated successfully");
			$this->errorInsertar   = t("Error creating Club");
			$this->errorActualizar = t("Error updating Club");
			$this->exitoListar     = t('Club list obtained successfully');
			$this->errorListar     = t('Error obtaining Club list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'asociacion_id'                        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid Club ID'),'valor'=>'','lectura'=>false),
				'asociacion_nombre'                    => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'asociacion_detalles'                  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'asociacion_fecha'                     => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false)
			);
			
			$this->relaciones = array(
			
				'profesional' => array (
					'tabla'       => 'asociacionprofesional',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'asociacionprofesional_asociacion_id',
					'claveAjena2' => 'asociacionprofesional_profesional_id',
					'campos'      => array(
						'asociacionprofesional_asociacion_id'  => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid asociacionprofesional_asociacion_id'),'valor'=>'','lectura'=>false),
						'asociacionprofesional_profesional_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid asociacionprofesional_profesional_id'),'valor'=>'','lectura'=>false),
						'asociacionprofesional_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
						'asociacionprofesional_fechaentrada'   => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid asociacionprofesional_fechaentrada'),'valor'=>'','lectura'=>false),
						'asociacionprofesional_fechasalida'    => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid asociacionprofesional_fechasalida'),'valor'=>'','lectura'=>false)
					)
				)
			);
		}
		
		public static function ListarAsociacionesProfesional($id)
		{
			$asociaciones = array();
			$bd             = BD::Instancia();			
			$consulta       = "SELECT asociacion_nombre,asociacion_detalles, asociacionprofesional_detalles,DATE_FORMAT(asociacion_fecha, '".FORMATO_FECHA_MYSQL_ANYO."') AS asociacion_fecha,DATE_FORMAT(asociacionprofesional_fechaentrada, '".FORMATO_FECHA_MYSQL_ANYO."') AS asociacionprofesional_fechaentrada, DATE_FORMAT(asociacionprofesional_fechasalida, '".FORMATO_FECHA_MYSQL_ANYO."') AS asociacionprofesional_fechasalida 
							   FROM asociacionprofesional 
							   INNER JOIN asociacion ON asociacion_id = asociacionprofesional_asociacion_id
							   WHERE asociacionprofesional_profesional_id ='".intval($id)."' ORDER BY asociacion_nombre";
			
			return $bd->ObtenerResultados($consulta);	
		}
		
		public function Listar($idProfesional,$norelacionadas=false,$filtros=null,$start=null,$limit = null, $sort = null)
		{		
			$res = new Comunicacion();
			
			if(!$norelacionadas)
			{
				
				$consulta = "SELECT *, DATE_FORMAT(asociacion_fecha, '".FORMATO_FECHA_MYSQL_ANYO."') AS asociacion_fecha,DATE_FORMAT(asociacionprofesional_fechaentrada, '".FORMATO_FECHA_MYSQL_ANYO."') AS asociacionprofesional_fechaentrada, DATE_FORMAT(asociacionprofesional_fechasalida, '".FORMATO_FECHA_MYSQL_ANYO."') AS asociacionprofesional_fechasalida 
				             FROM asociacion, asociacionprofesional 
							 WHERE asociacion_id = asociacionprofesional_asociacion_id AND asociacionprofesional_profesional_id='".intval($idProfesional)."'";
			}
			else
			{
				$consulta = "SELECT asociacion_id, CASE WHEN asociacion_id IS NOT NULL THEN '".$idProfesional."' END AS asociacionprofesional_profesional_id, asociacion_nombre,asociacion_detalles,DATE_FORMAT(asociacion_fecha, '".FORMATO_FECHA_MYSQL_ANYO."') AS asociacion_fecha FROM asociacion 
				             WHERE asociacion_id NOT IN (SELECT asociacionprofesional_asociacion_id FROM asociacionprofesional WHERE asociacionprofesional_profesional_id='".intval($idProfesional)."')";
			}
			
			if($idProfesional == "")return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}