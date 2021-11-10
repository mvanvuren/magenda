$DestionationPath = "\\diskstation.mvanvuren.local\web\magenda"
$SiteLocal = "http://magenda.mvanvuren.local"

Copy-Item "podia.csv" -Destination $DestionationPath -Force
Copy-Item "events.csv" -Destination $DestionationPath -Force

$response = Invoke-WebRequest "$($SiteLocal)/import.php"
$response.Content
