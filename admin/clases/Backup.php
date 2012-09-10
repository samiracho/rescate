<?php 
class Backup
{
	private $host;
	private $database;
	private $user;
	private $pass;
	private $tablas;
	private $file;
	
	function Backup($host = BD_SERVIDOR, $database =BD_NOMBRE, $user = BD_USUARIO, $pass = BD_PASS, $tablas = '*' ,$file = '')
	{
		$this->host = $host;
		$this->database = $database;
		$this->user =	$user;
		$this->pass =	$pass;
		$this->tablas =	$tablas;
		$this->file = $file;
	}
	
	public static function optimizeTables()
	{
		$res = new Comunicacion();
		$bd = BD::Instancia();
		$datos    = $bd->Ejecutar('SHOW TABLE STATUS WHERE Data_free / Data_length > 0.1 AND Data_free > 102400');

		while($fila = $bd->ObtenerFilaNum($datos)) {
			$bd->Ejecutar('OPTIMIZE TABLE ' . $fila['Name']);
		};
		
		if( $bd->ObtenerErrores() == "" )
		{
			$res->exito = true;
			$res->mensaje = t("Success");
			$res->errores = "";
		}
		else
		{
			$res->exito = false;
			$res->mensaje = t("Error");
			$res->errores = t("Add crossed relations operation failed");
		}
		return $res;
	}
	
	public function doBackup()
	{
		$bd = BD::Instancia();
		$tablas = array();
		$consulta = "";
		$resultado = "";
		
		$consulta = $this->tablas == '*' ? 'SHOW TABLES' : ( is_array($this->tablas) ? $this->tablas : explode(',',$this->tablas) );	
		
		$datos    = $bd->Ejecutar($consulta);
		
		while($fila = $bd->ObtenerFilaNum($datos))
		{
			$tablas[] = $fila[0];
		}
		
		
		foreach($tablas as $tabla)  
		{		

			$datos    = $bd->Ejecutar('SHOW CREATE TABLE '.$tabla);
			$fila2 = $bd->ObtenerFilaNum($datos);
			
			$datos    = $bd->Ejecutar('SELECT * FROM '.$tabla);
			
			$num_fields = $bd->NumFields($datos);    
			$resultado .= 'DROP TABLE IF EXISTS '.$tabla.';';		
			$resultado .= "\n\n".$fila2[1].";\n\n";
			   
		
			while($fila = $bd->ObtenerFilaNum($datos))	{
				$resultado .= 'INSERT INTO '.$tabla.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
				  $fila[$j] = addslashes($fila[$j]);
				  if (isset($fila[$j])) { $resultado.= '"'.$fila[$j].'"' ; } else { $resultado.= '""'; }
				  if ($j<($num_fields-1)) { $resultado.= ','; }
			 
				}
				$resultado .= ");\n";
			}
	 
			$resultado.="\n\n\n";
		}
		$this->descargarBackup($resultado);	
	}
	
	private function descargarBackup($sql)
	{
		// fix for IE catching or PHP bug issue
		header("Pragma: public");
		header("Expires: 0"); // set expiration time
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		// browser must download file from server instead of cache

		// force download dialog
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		// use the Content-Disposition header to supply a recommended filename and
		// force the browser to display the save dialog.
		header("Content-Disposition: attachment; filename=resc_".date("d-m-Y").".sql;");

		/*
		The Content-transfer-encoding header should be binary, since the file will be read
		directly from the disk and the raw bytes passed to the downloading computer.
		The Content-length header is useful to set for downloads. The browser will be able to
		show a progress meter as a file downloads. The content-lenght can be determines by
		filesize function returns the size of a file.
		*/
		header("Content-Transfer-Encoding: binary");
		//header("Content-Length: ".filesize($filename));
		echo $sql;
	}
}
?>