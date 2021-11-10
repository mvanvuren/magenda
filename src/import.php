<?php

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

$con = new mysqli($databasehost, $databaseusername, $databasepassword, $databasename); #or die(mysqli_connect_error());
mysqli_set_charset($con, "utf8");

$cities = array();
$podia = array();
$events = array();
$artists = array();
$genres = array();

$result = mysqli_query($con, 'select id, name from municipality');
while (list($id, $name) = mysqli_fetch_row($result)) $cities[$name] = $id;
$result = mysqli_query($con, 'select id, name from podium');
while (list($id, $name) = mysqli_fetch_row($result)) $podia[lc($name)] = $id;
$result = mysqli_query($con, 'select id, date, name from event');
while (list($id, $date, $name) = mysqli_fetch_row($result)) $events[$date . ':' .$name] = $id;
$result = mysqli_query($con, 'select id, name from artist');
while (list($id, $name) = mysqli_fetch_row($result)) $artists[lc($name)] = $id;
$result = mysqli_query($con, 'select id, name from genre');
while (list($id, $name) = mysqli_fetch_row($result)) $genres[lc($name)] = $id;

function lc($str) 
{
    return strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str));
}

$file = "podia.csv";
if (file_exists($file) && ($handle = fopen($file, "r"))) {
    while ($line = fgets($handle)) {
        list($name, $municipality, $link) = explode(";", trim($line));
        $lcname = lc($name);		
        $name = mysqli_real_escape_string($con, $name);
        $municipalityid = $cities[$municipality];
        $query = '';
        $link = mysqli_real_escape_string($con, $link);
        if (!array_key_exists($lcname, $podia)) {
            $query = "insert into podium (name, municipalityid, link) values ('$name', $municipalityid, '$link')";
            $result = mysqli_query($con, $query); # or die(mysqli_error($con));
            if (!$result) {
                echo mysqli_error($con), "<br/>\n", $query, "<br/>\n";
            }			
            $podia[$lcname] = mysqli_insert_id($con);
        } else {
            $id = $podia[$lcname];
            $query = "update podium set name = '$name', municipalityid = $municipalityid, link = '$link' where id = $id";
            $result = mysqli_query($con, $query);
            if (!$result) {
                echo mysqli_error($con), "<br/>\n", $query, "<br/>\n";
            }
        }
    }
    fclose($handle);
    unlink($file);
}

$file = "events.csv";
if (file_exists($file) && ($handle = fopen($file, "r"))) {
    while ($line = fgets($handle)) {
        list($date, $name, $location, $municipality, $podium, $artist, $genre, $attendance, $isfestival, $additiondate) = explode(";", trim($line));
        $municipalityid = $municipality != '' ? $cities[$municipality] : 'null';
        $podiumid = $podium != '' ? $podia[lc($podium)] : 'null';
        $artistid = 'null';
        if ($artist != '') {
            $lcartist = lc($artist);
            if (array_key_exists($lcartist, $artists)) {
                $artistid = $artists[$lcartist];
            } else {
                $artist = mysqli_real_escape_string($con, $artist);
                $query = "insert into artist (name) values ('$artist')";
                $result = mysqli_query($con, $query);	
                if (!$result) {
                    echo mysqli_error($con), "<br/>\n", $query, "<br/>\n";
                } else {
                    $artistid = mysqli_insert_id($con);
                    $artists[$lcartist] = $artistid;
                }
            }
        }
        $genreid = 'null';
        if ($genre != '') {
            $lcgenre = lc($genre);
            $genre = mysqli_real_escape_string($con, $genre);
            if (!array_key_exists($lcgenre, $genres)) {
                $query = "insert into genre (name) values ('$genre')";
                $result = mysqli_query($con, $query);	
                if (!$result) {
                    echo mysqli_error($con), "<br/>\n", $query, "<br/>\n";
                } else {
                    $genreid = mysqli_insert_id($con);
                    $genres[$lcgenre] = $genreid;
                }
            } else {
                $genreid = $genres[$lcgenre];
                $query = "update genre set name = '$genre' where id = $genreid"; // case change
                $result = mysqli_query($con, $query);
                if (!$result) {
                    echo mysqli_error($con), "<br/>\n", $query, "<br/>\n";
                }
            }
        }
        
        $event = $date . ':' . $name;
        $location = mysqli_real_escape_string($con, $location);
        
        $query = '';
        if (!array_key_exists($event, $events)) {
            $name = mysqli_real_escape_string($con, $name);
            $query = "insert into event (date, name, location, podiumid, municipalityid, artistid, genreid, attendance, isfestival, additiondate) values ('$date', '$name', '$location', $podiumid, $municipalityid, $artistid, $genreid, $attendance, $isfestival, '$additiondate')";
        } else {
            $id = $events[$event];
            $query = "update event set location = '$location', podiumid = $podiumid, municipalityid = $municipalityid, artistid = $artistid, genreid = $genreid, attendance = $attendance, isfestival = $isfestival where id = $id";
        }
        $result = mysqli_query($con, $query);
        if (!$result) {
            echo mysqli_error($con), "<br/>\n", $query, "<br/>\n";
        }
    }
    fclose($handle);
    unlink($file);
}

$result = mysqli_query($con, "delete from event where date < curdate()");
$result = mysqli_query($con, "delete from artist where not exists (select artistid from event evt where artist.id = artistid)");
$result = mysqli_query($con, "delete from genre where not exists (select genreid from event evt where genre.id = evt.genreid)");
$result = mysqli_query($con, "delete from podium where not exists (select evt.podiumid from event evt where podium.id = evt.podiumid)");

mysqli_close($con);

echo "OK";
?>