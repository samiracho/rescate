<?php	
	class Usuario extends ObjetoBD
	{
		protected $rol;					    // rol asignado al usuario
		
		function Usuario($idUsuario = '')
		{
			$this->id     = "usuario_id";
			$this->tabla  = "usuario";
			
			$this->exitoListar     = t("User list obtained successfully");
			$this->errorListar     = t("Error obtaining user list");
			$this->exitoInsertar   = t("User created successfully");
			$this->exitoActualizar = t("User updated successfully");
			$this->errorInsertar   = t("Error creating user");
			$this->errorActualizar = t("Error updating user");	
			
			$this->campos = array(
				'usuario_id'        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid user ID'),'valor'=>'','lectura'=>false),
				'usuario_nombre'    => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid user name'),'valor'=>'','lectura'=>false),
				'usuario_login'     => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid user login'),'valor'=>null,'lectura'=>false),
				'usuario_password'  => array('tipo'=>'string','nulo'=>false,'msg'=>t('Invalid password'),'valor'=>null,'lectura'=>false),
				'usuario_rol_id'    => array('tipo'=>'int','nulo'=>false,'msg'=>t('Invalid Rol'),'valor'=>null,'lectura'=>false),
				'usuario_email'     => array('tipo'=>'email','nulo'=>true,'msg'=>t('Invalid email'),'valor'=>'','lectura'=>false),
				'usuario_apellido1' => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname1'),'valor'=>'','lectura'=>false),
				'usuario_apellido2' => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid surname2'),'valor'=>'','lectura'=>false),
				'usuario_detalles'  => array('tipo'=>'html','nulo'=>true,'msg'=>t('Invalid details'),'valor'=>'','lectura'=>false)
			);

			$this->relaciones = array(
			
				'rol' => array (
					'tabla'         => 'rol',
					'relacion'      => '1a1',
					'soloLectura'   => true,
					'clavePrimaria' => 'usuario_rol_id',
					'claveAjena1'   => 'rol_id',
					'claveAjena2'   => '',
					'campos'        => array(
						'rol_id'        => array('tipo'=>'id','nulo'=>true,'msg'=>t('Invalid rol id'),'valor'=>'','lectura'=>true),					
						'rol_nombre'    => array('tipo'=>'string','nulo'=>true,'msg'=>t('Invalid rol name'),'valor'=>'','lectura'=>true)
					)
				)
			);
			
			$this->campos['usuario_id']['valor'] = $idUsuario;
		}

		// Obtiene una lista de usuarios en formato json
		public function Listar($filtros=null,$start=null,$limit = null,$sort = null) 
		{		
			$consulta = "SELECT usuario_id,usuario_nombre,usuario_login,usuario_rol_id, usuario_email, usuario_apellido1, usuario_apellido2,usuario_detalles,rol_nombre 
			             FROM usuario INNER JOIN rol ON usuario_rol_id=rol_id WHERE 1";

			return parent::Listar($consulta, false, $filtros, $start, $limit, $sort);
		}
		
		public static function GuardarEstado($datos)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
			$idUsuario = Usuario::IdUsuario();
			$datosGuardados;
			$datosLeidos;
				
			
			if($idUsuario != 0)
			{		
				$datosGuardados    = Comunicacion::DecodificarJson(Usuario::ObtenerEstado());
				$datosLeidos       = Comunicacion::DecodificarJson($datos);		
				$temp              = array();			
				$tamDatosGuardados = is_array($datosGuardados) ? sizeof($datosGuardados) : 0;
				$tamDatosLeidos    = is_array($datosLeidos) ? sizeof($datosLeidos) : 0;
				
				if($tamDatosGuardados != 0)
				{
					if($tamDatosLeidos == 0) $datosLeidos = $datosGuardados;
					else
					{
						for($i = 0; $i < $tamDatosGuardados ; $i++)
						{
							$encontrado = false;
							
							for($j = 0; $j < $tamDatosLeidos; $j++)
							{
								if( $datosGuardados[$i]->name == $datosLeidos[$j]->name)
								{
									$encontrado = true;
									break;
								}
							}
							
							if(!$encontrado)array_push($temp,$datosGuardados[$i]);
						}

						if(sizeof($temp)>0)
						{
							$datosLeidos = array_merge($datosLeidos,$temp);
						}
					}
				}

				

				$bd->Ejecutar("UPDATE usuario SET usuario_estado='".serialize($datosLeidos)."' WHERE usuario_id='".$idUsuario."' ");
				if( $bd->ObtenerErrores() == "" )
				{				
					$res->exito   = true;
					$res->mensaje = t("User state updated successfully");
					$res->errores = "";
				}
				else
				{
					$res->exito   = false;
					$res->mensaje = t("Error updating user state");
					$res->errores = $bd->ObtenerErrores();
				}
			}
			else
			{
				$res->exito   = false;
				$res->mensaje = t("Error updating user state. User not logged in");
				$res->errores = $bd->ObtenerErrores();
			}
			return $res;
		}
		
		public static function ObtenerEstado()
		{
			$bd = BD::Instancia();
			$idUsuario = Usuario::IdUsuario();
			if($idUsuario != 0 && GUARDAR_ESTADO_PANELES)
			{
				$consulta = "SELECT usuario_estado FROM usuario WHERE usuario_id = '" .$idUsuario."'";
				$datos    = $bd->Ejecutar($consulta);	
				$fila     = $bd->ObtenerFila($datos);
				
				return json_encode(unserialize($fila['usuario_estado']));

			}
			else
			{
				return "''";
			}
		}
		
		public function Guardar()
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
					
			// leemos los datos json
			parent::Leer();
			
			// comprobamos que el login no esté repetido
			$res = $this->ComprobarLoginUnico($this->campos['usuario_login']['valor'],$this->campos['usuario_id']['valor']);
			if(!$res->exito)return $res;
			
			// ciframos el password
			$this->campos['usuario_password']['valor'] = md5($this->campos['usuario_password']['valor']);
			
			$consulta = "SELECT COUNT(*) FROM usuario WHERE usuario_id='".$this->campos['usuario_id']['valor']."' AND usuario_rol_id='1'";
			
			if($this->campos['usuario_rol_id']['valor']!=1)
			{
				// si era administrador y se ha modificado su rol comprobamos que haya alguien más con privilegios de administrador
				if ( $bd->ContarFilas($consulta) > 0 )
				{
					$consulta = "SELECT COUNT(*) FROM usuario WHERE usuario_id!='".$this->campos['usuario_id']['valor']."' AND usuario_rol_id='1'";
					if ( $bd->ContarFilas($consulta) == 0 )
					{
						$res->exito = false;
						$res->mensaje = t("This is the only administrator account. You can't change it's rol");
						$res->errores = t("Error");
						return $res;
					}
				}
			}
			
			// una vez hechas todas las comprobaciones intentamos guardar (él solo se encargará de decidir si es un insert o un update)
			$res = parent::Guardar(true,false);
			
			// si todo ha ido bien actualizamos el rol en el objeto
			if($res->exito)
			{
				$this->rol  = new Rol($this->campos['usuario_rol_id']['valor']);
			}
			
			return $res;
		}
		
		// todo: que hacemos si eliminamos un usuario que ha creado registros
		public static function Eliminar($idUsuario)
		{
			$bd = BD::Instancia();
			$consulta = "";
			$res = new Comunicacion();
	
			if($idUsuario == '')
			{	
				$res->exito   = false;
				$res->mensaje = t("Error deleting user");
				$res->errores = t("User not defined");
				return $res;
			}
	
			// si se elimina un administrador hay que asegurarse de que no es el único, sino nadie más podría administrar
			$consulta = "SELECT COUNT(*) FROM usuario WHERE usuario_id!= '". $idUsuario ."' AND usuario_rol_id = '1' ";
	
			$numFilas = $bd->ContarFilas($consulta); 
			if (  $numFilas == 0 )
			{
				$res->exito = false;
				$res->mensaje = t("Error deleting user");
				$res->errores = t("This is the only administrator account. You can't delete it");
				return $res;
			}
			
			$consulta = "DELETE FROM usuario WHERE usuario_id='". $idUsuario ."'";
			$bd->Ejecutar($consulta);
			
			// los registros que eran propiedad de este usuario ahora pasan a ser del administrador
			$bd->Ejecutar("UPDATE profesional SET profesional_usuario_id='1' WHERE profesional_usuario_id='".$idUsuario."' ");
			$bd->Ejecutar("UPDATE documento SET documento_usuario_id='1' WHERE documento_usuario_id='".$idUsuario."' ");
			$bd->Ejecutar("UPDATE obra SET obra_usuario_id='1' WHERE obra_usuario_id='".$idUsuario."' ");
			$bd->Ejecutar("UPDATE bibliografia SET bibliografia_usuario_id='1' WHERE bibliografia_usuario_id='".$idUsuario."' ");
			$bd->Ejecutar("UPDATE intervencion SET intervencion_usuario_id='1' WHERE intervencion_usuario_id='".$idUsuario."' ");
			
			// si todo ha ido bien construimos la respuesta JSON y la devolvemos
			if( $bd->ObtenerErrores() == "" )
			{				
				$res->exito   = true;
				$res->mensaje = t("User deleted successfully");
				$res->errores = "";
			}
			else
			{
				$res->exito   = false;
				$res->mensaje = t("Error deleting user");
				$res->errores = $bd->ObtenerErrores();
			}
			return $res;
		}
		
		// comprueba que el login sea unico
		public function ComprobarLoginUnico($login, $idUsuario)
		{
			return ObjetoBD::ComprobarUnico("usuario_login", $login, $this->tabla,$this->id,$idUsuario);			
		}
			
		// --------------------------------------------------------------------------------------------------------------------------------
		// Funciones para hacer login, comprobar permisos e.t.c
		// --------------------------------------------------------------------------------------------------------------------------------
		
		// funcion para hacer login
		public static function Login($login, $password) 
		{
			$identificador = array();
			
			$bd = BD::Instancia();
			$login    = mysql_real_escape_string($login);
			$password = md5($password);
			$consulta = "SELECT usuario_id FROM usuario WHERE usuario_login = '" . $login . "' AND usuario_password = '" . $password . "' LIMIT 1";
			
			$datos    = $bd->Ejecutar($consulta);	
			$fila     = $bd->ObtenerFila($datos);
			
			if ($fila['usuario_id']!='')
			{
				$identificador["usuario_id"] = $fila['usuario_id']; 
				$identificador["hash"]      = md5($fila['usuario_id'].RESCATE_CLAVE);
				$identificador["ttl"]        = time();
				if(session_id() == '' ) session_start();
				$_SESSION["usuario"] = $identificador;
				return true;
			}
			else return false;
		}
		
		public static function EstaIdentificado()
		{
			if(session_id() == '' ) session_start();
			
			if( !isset($_SESSION["usuario"]) || sizeof($_SESSION["usuario"])!=3 || ((time() - $_SESSION["usuario"]["ttl"]) > TTL_SESION)  ){		
				session_destroy();
				session_unset(); 
				return false;
			}
			else{
				$_SESSION["usuario"]["ttl"] = time();
				//session_regenerate_id(true);
				return true;
			}
		}
		
		// para comprobar si el usuario tiene un permiso en concreto
		public static function TienePermiso($permiso)
		{
			if(!Usuario::EstaIdentificado()) return false;
			
			$identificador = $_SESSION["usuario"];
			
			
			// si no coincide el hash no damos permisos
			if( md5($identificador["usuario_id"].RESCATE_CLAVE) != $identificador["hash"] )
			{
				return false;
			}
			else
			{
				//$usuario = new Usuario(intval($identificador["usuario_id"]));

				//return $usuario->rol->ComprobarPermiso($permiso);
				
				$bd = BD::Instancia();
	
				// si tiene el rol de administrador siempre tiene permiso
				$consulta = "SELECT COUNT(*) FROM usuario WHERE usuario_id= '". intval($identificador["usuario_id"]) ."' AND usuario_rol_id = '1' ";
				if ( $bd->ContarFilas($consulta) > 0 )
				{
					return true;
				}
				
				// si no pertenece al rol de administrador miramos si tiene el permiso
				$consulta = "SELECT COUNT(*) FROM usuario LEFT JOIN rolpermiso ON usuario_rol_id=rolpermiso_rol_id LEFT JOIN permiso on permiso_id=rolpermiso_permiso_id WHERE permiso_nombreinterno = '".$permiso."' AND usuario_id='". intval($identificador["usuario_id"]) ."'";
				
				if ( $bd->ContarFilas($consulta) > 0 )
				{
					return true;
				}
				return false;
			}
		}
		
		// Con esta función se obtiene una lista de variables en javascript con los permisos del usuario.
		// No es inseguro porque todas las operaciones se comprueban del lado del servidor.
		// Lo he hecho así para por ejemplo poder ocultar botones de la interfaz a un usuario sin permisos e.t.c
		// De este modo también evito escribir variables php en los archivos .js, porque sino el navegador no podría guardarlos en la caché.
		public static function ObtenerPermisosJs()
		{
			$bd 		= BD::Instancia();
			$esAdmin    = false;
			
			if(!Usuario::EstaIdentificado())
			{
				$consulta = "SELECT permiso_id, permiso_nombreinterno, permiso_nombre, permiso_descripcion,
						     CASE WHEN rolpermiso_permiso_id IS NULL THEN '0' ELSE '1' END AS permiso_activo
						     FROM permiso LEFT JOIN rolpermiso ON permiso_id=rolpermiso_permiso_id";
			}
			else
			{
				$consulta = "SELECT permiso_id, permiso_nombreinterno, permiso_nombre, permiso_descripcion,
						     CASE WHEN rolpermiso_permiso_id IS NULL THEN '0' ELSE '1' END AS permiso_activo
						     FROM permiso LEFT JOIN rolpermiso ON permiso_id=rolpermiso_permiso_id AND rolpermiso_rol_id=(SELECT usuario_rol_id FROM usuario WHERE usuario_id = '".Usuario::IdUsuario()."')";
			}
			
			// si tiene el rol de administrador siempre tiene permiso
			$consulta2 = "SELECT COUNT(*) FROM usuario WHERE usuario_id= '".Usuario::IdUsuario()."' AND usuario_rol_id = '1' ";
			if ( $bd->ContarFilas($consulta2) > 0 )$esAdmin = true;
			
			
			$datos = $bd->Ejecutar($consulta);
			
			if( $bd->ObtenerErrores() == "" )
			{	
				echo "var CONFIG = new Object;";
				echo chr(13).chr(10);
				echo "CONFIG.perms = new Array();";
				echo chr(13).chr(10);
				
				// obtenemos una lista con todos los permisos
				while($fila = $bd->ObtenerFila($datos))
				{
					echo 'CONFIG.perms.'.$fila['permiso_nombreinterno'].'= ';
					echo $fila['permiso_activo'] || $esAdmin ? 'true' : 'false';
					echo chr(13).chr(10);					
				}
			}
			else echo 'CONFIG.perms = null';
		}
		
		// devuelve la id del usuario que ha iniciado sesión, si la variable de sesión ha sido modificada devolverá 0
		public static function IdUsuario()
		{
			if(!Usuario::EstaIdentificado()) return 0;
			
			$identificador = $_SESSION["usuario"];
			
			if( md5($identificador["usuario_id"].RESCATE_CLAVE) != $identificador["hash"] )
			{
				return 0;
			}
			else return $identificador["usuario_id"];
		}
	}
?>
