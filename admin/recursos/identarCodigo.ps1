$path = "c:\wamp\www\rescate\admin\recursos\jsbeautifier"
$JSpath = "c:\wamp\www\rescate\admin\js\"
$beautifyPath = "c:\wamp\www\rescate\admin\js\beautified\"

cd $JSpath

ls *.js | Foreach {$i=1} `
{
   C:\wamp\www\rescate\admin\recursos\jsbeautifier\jsBeautifier.net.exe "sourceFile=$_"  "destinationFile=$beautifyPath$(($_).Basename).js" "indent=4" "bracesInNewLine=true" "preserveEmptyLines=false" "detectPackers=false" "keepArrayIndent=false" | Out-Null
}

# sourceFile is the only required command line argument, all others are optional.
#./jsBeautifier.net "sourceFile=C:\wamp\www\rescate\js\profesional.js" "destinationFile=C:\TextToJs1.txt" "indent=4" "bracesInNewLine=true" "preserveEmptyLines=true" "detectPackers=false" "keepArrayIndent=false" | Out-Null

"Javascript beautification complete..."
