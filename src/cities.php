<?php
require_once 'init.php';
?>
<!DOCTYPE html>  
<html>  
<?php require_once 'head.php'; ?>
<body>
<?php
$query  = <<<EOL
select 
    mny.id as cityid, mny.name as city, (select count(1) from event evt where evt.municipalityid = mny.id and evt.date >= curdate()) as total
from 
    municipality mny
where
    (
        mny.id = $cityid
        or exists (
            select 1 from distance dtt where dtt.frommunicipalityid = $cityid and dtt.tomunicipalityid = mny.id and dtt.distance <= $range
            union
            select 1 from distance dtt where dtt.frommunicipalityid = mny.id and dtt.tomunicipalityid = $cityid and dtt.distance <= $range
        )
    )
   and (select count(1) from event evt where evt.municipalityid = mny.id and evt.date >= curdate()) > 0
order by
    mny.name
EOL;

$result = mysqli_query($con, $query);
$filter = mysqli_num_rows($result) > 10 ? "true" : "false";
?>
<div id="genres" data-role="page" data-dom-cache="true">    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1><?=$label['cities_title']?></h1>        
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul data-role="listview" data-filter="<?=$filter?>" data-mini="true">						
        <?php
$header1 = false;
$header2 = false;
while (list($id, $city, $total) = mysqli_fetch_row($result)) {
    echo "<li><a href=\"cevents.php?id=$id\">$city<span class=\"ui-li-count\">$total</span></a></li>";
} 	
        ?>
        </ul>
    </div><!-- /content -->
</div><!-- /page -->
</body>
</html>
<?php
require_once 'menu.php';
mysqli_close($con);
?>