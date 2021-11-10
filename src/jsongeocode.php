<?php
require_once 'init.php';

$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];

$con = mysqli_connect($databasehost, $databaseusername, $databasepassword, $databasename); # or die(mysql_error());
mysqli_set_charset($con, "utf8");

$query=<<<EOL
select 
    mpe.postalcode, mny.id as cityid
from 
    municipality mny  inner join municipalitypostalcode mpe on mny.id = mpe.municipalityid
order by
    sqrt(power(mny.latitude - $latitude, 2) + power(mny.longitude - $longitude, 2))
limit 1
EOL;
list($postalcode, $cityid) = mysqli_fetch_row(mysqli_query($con, $query));
mysqli_close($con);

echo json_encode(array('code' => $postalcode, 'cityid' => $cityid));
?>

