<?php	
	class Reconocimiento extends ObjetoBD
	{			
		function Reconocimiento()
		{				
			$this->id    = "reconocimiento_id";
			$this->tabla = "reconocimiento";
								
			$this->exitoInsertar   = t("Award created successfully");
			$this->exitoActualizar = t("Award updated successfully");
			$this->errorInsertar   = t("Error creating Award");
			$this->errorActualizar = t("Error updating Award");
			$this->exitoListar     = t('Award list obtained successfully');
			$this->errorListar     = t('Error obtaining Award list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'reconocimiento_id'              => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid award ID'),'valor'=>'','lectura'=>false),
				'reconocimiento_profesional_id'  => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>null,'lectura'=>false),
				'reconocimiento_nombre'          => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'reconocimiento_detalles'        => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'reconocimiento_fecha'           => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false)
			);	
		}
		
		public static function ListarReconocimientosProfesional($id)
		{
			$reconocimientos       = array();
			$bd           = BD::Instancia();			
			$consulta     = "SELECT reconocimiento_nombre, reconocimiento_detalles, DATE_FORMAT(reconocimiento_fecha, '".FORMATO_FECHA_MYSQL_ANYO."') AS reconocimiento_fecha
							 FROM reconocimiento
							 WHERE reconocimiento_profesional_id ='".intval($id)."' ORDER BY reconocimiento_fecha ASC";
			
			$datos = $bd->Ejecutar($consulta);
			if( $bd->ObtenerErrores() == '' )
			{	
				// obtenemos una lista con todos los registros
				while($fila = $bd->ObtenerFila($datos))
				{
					array_push($reconocimientos, $fila); 
				}
			}		
			return $reconocimientos;	
		}
		
		public function Listar($idProfesional,$filtros=null,$start=null, $limit= null, $sort= null)
		{		
			$res = new Comunicacion();
			
			$consulta ="SELECT reconocimiento_id, reconocimiento_profesional_id, reconocimiento_nombre, reconocimiento_detalles, DATE_FORMAT(reconocimiento_fecha, '".FORMATO_FECHA_MYSQL_ANYO."') AS reconocimiento_fecha 
			            FROM reconocimiento WHERE reconocimiento_profesional_id='".intval($idProfesional)."'";
			
			if( $idProfesional == "")return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}