<?php
/**
 * Created by PhpStorm.
 * User: Aruna
 * Date: 7/1/14
 * Time: 3:55 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../ARELLibrary/arel_xmlhelper.class.php';
require_once '../ARELLibrary/arel_object.class.php';


/**
 * The function takes two (latitude,longitude) pairs and calculates the distance in miles between them
 * @return float The distance between two locations in miles
 */
function sqlite3_distance_func($lat1,$lon1,$lat2,$lon2) {
    // convert lat1 and lat2 into radians now, to avoid doing it twice below
    $lat1rad = deg2rad($lat1);
    $lat2rad = deg2rad($lat2);
    // apply the spherical law of cosines to our latitudes and longitudes, and set the result appropriately
    // 6371 is the approximate radius of the earth in kilometres
    // 3959 is the approximate radius of the earth in miles
    return acos( sin($lat1rad) * sin($lat2rad) + cos($lat1rad) * cos($lat2rad) * cos( deg2rad($lon2) - deg2rad($lon1) ) ) * 3959;
}

/**
 * @param $dbname Name of the database
 * @return string An XML string containing all POIs
 */
function fetch_all_pois($dbname) {
    //connect to the created SQLite database
    $db = new SQLite3($dbname);

    //read all the table records from the database
    $records=$db->query("SELECT * FROM POIs;");
    $pois = build_xml($records, "", "", "en");
    $db->close();
//    return $records;
    return $pois;
}

/**
 * @param $lat latitude of the client's location
 * @param $lon longitude of the client's location
 * @param $dbname name of the database
 * @return string An XML string containing POIs close to the client's location
 */
function fetch_relevant_pois($lat, $lon, $dbname, $lang) {
    if (is_null($lat) || is_null($lon))
    {
        // Return empty result.
        ArelXMLHelper::createLocationBasedAREL(array(), $lang);
        return null;
    }
    //connect to the SQLite database
    $db = new SQLite3($dbname);
    $db->createFunction('DISTANCE', 'sqlite3_distance_func', 4);

    //run a proximity query to fetch ONLY closeby POIs
    $records=$db->query("SELECT * FROM POIs WHERE DISTANCE(latitude,longitude,$lat,$lon) < 50;");

    //build an AREL XML with the relevant POIs
    // TODO This function does not return anything, and we still return its return value (which we later echo).
    $poiXML = build_xml($records, $lat, $lon, $lang);
    $db->close();
    return $poiXML;
}

/**
 * @param $pois SQLite3Result with POIs
 * @return string An AREL XML with all the POIs in SQLite3Result
 */
function build_xml($pois, $lat, $lon, $lang) {
    $array_objects = array();
    while($row = $pois->fetchArray()){
        $obj = new ArelObjectPoi($row['id']);
        $obj->setTitle($row['title']);
        $obj->setLocation(array($row['latitude'], $row['longitude'], 0));
        $obj->setThumbnail($row['thumbnailURL']);
        $obj->setIcon($row['iconURL']);
        $obj->setDescription($row['description']);
        if ($row['phoneNumber'] != "") {
            $obj->addButton('BTN_CALL', 'tel:'.$row['phoneNumber']);
        }
        if ($row['homepage'] != "") {
            $obj->addButton('BTN_OPEN_WEB', $row['homepage']);
        }
        if ($row['imageURL'] != ''){
            $obj->addButton('BTN_VIEW_IMAGE', $row['imageURL']);
        }
        if ($row['videoURL'] != ''){
            $obj->addButton('BTN_PLAY_VIDEO', $row['videoURL']);
        }
        if ($row['soundURL'] != ''){
            $obj->addButton("BTN_PLAY_AUDIO", $row['soundURL']);
        }

//        $obj->addButton('BTN_ROUTE', 'route:daddr='.$lat.','.$lon);
        $obj->addButton('BTN_ROUTE', 'route:daddr='.$row['latitude'].','.$row['longitude']);

        array_push($array_objects, $obj);
    }
    ArelXMLHelper::createLocationBasedAREL($array_objects, $lang);
}

function save_pois($jsonStr, $dbname) {
    //create or open the channel database
    $db = new SQLite3($dbname, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);

    //create the POIs table
    $db->query("CREATE TABLE IF NOT EXISTS POIs (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, description TEXT, phoneNumber TEXT, homepage TEXT, iconURL TEXT, thumbnailURL TEXT, imageURL TEXT,
                videoURL TEXT, soundURL TEXT, modelURL TEXT, latitude REAL, longitude REAL, altitude REAL) ;");
    $db->query("DELETE FROM POIs;"); //required because we bulk-replace all POIs on updates to the channel
    $db->query("UPDATE SQLITE_SEQUENCE SET seq='0' WHERE name='POIs';"); //reset the id count to start from 1 again; Else, the id count starts from the previously used highest id for the table

    $json = json_decode($jsonStr);
    $objects = $json->{'pois'};

    //parse the input json for POI information
    foreach ($objects as $obj) {
        $description = "";
        $phoneNumber = "";
        $icon = "";
        $thumbnail = "";
        $homepage = "";
        $imageUrl = "";
        $movieUrl = "";
        $soundUrl = "";

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
        $query = "INSERT INTO POIs (title, description, phoneNumber, homepage, iconURL, thumbnailURL, imageURL, videoURL,
        soundURL, modelURL, latitude, longitude, altitude) VALUES ('" . $obj->title . "', '" . $description . "',
        '" . $phoneNumber . "', '" . $homepage . "', '" . $icon . "', '" . $thumbnail . "', '" . $imageUrl . "', '" .
            $movieUrl . "', '" . $soundUrl . "', '', '" . $obj->latitude . "', '" . $obj->longitude . "', '" .
            $obj->altitude . "');";

        $db->query($query);
    }
    $db->close();
}
