# README

## Introduction

In 2013 [mAgenda](https://magenda.mvanvuren.nl) was developed to allow users to view upcoming music events within a specified range of their current location. To feed the server side with as much music events as possible, a lot of sources on the internet were scraped. As time went on, more and more sites dissapeared (or changed to drastically) that I decided to stop collecting data for it.

mAgenda is based on [jQuery Mobile](https://jquerymobile.com/) which also stopped support a couple of years ago.

Recently I started working on a configuration driven scraping framework. Looking for test material I decided to start collecting some data of music venues located near me. Only a few changes were needed to import the data into mAgenda. So for now mAgenda is running again...

## Future

I will probably drop the location based features ;-) and make some minor other changes, but expect not much more...

## ToDo's

- [ ] create GitHub repo
- [ ] drop location based functionality
- [ ] use cached internet sources
- [ ] check FP details PS WinSCP:

```powershell
$sessionOption = New-WinSCPSessionOption -HostName ftp.dotps1.github.io
$sshHostKeyFingerprint = Get-WinSCPSshHostKeyFingerprint -SessionOption $sessionOption
$sessionOption.SshHostKeyFingerprint = $sshHostKeyFingerprint
```
