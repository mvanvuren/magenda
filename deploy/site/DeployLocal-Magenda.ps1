$SourcePath = "..\..\src"
$SafePath = "..\..\.safe"
$DestionationPath = "\\diskstation.mvanvuren.local\web\magenda"
$SiteLocal = "http://magenda.mvanvuren.local"

Copy-Item "$($SourcePath)\*" -Destination $DestionationPath -Recurse -Force
Copy-Item "$($SafePath)\config.local.php" -Destination "$($DestionationPath)\config.php" -Force

Start-Process $SiteLocal