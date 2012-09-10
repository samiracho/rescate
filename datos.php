<?php
	include "admin/inc.php";
	
if(isset($_GET['accion']))
{	
	switch($_GET['accion'])
	{
		case 'noticias_lista':
		
			$pagina = 1;
	
			if( isset($_GET["pagina"])  )
			{
				$pagina = intval($_GET["pagina"]);
			}
			Noticia::ListarNoticias($pagina, 2, "datos.php?accion=noticias_lista&");
			
		break;
		
		case 'ubicacion':
			$id = 0;
			if( isset($_GET["id"])  ) $id = intval($_GET["id"]);
			Ubicacion::GenerarPlantilla($id);
		break;
		
		case 'ubicacion_lista':
			$letra = isset($_GET["letra"]) ? $_GET["letra"] : "todas";
			Ubicacion::GenerarPlantillaLista($letra);
		break;
		
		case 'profesional':
			$id = 0;
			if( isset($_GET["id"])  ) $id = intval($_GET["id"]);
			Profesional::GenerarPlantilla($id);
		break;
		
		case 'profesional_lista':
			$letra = isset($_GET["letra"]) ? $_GET["letra"] : "todas";
			$tipo = 'Restaurador';
			if( isset($_GET["tipo"])  )
			{
				if($_GET["tipo"] == 'Restaurador' || $_GET["tipo"] == 'Colaborador' || $_GET["tipo"] =='Especialista' || $_GET["tipo"] =='Autor' ) $tipo = $_GET["tipo"];
			}
			Profesional::GenerarPlantillaLista($letra,$tipo);
		break;
		
		case 'obra':
			$id = 0;
			if( isset($_GET["id"])  ) $id = intval($_GET["id"]);
			Obra::GenerarPlantilla($id);
		break;
		
		case 'obra_lista':
			$letra = isset($_GET["letra"]) ? $_GET["letra"] : "todas";	
			Obra::GenerarPlantillaLista($letra);
		break;
	}
}
?>