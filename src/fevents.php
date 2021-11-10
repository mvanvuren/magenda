<?php require_once 'init.php'; ?>
<!DOCTYPE html>  
<html>  
<?php require_once 'head.php'; ?>
<body>
<?php
$query  = <<<EOL
select 
    evt.id, evt.date, evt.name, pdm.name as venue, mny.name as city, evt.attendance
from 
    event evt 
    inner join municipality mny on evt.municipalityid = mny.id
    left join podium pdm on evt.podiumid = pdm.id
where 
    evt.isfestival = 1
    and evt.date >= curdate()
order 
    by evt.date, evt.name
EOL;

$result = mysqli_query($con, $query);
$filter = mysqli_num_rows($result) > 10 ? "true" : "false";
?>
<div id="fevents" data-role="page" data-dom-cache="true">
    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1>Festivals</h1>
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul id="listview" data-role="listview" data-filter="<?=$filter?>" data-mini="true">				
        <?php
$pdate = '';
while (list($id, $date, $name, $venue, $city, $attendance) = mysqli_fetch_row($result)) {
    if ($date != $pdate) {
        $fdate = formatDate($date);
        $sdate = formatShortDate($date);
        echo "<li data-role=\"list-divider\">$fdate</li>";
        $pdate = $date;
    }			
    $attclass = $attendance >= 300 ? 1 : ($attendance >= 200 ? 2 : ($attendance >= 100 ? 3 : 0));
    $att = $attclass > 0 ? "<img src=\"img/as$attclass.png\" alt=\"Buzz\" class=\"ui-li-icon\">" : '';			
    echo "<li data-icon=\"bars\" data-id=\"$id\"><a href=\"#event\" data-rel=\"popup\">$name $att<p>$venue, $city</p><p class=\"ui-li-aside\">$sdate</p></a></li>";
} 	
        ?>
        </ul>
        <div id="event" data-role="popup" data-theme="b">
            <ul data-role="listview" data-inset="true" style="min-width:210px;"></ul>
        </div>
    </div><!-- /content -->
</div><!-- /page -->
</body>
</html>
<?php
require_once 'menu.php';
mysqli_close($con);
?>

