<?php require_once 'init.php'; ?>
<!DOCTYPE html>  
<html>  
<?php require_once 'head.php'; ?>
<body>
<?php 
$query  = <<<EOL
select 
    usr.id, coalesce(usr.code, '') as code, coalesce(usr.range, '') as `range`, coalesce(mny.name, '') as name, usr.counter, usr.datelast, coalesce(usr.information, '') as information
from 
    user usr 
    left join municipalitypostalcode mpe on usr.code = mpe.postalcode 
    left join municipality mny on mpe.municipalityid = mny.id
order 
    by usr.datelast desc
limit 
    0, 50
EOL;
$result = mysqli_query($con, $query);
?>
<div id="stat" data-role="page">
    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1>Statistics</h1>
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul id="event" data-role="listview">
        <?php
while (list($id, $code, $range, $name, $counter, $datelast, $information) = mysqli_fetch_row($result)) {
    if ($code != '') {
        echo "<li>[$id:$counter] $name ($code) ($range km)<br/>$datelast</li>";
    } else {
        echo "<li>[$id:$counter] $information</li>";
    }
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