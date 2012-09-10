<?php
require '../inc.php'; 

$adminBackups   = Usuario::TienePermiso('administrar_backups');

// comprobamos permisos
if (!Usuario::EstaIdentificado())
{
	$res = new Comunicacion();
	$res->exito   = false;
	$res->mensaje = t('You dont have the required permissions');
	$res->errores = t('Permission error');
	$res->ImprimirJson();
}
else if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'backup':
		
			if($adminBackups)
			{
				$backup = new Backup();			
				$backup-> doBackup();
			}			
		break;
		
		case 'optimize':
		
			Backup::optimizeTables()->ImprimirJson();
		
		break;
		
		default:
			$res = new Comunicacion();
			$res->exito   = false;
			$res->mensaje = t('Invalid action');
			$res->errores = t('Invalid action');
			$res->ImprimirJson();
		break;
	}
}
else
{
	$res = new Comunicacion();
	$res->exito   = false;
	$res->mensaje = t('Invalid action');
	$res->errores = t('Invalid action');
	$res->ImprimirJson();
}

?>