<?php

require_once 'config.php';
setlocale(LC_TIME, 'en_US');
require_once 'language.en.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$con = new mysqli($databasehost, $databaseusername, $databasepassword, $databasename); # or die(mysqli_connect_error());
mysqli_set_charset($con, "utf8");

$userid = 6150;
$code = 402;
$cityid = 641;
$range = 30;
$ismobile = isMobile();

header("Content-Type: text/html; charset=UTF-8");

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function formatDate($date) 
{
    list($year, $month, $day) = explode('-', $date);
    $time = mktime(0, 0, 0, $month, $day, (int)$year);
    
    return strftime("%a %d-%m-%y", $time);
}

function formatShortDate($date) 
{
    list($year, $month, $day) = explode('-', $date);
    $time = mktime(0, 0, 0, $month, $day, (int)$year);
    
    return strftime("%d-%m", $time);
}

?>