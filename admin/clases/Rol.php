<?php
	
	class Rol extends ObjetoBD
	{
		var $rol_id;					// id del rol
		var $rol_nombre;				// nombre del rol
		var $rol_descripcion;			// login del rol
		var $rol_basico;				// pass del rol
		var $rol_permisos = array(); 	// permisos del rol
		
		function Rol($rol_id = '')
		{
			// aquí definimos los tipos de campos
			$this->campos = array(
				'rol_id'             => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid award ID'),'valor'=>'','lectura'=>false),
				'rol_nombre'         => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'rol_descripcion'    => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid name'),'valor'=>null,'lectura'=>false),
				'rol_basico'     	 => array('tipo'=>'checkbox','nulo'=>true,'msg'=>t('Invalid tipo'),'valor'=>null,'lectura'=>false)
			);
			
			$this->rol_id = $rol_id;
			
			if($this->rol_id == '' || !$this->ObtenerRol()) // si no lo ha encontrado en la bd hacemos uno vacío
			{
				$this->rol_id = '';	
				$this->rol_nombre = "";	
				$this->rol_descripcion = "";
				$this->rol_basico = 0;
			}
			
			// si es un rol nuevo todos los permisos estarán desactivados
			$this->rol_permisos = Rol::ObtenerPermisos($this->rol_id);
		}
		
		// obtiene los datos de un rol de la bd
		private function ObtenerRol()
		{
			$bd = BD::Instancia();
			
			$consulta = "SELECT rol_id,rol_nombre,rol_descripcion,rol_basico FROM rol WHERE rol_id = '" . intval($this->rol_id) . "' LIMIT 1";
			
			$datos    = $bd->Ejecutar($consulta);
			$fila     = $bd->ObtenerFila($datos);
			
			if ($fila['rol_id']!='')
			{
				$this->rol_id      	    = $fila['rol_id'];
				$this->rol_nombre     	= $fila['rol_nombre'];
				$this->rol_descripcion	= $fila['rol_descripcion'];
				$this->rol_basico       = $fila['rol_basico'];
				return true;
			}
			else
			{
				return false;
			}
		}
		
		// comprueba que el rol sea unico
		public function ComprobarRolUnico($nombre, $idRol)
		{
			return ObjetoBD::ComprobarUnico("rol_nombre", $nombre, 'rol','rol_id');			
		}
		
		public function Valido()
		{
			$bd 		= BD::Instancia();
			$consulta 	= "SELECT COUNT(*) FROM rol WHERE rol_id='".intval($this->rol_id)."';";
			
			if ( $bd->ContarFilas($consulta) > 0 )return true;
			else return false;
		}
		
		// crea un objeto de tipo rol en base a un mensaje Json
		public function LeerRolJson()
		{
			$res = new Comunicacion();			
			$res->LeerJson();
			$perm = array();
			
			if(is_array($res->datos))$datos = $res->datos[sizeof($res->datos)-1];
			else $datos = $res->datos;
			
			$this->rol_id          = $datos->rol_id;
			$this->rol_nombre      = $datos->rol_nombre;
			$this->rol_descripcion = $datos->rol_descripcion;
			$this->rol_basico      = $datos->rol_basico;
			
			// array con los permisos asociados al rol
			foreach( $datos->rol_permisos as $permiso)
			{
				$perm[] = array('permiso_id' => $permiso->permiso_id, 'permiso_nombreinterno' => $permiso->permiso_nombreinterno, 'permiso_nombre' => $permiso->permiso_nombre, 'permiso_descripcion' => $permiso->permiso_descripcion, 'permiso_activo' => $permiso->permiso_activo );
			}
			$this->rol_permisos = $perm;
		}
		
		// Obtiene una lista de roles en formato json
		public function ListaRoles($filtros="",$start=0,$limit = SELECT_LIMIT) 
		{
			$bd 		    = BD::Instancia();
			
			//$objetoBD = new ObjetoBD();
			$filtroBusqueda = parent::CrearFiltro($filtros);
			
			$consulta 	= "SELECT rol_id, rol_nombre, rol_descripcion,rol_basico FROM rol WHERE 1 ".$filtroBusqueda." ORDER BY rol_id DESC LIMIT ".$start.",".$limit."";
			
			$usuarios	= array();
			$res		= new Comunicacion();
			
			$datos = $bd->Ejecutar($consulta);
			
			if( $bd->ObtenerErrores() == '' )
			{	
				$roles = array();
				
				// obtenemos una lista con todos los roles
				while($fila = $bd->ObtenerFila($datos))
				{
					$roles[] = array('rol_id'=>$fila['rol_id'],'rol_nombre'=>$fila['rol_nombre'],'rol_descripcion'=>$fila['rol_descripcion'],'rol_basico'=>$fila['rol_basico'],'rol_permisos'=>Rol::ObtenerPermisos(intval($fila['rol_id'])));
				}
				
				// construimos la resupesta JSON
				$res->exito = true;
				$res->mensaje = t('Roles list obtained');
				$res->errores = "";
				$res->datos = $roles;
				$res->total = $bd->ContarFilas("SELECT COUNT(*) FROM rol");
			}
			else 
			{
				$res->exito = false;
				$res->mensaje = $bd->ObtenerErrores();
				$res->errores = $bd->ObtenerErrores();
			}
			return $res->Json();
		}
		
		private static function ObtenerPermisos($rol_id='')
		{
			$filtro = ($rol_id!='') ? "AND rolpermiso_rol_id='".intval($rol_id)."'" : "";
			$bd 		= BD::Instancia();
			$consulta 	= "SELECT permiso_id, permiso_nombreinterno, permiso_nombre, permiso_descripcion,
			               CASE WHEN rolpermiso_permiso_id IS NULL THEN '' ELSE 'true' END AS permiso_activo
                           FROM permiso LEFT JOIN rolpermiso ON permiso_id=rolpermiso_permiso_id AND rolpermiso_rol_id='".intval($rol_id)."'";
			$perms 		= array();
			
			
			$datos = $bd->Ejecutar($consulta);
			
			if( $bd->ObtenerErrores() == "" )
			{	
				// obtenemos una lista con todos los permisos
				while($fila = $bd->ObtenerFila($datos))
				{
					$perms[] = array('permiso_id'=>$fila['permiso_id'],'permiso_nombreinterno'=>$fila['permiso_nombreinterno'],'permiso_nombre'=>$fila['permiso_nombre'],'permiso_descripcion'=>$fila['permiso_descripcion'],'permiso_activo'=>$fila['permiso_activo']);
				}
				return $perms;				
			}
			else 
			{
				echo $bd->ObtenerErrores(); // si ha habido algun error lo mostramos por pantalla
				return array();
			}
		}
		
		// Obtiene una lista de permisos en formato JSON, indicando si están activos o no para el rol actual
		public static function ObtenerPermisosJson($rol_id)
		{
			$res = new Comunicacion();
			
			$permisos = Rol::ObtenerPermisos($rol_id);
			
			if( sizeof($permisos) > 0 )
			{				
				
				$res->exito   = true;
				$res->errores = "";	
				$res->mensaje = t("Permissions list obtained successfully");
				$res->datos   = $permisos;
			}
			else
			{
				$res->exito   = false;
				$res->mensaje = t("Error obtaining permissions list");
				$res->errores = t("Error obtaining permissions list");
			}
			return $res->Json();
		}		
		
		// Crea o actualiza un rol en la BD
		// TODO: guardar permisos asociados
		public function Guardar()
		{
			$bd 		= BD::Instancia();
			$consulta 	= "";
			$res		= new Comunicacion();
			$insertado	= false;
			
						
			if($this->rol_id == '') // insertar rol nuevo
			{	
				// Al insertar un rol nuevo debemos comprobar antes que el nombre interno no este repetido
				$consulta = "SELECT COUNT(*) FROM rol WHERE rol_nombre= '". $this->rol_nombre ."' ";
				
				if ( $bd->ContarFilas($consulta) > 0 )
				{
					$res->exito = false;
					$res->mensaje = t("Error");
					$res->errores = t("The internal role name is in use");
					return $res->Json();
				}
				else
				{
					$consulta = "INSERT INTO rol (rol_nombre, rol_descripcion,rol_basico) VALUES ('". $this->rol_nombre ."','". $this->rol_descripcion ."','". $this->rol_basico ."')";
					$insertado = true;
				}
			}
			else // actualizar rol existente
			{
				// Al actualizar tenemos que comprobar antes que el nombre no corresponda a un rol ya existente
				$consulta = "SELECT COUNT(*) FROM rol WHERE rol_id!= '". $this->rol_id ."' AND rol_nombre = '". $this->rol_nombre ."' ";
	
				if ( $bd->ContarFilas($consulta) > 0 )
				{
					$res->exito = false;
					$res->mensaje = t("The internal role name is in use");
					$res->errores = t("The internal role name is in use");
					return $res->Json();
				}
				else
				{
					$consulta = "UPDATE rol SET rol_nombre='". $this->rol_nombre ."', rol_descripcion= '". $this->rol_descripcion ."', rol_basico='". $this->rol_basico ."' WHERE rol_id='". $this->rol_id ."'";
					$insertado = false;
				}	
			}
					
			$bd->Ejecutar($consulta);
			
			// si todo ha ido bien, actualizamos los permisos, construimos la respuesta JSON y la devolvemos
			if( $bd->ObtenerErrores() == "" )
			{				
				if($insertado) $this->rol_id = $bd->ObtenerUltimoID();
				
				// actualizamos los permisos
				for ($i=0;$i<sizeof($this->rol_permisos);$i++)
				{
					// si el permiso esta activo comprobamos que exista la relación, sino la creamos
					if( $this->rol_permisos[$i]['permiso_activo']==1 )
					{
						$consulta = "SELECT COUNT(*) FROM rolpermiso WHERE rolpermiso_rol_id= '". $this->rol_id ."' AND rolpermiso_permiso_id='". $this->rol_permisos[$i]['permiso_id'] ."' LIMIT 1";
						
						// si no existe lo creamos			
						if($bd->ContarFilas($consulta) == 0)
						{
							$consulta = "INSERT INTO rolpermiso (rolpermiso_rol_id, rolpermiso_permiso_id) VALUES ('". $this->rol_id ."','". $this->rol_permisos[$i]['permiso_id'] ."')";
							$bd->Ejecutar($consulta);
						}
					}
					else // si no está activo lo intentamos eliminar. (Si no existía no pasa nada, la consulta no borrara nada y ya está)
					{
						$consulta = "DELETE FROM rolpermiso WHERE rolpermiso_rol_id='". $this->rol_id ."' AND rolpermiso_permiso_id='". $this->rol_permisos[$i]['permiso_id'] ."'";
						$bd->Ejecutar($consulta);
					}
				}
				// si despues de actualizar los permisos no hay errores
				if( $bd->ObtenerErrores() == "" )
				{				
					$res->exito   = true;
					$res->errores = "";
				
					if($insertado)
					{
						$this->rol_id  = $bd->ObtenerUltimoID();
						$res->mensaje = t("Role created successfully");
						$res->datos   = array('rol_id' => $this->rol_id, 'rol_nombre' => $this->rol_nombre, 'rol_descripcion' => $this->rol_descripcion,'rol_basico'=>$this->rol_basico);
					}
					else
					{
						$res->mensaje = t("Role updated successfully");
						$res->datos   = array('rol_id' => $this->rol_id, 'rol_nombre' => $this->rol_nombre, 'rol_descripcion' => $this->rol_descripcion,'rol_basico'=>$this->rol_basico);
					}
				}
				else
				{
					$res->exito   = false;
					$res->mensaje = $insertado ? t("Error creating role permissions") : t("Error updating role permissions");
					$res->errores = $bd->ObtenerErrores();
				}
			}
			else
			{
				$res->exito   = false;
				$res->mensaje = $insertado ? t("Error creating role") : t("Error updating role");
				$res->errores = $bd->ObtenerErrores();
			}
			return $res->Json();
		}
		
		public static function Eliminar($rol_id)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			
			
			// comprobamos que no intenta eliminar un rol basico (los roles basicos no se pueden eliminar)
			$consulta = "SELECT COUNT(*) FROM rol WHERE rol_id= '". $rol_id ."' AND rol_basico='1' LIMIT 1";
					
			if($bd->ContarFilas($consulta)==0)
			{			
				// borramos de la tabla de roles
				$consulta = "DELETE FROM rol WHERE rol_id='". $rol_id ."'";
				$bd->Ejecutar($consulta);
			
				// borramos las relaciones en la tabla rolesPermisos
				$consulta = "DELETE FROM rolpermiso WHERE rolpermiso_rol_id='". $rol_id ."'";
				$bd->Ejecutar($consulta);
				
				// si hay usuarios con este rol, los ponemos con permisos de solo consulta
				$consulta = "UPDATE usuario SET usuario_rol_id='3' WHERE usuario_rol_id='". $rol_id ."'";
				$bd->Ejecutar($consulta);
			
				// si todo ha ido bien construimos la respuesta JSON y la devolvemos
				if( $bd->ObtenerErrores() == "" )
				{				
					$res->exito   = true;
					$res->mensaje = t("Role deleted successfully");
					$res->errores = "";
				}
				else
				{
					$res->exito   = false;
					$res->mensaje = t("Error deleting role");
					$res->errores = $bd->ObtenerErrores();
				}
				return $res->Json();
			}
			else
			{
				$res->exito = false;
				$res->mensaje = t("Error");
				$res->errores = t("You can't delete a basic role");
				return $res->Json();
			}
		}
	}