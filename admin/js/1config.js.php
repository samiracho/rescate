<?php 
	header("Content-type: application/x-javascript");
	include '../inc.php';
	Usuario::ObtenerPermisosJs();
?>
CONFIG.rev                = <?php echo "'".REVISION_RESCATE."'" ?>;
CONFIG.dateFormat         = <?php echo "'".FORMATO_FECHA."'" ?>;
CONFIG.UrlRelAplicacion   = <?php echo "'".RUTAREL_APLICACION."'" ?>;
CONFIG.UrlRelArchivos     = <?php echo "'".URL_ARCHIVOS."'" ?>;

CONFIG.ImagenesPermitidas = <?php echo "'".IMAGENES_PERMITIDAS."'" ?>;
CONFIG.DocumentosPermitidos = <?php echo "'".DOCUMENTOS_PERMITIDOS."'" ?>;
CONFIG.SonidosPermitidos = <?php echo "'".SONIDOS_PERMITIDOS."'" ?>;
CONFIG.VideosPermitidos = <?php echo "'".VIDEOS_PERMITIDOS."'" ?>;
CONFIG.TamMax = <?php echo "'".TAM_MAX."'" ?>;

CONFIG.GuardarEstado      = <?php echo !GUARDAR_ESTADO_PANELES ? 'false' : 'true'; ?>;
CONFIG.GuardarEstadoEnSrv = <?php echo !GUARDAR_ESTADO_PANELES_SRV ? 'false' : 'true'; ?>;
CONFIG.EstadoTTL          = <?php echo "'".ESTADO_PANELES_LIFETIME."'" ?>;
CONFIG.DatosEstado        = <?php //print_r(Usuario::ObtenerEstado()); ?>

// por defecto en navegadores antiguos desactivamos los editores enriquecidos
//CONFIG.textArea         = ( Ext.isIE6 || Ext.isIE7 || Ext.isIE8 )? 'textareafield' : 'htmleditor';
CONFIG.textArea = 'textareafield';