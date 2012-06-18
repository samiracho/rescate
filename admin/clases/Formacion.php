<?php	
	class Formacion extends ObjetoBD
	{			
		function Formacion()
		{				
			$this->id    = "formacion_id";
			$this->tabla = "formacion";
								
			$this->exitoInsertar   = t("Award created successfully");
			$this->exitoActualizar = t("Award updated successfully");
			$this->errorInsertar   = t("Error creating Award");
			$this->errorActualizar = t("Error updating Award");
			$this->exitoListar     = t('Award list obtained successfully');
			$this->errorListar     = t('Error obtaining Award list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'formacion_id'             => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid award ID'),'valor'=>'','lectura'=>false),
				'formacion_profesional_id' => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>null,'lectura'=>false),
				'formacion_centro_id'      => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid idCentro'),'valor'=>null,'lectura'=>false),
				'formacion_titulo'         => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'formacion_fechainicio'    => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false),
				'formacion_fechafin'       => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false),
				'formacion_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'centro_nombre'            => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid centro_nombre'),'valor'=>'','lectura'=>true),
				'centro_codigo'            => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid centro_codigo'),'valor'=>'','lectura'=>true),
				'centro_detalles'          => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid centro_detalles'),'valor'=>'','lectura'=>true)
			);	
		}
		
		public static function ListarFormacionesProfesional($id)
		{
			$bd                = BD::Instancia();			
			$consulta          = "SELECT formacion_id, formacion_titulo, DATE_FORMAT(formacion_fechainicio, '".FORMATO_FECHA_MYSQL_ANYO."') AS formacion_fechainicio, DATE_FORMAT(formacion_fechafin, '".FORMATO_FECHA_MYSQL_ANYO."') AS formacion_fechafin, formacion_detalles, centro_nombre, centro_codigo, centro_tipo 
								  FROM formacion INNER JOIN centro ON formacion_centro_id = centro_id 
								  WHERE formacion_profesional_id ='".intval($id)."' ORDER BY formacion_fechainicio ASC";
	
			return $bd->ObtenerResultados($consulta);	
		}
		
		public function Listar($idProfesional,$filtros="",$start=0,$limit = SELECT_LIMIT, $sort="")
		{		
			$res = new Comunicacion();
			$filtroBusqueda = parent::CrearFiltro($filtros);
			$sortBusqueda   = $sort!="" ? parent::CrearSort($sort) : "ORDER BY formacion_id DESC";
			
			$consulta =" SELECT formacion_id,centro_nombre, centro_codigo, centro_detalles, formacion_profesional_id, formacion_centro_id, formacion_titulo, DATE_FORMAT(formacion_fechainicio, '".FORMATO_FECHA_MYSQL_ANYO."') AS formacion_fechainicio, DATE_FORMAT(formacion_fechafin, '".FORMATO_FECHA_MYSQL_ANYO."') AS formacion_fechafin, formacion_detalles FROM formacion LEFT JOIN centro ON formacion_centro_id = centro_id WHERE formacion_profesional_id='".intval($idProfesional)."' ".$filtroBusqueda." ".$sortBusqueda." LIMIT ".$start.",".$limit."";
			
			if( $idProfesional == ""  )return Comunicacion::Error(); 
			else return parent::Listar($consulta);
		}
	}