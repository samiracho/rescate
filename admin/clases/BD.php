<?php

Class BD
{ 
	private $link; 
	private $stmt; 
	private $array; 
	private $error;
	private $consulta;
	static $_instance; 
 
	/*La función construct es privada para evitar que el objeto pueda ser creado mediante new*/ 
	private function __construct()
	{ 
		$this->Conectar(); 
	} 
 
	/*Evitamos el clonaje del objeto. Patrón Singleton*/ 
	private function __clone(){ } 
 
	/*Función encargada de crear, si es necesario, el objeto. Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos*/ 
	public static function Instancia()
	{ 
		if (!(self::$_instance instanceof self))
		{ 
			self::$_instance=new self(); 
		} 
		return self::$_instance; 
	} 
 
	/*Realiza la conexión a la base de datos.*/ 
	private function Conectar()
	{  
		$this->link=mysql_connect(BD_SERVIDOR, BD_USUARIO, BD_PASS);
		mysql_query('set NAMES utf8'); // por los acentos y todo eso
		mysql_select_db(BD_NOMBRE,$this->link);  
   	} 
 

	public function ObtenerResultados($consulta)
	{
		$resultado = array();		
		$datos = $this->Ejecutar($consulta);
		if( $this->ObtenerErrores() == '' )
		{	
			while($fila = $this->ObtenerFila($datos))
			{
				array_push($resultado, $fila); 
			}
		}
		return $resultado;
	}	

	/*Método para ejecutar una sentencia sql. Si la consulta falla devuelve false*/ 
	public function Ejecutar($sql)
	{ 
		//print_r($sql);
		$this->consulta = $sql;

		$this->stmt=mysql_query($sql,$this->link);
		if (mysql_errno()) 
		{ 
			$this->error = "MySQL error ".mysql_errno().": ".mysql_error()." Executing: ". $sql. "";
		}
		else
		{
			$this->error = "";
		}
		
		return $this->stmt; 
	}
	
	public function UltimaId()
	{
		return mysql_insert_id();
	}
 
	/*Método para obtener una fila de resultados de la sentencia sql*/ 
	/*Devuelve false si no hay fila*/
	public function ObtenerFila($stmt,$fila=0)
	{ 
		if(!is_resource($stmt))
		{
			$this->error = "Consulta errónea ".$this->consulta;			
			return;
		}
		
		if ($fila==0)
		{ 
			$this->array = mysql_fetch_array($stmt,MYSQL_ASSOC); 
		}
		else
		{ 
			mysql_data_seek($stmt,$fila); 
			$this->array = mysql_fetch_array($stmt,MYSQL_ASSOC); 
		} 	
		return $this->array; 
	}
	
	/*Método para obtener una fila de resultados de la sentencia sql*/ 
	/*Devuelve false si no hay fila*/
	public function ObtenerFilaNum($stmt,$fila=0)
	{ 
		if(!is_resource($stmt))
		{
			$this->error = "Consulta errónea ".$this->consulta;			
			return;
		}
		
		if ($fila==0)
		{ 
			$this->array = mysql_fetch_row($stmt); 
		}
		else
		{ 
			mysql_data_seek($stmt,$fila); 
			$this->array = mysql_fetch_row($stmt); 
		} 	
		return $this->array; 
	}
	
	public function NumFields($stmt)
	{ 
		if(!is_resource($stmt))
		{
			$this->error = "Consulta errónea ".$this->consulta;			
			return;
		}
		
		return mysql_num_fields($stmt); 
	}
	
	
   
	public function ObtenerErrores()
	{
		return $this->error;
	}
   
	/*Método para obtener el numero de filas devuelto por una consulta sql*/ 
	public function ObtenerNumFilas($stmt)
	{ 
		return mysql_num_rows($stmt); 
	}
	
	// hay que retocar esta funcion porque es confusa
	public function ContarFilas($consulta="")
	{	
		if($consulta=="")return 0;
		
		$datos     = $this->Ejecutar($consulta);
		$filas     = $this->ObtenerFila($datos);
		
		
		$first_key = array_shift(array_keys($filas));
		
		// si la consulta ha sido un COUNT devolvemos el resultado
		if(stristr($first_key, 'COUNT')) return $filas[$first_key];
		else return sizeof($filas);
	}
 
	//Devuelve el último id del insert introducido 
	public function ObtenerUltimoID()
	{ 
		//return mysql_insert_id($this->link); 
		return mysql_insert_id(); 
	} 
} 

?>
