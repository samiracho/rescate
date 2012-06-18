<?php /* Smarty version Smarty-3.1.5, created on 2012-06-18 13:39:05
         compiled from "C:\wamp\www\rescate\admin\..\plantillas\noticias.htm" */ ?>
<?php /*%%SmartyHeaderCode:301124fdf2f79051e85-97542866%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '56dda7906730496bc1b07911ce8132faea25d36a' => 
    array (
      0 => 'C:\\wamp\\www\\rescate\\admin\\..\\plantillas\\noticias.htm',
      1 => 1339974602,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '301124fdf2f79051e85-97542866',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'noticias' => 0,
    'noticia' => 0,
    'paginador' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_4fdf2f790b5df',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4fdf2f790b5df')) {function content_4fdf2f790b5df($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<link href="css/lista_noticias.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<h2>Noticias: <br></h2>
			<?php  $_smarty_tpl->tpl_vars['noticia'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['noticia']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['noticias']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['noticia']->key => $_smarty_tpl->tpl_vars['noticia']->value){
$_smarty_tpl->tpl_vars['noticia']->_loop = true;
?>
				<h6><?php echo $_smarty_tpl->tpl_vars['noticia']->value['noticia_fechapub'];?>
</h1>
				<h2><a href=""><?php echo $_smarty_tpl->tpl_vars['noticia']->value['noticia_titulo'];?>
</a></h2>
				<div class="noticiaCuerpo"><?php echo $_smarty_tpl->tpl_vars['noticia']->value['noticia_cuerpo'];?>
</div>
				<br />
			<?php } ?>
		<?php echo $_smarty_tpl->tpl_vars['paginador']->value;?>

	</body>
</html><?php }} ?>