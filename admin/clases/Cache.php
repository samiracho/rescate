<?php	
// clase para cachear resultados. En construcción
class Cache
{	
	public static function Guardar($nombre, $datos)
	{
		$datosSerializados = serialize($datos);
		$archivo = RUTA_CACHE.DIRECTORY_SEPARATOR.'cache_'.md5($nombre);
		file_put_contents($archivo, $datosSerializados);	
	}
	
	public static function Obtener($nombre)
	{
		$tiempo = 0;
		$datos = false;
		
		$archivo = RUTA_CACHE.DIRECTORY_SEPARATOR.'cache_'.md5($nombre);
		
		if (@file_exists($archivo)) 
		{
			$tiempo = @filemtime($archivo);
		}
		else
		{
			return $datos;
		}
		
		if (time() - $tiempo < TTL_CACHE) 
		{
			$datos = unserialize( file_get_contents( $archivo ) );
		}
		else
		{
			Cache::Eliminar($nombre);
		}
		
		return $datos;
	}
	
	public static function Eliminar($nombre)
	{
		$archivo = RUTA_CACHE.DIRECTORY_SEPARATOR.'cache_'.md5($nombre);
		if (@file_exists($archivo)) 
		{
			unlink($archivo);
		}
	}
}
?>