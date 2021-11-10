<?php require_once 'init.php';

$eventid = $_GET['id'];

$query  = <<<EOL
select 
    evt.date, evt.name, pdm.name as stage, mny.name as city, pdm.link, att.id as artistid
    , (select coalesce(sum(1), 0) from tag tgs where tgs.userid = $userid and tgs.name = evt.name) as tag	
from 
    event evt 
    inner join municipality mny on evt.municipalityid = mny.id
    left join podium pdm on evt.podiumid = pdm.id
    left join artist att on evt.artistid = att.id
    left join genre gre on evt.genreid = gre.id
where 
    evt.id = $eventid
EOL;

$result = mysqli_query($con, $query);
$numrows = mysqli_num_rows($result);

list($date, $name, $stage, $city, $link, $artistid, $tag) = mysqli_fetch_row($result);

$ename = urlencode($name);
$estage = urlencode($stage);
$ecity = urlencode($city);
$fdate = formatDate($date);

$title = htmlspecialchars($name, ENT_COMPAT) . ' - ' . htmlspecialchars($stage, ENT_COMPAT) . ' - ' . $fdate;
$etitle = rawurlencode($title);
$url = "$site/event.php?id=$eventid";
$class="ui-btn ui-btn-icon-right ui-icon-carat-r"; // TODO
//$class="";
?>
<li class="ui-li-has-icon ui-first-child">
<?php 
if (!is_null($artistid)) { 
?>
    <a href="http://<?=$ismobile?'m':'www'?>.last.fm/music/<?=$ename?>" target="_blank" style="white-space: normal;" class="<?=$class?>"><img src="img/lastfm.png" alt="Last.Fm" class="ui-li-icon"><?=$name?></a>
<?php 
} else { 
?>
    <a href="http://www.google.com/search?q=<?=$ename?>" target="_blank" style="white-space: normal;" class="<?=$class?>"><img src="img/gm.png" alt="Google" class="ui-li-icon"><?=$name?></a>
<?php 
} 
?>
<!--<a href="#" id="tag" data-name="<?=$name?>"><?=$label[$tag == 0 ? 'event_del_favorite' : 'event_add_favorite']?></a>-->
</li>
<li class="ui-li-has-icon"><a href="http://<?=$link?>" target="_blank" class="<?=$class?>"><img src="img/venue.png" alt="Venue" class="ui-li-icon"><?=$stage?></a></li>
<li class="ui-li-has-icon"><a href="http://maps.google.nl/maps?q=<?=$estage?>,<?=$ecity?>,Nederland" target="_blank" class="<?=$class?>"><img src="img/gm.png" alt="Map" class="ui-li-icon"><?=$city?></a></li>
<li class="ui-li-has-icon ui-last-child"><a href="http://<?=$ismobile?'m':'www'?>.youtube.com/results?gl=US&client=mv-google&hl=en&q=<?=$ename?>&submit=Search" target="_blank" class="<?=$class?>"><img src="img/yt.png" alt="YouTube" class="ui-li-icon">YouTube</a></li>
<!--<-?php
if (!is_null($artistid)) {
    echo "<li><a href=\"http://www.allmusic.com/search/artist/$ename\" target=\"_blank\" class=\"$class\"><img src=\"img/am.png\" alt=\"AllMusic\" class=\"ui-li-icon\">AllMusic</a></li>";	
    $mname = urlencode(str_replace(' ', '', $name));
    echo "<li><a href=\"http://www.myspace.com/$mname\" target=\"_blank\" class=\"$class\"><img src=\"img/ms.png\" alt=\"MySpace\" class=\"ui-li-icon\">MySpace</a></li>";					
}
?>-->
<?php
mysqli_close($con);
?>
