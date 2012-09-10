<?php /* Smarty version Smarty-3.1.5, created on 2012-09-09 23:31:40
         compiled from "C:\wamp\www\rescate\admin\..\plantillas\profesional.htm" */ ?>
<?php /*%%SmartyHeaderCode:204514fdf325989f845-76564493%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '99aceeebc80636679f5339c383fc72716cee6179' => 
    array (
      0 => 'C:\\wamp\\www\\rescate\\admin\\..\\plantillas\\profesional.htm',
      1 => 1347233485,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '204514fdf325989f845-76564493',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_4fdf3259e0290',
  'variables' => 
  array (
    'profesional' => 0,
    'url_archivos' => 0,
    'fecha_vacia' => 0,
    'obras' => 0,
    'obra' => 0,
    'autoriaobras' => 0,
    'autoriaobra' => 0,
    'documentos' => 0,
    'documento' => 0,
    'colaboradores' => 0,
    'colaborador' => 0,
    'formaciones' => 0,
    'formacion' => 0,
    'cargos' => 0,
    'cargo' => 0,
    'reconocimientos' => 0,
    'reconocimiento' => 0,
    'especialidades' => 0,
    'especialidad' => 0,
    'tecnicas' => 0,
    'tecnica' => 0,
    'equipamientos' => 0,
    'equipamiento' => 0,
    'asociaciones' => 0,
    'asociacion' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4fdf3259e0290')) {function content_4fdf3259e0290($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
		<title>Base de Datos</title>
		<link href="css/b_stylerescate.css" rel="stylesheet" type="text/css" />
		<link href="css/plantilla.css" rel="stylesheet" type="text/css" />
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
		<li><a href="admin/index.php"  >Investigadores</a></li> 
	</ul>
    
    
<!--Fin de navIn-->
</div>
<!--Principio del cont-->
<div id="cont">
<div id="contin_g">
<div id="contenido_plantilla">



<br />
<h6><a href="javascript:history.back(1)"><< Volver atrás </a></h6>

<h1>
		<?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_nombre'];?>
 <?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_apellido1'];?>
 <?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_apellido2'];?>

</h1>
<!--
<ul class="indiceContenido">
	<li><a href="#datosPersonales">Datos Personales</a></li>
	<li><a href="#obras">Obras Intervenidas</a></li>
	<li><a href="#documentos">Documentos Relacionados</a></li>
	<li><a href="#colaboradores">Colaboradores</a></li>
	<li><a href="#formacion">Formación Recibida</a></li>
	<li><a href="#cargos">Cargos</a></li>
	<li><a href="#reconocimientos">Reconocimientos</a></li>
	<li><a href="#especialidades">Especialidades</a></li>
	<li><a href="#tecnicas">Técnicas Utilizadas</a></li>
	<li><a href="#asociaciones">Asociaciones a las que pertenece</a></li>
</ul>
-->
	<a class="expContraer" href="#">Expandir/Contraer Todo</a>
	
	<div class="acordeon">
		<a name="datosPersonales">Datos Personales</a>
		<table class="exp">
		  <tr>
			<th>Foto</th>
			<td>
				<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_miniatura']!=''){?>
					<a   href="<?php echo $_smarty_tpl->tpl_vars['url_archivos']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_directorio'];?>
/<?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_archivo'];?>
">
						<img src="<?php echo $_smarty_tpl->tpl_vars['url_archivos']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_directorio'];?>
/miniaturas/<?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_miniatura'];?>
" />
					</a>
				<?php }?>		
			</td>
		  </tr>
		  <tr>
			<th>Nombre</th>
			<td><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_nombre'];?>
 <?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_apellido1'];?>
 <?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_apellido2'];?>
</td>
		  </tr>
		  <tr>
			<th>Sexo</th>
			<td><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_sexo'];?>
</td>
		  </tr>
		  <tr>
			<th>Nacimiento</th>
			<td>
				<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_fechanacimiento_dia']>0){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_fechanacimiento_dia'];?>
<?php }else{ ?>--<?php }?>/
				<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_fechanacimiento_mes']>0){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_fechanacimiento_mes'];?>
<?php }else{ ?>--<?php }?>/
				<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_fechanacimiento_anyo']>0){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_fechanacimiento_anyo'];?>
<?php }else{ ?>----<?php }?>
				<br>
				<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_direccionn']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_direccionn'];?>
<br /><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_poblacionn']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_poblacionn'];?>
, <?php }?>
				<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_provincian']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_provincian'];?>
, <?php }?> 
				<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_paisn']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_paisn'];?>
<?php }?> 
			</td>
		  </tr>
		  
		  <?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_fechadefuncion']!=$_smarty_tpl->tpl_vars['fecha_vacia']->value){?>
			  <tr>
				<th>Defunción</th>
				<td>
					<?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_fechadefuncion'];?>
<br />
					<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_direcciond']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_direcciond'];?>
<br /><?php }?>
					<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_direcciond']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_direccionn'];?>
<br /><?php }?>
					<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_poblaciond']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_poblaciond'];?>
, <?php }?>
					<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_provinciad']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_provinciad'];?>
, <?php }?> 
					<?php if ($_smarty_tpl->tpl_vars['profesional']->value['profesional_paisd']!=''){?><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_paisd'];?>
<?php }?> 
				</td>
			  </tr>
		  <?php }?>
		  
		  <tr>
			<th>Observaciones</th>
			<td ><?php echo $_smarty_tpl->tpl_vars['profesional']->value['profesional_observaciones'];?>
</td>
		  </tr>
		</table>
	</div>
	
	<?php if ($_smarty_tpl->tpl_vars['obras']->value){?>
	<div class="acordeon">
		<a name="obras">Obras Intervenidas</a>
			<table>
			  <thead>
			  <tr>
				<th>Nombre</th>
				<th>Autor</th>
				<th>Ficha Completa</th>
			  </thead>
			  <tbody>
			  <?php  $_smarty_tpl->tpl_vars['obra'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['obra']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['obras']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['obra']->key => $_smarty_tpl->tpl_vars['obra']->value){
$_smarty_tpl->tpl_vars['obra']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['obra']->value['profesional_nombre'];?>
 <?php echo $_smarty_tpl->tpl_vars['obra']->value['profesional_apellido1'];?>
 <?php echo $_smarty_tpl->tpl_vars['obra']->value['profesional_apellido2'];?>

					</td>
					<td>
						<a href="datos.php?accion=obra&id=<?php echo $_smarty_tpl->tpl_vars['obra']->value['obra_id'];?>
"  >Ficha...</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['autoriaobras']->value){?>
	<div class="acordeon">
		<a name="obras">Autor de las siguientes Obras</a>
			<table>
			  <thead>
			  <tr>
				<th>Nombre</th>
				<th>Ficha Completa</th>
			  </thead>
			  <tbody>
			  <?php  $_smarty_tpl->tpl_vars['autoriaobra'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['autoriaobra']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['autoriaobras']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['autoriaobra']->key => $_smarty_tpl->tpl_vars['autoriaobra']->value){
$_smarty_tpl->tpl_vars['autoriaobra']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['autoriaobra']->value['obra_nombre'];?>

					</td>
					<td>
						<a href="datos.php?accion=obra&id=<?php echo $_smarty_tpl->tpl_vars['autoriaobra']->value['obra_id'];?>
"  >Ficha...</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>

	<?php if ($_smarty_tpl->tpl_vars['documentos']->value){?>
	<div class="acordeon">
		<a name="documentos">Documentos Relacionados</a>
			<table>
			  <thead>
			  <tr>
				<th>Miniatura</th>
				<th>Nombre</th>
				<th>Autor</th>
				<th>Observaciones</th>
				<th>Ficha Completa</th>
			  </thead>
			  <tbody>
			  <?php  $_smarty_tpl->tpl_vars['documento'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['documento']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['documentos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['documento']->key => $_smarty_tpl->tpl_vars['documento']->value){
$_smarty_tpl->tpl_vars['documento']->_loop = true;
?>
				<tr>
					<td>
						<a   href="<?php echo $_smarty_tpl->tpl_vars['url_archivos']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['documento']->value['documento_directorio'];?>
/<?php echo $_smarty_tpl->tpl_vars['documento']->value['documento_miniatura'];?>
">   
						<img width="64" src="<?php echo $_smarty_tpl->tpl_vars['url_archivos']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['documento']->value['documento_directorio'];?>
/miniaturas/<?php echo $_smarty_tpl->tpl_vars['documento']->value['documento_miniatura'];?>
" alt="Vista previa" />
						</a>
					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['documento']->value['documento_titulo'];?>

					</td>
					<td>
						 <?php echo $_smarty_tpl->tpl_vars['documento']->value['profesional_nombre'];?>
 <?php echo $_smarty_tpl->tpl_vars['documento']->value['profesional_apellido1'];?>
 <?php echo $_smarty_tpl->tpl_vars['documento']->value['profesional_apellido2'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['documento']->value['documentoprofesional_detalles'];?>

					</td>
					<td>
						<a href="datos.php?accion=documento&id=<?php echo $_smarty_tpl->tpl_vars['documento']->value['documento_id'];?>
"  >Ficha...</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['colaboradores']->value){?>
	<div class="acordeon">
		<a name="colaboradores">Colaboradores</a>
		<table>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Tipo</th>
					<th>Detalles</th>
					<th>Ficha Completa</th>
				</tr>
			</thead>
			<tbody>
				<?php  $_smarty_tpl->tpl_vars['colaborador'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['colaborador']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['colaboradores']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['colaborador']->key => $_smarty_tpl->tpl_vars['colaborador']->value){
$_smarty_tpl->tpl_vars['colaborador']->_loop = true;
?>
				<tr>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['colaborador']->value['profesional_apellido1']!=''){?> <?php echo $_smarty_tpl->tpl_vars['colaborador']->value['profesional_apellido1'];?>
, <?php }?> 
						<?php if ($_smarty_tpl->tpl_vars['colaborador']->value['profesional_apellido2']!=''){?> <?php echo $_smarty_tpl->tpl_vars['colaborador']->value['profesional_apellido2'];?>
, <?php }?>
						<?php if ($_smarty_tpl->tpl_vars['colaborador']->value['profesional_nombre']!=''){?> <?php echo $_smarty_tpl->tpl_vars['colaborador']->value['profesional_nombre'];?>
 <?php }?>
					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['colaborador']->value['colaborador_tipo'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['colaborador']->value['colaborador_detalles'];?>

					</td>
					<td>
						<a href="datos.php?accion=profesional&id=<?php echo $_smarty_tpl->tpl_vars['colaborador']->value['profesional_id'];?>
"  >Ficha...</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['formaciones']->value){?>
	<div class="acordeon">
		<a name="formacion">Formación Recibida</a>
		<table>
			<thead>
			  <tr>
				<th>Título</th>
				<th>Fecha Inicio</th>
				<th>Fecha Fin</th>
				<th>Nombre del Centro</th>
				<th>Código del Centro</th>
				<th>Tipo de Centro</th>
				<th>Detalles</th>
			  </tr>
			</thead>
			<tbody>
			  <?php  $_smarty_tpl->tpl_vars['formacion'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['formacion']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['formaciones']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['formacion']->key => $_smarty_tpl->tpl_vars['formacion']->value){
$_smarty_tpl->tpl_vars['formacion']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['formacion']->value['formacion_titulo'];?>

					</td>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['formacion']->value['formacion_fechainicio']!='0000'){?><?php echo $_smarty_tpl->tpl_vars['formacion']->value['formacion_fechainicio'];?>
<?php }?>
					</td>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['formacion']->value['formacion_actualmente']){?>Actualmente
							<?php }elseif($_smarty_tpl->tpl_vars['formacion']->value['formacion_fechafin']!='0000'){?><?php echo $_smarty_tpl->tpl_vars['formacion']->value['formacion_fechafin'];?>

						<?php }?>
					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['formacion']->value['centro_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['formacion']->value['centro_codigo'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['formacion']->value['centro_tipo'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['formacion']->value['formacion_detalles'];?>

					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['cargos']->value){?>
	<div class="acordeon">
		<a name="cargos">Cargos</a>
		<table>
			<thead> 
			 <tr>
				<th>Nombre</th>
				<th>Departamento</th>
				<th>Fecha Inicio</th>
				<th>Fecha Fin</th>
				<th>Nombre del Centro</th>
				<th>Código del Centro</th>
				<th>Tipo de Centro</th>
				<th>Detalles</th>
			  </tr>
			</thead>
			<tbody>
			  <?php  $_smarty_tpl->tpl_vars['cargo'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cargo']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cargos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cargo']->key => $_smarty_tpl->tpl_vars['cargo']->value){
$_smarty_tpl->tpl_vars['cargo']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['cargo']->value['cargo_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['cargo']->value['cargo_departamento'];?>

					</td>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['cargo']->value['cargo_fechainicio']!='0000'){?><?php echo $_smarty_tpl->tpl_vars['cargo']->value['cargo_fechainicio'];?>
<?php }?>
					</td>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['cargo']->value['cargo_actualmente']){?>Actualmente
							<?php }elseif($_smarty_tpl->tpl_vars['cargo']->value['cargo_fechafin']!='0000'){?><?php echo $_smarty_tpl->tpl_vars['cargo']->value['cargo_fechafin'];?>

						<?php }?>
					
					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['cargo']->value['centro_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['cargo']->value['centro_codigo'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['cargo']->value['centro_tipo'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['cargo']->value['cargo_detalles'];?>

					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['reconocimientos']->value){?>
	<div class="acordeon">
		<a name="reconocimientos">Reconocimientos</a>
		<table>
			<thead>
			  <tr>
				<th>Nombre</th>
				<th>Detalles</th>
				<th>Fecha</th>
			  </tr>
			</thead>
			<tbody>
			  <?php  $_smarty_tpl->tpl_vars['reconocimiento'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['reconocimiento']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['reconocimientos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['reconocimiento']->key => $_smarty_tpl->tpl_vars['reconocimiento']->value){
$_smarty_tpl->tpl_vars['reconocimiento']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['reconocimiento']->value['reconocimiento_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['reconocimiento']->value['reconocimiento_detalles'];?>

					</td>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['reconocimiento']->value['reconocimiento_fecha']!='0000'){?><?php echo $_smarty_tpl->tpl_vars['reconocimiento']->value['reconocimiento_fecha'];?>
<?php }?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['especialidades']->value){?>
	<div class="acordeon">
		<a name="especialidades">Especialidades</a>
		<table>
			<thead>
			  <tr>
				<th>Nombre</th>
				<th>Detalles</th>
				<th>Observaciones</th>
			  </tr>
			</thead>
			<tbody>
			  <?php  $_smarty_tpl->tpl_vars['especialidad'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['especialidad']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['especialidades']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['especialidad']->key => $_smarty_tpl->tpl_vars['especialidad']->value){
$_smarty_tpl->tpl_vars['especialidad']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['especialidad']->value['especialidad_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['especialidad']->value['especialidad_detalles'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['especialidad']->value['especialidadprofesional_detalles'];?>

					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['tecnicas']->value){?>
	<div class="acordeon">
		<a name="tecnicas">Técnicas Utilizadas</a>
		<table>
			<thead>
			  <tr>
				<th>Nombre</th>
				<th>Detalles</th>
				<th>Observaciones</th>
			  </tr>
			</thead>
			<tbody>
			  <?php  $_smarty_tpl->tpl_vars['tecnica'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tecnica']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tecnicas']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tecnica']->key => $_smarty_tpl->tpl_vars['tecnica']->value){
$_smarty_tpl->tpl_vars['tecnica']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['tecnica']->value['tecnica_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['tecnica']->value['tecnica_detalles'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['tecnica']->value['tecnicaprofesional_detalles'];?>

					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['equipamientos']->value){?>
	<div class="acordeon">
		<a name="tecnicas">Equipamiento científico y Grandes equipos</a>
		<table>
			<thead>
			  <tr>
				<th>Nombre</th>
				<th>Detalles</th>
				<th>Observaciones</th>
			  </tr>
			</thead>
			<tbody>
			  <?php  $_smarty_tpl->tpl_vars['equipamiento'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['equipamiento']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['equipamientos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['equipamiento']->key => $_smarty_tpl->tpl_vars['equipamiento']->value){
$_smarty_tpl->tpl_vars['equipamiento']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['equipamiento']->value['equipamiento_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['equipamiento']->value['equipamiento_detalles'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['equipamiento']->value['equipamientoprofesional_detalles'];?>

					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['asociaciones']->value){?>
	<div class="acordeon">
		<a name="asociaciones">Asociaciones a las que pertenece</a>
		<table>
			<thead>
			  <tr>
				<th>Nombre</th>
				<th>Detalles</th>
				<th>Observaciones</th>
				<th>Fecha Entrada</th>
				<th>Fecha Salida</th>
			  </tr>
			</thead>
			<tbody>
			  <?php  $_smarty_tpl->tpl_vars['asociacion'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['asociacion']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['asociaciones']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['asociacion']->key => $_smarty_tpl->tpl_vars['asociacion']->value){
$_smarty_tpl->tpl_vars['asociacion']->_loop = true;
?>
				<tr>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['asociacion']->value['asociacion_nombre'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['asociacion']->value['asociacion_detalles'];?>

					</td>
					<td>
						<?php echo $_smarty_tpl->tpl_vars['asociacion']->value['asociacionprofesional_detalles'];?>

					</td>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['asociacion']->value['asociacionprofesional_fechaentrada']!='0000'){?><?php echo $_smarty_tpl->tpl_vars['asociacion']->value['asociacionprofesional_fechaentrada'];?>
<?php }?>
					</td>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['asociacion']->value['asociacionprofesional_fechasalida']!='0000'){?><?php echo $_smarty_tpl->tpl_vars['asociacion']->value['asociacionprofesional_fechasalida'];?>
<?php }?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>

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