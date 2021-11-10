<?php

require_once 'config.php';

//$english = (isset($_COOKIE['language']) && $_COOKIE['language'] == 'en');
$english = True;
$locale = $english ? 'en_US' : 'nl_NL';
setlocale(LC_TIME, $locale);
require_once $english ? 'language.en.php' : 'language.nl.php';

$con = mysqli_connect($databasehost, $databaseusername, $databasepassword, $databasename); # or die(mysql_error());
mysqli_set_charset($con, "utf8");

$code = intval($_COOKIE['code']);
$cityid = intval($_COOKIE['cityid']);
$range = intval($_COOKIE['range']);
$width = intval($_COOKIE['width']);
$ismobile = $width <= 640;

$userid = 0;
if (isset($_COOKIE['userid'])) {
    $cuserid = $_COOKIE['userid'];
    list($uid, $userid) = explode('-', $cuserid);
    $userid = base_convert($userid, 16, 10);
    $query = "update user set counter = counter + 1, datelast = now(), code = $code, cityid = $cityid, `range` = $range where id = $userid and uid = '$uid'";
    $result = mysqli_query($con, $query); #or die(mysql_error());
} 
if (mysqli_affected_rows($con) != 1) {
    if (!isBot()) {
        $uid = uniqid();
        $userAgent = mysqli_real_escape_string($con, $_SERVER['HTTP_USER_AGENT']);
        $query = "insert into user (uid, counter, datelast, information) values ('$uid', 1, now(), '$userAgent')";
        $result = mysqli_query($con, $query); #or die(mysql_error());
        $userid = mysqli_insert_id($con);
        $cuserid = sprintf('%s-%08x', $uid, $userid);
        setcookie('userid', $cuserid, time() + 31536000);
    }
}
// if (isset( $_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) { 
//     ob_start("ob_gzhandler"); 
// } else { 
//     ob_start(); 
// }
header("Content-Type: text/html; charset=UTF-8");

function isBot() 
{ 
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return true;
    }
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $bots = array(
        'bingbot', 'Birubot', 'bitlybot', 'Butterfly',
        'DotBot', 
        'Embedly', 'Exabot', 'Ezooms', 
        'facebookexternalhit', 
        'GIDBot', 'Googlebot', 
        'Jyxobot',
        'LinkedInBot', 
        'msnbot', 
        'PaperLiBot', 'PostPost', 'PostRank',
        'SISTRIX Crawler', 'Showyoubot', 'Summify', 
        'TurnitinBot', 'TweetmemeBot', 'TweetedTimes Bot', 'Twitterbot', 
        'UnwindFetchor', 
        'Vagabondo', 
        'YandexBot', 'Yahoo! Slurp'
    );
    foreach ($bots as $bot) {
        if (!(strpos($userAgent, $bot) === false)) {
            return true;
        }
    }
    return false;
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

mysqli_close($con);
?>