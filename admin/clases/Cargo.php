<?php	
	class Cargo extends ObjetoBD
	{			
		function Cargo()
		{				
			$this->id    = "cargo_id";
			$this->tabla = "cargo";
								
			$this->exitoInsertar   = t("Job created successfully");
			$this->exitoActualizar = t("Job updated successfully");
			$this->errorInsertar   = t("Error creating Job");
			$this->errorActualizar = t("Error updating Job");
			$this->exitoListar     = t('Job list obtained successfully');
			$this->errorListar     = t('Error obtaining Job list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'cargo_id'             => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid award ID'),'valor'=>'','lectura'=>false),
				'cargo_profesional_id' => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>null,'lectura'=>false),
				'cargo_centro_id'      => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid idCentro'),'valor'=>null,'lectura'=>false),
				'cargo_nombre'         => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'cargo_departamento'   => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid name'),'valor'=>'','lectura'=>false),
				'cargo_fechainicio'    => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false),
				'cargo_fechafin'       => array('tipo'=>'date','nulo'=>true,'msg'=>t('Invalid date'),'valor'=>'','lectura'=>false),
				'cargo_detalles'       => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'cargo_principal'      => array('tipo'=>'checkbox','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false),
				'centro_nombre'        => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid centro_nombre'),'valor'=>'','lectura'=>true),
				'centro_codigo'        => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid centro_codigo'),'valor'=>'','lectura'=>true),
				'centro_detalles'      => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid centro_detalles'),'valor'=>'','lectura'=>true)
			);	
		}
		
		public function Guardar()
		{		
			$bd 		= BD::Instancia();
			$consulta 	= "";
						
			// itentamos guardar
			$res = parent::Guardar(true, true);
			
			// Como solo puede haber un cargo principal, Si el usuario ha marcado ese cargo como principal, ponemos todos los demás como no principales
			if($res->exito && $this->campos['cargo_principal']['valor'] == 1)
			{
				$consulta = "UPDATE cargo SET cargo_principal='0' WHERE cargo_profesional_id='".$this->campos['cargo_profesional_id']['valor']."' AND cargo_id!='".$this->campos['cargo_id']['valor']."' ";
				$bd->Ejecutar($consulta);
			}
			
			return $res;
		}
		
		public static function ListarCargosProfesional($id)
		{
			$cargos       = array();
			$bd           = BD::Instancia();			
			$consulta     = "SELECT *, DATE_FORMAT(cargo_fechainicio, '".FORMATO_FECHA_MYSQL_ANYO."') AS cargo_fechainicio, DATE_FORMAT(cargo_fechafin, '".FORMATO_FECHA_MYSQL_ANYO."') AS cargo_fechafin
							 FROM cargo INNER JOIN centro ON cargo_centro_id = centro_id 
							 WHERE cargo_profesional_id ='".intval($id)."' ORDER BY cargo_nombre";	
			return $bd->ObtenerResultados($consulta);	
		}
		
		public function Listar($idProfesional, $filtros=null, $start=null,$limit = null, $sort = null)
		{		
			$res = new Comunicacion();
			
			$consulta ="SELECT *, DATE_FORMAT(cargo_fechainicio, '".FORMATO_FECHA_MYSQL_ANYO."') AS cargo_fechainicio, DATE_FORMAT(cargo_fechafin, '".FORMATO_FECHA_MYSQL_ANYO."') AS cargo_fechafin
			             FROM cargo LEFT JOIN centro ON cargo_centro_id = centro_id WHERE cargo_profesional_id='".intval($idProfesional)."'";
			
			if($idProfesional == "") 
			{
				$res->exito = false;
				$res->mensaje = t('Error');
				$res->errores = t('Invalid ID');
				return $res;
			}
			else
			{
				return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
			}
		}
	}