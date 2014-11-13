<?php
/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @link       http://www.metaio.com
 * @author     Frank Angermann
 * 
 * @abstract	Learn about the different types of POIs available in junaio. It is a different media type linked with each POI.
 * 				
 * 				Learnings:
 * 					- create multiple POIs within 1 channel
 * 					- use the AREL XML Helper to create the XML output
 * 					- link movie, sound or image with the POI
 * 					- create a custom HTML overlay to be referenced and opened one the custom POI is clicked
 * 					- adding parameters to the POI to be used in AREL JS
 *  			
 **/

require_once '../ARELLibrary/arel_xmlhelper.class.php';

//use the Arel Helper to start the output with arel
//start output
ArelXMLHelper::start(NULL, "/arel/index.html", ArelXMLHelper::TRACKING_GPS);

//1. Sound POI
$oObject = ArelXMLHelper::createLocationBasedPOI(
		"1", //id
		"Hello Sound POI", //title
		array(38.12310, 11.218648, 0), //location
		"/resources/thumb_sound.png", //thumb
		"/resources/icon_sound.png", //icon
		"This is our Sound POI", //description
		array() //buttons
	);

//output the object
ArelXMLHelper::outputObject($oObject);

//2. Image POI
$oObject = ArelXMLHelper::createLocationBasedPOI(
		"2", //id
		"Hello Image POI", //title
		array(48.12325, 11.218691, 0), //location
		"/resources/thumb_image.png", //thumb
		"/resources/icon_image.png", //icon
		"This is our Image POI\n\nThe image source is: http://www.flickr.com/photos/ediamjunaio/5206110815/", //description
		array(array("Show Image", "imageButton", "http://farm5.static.flickr.com/4104/5206110815_7ea891be0b.jpg")) //buttons
	);

//output the object
ArelXMLHelper::outputObject($oObject);

//3. Video POI
$oObject = ArelXMLHelper::createLocationBasedPOI(
		"3", //id
		"Hello Video POI", //title
		array(48.12307, 11.218636, 0), //location
		"/resources/thumb_video.png", //thumb
		"/resources/icon_video.png", //icon
		"This is our Video POI", //description
		array(array("Start Movie", "movieButton", "http://dev.junaio.com/publisherDownload/tutorial/movie.mp4")) //buttons
	);

//output the object
ArelXMLHelper::outputObject($oObject);

//4. Custom POPup POI
$oObject = ArelXMLHelper::createLocationBasedPOI(
		"4", //id
		"Custom PopUp", //title
		array(48.12317,11.218670,0), //location
		"/resources/thumb_custom.png", //thumb
		"/resources/icon_custom.png", //icon
        array()
	);

//add some parameters we will need with AREL
$oObject->addParameter("description", "This is my special POI. It will do just what I want.");
$oObject->addParameter("url", "http://www.junaio.com");
	
//output the object
ArelXMLHelper::outputObject($oObject);

//5. Phone Call POI
$oObject = ArelXMLHelper::createLocationBasedPOI(
    "5", //id
    "Do Phone Call", //title
    array(28.12302,11.218644,0), //location
    "/resources/thumb_custom.png", //thumb
    "/resources/icon_custom.png", //icon
    array()
);

//output the object
ArelXMLHelper::outputObject($oObject);

//end the output
ArelXMLHelper::end();

?>