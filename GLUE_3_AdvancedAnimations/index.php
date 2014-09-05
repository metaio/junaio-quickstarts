<?php
/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @link       http://www.metaio.com
 * @author     Frank Angermann
 * 
 * @abstract	This tutorial provides an advanced understanding of how animation work in junaio and how they can be controlled. Also, an occlusion model is included 
 * 				in this tutorial.
 * 				
 * 				Learnings:
 * 					- overlay multiple 3D models on an image
 * 					- use occlusion and transparency on models
 * 					- provide additional parameter in the AREL XML output to be used by AREL JS
 * 					- show html information on which image to track
 * 					- advanced way of hiding and displaying the "what to track" information based on tracking events
 * 					- start an animation based on tracking events
 * 					- using an arel plugin to allow fade in and fade out
 * 					- playing sounds in junaio
 * 
 *  			Animation Flow in the tutorial:
 *  			trooper "appear" -> metaio man fade in (no animation) -> metaio man "idle" {1s after metaio man is done fading in} trooper "fire" -> 
 *  			metaio man "shock_up" -> metaio man "idle" once -> metaio man "close_down" -> metaio man "close_up" AND trooper "die" -> metaio man "idle" 
 *  
 *  			{2s after trooper died}
 *  			start over! 
 **/

error_reporting(E_ALL);			//for displaying errors
ini_set("display_errors", 1);

require_once '../ARELLibrary/arel_xmlhelper.class.php';
 
//use the Arel Helper to start the output with arel

//start output
ArelXMLHelper::start(NULL, "arel/index.html", "http://dev.junaio.com/publisherDownload/tutorial/tracking_tutorial.zip");

//metaio man 3D Model
//Important: note how it is set to transparent 
$oObject = ArelXMLHelper::createGLUEModel3D(
											"mMan",	//ID 
											"http://dev.junaio.com/publisherDownload/tutorial/metaioman.md2", //model Path 
											"http://dev.junaio.com/publisherDownload/tutorial/metaioman.png", //texture Path
											array(-150,-100,0), //translation
											array(2,2,2), //scale
											new ArelRotation(ArelRotation::ROTATION_EULERDEG, array(0,0,90)), //rotation
											1 //CoordinateSystemID
										);
//set the object transparent
$oObject->setTransparency(1);
//return the model
ArelXMLHelper::outputObject($oObject);

//2. a trooper model
//note the additional parameter that is used in AREL JS
$oObject = ArelXMLHelper::createGLUEModel3D(
											"lTrooper",	//ID 
											"resources/legoStormTrooper.zip", //model 
											"resources/legoStormTrooper.png", //texture
											array(150,-100,0), //translation
											array(0.1,0.1,0.1), //scale
											new ArelRotation(ArelRotation::ROTATION_EULERDEG, array(90,0,0)), //rotation
											1 //CoordinateSystemID
										);
										
//the sound will be needed in AREL JS once the anmation starts
$oObject->addParameter("appearSound", "resources/beam.mp3");
//return the model
ArelXMLHelper::outputObject($oObject);

//the occlusion box, making the trooper invisible for the time it has not appeared
$box = ArelXMLHelper::createGLUEModel3D(
						"box", //id
						"resources/occlusionBox.zip", //model
						NULL, 
						array(0,-350,-350), //translation
						array(7, 7, 7), //scale
						new ArelRotation(ArelRotation::ROTATION_EULERDEG, array(0,0,0)),
						1 //rotation
				);

//this model is set as a occlusion model
$box->setOccluding(true);

//output the object
ArelXMLHelper::outputObject($box);

//end the output
ArelXMLHelper::end();

?>