cd c:\wamp\www\rescate\admin
ls * -recurse -include *.js, *.php, *.css | Get-Content | Measure-Object -Line
$x = $host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")