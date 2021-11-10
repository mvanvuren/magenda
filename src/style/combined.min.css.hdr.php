<?php
if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) { 
	ob_start("ob_gzhandler"); 
} else { 
	ob_start(); 
}
$cache_expire = 60 * 60 * 24 * 365;
header("Content-type: text/css; charset: UTF-8");
header('Pragma: public');
header('Cache-Control: max-age=' . $cache_expire);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_expire) . ' GMT');
?>