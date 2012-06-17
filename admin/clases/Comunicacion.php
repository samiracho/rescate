<?php
/**
 * @clase Comunicacion
 * Una clase de lectura/escritura de mensajes JSON.
 */
 
class Comunicacion{
    public $exito, $datos, $mensaje, $total, $errores;

    public function __construct($params = array()) {
        $this->exito  	= isset($params["exito"])	? $params["exito"]		: 0;
        $this->mensaje  = isset($params["mensaje"]) ? $params["mensaje"]	: '';
		$this->total    = isset($params["total"])   ? $params["total"]		: '';
        $this->datos    = isset($params["datos"])	? $params["datos"]		: array();
		$this->errores  = isset($params["errores"])	? $params["errores"]	: '';
    }

    public function Json() {
        return json_encode(array(
            'success'	=> $this->exito,
            'message'	=> $this->mensaje,
            'data'		=> $this->datos,
			'total'		=> $this->total,
			'errors'	=> $this->errores,
        ));
    }
	
	// función para imprimir el objeto en formato json
	public function ImprimirJson()
	{
		print_r($this->Json());
	}
	
	public static function Error()
	{
		$res = new Comunicacion();
		$res->exito = false;
		$res->mensaje = 'Error';
		$res->errores = 'Invalid ID';
		return $res;
	}
	
	public static function DecodificarJson($json, $stripSlashes = false)
	{
		/*if(get_magic_quotes_gpc()){
			$params = json_decode(stripslashes($raw));
		}else{
			$params = json_decode($raw);
		}*/
		if($stripSlashes) return json_decode(stripslashes($json));
		else return json_decode($json);
	}
	
	// funcion para leer los datos json enviados del cliente al servidor
	public function LeerJson()
	{
		$raw  = '';
        $httpContent = fopen('php://input', 'r');
        while ($kb = fread($httpContent, 1024))
		{
			$raw .= $kb;
        }
		
		$params = Comunicacion::DecodificarJson($raw);
		
        if ($params)
		{
            $pers = $params->data;
        }
			
		$this->datos = $pers;
	}
}
