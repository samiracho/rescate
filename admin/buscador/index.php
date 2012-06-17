<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>


<style type="text/css">
  
/* Estilos por defecto para los campos del formulario*/
input, select, textarea {
	box-sizing: border-box;
	margin-right:4px;
	margin-bottom:4px;
	border: 1px solid #ccc;
}


input:hover, textarea:hover,
input:focus, textarea:focus { 
	border-color: #C9C9C9; 
}

/*Tabla que contiene un grupo de filtros*/
.contenedorGrupo{
	border:none;
	width:100%;
	margin-bottom:8px;
	border:1px solid #a2a2a2;
	border-collapse: collapse;
	table-layout:fixed; /*Estúpido IE7, sin esto ignora las celdas con anchura fija*/
}
  
.contenedorGrupo td{
	border:1px solid #a2a2a2;
	vertical-align: top;
}
  
.contenedorCampos{
	padding:4px;
	height:100px;
	width:120px
}
  
.contenedorFiltros{
	padding:4px;
}

.contenedorFiltros div
{
	min-width:630px;	
}
  
.contenedorBotones{
}
  
.grupoBusqueda{
  	width:100%;
}

.botonAgregarGrupo{
  	background: white url('imagenes/add.png') no-repeat center left;
	padding:4px 4px 4px 16px;
	margin:4px;
	font-size:11px;
	text-align:left;
	width: 100px; 
}

.botonQuitarGrupo:hover, .botonAgregarGrupo:hover{
	background-color:#ccc;
	text-decoration:underline;
}
  
.botonQuitarGrupo{
  	background: white url('imagenes/delete.png') no-repeat 0px 3px;
	padding:4px 4px 4px 16px;
	margin:4px;
	font-size:11px;
	text-align:left;
	width: 100px; 
}

  
 /* Los campos de los filtros*/
.filtroValor , .filtroCampo{
	width:200px;
}
  
.filtroValorSelect{
	width:200px;
	*width:204px; /*Para IE7 o inferior. Tiene que ser 4px más grande para tener el mismo tamaño que un campo de texto*/
}
  
.filtroUnion ,.filtroGrupoCampo{
	width:100%;
}
  
.filtroComparador{
	width:100px;
}

.filtroQuitar{
  	background: transparent url('imagenes/delete.png') no-repeat 2px 2px;
	border:none;
	width:20px;
	height: 20px; /* Estúpido IE8. Si no defino alto hace desaparecer el botón*/
}

.filtroQuitar:hover{
  	background-position: 2px -20px;
}
  
</style>
  

<script type="text/javascript" src="jquery-1.7.min.js"></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<script type='text/javascript' src='jquery.inputmask.js'></script>
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />


<script type="text/javascript">

<?php

	include "../inc.php";
	$profesional   = new Profesional();
	$profesional->ObtenerJsonBusqueda();
?>
	;

	
	$(document).ready(function() {
	
		var formularioBusqueda = {
			idFormulario: '#filtrosBusqueda',
			numGrupos: 0,
			camposFormulario: camposBusqueda, // campos de búsqueda (variable generada en el servidor)
			limiteGrupos: 3, // número máximo de grupos que podrán añadirse
			limiteFiltros: 6, // número máximo de filtros que podrán añadirse por grupo
			limiteOpciones: 20, // número máximo de opciones que se mostrarán en una lista de selección.
			crearGrupoBusqueda: function() {
				
				var me = this;
				
				if( me.numGrupos >= me.limiteGrupos) {
					alert ('No se pueden aplicar más de '+me.limiteGrupos+' grupos de filtros');
					return;
				}
				
				var formulario       = $(me.idFormulario);
				var selector         = $( document.createElement('select') ).attr({'class':'filtroGrupoCampo'} );;
				var botonQuitar      = $( document.createElement('input') ).attr({'type':'button','value':'Quitar Grupo','class':'botonQuitarGrupo','title':'Quitar grupo de filtros'});
				var botonAgregar     = $( document.createElement('input') ).attr({'type':'button','value':'Agregar Grupo','class':'botonAgregarGrupo','title':'Agregar un nuevo grupo de filtros'});
				var selectorUnion    = $( document.createElement('select') ).attr({'class':'filtroUnion','name':'grupo['+me.numGrupos+'][union]'} );
				
				var contenedorGrupo  =	$( document.createElement('table') ).attr({'class':'contenedorGrupo','numGrupos':me.numGrupos});
				contenedorGrupo.append('<tr><td class="contenedorCampos"></td><td class="contenedorFiltros"></td></tr><td class="contenedorBotones" colspan="2"></td></tr>');
				
				var contenedorCampos  = contenedorGrupo.find('.contenedorCampos');
				var contenedorBotones = contenedorGrupo.find('.contenedorBotones');
				
				selectorUnion.append('<option value="0"> Y </option><option value="1"> Y NO </option><option value="2"> O </option><option value="3"> O NO </option>');		
				selector.append('<option value=" ">Agregar Filtro</option>');		
				
				selector.on("change",function(){ $.proxy( me.crearFiltro(this), me )});
				botonAgregar.on("click",function(){ $.proxy( me.crearGrupoBusqueda(), me )});
				botonQuitar.on("click",function(){$(this).parents('table').remove();me.numGrupos--});	
			
				for(i = 0; i < this.camposFormulario.length; i++ )
				{
					selector.append('<option value="'+i+'">'+this.camposFormulario[i]['nombre']+'</option>');
				}		
				
				contenedorBotones.append(botonAgregar);
				if(me.numGrupos > 0){contenedorBotones.append(botonQuitar);contenedorCampos.append(selectorUnion)};
				
				contenedorCampos.append(selector);
				
				if(me.numGrupos > 0)formulario.append(contenedorGrupo);
				else formulario.prepend(contenedorGrupo);

				me.numGrupos++;
			},
			crearFiltro: function(miSelector) {
			
				var me = this;
				var numGrupo   = $(miSelector).parents('table').attr('numgrupos');		
				var numFiltro  = $(miSelector).parents('table').find('.contenedorFiltros').children('div').length;
						
				if( numFiltro >= this.limiteFiltros ){
					alert ('No se pueden aplicar más de '+this.limiteFiltros+' filtros por grupo');
					return;
				}
				
				var valor        = miSelector.value;
				var grupoFiltros = $( document.createElement('div') ).attr({'numfiltro':numFiltro} );
				var operador     = $( document.createElement('select') ).attr({'class':'filtroOperador','name':'grupo['+numGrupo+'][filtro]['+numFiltro+'][operador]'} );
				var selector     = $( document.createElement('select') ).attr({'class':'filtroCampo','name':'grupo['+numGrupo+'][filtro]['+numFiltro+'][campo]'} );
				
				operador.append('<option value="0"> Y </option><option value="1"> Y NO </option><option value="2"> O </option><option value="3"> O NO </option>');		
				selector.append('<option value="">Seleccione un campo</option>');

				selector.on("change",function(){ $.proxy( me.crearCamposFiltro(this), me )});
				
				for(i = 0; i < this.camposFormulario[valor]['campos'].length; i++ )
				{	
					selector.append('<option value="'+this.camposFormulario[valor]['campos'][i]['nombre']+'" indicegrupo="'+valor+'" tipo="'+this.camposFormulario[valor]['campos'][i]['tipo']+'">'+this.camposFormulario[valor]['campos'][i]['nombre']+'</option>');
				}
				
				// si es el primer filtro no tiene sentido el operador, así que lo desactivamos
				if(numFiltro == 0)operador.attr({'disabled':true} ).css('visibility','hidden');
				
				grupoFiltros.append(operador);
				grupoFiltros.append(selector);
				
				// agregamos el grupo de filtros al div contenedor de filtros
				$($(miSelector).parent().siblings('.contenedorFiltros')[0]).append(grupoFiltros);
				
				// volvemos a seleccionar la opción vacía del selector
				$(miSelector).val(" ");		
			},
			crearCamposFiltro: function(miSelector)
			{
				var esLista     = false;
				var numGrupo    = $(miSelector).parents('table').attr('numgrupos');
				var grupo       = $(miSelector).parent("div"); 
				var opcion      = $(miSelector).children("option").eq($(miSelector)[0].selectedIndex);	
				var tipoDato    = opcion.attr('tipo');
				var campoValor  = $( document.createElement('input') ).attr({'type':'text','class':'filtroValor'});	
				var selector    = $( document.createElement('select') ).attr({'class':'filtroComparador','name':'grupo['+numGrupo+'][filtro]['+grupo.attr('numfiltro')+'][comparador]'} );		

				// si es un array crearemos un select con la lista, sino crearemos un campo de texto vacío
				// si hay demasiadas opciones pondremos un campo de texto con autocompletar
				var opcionesCampo = this.camposFormulario[opcion.attr('indicegrupo')]['campos'][ $(miSelector)[0].selectedIndex -1 ]['opciones'];
				if( opcionesCampo instanceof Array )
				{
					esLista         = true;
					var numOpciones = opcionesCampo.length
					
					if(numOpciones > this.limiteOpciones)
					{
							campoValor.autocompleteArray(opcionesCampo,
							{
								delay:10,
								minChars:1,
								matchSubset:1,
								autoFill:true,
								maxItemsToShow:10,
								selectFirst:true
							}
						)	
					}
					else
					{			
						campoValor = $( document.createElement('select') ).attr({'class':'filtroValorSelect'});
						for (i = 0; i < numOpciones; i++)
						{
							campoValor.append('<option value="'+opcionesCampo[i]+'">'+opcionesCampo[i]+'</option>');
						}
					}
				}
				
				// si es una lista de opciones solo permitiremos los operadores "=" y "distinto de", independientemente del tipo de dato de que se trate			
				if (esLista == true)
				{
					selector.append('<option value="0"> = </option><option value="1"> Distinto de </option>');
				}
				else if(tipoDato == "string" || tipoDato == "html")
				{
					selector.append('<option value="0"> = </option><option value="1"> Distinto de </option><option value="2"> Parecido a </option>');
				}
				else if (tipoDato == "int" || tipoDato == "id")
				{
					selector.append('<option value="0"> = </option><option value="4"> > </option><option value="5"> < </option><option value="5"> Distinto de </option>');
					campoValor.inputmask("99999999");
				}
				else if (tipoDato == "date" )
				{
					selector.append('<option value="0"> Antes de </option><option value="4"> Después de </option><option value="5"> = </option>');
					
					campoValor.inputmask("d/m/y");
					campoValor.val("30/12/1900");
				}
			
				campoValor.attr({'name':'grupo['+numGrupo+'][filtro]['+grupo.attr('numfiltro')+'][valor]'});
				
				var botonQuitar    = $( document.createElement('input') ).attr({'type':'button','class':'filtroQuitar','title':'Eliminar Filtro','value':' '});
				
				// si ya los había creado los elimino y los vuelvo a crear para que reflejen los cambios.
				grupo.find('.filtroComparador').remove();
				grupo.find('.filtroValor').remove();
				grupo.find('.filtroQuitar').remove();
				
				grupo.append(selector);
				grupo.append(campoValor);
				grupo.append(botonQuitar);
				
				
				botonQuitar.on('click',function()
				{
					var contenedor = $(this).parent('div');
					//eliminamos la div que contiene al filtro
					contenedor.remove();
				});
			}
		};
		
		formularioBusqueda.crearGrupoBusqueda();
	});


</script>


</head>

<body>

<form name="form1" method="post" action="check.php">
  <div id="filtrosBusqueda"></div>
  <input type="submit" name="button" id="button" value="Buscar" style="float:right" />
</form>

</body>
</html>
