<?php
require_once 'init.php';

$podiumid = $_GET['id'];
?>
<!DOCTYPE html>
<html>  
<?php require_once 'head.php'; ?>
<body>
<?php
$podium = mysqli_query($con, "select name from podium where id = $podiumid")->fetch_row()[0];

$query  = <<<EOL
select 
    evt.id, evt.date, evt.name, att.id as artistid, gre.name as genre, evt.attendance
from 
    event evt 
    left join artist att on evt.artistid = att.id
    left join genre gre on evt.genreid = gre.id
where 
    evt.podiumid = $podiumid
    and evt.date >= curdate()
order 
    by evt.date, evt.name
EOL;

$result = mysqli_query($con, $query);
$filter = mysqli_num_rows($result) > 10 ? "true" : "false";
?>
<div id="vevents" data-role="page">    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1><?=$podium?></h1>
        <a href="venues.php" data-role="button" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-icon-left ui-btn-right ui-icon-back"><?=$label['nav_back']?></a>
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul id="listview" data-role="listview" data-filter="<?=$filter?>" data-mini="true">
        <?php
$pdate = '';
while (list($id, $date, $name, $artistid, $genre, $attendance) = mysqli_fetch_row($result)) {
    if ($date != $pdate) {
        $fdate = formatDate($date);
        $sdate = formatShortDate($date);
        echo "<li data-role=\"list-divider\">$fdate</li>";
        $pdate = $date;
    }
    $fgenre = is_null($genre) ? '' : "&nbsp;($genre)";
    $attclass = $attendance >= 300 ? 1 : ($attendance >= 200 ? 2 : ($attendance >= 100 ? 3 : 0));
    $att = $attclass > 0 ? "<img src=\"img/as$attclass.png\" alt=\"Buzz\" class=\"ui-li-icon\">" : '';
    echo "<li data-icon=\"bars\" data-id=\"$id\"><a href=\"#event\" data-rel=\"popup\">$name$fgenre $att<p class=\"ui-li-aside\">$sdate</p></a></li>";
}
        ?>
        </ul>
        <div id="event" data-role="popup" data-theme="b">
            <ul data-role="listview" data-inset="true" style="min-width:210px;"></ul>
        </div>
    </div><!-- /content -->
</div><!-- /page -->
</body>
<?php require_once 'menu.php'; ?>
</html>
<?php
mysqli_close($con);
?>