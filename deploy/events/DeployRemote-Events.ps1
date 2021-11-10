$HostName = $Env:SftpMdHostName
$UserName = $Env:SftpMdUserName
$FingerPrint = $Env:SftpMdFingerPrint
$Protocol = "Sftp"
$RemotePath = "/public/sites/magenda.mvanvuren.nl"
$SiteRemote = "https://magenda.mvanvuren.nl"

Import-Module WinSCP

$credential = Get-Credential $UserName

$sessionOption = New-WinSCPSessionOption -HostName $HostName -Protocol $Protocol -SshHostKeyFingerPrint $FingerPrint -Credential $credential

$session = New-WinSCPSession -SessionOption $sessionOption
Send-WinSCPItem -WinSCPSession $session -LocalPath "podia.csv" -RemotePath "$($RemotePath)"
Send-WinSCPItem -WinSCPSession $session -LocalPath "events.csv" -RemotePath "$($RemotePath)"

Remove-WinSCPSession -WinSCPSession $session

$response = Invoke-WebRequest "$($SiteRemote)/import.php"
$response.Content
