<?php /* Smarty version Smarty-3.1.5, created on 2012-06-18 14:06:01
         compiled from "C:\wamp\www\rescate\admin\..\plantillas\obra.htm" */ ?>
<?php /*%%SmartyHeaderCode:254174fdf25b0c8a4b2-60514723%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '701b1c0b914ff28d26d46e78bd3b17d178b9020a' => 
    array (
      0 => 'C:\\wamp\\www\\rescate\\admin\\..\\plantillas\\obra.htm',
      1 => 1340028336,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '254174fdf25b0c8a4b2-60514723',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_4fdf25b0e3082',
  'variables' => 
  array (
    'obra' => 0,
    'portada' => 0,
    'url_archivos' => 0,
    'fecha_vacia' => 0,
    'tecnicas' => 0,
    'tecnica' => 0,
    'intervenciones' => 0,
    'intervencion' => 0,
    'profesional' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4fdf25b0e3082')) {function content_4fdf25b0e3082($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
		<title>Base de Datos</title>
		<link href="css/plantilla.css" rel="stylesheet" type="text/css" />
		<link href="css/b_stylerescate.css" rel="stylesheet" type="text/css" />
		<link href="css/backgroundrest.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/desplegable.js"></script>
	</head>

<body>
<!--Principio de wrp-->
<div id="wrp">
<!--Principio de head1-->
<div id="head1">

<!--Fin de head1-->
</div>
<!--Principio de navIn-->
<div id="navin">
 <ul>
      <li><a href="index.html">Inicio</a></li>
      <li><a href="objetivos.html">Objetivos</a></li>
      <li><a href="equipo.html">Sobre el equipo</a></li>
      <li><a href="contacto.html">Contacto</a></li>                    
      <li><a href="dbs_c.html" class="current">Base de datos</a></li>
      <li><a href="pb.html" >Publicaciones</a></li> 
	  <li><a href="admin/index.php" target="_blank">Investigadores</a></li> 
    </ul>
    
    
<!--Fin de navIn-->
</div>
<!--Principio del cont-->
<div id="cont">
<div id="contin_g">
<div id="contin_g_ele">



<br />
<h6><a href="datos.php?accion=obra_lista"><< Volver al índice </a></h6>

<h1>
		<?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_nombre'];?>

</h1>

<a class="expContraer" href="#">Expandir/Contraer Todo</a>

	<div class="acordeon">
		<a name="datosPersonales">Datos Personales</a>
		<table class="exp">
		  <tr>
			<th>Imagen</th>
			<td >
				<?php if ($_smarty_tpl->tpl_vars['portada']->value['documento_miniatura']!=''){?>
					<a target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['url_archivos']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['portada']->value['documento_directorio'];?>
/<?php echo $_smarty_tpl->tpl_vars['portada']->value['documento_archivo'];?>
">
						<img src="<?php echo $_smarty_tpl->tpl_vars['url_archivos']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['portada']->value['documento_directorio'];?>
/miniaturas/<?php echo $_smarty_tpl->tpl_vars['portada']->value['documento_miniatura'];?>
" />
					</a>
				<?php }?>
			</td>
		  </tr>
		  <tr>
			<th>Nombre</th>
			<td><?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_nombre'];?>
</td>
		  </tr>
		  <tr>
			<th>Nombre Autor</th>
			<td><a href="datos.php?accion=profesional&id=<?php echo $_smarty_tpl->tpl_vars['obra']->value['profesional_id'];?>
" target="blank"><?php echo $_smarty_tpl->tpl_vars['obra']->value['profesional_nombre'];?>
 <?php echo $_smarty_tpl->tpl_vars['obra']->value['profesional_apellido1'];?>
 <?php echo $_smarty_tpl->tpl_vars['obra']->value['profesional_apellido2'];?>
</a></td>
		  </tr>
		  <tr>
			<th>Fecha</th>
			<td><?php if ($_smarty_tpl->tpl_vars['obra']->value['obra_fecha1']!=$_smarty_tpl->tpl_vars['fecha_vacia']->value){?><?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_fecha1'];?>
<?php }?></td>
		  </tr>
		  <tr>
		  <tr>
			<th>Ubicación Actual</th>
			<td><a href="datos.php?accion=ubicacion&id=<?php echo $_smarty_tpl->tpl_vars['obra']->value['ubicacion_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['obra']->value['ubicacion_nombre'];?>
...</a></td>
		  </tr>
			<th>Ubicación Original</th>
			<td><?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_direccion'];?>
, <?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_poblacion'];?>
 <?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_provincia'];?>
 <?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_pais'];?>
</td>
		  </tr>
		  <tr>
			<th>Dimensión altura (cm)</th>
			<td><?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_dimension_altura'];?>
</td>
		  </tr>
		  <tr>
			<th>Dimensión anchura (cm)</th>
			<td><?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_dimension_anchura'];?>
</td>
		  </tr>
		  <tr>
			<th>Dimensión profundidad (cm)</th>
			<td><?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_dimension_profundidad'];?>
</td>
		  </tr>
		  <tr>
			<th>Dimensión Superficie (m2)</th>
			<td><?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_dimension_m2'];?>
</td>
		  </tr>
		  <tr>
			<th>Observaciones</th>
			<td ><?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_detalles'];?>
</td>
		  </tr>
		</table>
	</div>
	
	<div class="acordeon">
		<a>Técnicas utilizadas en la Obra</a>
			<table>
			  <thead>
			  <tr>
				<th>Nombre</th>
				<th>Detalles</th>
			  </thead>
			  <tbody>
			  <?php  $_smarty_tpl->tpl_vars['tecnica'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tecnica']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tecnicas']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tecnica']->key => $_smarty_tpl->tpl_vars['tecnica']->value){
$_smarty_tpl->tpl_vars['tecnica']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['tecnica']->value['metodo_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['tecnica']->value['metodoobra_detalles'];?>

					</td>
					
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	
	<div class="acordeon">
		<a>Intervenciones realizadas en la Obra</a>
			<table>
			  <thead>
			  <tr>
				<th>Nombre</th>
				<th>Fecha</th>
				<th>Restaurador(es)</th>
				<th>Ficha Completa</th>
			  </thead>
			  <tbody>
			  <?php  $_smarty_tpl->tpl_vars['intervencion'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['intervencion']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['intervenciones']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['intervencion']->key => $_smarty_tpl->tpl_vars['intervencion']->value){
$_smarty_tpl->tpl_vars['intervencion']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['intervencion']->value['intervencion_nombre'];?>

					</td>
					<td>
					<?php if ($_smarty_tpl->tpl_vars['intervencion']->value['intervencion_fechainicio']!=$_smarty_tpl->tpl_vars['fecha_vacia']->value){?><?php echo $_smarty_tpl->tpl_vars['intervencion']->value['intervencion_fechainicio'];?>
<?php }?><br />
					<?php if ($_smarty_tpl->tpl_vars['intervencion']->value['intervencion_fechafin']!=$_smarty_tpl->tpl_vars['fecha_vacia']->value){?><?php echo $_smarty_tpl->tpl_vars['intervencion']->value['intervencion_fechafin'];?>
<?php }?>
					</td>
					<td>
					
					<?php  $_smarty_tpl->tpl_vars['profesional'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['profesional']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['intervencion']->value['profesionales']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['profesional']->key => $_smarty_tpl->tpl_vars['profesional']->value){
$_smarty_tpl->tpl_vars['profesional']->_loop = true;
?>
					
						<a href="datos.php?accion=profesional&id=<?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_id'];?>
" target="blank"><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_nombre'];?>
 <?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_apellido1'];?>
 <?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_apellido2'];?>
</a><br />
					
					<?php } ?>
					
					</td>
					<td>
						<a href="datos.php?accion=intervencion&id=<?php echo $_smarty_tpl->tpl_vars['intervencion']->value['intervencion_id'];?>
" target="blank">Ficha...</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>

</div>
</div>

<!--Fin del cont-->
</div>
<!--Principio del navlogo-->
<div id="navlogo">

<div id="navlogoinleft">

<h3>Entidades</h3>
          <ul>

				<li><a class="bbaa" href="http://www.upv.es/entidades/BBAA/" ></a></li>
				<li><a class="upv" href="http://www.upv.es/index-es.html" ></a></li>
				<li><a class="cr" href="http://www.upv.es/entidades/DCRBC/index-es.html" ></a></li>
				<li><a class="irp" href="http://www.upv.es/irp/seccions/irp.htm" ></a></li>


		  </ul>       
</div>

<!--Fin del navlogo-->
</div>
<!--Principio del pie-->
<div id="pie">

<p class="pie">Copyright © 2011 | Designed by <a title="Laboratorio de Rayos X" href="http://jmadrid.webs.upv.es" target="J.A. Madrid">J. A. Madrid García</a> <br />
  Departamento de Conservación y Restauración de Bienes Culturales | Universidad Politécnica de Valencia.</p>
<!--Fin del pie-->
</div>
</div>

</body>
</html>
<?php }} ?>