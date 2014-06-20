<?php
/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @link       http://www.metaio.com
 * @author     Frank Angermann
 * 
 * @abstract	This tutorial provides a model using a reflection map.
 * 				 				
 * 				Learnings:
 * 					- how to use reflection maps
 **/

require_once '../ARELLibrary/arel_xmlhelper.class.php';

//use the Arel Helper to start the output with arel


/**
 * 	For more information about using reflection map, please look at those pages:
 * 	
 * 	http://dev.metaio.com/content-creation/environment-mapping/
 *  http://dev.metaio.com/sdk/tutorials/content-types/
 * 
 */

//start output
$list = array();
$list["EnvironmentMap"] = "resources/space.jpeg";
ArelXMLHelper::start(NULL, "index.html", "resources/tracking_tutorial.zip", $list);

//output the truck with reflection maps included
$oObject = ArelXMLHelper::createGLUEModel3D(
											"1",	//ID
											"truck.zip", //model Path
											NULL, //texture Path
											array(0,0,0), //translation
											array(1,1,1), //scale
											new ArelRotation(ArelRotation::ROTATION_EULERDEG, array(90,0,0)), //rotation
											1 //CoordinateSystemID
										);
//output the object
ArelXMLHelper::outputObject($oObject);

$oObject1 = ArelXMLHelper::createGLUEModel3D(
                                            "2",	//ID
                                            "sphere.zip", //model Path
                                            NULL, //texture Path
                                            array(0,200,80), //translation
                                            array(70,70,70), //scale
                                            new ArelRotation(ArelRotation::ROTATION_EULERDEG, array(0,0,0)), //rotation
                                            1 //CoordinateSystemID
                                        );

ArelXMLHelper::outputObject($oObject1);

//end the output
ArelXMLHelper::end();

?>