<?php
/**
 * Created by PhpStorm.
 * User: shahid.aslam
 * Date: 22/12/14
 * Time: 10:55
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
function save_pois($jsonStr, $dbname) {
    //create or open the channel database
    $db = new SQLite3($dbname, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);

    //create the POIs table
    $db->query("CREATE TABLE IF NOT EXISTS POIsM (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, description TEXT, phoneNumber TEXT, homepage TEXT, iconURL TEXT, thumbnailURL TEXT, imageURL TEXT,
                videoURL TEXT, soundURL TEXT, modelURL TEXT, latitude REAL, longitude REAL, altitude REAL) ;");
    $db->query("DELETE FROM POIsM;"); //required because we bulk-replace all POIs on updates to the channel
    $db->query("UPDATE SQLITE_SEQUENCE SET seq='0' WHERE name='POIsM';"); //reset the id count to start from 1 again; Else, the id count starts from the previously used highest id for the table

    //Decode the json string to UTF-8 encoding as "json_decode" only works for UTF-8 encoding
    $poisJson = json_decode(utf8_decode($jsonStr));

    $objects = $poisJson->pois;

    //parse the input json for POI information
    foreach ($objects as $obj) {
        $description = "";
        $phoneNumber = "";
        $icon = "";
        $thumbnail = "";
        print_r($obj);
        $homepage = "";
        $imageUrl = "";
        $movieUrl = "";
        $soundUrl = "";

        //Check if attributes needed for POI are present in JSON
        if (isset($obj->description)) {
            $description = $obj->description;
        }

        if (isset($obj->phoneNumber)) {
            $phoneNumber = $obj->phoneNumber;
        }

        if (isset($obj->iconURL)) {
            $icon = $obj->iconURL;
        } else {
            $icon = "http://channels.excel.junaio.com/resources/icon_thumbnail.png";
        }

        if (isset($obj->thumbnailURL)) {
            $thumbnail = $obj->thumbnailURL;
        } else {
            $thumbnail = "http://channels.excel.junaio.com/resources/icon_thumbnail.png";
        }

        if (isset($obj->homepage)) {
            $homepage = $obj->homepage;
        }

        if (isset($obj->imageURL)) {
            $imageUrl = $obj->imageURL;
        }

        if (isset($obj->video)) {
            $movieUrl = $obj->video;
        }

        if (isset($obj->sound)) {
            $soundUrl = $obj->sound;
        }

        //insert each POI to the db
        $query = "INSERT INTO POIsM (title, description, phoneNumber, homepage, iconURL, thumbnailURL, imageURL, videoURL,
        soundURL, modelURL, latitude, longitude, altitude) VALUES ('" . $obj->title . "', '" . $description . "',
        '" . $phoneNumber . "', '" . $homepage . "', '" . $icon . "', '" . $thumbnail . "', '" . $imageUrl . "', '" .
            $movieUrl . "', '" . $soundUrl . "', '', '" . $obj->latitude . "', '" . $obj->longitude . "', '" .
            $obj->altitude . "');";

        $db->query($query);
    }
    $db->close();
}

//Header. It is same as in the excel spreadsheet
$header_array = array("id", "title", "description", "phoneNumber", "homepage", "iconURL", "thumbnailURL", "imageURL", "videoURL", "soundURL", "modelURL",
    "latitude", "longitude", "altitude");

$log_file = fopen("logFile.log","a");

//Open CSV file in read mode
$file = fopen('POIs_m.csv', 'r');

//Initialize creation of JSON string
$json = '{"pois": [';

$line = fgetcsv($file); //Its header, we don't need it.

//Counter to maintain count of how many POIs have been read from CSV file.
$counter = 0;

//Loop through all the lines of CSV file. Parse it and create json string
while (($line = fgetcsv($file)) !== false)
{
    $json = $json."{";
    for($index = 0; $index < count($line); $index++) {
        $json = $json.'"'.$header_array[$index].'":"'.$line[$index].'",';
    }
    $json = substr($json, 0, -1);
    $json = $json."},";
    $counter = $counter + 1;
}

$json = substr($json, 0, -1);
$json = $json."]}";

//Write to file how many POIs have been retrieved
echo fwrite($log_file,"Total Lines read from CSV file: ".$counter.".\n");
fclose($log_file);

//Close the file
fclose($file);

save_pois($json, "poisM");