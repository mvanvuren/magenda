$HostName = $Env:SftpMdHostName
$UserName = $Env:SftpMdUserName
$FingerPrint = $Env:SftpMdFingerPrint
$Protocol = "Sftp"
$LocalPath = "..\..\src"
$SafePath = "..\..\.safe"
$RemotePath = "/public/sites/magenda.mvanvuren.nl"
$SiteRemote = "https://magenda.mvanvuren.nl"

Import-Module WinSCP

$credential = Get-Credential $UserName

$sessionOption = New-WinSCPSessionOption -HostName $HostName -Protocol $Protocol -SshHostKeyFingerPrint $FingerPrint -Credential $credential

$session = New-WinSCPSession -SessionOption $sessionOption
Sync-WinSCPPath -WinSCPSession $session -RemotePath $RemotePath -LocalPath $LocalPath -Mode Remote -Remove:$true
Remove-WinSCPItem -WinSCPSession $session -Path "$($RemotePath)/config.php" -Confirm:$false
Send-WinSCPItem -WinSCPSession $session -LocalPath "$($SafePath)\config.nl.php" -RemotePath "$($RemotePath)/config.php"

Remove-WinSCPSession -WinSCPSession $session

Start-Process $SiteRemote
