<?php
	require 'inc.php';
	require 'idiomas/ES_es/ES_es.php';


/*if (!isset($_SESSION["usuario"]))
{	
	header( 'Location: index.php' );
}*/
	$bd = BD::Instancia();
	$numRestauradores = $bd->ContarFilas("SELECT COUNT(*) FROM profesional WHERE profesional_tipo = 'Restaurador' AND profesional_supervisado = '0'"); 
	$numEspecialistas = $bd->ContarFilas("SELECT COUNT(*) FROM profesional WHERE profesional_tipo = 'Especialista' AND profesional_supervisado = '0'"); 
	$numColaboradores = $bd->ContarFilas("SELECT COUNT(*) FROM profesional WHERE profesional_tipo = 'Colaborador' AND profesional_supervisado = '0'"); 
	$numIntervenciones = $bd->ContarFilas("SELECT COUNT(*) FROM intervencion WHERE intervencion_supervisado = '0'"); 
	$numObras = $bd->ContarFilas("SELECT COUNT(*) FROM obra WHERE obra_supervisado = '0'"); 
	$numDocumentos = $bd->ContarFilas("SELECT COUNT(*) FROM documento WHERE documento_supervisado = '0'"); 
	$numBibliografias = $bd->ContarFilas("SELECT COUNT(*) FROM bibliografia WHERE bibliografia_supervisado = '0'"); 
	
	?>
	
<div class="contenedorDashBoard dashBoardTareas">
	<h1><?= t('Pending Tasks') ?></h1>
	<ul>
		<li><a <? if($numRestauradores == 0) echo 'class="supervised"' ?> href="index.php?opcion=Restaurador"><?= $numRestauradores.' '.t('Restorer(s) to Supervise') ?></a></li>
		<li><a <? if($numEspecialistas == 0) echo 'class="supervised"' ?>href="index.php?opcion=Especialista"><?= $numEspecialistas.' '.t('Specialist(s) to Supervise') ?></a></li>
		<li><a <? if($numColaboradores == 0) echo 'class="supervised"' ?> href="index.php?opcion=Colaborador"><?= $numColaboradores.' '.t('Collaborator(s) to Supervise') ?></a></li>
		<li><a <? if($numIntervenciones == 0) echo 'class="supervised"' ?> href="index.php?opcion=Intervencion"><?= $numIntervenciones.' '.t('Intervention(s) to Supervise') ?></a></li>
		<li><a <? if($numObras == 0) echo 'class="supervised"' ?> href="index.php?opcion=Obra"><?= $numObras.' '.t('Work(s) of Art to Supervise') ?></a></li>
		<li><a <? if($numDocumentos == 0) echo 'class="supervised"' ?> href="index.php?opcion=Documento"><?= $numDocumentos.' '.t('Document(s) to Supervise') ?></a></li>
		<li><a <? if($numBibliografias == 0) echo 'class="supervised"' ?> href="index.php?opcion=Bibliografia"><?= $numBibliografias.' '.t('Bibliographi(es) to Supervise') ?></a></li>
	<ul>
</div>

<div class="contenedorDashBoard dashBoardBackup">
	<h1><?= t('Backup Tasks') ?></h1>
	<ul>
		<li><a class="normal" href="index.php?opcion=Restaurador"><?= t('Backup Database') ?></a></li>
		<li><a class="normal" href="index.php?opcion=Restaurador"><?= t('Restore Database') ?></a></li>
	</ul>
</div>

<div class="contenedorDashBoard dashBoardConsejos">
	<h1><?= t('Tips') ?></h1>
	<blockquote>No olvide realizar copias de seguridad de vez en cuando</blockquote>
</div>