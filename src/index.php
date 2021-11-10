<?php
/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * @category MusicAgenda
 * @package  MAgenda
 * @author   M.F. van Vuren <m.vuren@upcmail.nl>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://magenda.mvanvuren.nl
 */
require_once 'init.php';
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$pagesize = 25;
$pageoffset = 25 * $page;
?>
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
    and (evt.municipalityid = $cityid
    or exists (
        select 1 from distance dtt where dtt.frommunicipalityid = $cityid and dtt.tomunicipalityid = evt.municipalityid and dtt.distance <= $range
        union
        select 1 from distance dtt where dtt.frommunicipalityid = evt.municipalityid and dtt.tomunicipalityid = $cityid and dtt.distance <= $range
    )
)
order 
    by evt.date, evt.name
limit 
    $pageoffset, $pagesize
EOL;

$result = mysqli_query($con, $query) or die(mysqli_error($con));
$filter = mysqli_num_rows($result) > 10 ? "true" : "false";
?>
<div id="index" data-role="page" data-dom-cache="true">    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1><?=$label['agenda_title']?></h1>		
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul id="listview" data-role="listview" data-filter="<?=$filter?>" data-mini="true">
        <?php
        $pdate = '';
        $count = 0;
        while (list($id, $date, $name, $stage, $city, $artistid, $genre, $attendance) = mysqli_fetch_row($result)) {
            if ($date != $pdate) {
                $fdate = formatDate($date);
                $sdate = formatShortDate($date);
                echo "<li data-role=\"list-divider\">$fdate</li>";
                $pdate = $date;
            }
            $fgenre = is_null($genre) ? '' : "&nbsp;($genre)";
            $attclass = $attendance >= 300 ? 1 : ($attendance >= 200 ? 2 : ($attendance >= 100 ? 3 : 0));
            $att = $attclass > 0 ? "<img src=\"img/as$attclass.png\" alt=\"Buzz\" class=\"ui-li-icon\">" : '';
            echo "<li data-icon=\"bars\" data-id=\"$id\"><a href=\"#event\" data-rel=\"popup\" position-to=\"window\">$name$fgenre $att<p>$stage, $city</p><p class=\"ui-li-aside\">$sdate</p></a></li>";
            $count++;
        }
        ?>
        </ul>
        <div id="event" data-role="popup" data-theme="b">
            <ul data-role="listview" data-inset="true" style="min-width:210px;"></ul>
        </div>
    </div><!-- /content -->
	<div data-role="footer" data-position="fixed" class="ui-bar"> 
            <?php
            if ($page > 0) {
                echo '<a href="index.php?page=' . ($page - 1) . '" id="previous" data-role="button" data-icon="back" data-iconpos="bottom" data-direction="reverse">' . $label['agenda_previous'] . '</a>';			
            }
            if ($count == $pagesize) {
                echo '<a href="index.php?page=' . ($page + 1) . '" id="next" data-role="button" data-prefetch="true" data-icon="forward" data-iconpos="bottom">' . $label['agenda_next'] . '</a>';			
            }
            ?>
	</div> 	
</div><!-- /page -->
<?php require_once 'menu.php'; ?>
</body>
</html>
<?php
mysqli_close($con);
?>