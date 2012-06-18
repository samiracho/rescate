<?php /* Smarty version Smarty-3.1.5, created on 2012-06-18 13:50:49
         compiled from "C:\wamp\www\rescate\admin\..\plantillas\profesional_lista.htm" */ ?>
<?php /*%%SmartyHeaderCode:183324fdf323993f902-14251837%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a3ae8b0176320cac6babb8e901b0da05a8667bda' => 
    array (
      0 => 'C:\\wamp\\www\\rescate\\admin\\..\\plantillas\\profesional_lista.htm',
      1 => 1339974602,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183324fdf323993f902-14251837',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'profesionales' => 0,
    'columnas' => 0,
    'columnHeight' => 0,
    'letra' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_4fdf3239aea61',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4fdf3239aea61')) {function content_4fdf3239aea61($_smarty_tpl) {?><?php if (!is_callable('smarty_function_math')) include 'C:\\wamp\\www\\rescate\\admin\\lib\\smarty\\plugins\\function.math.php';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Base de Datos</title>
		<link href="css/b_stylerescate.css" rel="stylesheet" type="text/css" />
		<link href="css/backgroundrest.css" rel="stylesheet" type="text/css" />		
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
<h6><a href="dbs_c.html"><< Atrás </a></h6>

<h2>Lista de Restauradores</h2>



<table class="abecedario">
<tr>
<td><a href="datos.php?accion=profesional_lista&letra=todas">*</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=A">A</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=B">B</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=C">C</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=D">D</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=E">E</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=F">F</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=G">G</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=H">H</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=I">I</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=J">J</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=K">K</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=L">L</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=M">M</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=N">N</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=Ñ">Ñ</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=O">O</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=P">P</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=Q">Q</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=R">R</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=S">S</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=T">T</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=U">U</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=V">V</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=W">W</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=X">X</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=Y">Y</a></td>
<td><a href="datos.php?accion=profesional_lista&letra=Z">Z</a></td>
</tr>
</table>



<?php if (!empty($_smarty_tpl->tpl_vars['profesionales']->value)){?>
<!--Numero de columnas que queremos mostrar-->
<?php $_smarty_tpl->tpl_vars["columnas"] = new Smarty_variable("4", null, 0);?> 

<?php echo smarty_function_math(array('equation'=>"ceil(count / columnas)",'count'=>count($_smarty_tpl->tpl_vars['profesionales']->value),'columnas'=>$_smarty_tpl->tpl_vars['columnas']->value,'assign'=>"columnHeight"),$_smarty_tpl);?>
 

<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['tr'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['tr']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['name'] = 'tr';
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['columnHeight']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['tr']['total']);
?> 

	<?php if ($_smarty_tpl->getVariable('smarty')->value['section']['tr']['first']==true){?> 
	<table class="lista"> 
	<?php }?> 

	<tr> 
		<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['td'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['td']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['name'] = 'td';
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start'] = (int)$_smarty_tpl->getVariable('smarty')->value['section']['tr']['index'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['step'] = ((int)$_smarty_tpl->tpl_vars['columnHeight']->value) == 0 ? 1 : (int)$_smarty_tpl->tpl_vars['columnHeight']->value;
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['columnHeight']->value*$_smarty_tpl->tpl_vars['columnas']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['td']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['td']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['td']['total']);
?> 
		<td> 
		<?php if (isset($_smarty_tpl->tpl_vars['profesionales']->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['td']['index']])){?> 
			<a href="datos.php?accion=profesional&id=<?php echo $_smarty_tpl->tpl_vars['profesionales']->value[$_smarty_tpl->getVariable('smarty')->value['section']['td']['index']]['profesional_id'];?>
">
				<?php if ($_smarty_tpl->tpl_vars['profesionales']->value[$_smarty_tpl->getVariable('smarty')->value['section']['td']['index']]['profesional_apellido1']!=''){?> <?php echo $_smarty_tpl->tpl_vars['profesionales']->value[$_smarty_tpl->getVariable('smarty')->value['section']['td']['index']]['profesional_apellido1'];?>
 <?php }?> 
				<?php if ($_smarty_tpl->tpl_vars['profesionales']->value[$_smarty_tpl->getVariable('smarty')->value['section']['td']['index']]['profesional_apellido2']!=''){?> <?php echo $_smarty_tpl->tpl_vars['profesionales']->value[$_smarty_tpl->getVariable('smarty')->value['section']['td']['index']]['profesional_apellido2'];?>
, <?php }?>
				<?php if ($_smarty_tpl->tpl_vars['profesionales']->value[$_smarty_tpl->getVariable('smarty')->value['section']['td']['index']]['profesional_nombre']!=''){?> <?php echo $_smarty_tpl->tpl_vars['profesionales']->value[$_smarty_tpl->getVariable('smarty')->value['section']['td']['index']]['profesional_nombre'];?>
 <?php }?>
			</a> 
		<?php }?> 
		</td> 
		<?php endfor; endif; ?> 
	</tr> 
	
	<?php if ($_smarty_tpl->getVariable('smarty')->value['section']['tr']['last']==true){?> 
	</table> 
	<?php }?> 
  
<?php endfor; endif; ?> 

<?php }else{ ?>
	<div style="text-align:center; width:100%;padding-bottom:60px">No se encontraron resultados con la letra <?php echo $_smarty_tpl->tpl_vars['letra']->value;?>
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