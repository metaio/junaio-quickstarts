<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'jsonTranslator.php';
require_once '../ARELLibrary/arel_object.class.php';
require_once '../ARELLibrary/arel_xmlhelper.class.php';

define('WWW_ROOT', "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
define('LANGUAGE_INFO', WWW_ROOT.'/button_translations.json');

/**
 * This function is wrapper on the ARELXMLHelper class to offer localization and make POIs creation process easy
 * @param $objects_array: array of POIs object
 * @param string $lang: Language of the phone for localization
 * @param null $html_page: The Html page path to show in your channel if any
 */
function createLocationBasedAREL($objects_array, $lang = 'en', $html_page = NULL){

    //getting language pack according to the user specified language
    $translator = new JsonTranslator($lang);
    $language_pack = $translator->getLanguagePack(LANGUAGE_INFO);
    if($lang != 'en')
    {
        //getting language pack for english so that if the user specified language pack don't have a specific word, then the english word can be used.
        $language_pack_en = self::getLocals(array('en'));
    }
    ArelXMLHelper::start(NULL, $html_page, ArelXMLHelper::TRACKING_GPS);
    if (!empty($objects_array)){
        foreach($objects_array as $object) {
            $popup = $object->getPopup();
            $buttons = $popup->getButtons();
            $new_buttons_array = array();
            foreach($buttons as $button) {
                if (isset($language_pack[$button[0]])) {
                    $button[0] = utf8_encode($language_pack[$button[0]]);
                }
                elseif (isset($language_pack_en[$button[0]])) {
                    $button[0] = utf8_encode($language_pack_en[$button[0]]);
                }
                array_push($new_buttons_array, $button);
            }
            $popup->setButtons($new_buttons_array);
            $object->setPopup($popup);
            ArelXMLHelper::outputObject($object);
        }
    }
    ArelXMLHelper::end();
}

/**
 * The function takes teo latitude and longitude pairs and calculates the distance in miles between them
 * @param $lat1 latitude of the client's location
 * @param $lon1 longitude of the client's location
 * @param $lat2 name of the database
 * @param $lon2 Detected language for localization
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
 * @param $lat latitude of the client's location
 * @param $lon longitude of the client's location
 * @param $dbname name of the database
 * @param $lang Detected language for localization
 * @return string An XML string containing POIs close to the client's location
 */
function fetch_relevant_pois($lat, $lon, $dbname) {
    if (is_null($lat) || is_null($lon))
    {
        // Return empty result.
        ArelXMLHelper::createLocationBasedAREL(array());
        return null;
    }

    //connect to the SQLite database
    $db = new SQLite3($dbname);
    $db->createFunction('DISTANCE', 'sqlite3_distance_func', 4);

    //run a proximity query to fetch ONLY close by POIs
    $records=$db->query("SELECT * FROM POIsM WHERE DISTANCE(latitude,longitude,$lat,$lon) < 50 ORDER BY abs(latitude - $lat) + abs(longitude - $lon);");

    //build an AREL XML with the relevant POIs

    $poiXML = build_xml($records, "en");
    $db->close();
    return $poiXML;
}

/**
 * @param $dbname Name of the database
 * @return string An XML string containing all POIs
 */
function fetch_all_pois($dbname) {
    //connect to the created SQLite database
    $db = new SQLite3($dbname);

    //read all the table records from the database
    $records=$db->query("SELECT * FROM POIsM;");
    $pois = build_xml($records, "en");
    $db->close();
//    return $records;
    return $pois;
}

/**
 * @param $pois SQLite3Result with POIs
 * @return string An AREL XML with all the POIs in SQLite3Result
 */
function build_xml($pois, $lang = "en") {
    $array_objects = array();

    //Loop through all the results from query and create POI objects
    while($row = $pois->fetchArray()){
        //Create ArelObjectPOI using AREL Library.
        $obj = new ArelObjectPoi($row['id']);
        //Set properties of the POI object
        $obj->setTitle($row['title']);
        $obj->setLocation(array($row['latitude'], $row['longitude'], 0));
        $obj->setThumbnail($row['thumbnailURL']);
        $obj->setIcon($row['iconURL']);
        //Create localized buttons. The localized string are in "jsonTranslator.php" file.
        $array_buttons = array();
        if ($row['phoneNumber'] != "") {
            array_push($array_buttons, array('BTN_CALL', 'Call', 'tel:'.$row['phoneNumber']));
        }
        if ($row['homepage'] != "") {
            array_push($array_buttons, array('BTN_OPEN_WEB', 'Web Page', $row['homepage']));
        }
        if ($row['imageURL'] != ''){
            array_push($array_buttons, array('BTN_VIEW_IMAGE', 'Image', $row['imageURL']));
        }
        if ($row['videoURL'] != ''){
            array_push($array_buttons, array('BTN_PLAY_VIDEO', 'Video', $row['videoURL']));
        }
        if ($row['soundURL'] != ''){
            array_push($array_buttons, array('BTN_PLAY_AUDIO', 'Sound', $row['soundURL']));
        }

        array_push($array_buttons, array('BTN_ROUTE', 'Sound', 'route:daddr='.$row['latitude'].','.$row['longitude']));

        //Setting up POI object popup properties
        $popup = new ArelPopup();
        $popup->setDescription($row['description']);
        $popup->setButtons($array_buttons);

        $obj->setPopup($popup);

        array_push($array_objects, $obj);
    }

    $log_file = fopen("logFile.log","a");
    //Write to file how many POIs have been retrieved
    fwrite($log_file,"Total POIs retrieved from sqlite database: ".count($array_objects).".\n");
    fclose($log_file);

    //call function to create XML response using AREL Library
    createLocationBasedAREL($array_objects, $lang);
}

$lat = null;
$lon = null;

if (isset($_GET['l']))
{
    $position = explode(',', $_GET['l']);
    if (is_array($position) && count($position) > 0)
    {
        $lat = $position[0];
        if (count($position) > 1)
        {
            $lon = $position[1];
        }
    }
}

//get relevant POIs from the channel's db
$dbname = "poisM";

$pois = fetch_relevant_pois($lat, $lon, $dbname);

echo $pois;