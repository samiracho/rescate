<?php
// Funciones comunes

	// funcion para traducir cadenas de texto
	function t($texto) 
	{
		global $LOCALE;
		
		if(empty($LOCALE[$texto]))
		{
			return $texto;
		}
		else
		{
			return $LOCALE[$texto];
			//return htmlentities($LOCALE[$texto], ENT_QUOTES, "UTF-8");
		}
	}
	
	function mysqlDate($fecha)
	{ 
		$year  = "0000";
		$month = "00";
		$date  = "00";
		
		if(is_numeric($fecha))
		{
			$fecha =  $fecha . '-1-1';
		}
		else if(!empty($fecha) && $fecha!= 0 )
		{
			list($date, $month, $year) = explode("/", $fecha);
			$fecha =  $year . '-' . $month . '-' . $date; 
		}

		return $fecha;
		//$d  = DateTime::createFromFormat(FORMATO_FECHA,$fecha);
		//if(!empty($d)) return $d->format('Y-m-d');
		//else return '';
	}
?>