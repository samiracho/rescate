#comprobar rutas antes de lanzar el script
$pathRescate = "c:\wamp\www\rescate"


Write-Host "-Concatenamos todos los archivos js en uno solo y lo comprimimos con el YuiCompressor 2.4.6"
cd $pathRescate\js
ls *.js -Exclude 1config.js.php,login.js,todo.js|%{cat $_ >>"temp.js" -Encoding UTF8}