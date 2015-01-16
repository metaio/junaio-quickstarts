<?php
/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @link       http://www.metaio.com
 * @author     Frank Angermann
 * 
 * @abstract	The tutorial shows how to use the google analytics plugin in combination with your channel. Please make sure to include
 * 				your profile ID in the config.php. More information can be found on junai in the arel plugin section.
 * 				Please make sure to follow the Google Analytics Terms of service.
 * 				 				
 * 				Learnings:
 * 					- using Google Analytics with junaio
 * 					- calling javascript methods from a popup button
 **/

require_once '../ARELLibrary/arel_xmlhelper.class.php';

//use the Arel Helper to start the output with arel
//start output
ArelXMLHelper::start(NULL, "/arel/index.php?analyticsuser=" . ANALYTICS_USER);

//1. Sound POI
$oObject = ArelXMLHelper::createLocationBasedPOI(
		"1", //id
		"Hello Sound POI", //title
		array(48.12310, 11.218648, 0), //location
		"/resources/thumb_sound.png", //thumb
		"/resources/icon_sound.png", //icon
		"This is our Sound POI", //description
		array(array("Start Audio", "soundButton", "javascript: startSound(\"http://dev.junaio.com/publisherDownload/tutorial/test.mp3\")")) //buttons
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
		array(array("Show Image", "imageButton", "javascript: openImage(\"http://farm5.static.flickr.com/4104/5206110815_7ea891be0b.jpg\")")) //buttons
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
		array(array("Start Movie", "movieButton", "javascript: startVideo(\"http://dev.junaio.com/publisherDownload/tutorial/movie.mp4\")")) //buttons
	);

//output the object
ArelXMLHelper::outputObject($oObject);

//4. Custom POPup POI
$oObject = ArelXMLHelper::createLocationBasedPOI(
		"4", //id
		"Custom PopUp", //title
		array(48.12317,11.218670,0), //location
		"/resources/thumb_custom.png", //thumb
		"/resources/icon_custom.png" //icon		
	);

//add some parameters we will need with AREL
$oObject->addParameter("description", "This is my special POI. It will do just what I want.");
$oObject->addParameter("url", "http://www.junaio.com");
	
//output the object
ArelXMLHelper::outputObject($oObject);

//end the output
ArelXMLHelper::end();

?>