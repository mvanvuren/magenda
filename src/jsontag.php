<?php
require_once 'init.php';

if (!isset($_REQUEST['name'])) return;
$name = $_REQUEST['name'];

$con = mysqli_connect($databasehost, $databaseusername, $databasepassword, $databasename); # or die(mysql_error());
mysqli_set_charset($con, "utf8");

$isAdded = false;
$result = mysqli_query($con, "delete from tag where userid = $userid and name = '$name'");
if (mysqli_affected_rows($con) != 1) {
    $result = mysqli_query($con, "insert into tag (userid, name) values ($userid, '$name')");
    $isAdded = true;
}
mysqli_close($con);
echo json_encode($isAdded);
?>