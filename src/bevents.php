<?php require_once 'init.php'; ?>
<!DOCTYPE html>  
<html>  
<?php require_once 'head.php'; ?>
<body>
<?php
$query  = <<<EOL
select 
    evt.id, evt.date, evt.name, pdm.name as stage, mny.name as city, att.id as artistid, gre.name as genre, evt.attendance
from 
    event evt 
    inner join municipality mny on evt.municipalityid = mny.id
    left join podium pdm on evt.podiumid = pdm.id
    left join artist att on evt.artistid = att.id
    left join genre gre on evt.genreid = gre.id
where 
    evt.date >= curdate()
    and evt.attendance >= 100
order 
    by evt.attendance desc, evt.date, evt.name
EOL;

$result = mysqli_query($con, $query);
$filter = mysqli_num_rows($result) > 10 ? "true" : "false";
?>
<div id="bevents" data-role="page" data-dom-cache="true">   
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1><?=$label['bevents_title']?></h1>
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul id="listview" data-role="listview" data-filter="<?=$filter?>" data-mini="true">
        <?php
while (list($id, $date, $name, $stage, $city, $artistid, $genre, $attendance) = mysqli_fetch_row($result)) {
    $fdate = formatDate($date);
    $sdate = formatShortDate($date);
    $fgenre = is_null($genre) ? '' : "&nbsp;($genre)";
    $attclass = $attendance >= 300 ? 1 : ($attendance >= 200 ? 2 : 3);
    $att = $attclass > 0 ? "<img src=\"img/as$attclass.png\" alt=\"Buzz\" class=\"ui-li-icon\">" : '';
    echo "<li data-icon=\"bars\" data-id=\"$id\"><a href=\"#event\" data-rel=\"popup\">$name$fgenre $att<p>$stage, $city</p><p class=\"ui-li-aside\">$sdate</p></a></li>";
}
        ?>
        </ul>
        <div id="event" data-role="popup" data-theme="b">
            <ul data-role="listview" data-inset="true" style="min-width:210px;"></ul>
        </div>
    </div><!-- /content -->
</div><!-- /page -->
<?php require_once 'menu.php'; ?>
</body>
</html>
<?php
mysqli_close($con);
?>