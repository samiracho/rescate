<?php	
	class Tipo extends ObjetoBD
	{			
		function Tipo()
		{				
			$this->id    = "tipo_id";
			$this->tabla = "tipo";
								
			$this->exitoInsertar   = t("Tech created successfully");
			$this->exitoActualizar = t("Tech updated successfully");
			$this->errorInsertar   = t("Error creating Tech");
			$this->errorActualizar = t("Error updating Tech");
			$this->exitoListar     = t('Tech list obtained successfully');
			$this->errorListar     = t('Error obtaining Tech list');
			
			// aquí definimos los tipos de campos
			$this->campos = array(
				'tipo_id'                    => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid method ID'),'valor'=>'','lectura'=>false),
				'tipo_nombre'                => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'tipo_detalles'              => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
			);
			
			$this->relaciones = array(
			
				'documento' => array (
					'tabla'       => 'tipodocumento',
					'relacion'	  => 'MaN',
					'soloLectura' => false,
					'claveAjena1' => 'tipodocumento_tipo_id',
					'claveAjena2' => 'tipodocumento_documento_id',
					'campos'      => array(
						'tipodocumento_tipo_id'      => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'tipodocumento_documento_id' => array('tipo'=>'id','nulo'=>false,'msg'=>t('Invalid idProfessional'),'valor'=>'','lectura'=>false),
						'tipodocumento_detalles'     => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
					)
				)
			);	
		}
		
		public function Listar($idDocumento,$norelacionadas=false,$filtros=null,$start=null,$limit = null, $sort=null)
		{		
			$res = new Comunicacion();
			
			if(!$norelacionadas)
			{
				
				$consulta = "SELECT *
				             FROM tipo, tipodocumento 
							 WHERE tipo_id = tipodocumento_tipo_id AND tipodocumento_documento_id='".intval($idDocumento)."'";
			}
			else
			{
				$consulta = "SELECT *, CASE WHEN tipo_id IS NOT NULL THEN '".$idDocumento."' END AS tipodocumento_documento_id 
				             FROM tipo 
							 WHERE tipo_id NOT IN (SELECT tipodocumento_tipo_id FROM tipodocumento WHERE tipodocumento_documento_id='".intval($idDocumento)."')";
			}
			
			if( $idDocumento == "" && $norelacionadas == false )return Comunicacion::Error(); 
			else return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
	}