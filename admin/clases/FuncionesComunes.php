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
		$day  = "00";
		
		if(is_numeric($fecha))
		{
			$fecha =  $fecha . '-00-00';
		}
		else if(!empty($fecha) && $fecha!== 0 )
		{
			list($day, $month, $year) = explode("/", $fecha);
			$fecha =  $year . '-' . $month . '-' . $day; 
		}

		return $fecha;
		//$d  = DateTime::createFromFormat(FORMATO_FECHA,$fecha);
		//if(!empty($d)) return $d->format('Y-m-d');
		//else return '';
	}
?>