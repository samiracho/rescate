#comprobar rutas antes de lanzar el script
$pathExtjs = "c:\wamp\www\"
$extjsFolder = "extjs-4.1.1-rc2"
$pathRescate = $pathExtjs+"rescate\admin"
$pathRescate2 = $pathExtjs+"rescate"
$pathExport  = "c:\wamp\www\"
$exportFolder = "Exportado\admin"
$exportFolder2 = "Exportado"
$archivosIdioma = "ext-lang-es.js","ext-lang-en.js"
$estilos = "ext-all.css"
$temas = "default"

$bdusuario = "rescate"
$bdpass = "Asry2345674C_"
$bdnombre = "rescate"

$cachePhpJS = "<?php
	ob_start ('ob_gzhandler');
	header('Content-type: text/javascript; charset=utf-8');
	//header('Cache-Control: must-revalidate');
	`$offset = 60 * 60 ;
	`$ExpStr = 'Expires: ' .
	gmdate('D, d M Y H:i:s', time() + `$offset) . ' GMT';
	header(`$ExpStr);
?>"

$cachePhpCSS = "<?php
	ob_start ('ob_gzhandler');
	header('Content-type: text/css; charset: UTF-8');
	//header('Cache-Control: must-revalidate');
	`$offset = 60 * 60 ;
	`$ExpStr = 'Expires: ' .
	gmdate('D, d M Y H:i:s', time() + `$offset) . ' GMT';
	header(`$ExpStr);
?>"

Write-Host "-Intentamos borrar los archivos temporales js antiguos"
Remove-Item $pathRescate\js\todo.js -ErrorAction "SilentlyContinue"
Remove-Item $pathRescate\js\temp.js -ErrorAction "SilentlyContinue"
Remove-Item $pathRescate\js\temp2.js -ErrorAction "SilentlyContinue"

Write-Host "-Concatenamos todos los archivos js en uno solo y lo comprimimos con el YuiCompressor 2.4.6"
cd $pathRescate\js
#cat 1aplicacion.js,2modelos.js,3stores.js,usuario.js,reconocimiento.js,asociacion.js,bibliografia.js,tecnica.js,especialidad.js,profesional.js,centro.js,ClearButton.js > temp.js -Encoding UTF8
ls *.js -Exclude 1config.js.php,login.js,todo.js|%{cat $_ >>"temp.js" -Encoding UTF8}

Get-Content -Encoding UTF8 temp.js | Out-File -Encoding UTF8 temp2.js
Remove-Item $pathRescate\js\temp.js -ErrorAction "SilentlyContinue"
java -jar $pathRescate\recursos\yuicompressor-2.4.6.jar --type js temp2.js -o todo.js

Write-Host "-Generamos todo.js.php preparado con cabecera de compresion"
New-Item todo.js.php -type file
Add-Content todo.js.php $cachePhpJS
$script = Get-Content "todo.js"
Add-Content todo.js.php  $script

Remove-Item $pathRescate\js\temp2.js -ErrorAction "SilentlyContinue"
Remove-Item -Recurse -Force $pathExport$exportFolder -ErrorAction "SilentlyContinue"
New-Item -path $pathExport -name $exportFolder -type directory 

Write-Host "-Exportamos el proyecto quitando los archivos .svn y los directorios innecesarios"
XCopy $pathRescate $pathExport$exportFolder /EXCLUDE:$pathRescate\recursos\No.SVN.txt /E /C /I /F /R /Y /Q
Remove-Item $pathRescate\js\todo.js -ErrorAction "SilentlyContinue"
Remove-Item $pathRescate\js\todo.js.php -ErrorAction "SilentlyContinue"

Write-Host "-Exportamos el proyecto quitando los archivos .svn y los directorios innecesarios"
XCopy $pathRescate2 $pathExport$exportFolder2 /EXCLUDE:$pathRescate\recursos\No.SVN.txt /E /C /I /F /R /Y /Q

Write-Host "-Ponemos el index.php en modo producción"
cd $pathExport$exportFolder
Remove-Item index.php -ErrorAction "SilentlyContinue"
Rename-item index-final.php -newname index.php

Write-Host "-Copiamos los archivos necesarios de extjs y eliminamos todos los demás"
cd $pathExport$exportFolder\js
Remove-Item * -exclude todo.js,todo.js.php,login.js,1config.js.php -ErrorAction "SilentlyContinue"
Copy-Item $pathExtjs$extjsFolder -Recurse -Destination $extjsFolder
cd $pathExport$exportFolder\js\$extjsFolder
Remove-Item -Recurse -Force build,builds,docs,examples,jsbuilder,overview,pkgs,src,welcome -ErrorAction "SilentlyContinue"
Remove-Item *.js,*.txt,*.html,*.jsb2 -exclude ext-all.js -ErrorAction "SilentlyContinue"

Write-Host "-Generamos ext-all.js.php preparado con cabecera de compresion"
New-Item ext-all.js.php -type file
Add-Content ext-all.js.php $cachePhpJS
$script = Get-Content "ext-all.js"
Add-Content ext-all.js.php  $script

Write-Host "-Borramos los archivos de idioma que no necesitemos"
cd locale
Remove-Item *.js -exclude $archivosIdioma -ErrorAction "SilentlyContinue"
cd ..

Write-Host "-Borramos los estilos que no necesitamos"
cd resources\css
Remove-Item * -exclude $estilos -ErrorAction "SilentlyContinue"

Write-Host "-Generamos $estilos preparado con cabecera de compresion"
New-Item $estilos".php" -type file
Add-Content $estilos".php" $cachePhpCSS
$script = Get-Content $estilos
Add-Content $estilos".php"  $script


cd ..

Write-Host "-Borramos la carpeta sass"
Remove-Item -Recurse -Force sass -ErrorAction "SilentlyContinue"

Write-Host "-Borramos los temas que no necesitamos"
cd themes
Remove-Item -Recurse -Force lib,templates,stylesheets -ErrorAction "SilentlyContinue"
Remove-Item compass_init.rb -ErrorAction "SilentlyContinue"

cd images
Remove-Item -Recurse -Force * -exclude $temas -ErrorAction "SilentlyContinue"

Write-Host "-Hacemos que el proyecto busque la librería extjs en el lugar adecuado"
cd $pathExport$exportFolder

Write-Host "-Comprimimos css"
java -jar $pathRescate\recursos\yuicompressor-2.4.6.jar --type css comun.css -o todo.css
Remove-Item comun.css -ErrorAction "SilentlyContinue"
#$file2 = Get-Content "comprimido.css"
#Add-Content  $pathExport$exportFolder\js\$extjsFolder\resources\css\ext-all.css $file2


Remove-Item comun.css -ErrorAction "SilentlyContinue"

(Get-Content config.php) | 
Foreach-Object { $_ -replace "../../"+$extjsFolder, "js"+$extjsFolder } | 
Set-Content config.php

#resca_bd
(Get-Content config.php) | 
Foreach-Object { $_ -replace '"BD_USUARIO","root"', '"BD_USUARIO","rescate"' } | 
Set-Content config.php

#u7amupa9
(Get-Content config.php) | 
Foreach-Object { $_ -replace '"BD_PASS",""', '"BD_PASS","Asry2345674C_"' } | 
Set-Content config.php

#rescate_prueba
(Get-Content config.php) | 
Foreach-Object { $_ -replace '"BD_NOMBRE","rescate"','"BD_NOMBRE","rescate"' } | 
Set-Content config.php

(Get-Content config.php) | 
Foreach-Object { $_ -replace '"FORZAR_SSL",false', '"FORZAR_SSL",false' } | 
Set-Content config.php

cd $pathRescate\recursos

Write-Host " "
Write-Host "Proyecto exportado a "$pathExport$exportFolder
Write-Host "No olvide editar config.php para revisar los datos"
Write-Host "Presione cualquier tecla para continuar ..."
$x = $host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

