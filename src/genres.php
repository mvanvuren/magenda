<?php
require_once 'init.php';
?>
<!DOCTYPE html>  
<html>  
<?php require_once 'head.php'; ?>
<body>
<?php
$query  = <<<EOL
(select 
    gre.id, gre.name, count(1) as total, 1 as popularity
from 
    genre gre 
    inner join event evt on gre.id = evt.genreid
    inner join 
    (
        select 
            pdm.id as podiumid
        from 
            podium pdm 
        where 
            pdm.municipalityid = $cityid 	
            or exists (
                select 1 from distance dtt where dtt.frommunicipalityid = $cityid and dtt.tomunicipalityid = pdm.municipalityid and dtt.distance <= $range
                union
                select 1 from distance dtt where dtt.frommunicipalityid = pdm.municipalityid and dtt.tomunicipalityid = $cityid and dtt.distance <= $range
            )			
    ) podia on evt.podiumid = podia.podiumid and evt.date >= curdate()	
group by
    gre.id, gre.name
having 
    count(1) >= 5
) 
union
(select 
    gre.id, gre.name, count(1) as total, 2 as popularity
from 
    genre gre 
    inner join event evt on gre.id = evt.genreid
    inner join 
    (
        select 
            pdm.id as podiumid
        from 
            podium pdm 
        where 
            pdm.municipalityid = $cityid 	
            or exists (
                select 1 from distance dtt where dtt.frommunicipalityid = $cityid and dtt.tomunicipalityid = pdm.municipalityid and dtt.distance <= $range
                union
                select 1 from distance dtt where dtt.frommunicipalityid = pdm.municipalityid and dtt.tomunicipalityid = $cityid and dtt.distance <= $range
            )			
    ) podia on evt.podiumid = podia.podiumid and evt.date >= curdate()	
group by
    gre.id, gre.name
having 
    count(1) < 5
)
order by 
    popularity, name
EOL;

$result = mysqli_query($con, $query);
$filter = mysqli_num_rows($result) > 10 ? "true" : "false";
?>
<div id="genres" data-role="page" data-dom-cache="true">    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1><?=$label['genres_title']?></h1>        
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul data-role="listview" data-filter="<?=$filter?>" data-mini="true">						
        <?php
$header1 = false;
$header2 = false;
while (list($id, $genre, $total) = mysqli_fetch_row($result)) {
    if ($header1 == false && $total >= 5) {
        echo "<li data-role=\"list-divider\">{$label['genres_popular']}</li>";
        $header1 = true;
    } 
    if ($header2 == false && $total < 5) {
        echo "<li data-role=\"list-divider\">{$label['genres_lesspopular']}</li>";
        $header2 = true;				
    }
    echo "<li><a href=\"gevents.php?id=$id\">$genre<span class=\"ui-li-count\">$total</span></a></li>";
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