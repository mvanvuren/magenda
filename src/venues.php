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
    pdm.id, pdm.name as stage, pdm.link, mny.name as city, (select count(1) from event evt where evt.podiumid = pdm.id and evt.date >= curdate()) as total
from 
    podium pdm 
    inner join municipality mny on pdm.municipalityid = mny.id
where 
    (pdm.municipalityid = $cityid 	
    or exists (
        select 1 from distance dtt where dtt.frommunicipalityid = $cityid and dtt.tomunicipalityid = pdm.municipalityid and dtt.distance <= $range
        union
        select 1 from distance dtt where dtt.frommunicipalityid = pdm.municipalityid and dtt.tomunicipalityid = $cityid and dtt.distance <= $range
    ))	
    and exists (select 1 from event evt where evt.podiumid = pdm.id and evt.date >= curdate())
order 
    by pdm.name
EOL;

$result = mysqli_query($con, $query);
$filter = mysqli_num_rows($result) > 10 ? "true" : "false";
?>
<div id="venues" data-role="page" data-dom-cache="true">
    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars"><?=$label['venues_title']?></a>
        <h1><?=$label['venues_title']?></h1>
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul data-role="listview" data-filter="<?=$filter?>" data-mini="true" >				
        <?php
        while (list($id, $stage, $link, $city, $total) = mysqli_fetch_row($result)) {
            echo "<li><a href=\"vevents.php?id=$id\">$stage<p>$city</p><span class=\"ui-li-count\">$total</span></a></li>";
        }
        ?>
        </ul>
    </div><!-- /content -->
</div><!-- /page -->
<?php require_once 'menu.php'; ?>
</body>
</html>
<?php
mysqli_close($con);
?>

