<?php
	require SMARTY;

	class Plantilla
	{				
		public static function Smarty()
		{
			$template               = new Smarty;
			$template->template_dir = SMARTY_PLANTILLAS; 
			$template->compile_dir  = RUTA_CACHE; 
			$template->config_dir   = RUTA_CACHE; 
			$template->cache_dir    = RUTA_CACHE; 
			$template->caching      = SMARTY_CACHING;
			$template->assign('fecha_vacia',FECHA_VACIA);
			$template->assign('url_archivos',URL_ARCHIVOS);
			return $template;
		}
	}