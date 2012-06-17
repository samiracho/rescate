<?php

// EN ESTE ARCHIVO DEFINIREMOS TODOS LOS DATOS DE CONFIGURACIÓN
 
// VERSION DE RESCATE
define("REVISION_RESCATE","309");
 
// DIRECTORIOS DE LA APLICACION
define("CARPETA_APLICACION","rescate/admin"); // nombre de la carpeta donde se alojará la aplicación
define("CARPETA_ARCHIVOS","archivos");
define("RUTAREL_APLICACION","/".CARPETA_APLICACION);
define("RUTA_ARCHIVOS",realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.CARPETA_ARCHIVOS); // ruta absoluta donde almacenaremos los archivos
define("RUTA_CACHE",RUTA_ARCHIVOS.DIRECTORY_SEPARATOR."cache");
define("DOMINIO","http://localhost");
define("URL_ARCHIVOS",'/'.CARPETA_APLICACION.'/'.CARPETA_ARCHIVOS);
 
// CONFIGURACION BASE DE DATOS
define("BD_SERVIDOR","localhost");	// servidor bd
define("BD_USUARIO","root");		// login bd
define("BD_PASS","");				// pass bd
define("BD_NOMBRE","rescate");		// nombre bd

// CONFIGURACIÓN INTERNA
define("RESCATE_CLAVE","clave_secreta_rescate011"); // texto para crear hashes md5
define("SELECT_LIMIT",50); // numero por defecto de registros mostrados por página
define("EXT_PATH","../../extjs-4.1.1-rc2"); // ruta de extjs
define("FORZAR_SSL",false); // para forzar su uso via https
define("TTL_SESION",18000); //tiempo en segundos que una sesión de usuario puede estar inactiva antes de cerrarla automáticamente
define("USAR_COMPRESION",true); // para servir los archivos javascript y css comprimidos
define("LIMPIAR_HTML",false); // limpiará los campos de tipo html para que cumplan el formato html establecido
define("FORMATO_HTML", 'XHTML 1.0 Strict'); // formato para los campos de tipo html

// CONFIGURACIÓN CACHÉ
define("TTL_CACHE",864000); // tiempo que se guarda el cache de las consultas cacheadas en segundos (86400 = 10 días)
define("SMARTY_CACHING",false);
define("SMARTY_CACHELIFETIME",3600); // por defecto una hora
define("GUARDAR_ESTADO_PANELES",false); // define si se guardará el estado de los paneles. Tamaño/posición/columnas e.t.c
define("GUARDAR_ESTADO_PANELES_SRV",false); // si es true, el estado de los paneles se guardará en el servidor. Sino se guardará en una Cookie.
define("ESTADO_PANELES_LIFETIME",1000*60*60*24*30); // el tiempo que se guardará la cookie con el estado de los paneles (30 días)

// CONFIGURACION SMARTY
define("SMARTY",realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."smarty".DIRECTORY_SEPARATOR."Smarty.class.php");
define("SMARTY_PLANTILLAS",realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."plantillas"); // ruta absoluta donde almacenaremos las plantillas smarty

// FORMATOS DE FECHA
define("FORMATO_FECHA","d/m/Y");
define("FORMATO_FECHA_MYSQL","%d/%m/%Y");
define("FORMATO_FECHA_MYSQL_ANYO","%Y");
define("FECHA_VACIA","00/00/0000");

// ARCHIVOS
define("IMAGENES_PERMITIDAS",".jpg,.jpeg,.gif,.png");
define("DOCUMENTOS_PERMITIDOS",".doc,.docx,.odf,.pdf,.txt,.xls,.xlsx,.zip,.rar");
define("SONIDOS_PERMITIDOS",".mp3");
define("VIDEOS_PERMITIDOS",".avi,.ogg,.mpg,.mpeg");
define("TAM_MAX","10485760"); // 10MB
define("TAM_MINIATURAS", "128");

?>
