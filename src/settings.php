<?php
require_once 'init.php';
?>
<!DOCTYPE html>
<html>  
<?php require_once 'head.php'; ?>
<body>
<?php
$query = <<<EOL
select 
    (select min(postalcode) from municipalitypostalcode mpe where mny.id = mpe.municipalityid) as postalcode, mny.id, mny.name
from
    municipality mny
order by 
    mny.name
EOL;

$result = mysqli_query($con, $query);
?>
<div id="settings" data-role="page">    
    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bars">Menu</a>
        <h1><?=$label['settings_title']?></h1>		            
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul data-role="listview">
            <li class="ui-field-contain"> 
                <label for="cities"><?=$label['settings_city']?></label>
                <select id="cities" name="cities" data-mini="true">
                <?php
while (list($postalcode, $id, $city) = mysqli_fetch_row($result)) {
$selected = $id == $cityid ? ' selected="selected"' : '';
echo "<option value=\"$postalcode\" data-id=\"$id\"$selected>$city</option>";
}
                ?>
                </select>
            </li> 
            <li class="ui-field-contain"> 
                <label for="range"><?=$label['settings_range']?></label>
                <input type="range" id="range" name="range" min="5" max="335" required="required" value="<?=$range?>"/>
                <span id="msgRange" style="display: none"><br/><?=$label['settings_range_msg']?></span>
            </li>
            <li class="ui-field-contain">
                <label for="lg"><?=$label['settings_language']?></label>
                <fieldset id="lg" data-role="controlgroup">
                    <input type="radio" name="language" id="nl" value="nl" <?php if ($_COOKIE['language'] != 'en') echo "checked=\"checked\""; ?>/>
                    <label for="nl"><?=$label['settings_language_nl']?></label>
                    <input type="radio" name="language" id="en" value="en" <?php if ($_COOKIE['language'] == 'en') echo "checked=\"checked\""; ?>/>
                    <label for="en"><?=$label['settings_language_en']?></label>
                </fieldset>
            </li>
            <li class="ui-field-contain">
                <label for="save">&nbsp;</label>
                <button id="save" class="ui-btn ui-shadow ui-corner-all ui-icon-check ui-btn-icon-left ui-btn-inline"><?=$label['settings_submit']?></button>
                <label for="reset">&nbsp;</label>
                <button id="reset" class="ui-btn ui-shadow ui-corner-all ui-icon-refresh ui-btn-icon-left ui-btn-inline"><?=$label['settings_reset']?></button>
            </li>
        </ul>
    </div><!-- /content -->
</div><!-- /page -->
<?php require_once 'menu.php'; ?>
</body>
</html>
<?php
mysqli_close($con);
?>