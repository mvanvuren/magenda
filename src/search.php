<?php
require_once 'init.php';
mysqli_close($con);
?>
<!DOCTYPE html>  
<html>  
<?php require_once 'head.php'; ?>
<body>
<div id="search" data-role="page">
    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1><?=$label['search_title']?></h1>        
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <form id="sresult" action="sresult.php" method="post" accept-charset="UTF8">
            <ul data-role="listview">
                <li class="ui-field-contain">
                    <label for="sterm"><?=$label['search_description']?></label>
                    <input type="search" name="sterm" id="sterm" value="" placeholder="<?=$label['search_placeholder']?>" />
                </li>			
                <!-- <li class="ui-field-contain">
                    <fieldset data-role="controlgroup">
                        <legend><?=$label['search_range']?></legend>
                        <input type="radio" name="range" id="rlocal" value="local" checked="checked" />
                        <label for="rlocal"><?=$label['search_range_local']?></label>
                        <input type="radio" name="range" id="rnl" value="nl" />
                        <label for="rnl"><?=$label['search_range_nl']?></label>
                    </fieldset>
                </li> -->
                <li class="ui-field-contain">
                    <label for="submit"></label>
                    <button type="submit" id="submit" class="ui-btn ui-shadow ui-corner-all ui-icon-search ui-btn-inline"><?=$label['search_submit']?></button>
                </li>	
            </ul>		
        </form>
    </div><!-- /content -->
</div><!-- /page -->
 <?php require_once 'menu.php'; ?>
</body>
</html>