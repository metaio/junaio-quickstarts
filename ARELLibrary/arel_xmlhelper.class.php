<?php
/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @author     metaio GmbH
 **/

require_once 'arel_object_model3D.class.php';
require_once 'arel_object_poi.class.php';
require_once 'arel_anchor.class.php';
require_once 'SimpleXMLExtended.php';

/**
 * 
 * A helper class to create correct junaio AREL output (XML).
 *
 */
class ArelXMLHelper
{	
	/**
	 * Tracking type GPS 
	 */
	const TRACKING_GPS = "GPS";
	
	/**
	 * Tracking type Orientation 
	 */
	const TRACKING_ORIENTATION = "Orientation";
	
	/**
	 * Tracking type LLA Marker 
	 */
	const TRACKING_LLA_MARKER = "LLA";
	
	/**
	 * Tracking type bar code and QR codes
	 */
	const TRACKING_BARCODE_QR = "Code";


	/** @brief Create a basic Location Based 3D Model.
	 * @param String $id Id of the AREL Object
	 * @param String $title Title of the AREL Object to be displayed in the popup (if added) as well as list and map
	 * @param String $model Path to the model of the Object or to the zip package holding all the information
	 * @param String $texture Path to the texture of the Object (jpg or png file) 
	 * @param Array $location An array given all location parameters (latitude, longitude and altitude)
	 * @param Array $scale An array providing scale values along three axis (x, y, z)
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix 
	 * @param String $icon provides an icon how the model will be displayed on a map (optional)
	 */
	static public function createLocationBasedModel3D($id, $title, $model, $texture, $location, $scale, $rotation, $icon = NULL)
	{
		$obj = new ArelObjectModel3D($id);
		$obj->setTitle($title);
		$obj->setModel($model);
		$obj->setTexture($texture);
		$obj->setScale($scale);
		$obj->setRotation($rotation);
		$obj->setLocation($location);
		$obj->setIcon($icon);
		
		return $obj;
	}
	
	/**
	 * @brief Create a basic Location Based 3D Movie Texture.
	 * @param String $id Id of the AREL Object
	 * @param String $title Title of the AREL Object to be displayed in the popup (if added) as well as list and map
	 * @param String $moviePath Path to the 3G2 movie that shall be displayed in the real world 
	 * @param Array $location An array given all location parameters (latitude, longitude and altitude)
	 * @param Array $scale An array providing scale values along three axis (x, y, z)
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix 
	 * @param String $icon provides an icon how the model will be displayed on a map (optional)
	 */
	static public function createLocationBasedModel3DFromMovie($id, $title, $moviePath, $location, $scale, $rotation, $icon = NULL)
	{
		$obj = ArelObjectModel3D::createFromMovie($id, $moviePath);
		
		$obj->setTitle($title);
		$obj->setScale($scale);
		$obj->setRotation($rotation);
		$obj->setLocation($location);
		$obj->setIcon($icon);
		
		return $obj;
	}
	
	/**
	 * @brief Create a basic Location Based 3D Image.
	 * @param String $id Id of the AREL Object
	 * @param String $title Title of the AREL Object to be displayed in the popup (if added) as well as list and map
	 * @param String $imagePath Path to the JPG or PNG image that shall be displayed in the real world 
	 * @param Array $location An array given all location parameters (latitude, longitude and altitude)
	 * @param Array $scale An array providing scale values along three axis (x, y, z)
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix 
	 * @param String $icon provides an icon how the model will be displayed on a map (optional)
	 */
	
	static public function createLocationBasedModel3DFromImage($id, $title, $imagePath, $location, $scale, $rotation, $icon = NULL)
	{
		$obj = ArelObjectModel3D::createFromImage($id, $imagePath);
		
		$obj->setTitle($title);
		$obj->setScale($scale);
		$obj->setRotation($rotation);
		$obj->setLocation($location);
		$obj->setIcon($icon);
		
		return $obj;
	}

	/**
	 * @brief Create a basic Location Based POI (Textual representation with default design)
	 * @param String $id Id of the AREL Object
	 * @param String $title Title of the AREL Object to be displayed in the popup (if added) as well as list and map
	 * @param Array $location An array given all location parameters (latitude, longitude and altitude)
	 * @param String $thumbnail provides an thumbnail to be displayed in the liveview and on the list
	 * @param String $icon provides an icon how the model will be displayed on a map (optional)
	 * @param String $description The description to be written in the pop up or the additional information
	 * @param Array $buttons An array defining buttons to be shown in the pop up / detail display 
	 
	 */
	static public function createLocationBasedPOI($id, $title, $location, $thumbnail, $icon, $description, $buttons = array())
	{
		$obj = new ArelObjectPoi($id);
		$obj->setTitle($title);
		$obj->setLocation($location);
		$obj->setThumbnail($thumbnail);
		$obj->setIcon($icon);
		
		if(!empty($description) || !empty($buttons))
		{
			$popup = new ArelPopup();
			$popup->setDescription($description);
			$popup->setButtons($buttons);
			
			$obj->setPopup($popup);
		}
		
		return $obj;
	}
	
	 /**
	 * @brief Create a 360 degree Object (3D Model)
	 * @param String $id Id of the AREL Object
	 * @param String $model Path to the model of the Object or to the zip package holding all the information
	 * @param String $texture Path to the texture of the Object (jpg or png file) 
	 * @param Array $translation An array given all translation parameters x,y,z
	 * @param Array $scale An array providing scale values along three axis (x, y, z)
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix 
	 * @param Int $renderPosition If you have multiple 360Objects created with transparencies
	 */
	static public function create360Object($id, $model, $texture, $translation, $scale, $rotation, $renderPosition = NULL)
	{
		$obj = new ArelObjectModel3D($id);
		$obj->setModel($model);
		$obj->setTexture($texture);
		$obj->setScale($scale);
		$obj->setRotation($rotation);
		$obj->setTranslation($translation);
		$obj->setRenderOrderPosition($renderPosition);
		
		return $obj;
	}

	/**
	 * @brief Create a basic Glue 3D Model.
	 * @param String $id Id of the AREL Object
	 * @param String $model Path to the model of the Object or to the zip package holding all the information
	 * @param String $texture Path to the texture of the Object (jpg or png file) 
	 * @param Array $translation An array given all translation parameters x,y,z
	 * @param Array $scale An array providing scale values along three axis (x, y, z)
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix 
	 * @param Int $coordinateSystemID ID of the corrdinateSystem the model shall be attached to
	 */
	static public function createGLUEModel3D($id, $model, $texture, $translation, $scale, $rotation, $coordinateSystemID)
	{
		$obj = new ArelObjectModel3D($id);
		$obj->setModel($model);
		$obj->setTexture($texture);
		$obj->setScale($scale);
		$obj->setRotation($rotation);
		$obj->setTranslation($translation);
		$obj->setCoordinateSystemID($coordinateSystemID);
		
		return $obj;
	}
	
	/**
	 * @brief Create a Glue Movie Texture (Movie overlaid a real world object)
	 * @param String $id Id of the AREL Object
	 * @param String $moviePath Path to the 3G2 movie that shall be displayed on the reference (coordinate system)
	 * @param Array $translation An array given all translation parameters x,y,z
	 * @param Array $scale An array providing scale values along three axis (x, y, z)
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix 
	 * @param Int $coordinateSystemID ID of the corrdinateSystem the model shall be attached to
	 */
	
	static public function createGLUEModel3DFromMovie($id, $moviePath, $translation, $scale, $rotation, $coordinateSystemID)
	{
		$obj = ArelObjectModel3D::createFromMovie($id, $moviePath);
		
		$obj->setScale($scale);
		$obj->setRotation($rotation);
		$obj->setTranslation($translation);
		$obj->setCoordinateSystemID($coordinateSystemID);
		
		return $obj;
	}
	
	/**
	 * @brief Create a Glue Movie Texture (Movie overlaid a real world object)
	 * @param String $id Id of the AREL Object
	 * @param String $imagePath Path to the JPG or PNG image that shall be displayed in the real world 
	 * @param Array $translation An array given all translation parameters x,y,z
	 * @param Array $scale An array providing scale values along three axis (x, y, z)
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix 
	 * @param Int $coordinateSystemID ID of the corrdinateSystem the model shall be attached to
	 */
	static public function createGLUEModel3DFromImage($id, $imagePath, $translation, $scale, $rotation, $coordinateSystemID)
	{
		$obj = ArelObjectModel3D::createFromImage($id, $imagePath);
		
		$obj->setScale($scale);
		$obj->setRotation($rotation);
		$obj->setTranslation($translation);
		$obj->setCoordinateSystemID($coordinateSystemID);
		
		return $obj;
	}
	
	/**
	 * @brief Create a screen fixed 3D Model, meaning it is always stuck to the devices screen
	 * @param String $id Id of the AREL Object
	 * @param String $model Path to the model of the Object or to the zip package holding all the information
	 * @param String $texture Path to the texture of the Object (jpg or png file) 
	 * @param int $screenAnchor A constant defining the screen anchor @see ArelAnchor.
	 * @param Array $scale An array providing scale values along three axis (x, y, z)
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix
	 * @return ArelObjectModel3D The object created
	 */
	static public function createScreenFixedModel3D($id, $model, $texture, $screenAnchor, $scale, $rotation)
	{
		$obj = new ArelObjectModel3D($id);
		$obj->setModel($model);
		$obj->setTexture($texture);
		$obj->setScale($scale);
		$obj->setRotation($rotation);
		$obj->setScreenAnchor($screenAnchor);
		
		return $obj;
	}
	
	/**
	 * @brief Start output to junaio
	 * @param string $resourcesPath Path to a zip holding all resources (models, images, shader, materials) [NOT SUPPORTED AT THE MOMENT)
	 * @param string $arelPath Path to a an HTML defining the GUI and hosting the arel JS
	 * @param string $trackingXML Path to the tracking xml or identifier of what shall be done (LLA Marker, Barcode / QR code). If nothing is provided, GPS is assumed.
	 * @param Array $sceneOptions An array providing scene options
	 */
	static public function start($resourcesPath = NULL, $arelPath = NULL, $trackingXML = null, $sceneOptions = NULL)
	{
		$arelBackUpPath = "";
		
		ob_start();
		ob_clean();
		
		if(isset($trackingXML) && $trackingXML != "")
	 		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><results trackingurl=\"$trackingXML\">";
		else
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><results>";
			
		if(isset($resourcesPath) && !empty($resourcesPath))
		{
			echo "<resources><![CDATA[$resourcesPath]]></resources>";
		}
		
		if(isset($arelPath) && !empty($arelPath))
		{
			echo "<arel><![CDATA[$arelPath]]></arel>";
		}
		else
			echo "<arel><![CDATA[$arelBackUpPath]]></arel>";
			
		if(isset($sceneOptions) && !empty($sceneOptions))
		{
			echo "<sceneoptions>";
			foreach ($sceneOptions as $sceneOptionKey => $sceneOptionValue)
			{
				echo "<sceneoption key=\"$sceneOptionKey\"><![CDATA[$sceneOptionValue]]></sceneoption>";
			}
			echo "</sceneoptions>";
		}
	}
	
	/**
	 * @brief End the XML output to junaio
	 */
	static public function end()
	{
		echo "</results>";
		ob_end_flush();	
	}
	
	/**
	 * @brief Create the XML output of the AREL Object and send it to the junaio server.
	 * @param ArelObject $oObject Object to be sent out
	 */
	static public function outputObject($oObject)
	{
		$object = new SimpleXMLExtended("<object></object>");
		$object->addAttribute('id', (string)$oObject->getID());
		
		if($oObject->getTitle())
			$object->addCData('title', $oObject->getTitle());
			
		if($oObject->getThumbnail())
			$object->addCData('thumbnail', $oObject->getThumbnail());
			
		if($oObject->getIcon())
			$object->addCData('icon', $oObject->getIcon());
			
		//location
		if($oObject->getLocation())
		{
		   	$location = $object->addChild("location");
		   	$oLocation = $oObject->getLocation();
	    	
		   	try {
		   		$location->addChild('lat', $oLocation[0]);
	    			$location->addChild('lon', $oLocation[1]);
	    			if(isset($oLocation[2]))
	    			{
	    		    		$location->addChild('alt', $oLocation[2]);
	    			}
                		else
                		{
                    	    		$location->addChild('alt', 0);
                		}
		   	}
		   	catch(Exception $e)
		   	{
		   		return $e;
		   	}	    		    	 
		}
		
		//popup
		if($oObject->getPopup())
		{
			$popup = $object->addChild("popup");
		   	$oPopUp = $oObject->getPopup();
		   	
		   	if($oPopUp->getDescription())
		   		$popup->addCData('description', $oPopUp->getDescription());
		   		
		   	if($oPopUp->getButtons())
		   	{
		   		$buttons = $popup->addChild("buttons");
		   		$aButtons = $oPopUp->getButtons();
		   		
		   		foreach($aButtons as $oButton)
		   		{
		   			$button = $buttons->addCData("button", $oButton[2]);
		   			$button->addAttribute("id", $oButton[1]);
		   			$button->addAttribute("name", $oButton[0]);		   			
		   		}
		   	}		   	
		}
		
		if($oObject instanceof ArelObjectModel3D)
		{
			//assets3D
			$assets3D = $object->addChild("assets3d");
					
			if($oObject->getModel())
		   		$assets3D->addCData('model', $oObject->getModel());
		   		
		   	if($oObject->getMovie())
		   		$assets3D->addCData('movie', $oObject->getMovie());
		   		
		   	if($oObject->getTexture())
		   		$assets3D->addCData('texture', $oObject->getTexture());
		   		
		   	//transform
		   	$transform = $assets3D->addChild("transform");
			
			$oTransform = $oObject->getTransformParent();
			if (isset($oTransform))
				$transform->addAttribute("parent", $oTransform);
		   	
		   	try {
		   		
		   		//translation
		   		$translation = $transform->addChild("translation");
		   		$oTranslation = $oObject->getTranslation();
		   		$translation->addChild("x", $oTranslation[0]);
		   		$translation->addChild("y", $oTranslation[1]);
		   		$translation->addChild("z", $oTranslation[2]);
		   		
		   		//rotation
		   		$rotation = $transform->addChild("rotation");
		   		$oRotationElement = $oObject->getRotation();
		   		$oRotation = $oRotationElement->getRotationValues();
		   		$oRotationType = $oRotationElement->getRotationType();
		   		
		   		$rotation->addAttribute("type", $oRotationType);
		   		
		   		if($oRotationType !== ArelRotation::ROTATION_MATRIX)
		   		{
		   			$rotation->addChild("x", $oRotation[0]);
			   		$rotation->addChild("y", $oRotation[1]);
			   		$rotation->addChild("z", $oRotation[2]);
			   		
			   		if($oRotationType == ArelRotation::ROTATION_QUATERNION)
			   			$rotation->addChild("w", $oRotation[3]);
			   		else if($oRotationType == ArelRotation::ROTATION_AXISANGLE)
			   			$rotation->addChild("angle", $oRotation[3]);
		   		}
		   		else //Matrix
		   		{
		   			$rotation->addChild("m0", $oRotation[0]);
		   			$rotation->addChild("m1", $oRotation[1]);
		   			$rotation->addChild("m2", $oRotation[2]);
		   			$rotation->addChild("m3", $oRotation[3]);
		   			$rotation->addChild("m4", $oRotation[4]);
		   			$rotation->addChild("m5", $oRotation[5]);
		   			$rotation->addChild("m6", $oRotation[6]);
		   			$rotation->addChild("m7", $oRotation[7]);
		   			$rotation->addChild("m8", $oRotation[8]);
		   		}
		   		
		   		//scale
		   		$scale = $transform->addChild("scale");
		   		$oScale = $oObject->getScale();
		   		$scale->addChild("x", $oScale[0]);
		   		$scale->addChild("y", $oScale[1]);
		   		$scale->addChild("z", $oScale[2]);
		   		
		   	}
		   	catch(Exception $e)
		   	{
		   		return $e;
		   	}
		
	   		//properties
	   		$pickingEnabled = $oObject->isPickingEnabled();
	   		$cosID = $oObject->getCoordinateSystemID();
			$shaderMaterial = $oObject->getShaderMaterial();
	   		$occluding = $oObject->isOccluding();
	   		$transparency = $oObject->getTransparency();
	   		$renderPosition = $oObject->getRenderOrderPosition();
            $screenAnchor = $oObject->getScreenAnchor();
	   		   	
		   	if(	isset($cosID) || isset($shaderMaterial) || isset($occluding) || isset($pickingEnabled) || 
		   		isset($screenAnchor) || isset($transparency) || isset($renderPosition))
		   	{
		   		$properties = $assets3D->addChild("properties");
		   		
		   		if(isset($cosID))
		   			$properties->addChild("coordinatesystemid", $cosID);
					
				if(isset($shaderMaterial))
		   			$properties->addChild("shadermaterial", $shaderMaterial);	
		   			
		   		if($occluding)
		   			$properties->addChild("occluding", "true");
		   			
		   		if(isset($transparency) && $transparency > 0)
		   			$properties->addChild("transparency", $oObject->getTransparency());
		   			
		   		if(isset($pickingEnabled) && !$pickingEnabled)
		   			$properties->addChild("pickingenabled", "false");
		   			
		   		if(isset($renderPosition))
		   			$properties->addChild("renderorder", $oObject->getRenderOrderPosition());

                if(isset($screenAnchor)) {
                    $screenAnchorProperty = $properties->addChild("screenanchor", $oObject->getScreenAnchor());
                    if( $oObject->getScreenAnchorFlag() != NULL)
                        $screenAnchorProperty->addAttribute("flags", $oObject->getScreenAnchorFlag(), null);
                }
		   	}
	   	}
	   	
	   	//viewparameters
	   	if($oObject->getVisibility() || $oObject->getMinAccuracy() || $oObject->getMinDistance() || $oObject->getMaxDistance())
	   	{
	   		$viewparameter = $object->addChild("viewparameters");
	   		
	   		if($oObject->getVisibility())
	   		{
	   			$visibility = $viewparameter->addChild("visibility");
	   			$oVisibility = $oObject->getVisibility();
	   			
	   			if((isset($oVisibility["liveview"]) && !$oVisibility["liveview"]))
	   				$visibility->addChild("liveview", "false");
	   				
	   			if(isset($oVisibility["maplist"]) && !$oVisibility["maplist"])
	   				$visibility->addChild("maplist", "false");
	   				
	   			if(isset($oVisibility["radar"]) && !$oVisibility["radar"])
	   				$visibility->addChild("radar", "false");
	   				
	   			//alternatively for 0,1,2
	   			if((isset($oVisibility[0]) && !$oVisibility[0]))
	   				$visibility->addChild("liveview", "false");
	   				
	   			if(isset($oVisibility[1]) && !$oVisibility[1])
	   				$visibility->addChild("maplist", "false");
	   				
	   			if(isset($oVisibility[2]) && !$oVisibility[2])
	   				$visibility->addChild("radar", "false");
	   		}
	   		
	   		if($oObject->getMinAccuracy())
	   			$viewparameter->addChild("minaccuracy", $oObject->getMinAccuracy());
	   			
	   		if($oObject->getMinDistance())
	   			$viewparameter->addChild("mindistance", $oObject->getMinDistance());
	   			
	   		if($oObject->getMaxDistance())
	   			$viewparameter->addChild("maxdistance", $oObject->getMaxDistance());
	   	}
	   	
	   	//parameters
	   	if($oObject->getParameters())
	   	{
	   		$parameters = $object->addChild("parameters");
	   		
	   		foreach($oObject->getParameters() as $key => $parValue)
	   		{
	   			$parameter = $parameters->addCData("parameter", $parValue);
	   			$parameter->addAttribute("key", $key);
	   		}
	   	}
	   	    	
    	$out = $object->asXML();
    	$pos = strpos($out, "?>");
	    echo utf8_encode(trim(substr($out, $pos + 2)));
	    ob_flush();		
	}	
	
	/**
	 * @brief Create array of currently supported scene options (with expected keys and based on provided locations), which is used as argument when starting output to junaio.
	 * @param String $environmentMapLocation Relative path or URL to environment map.
	 * @param Array $shaderMaterialsLocation Relative path or URL to shader materials.
	 * @return Array array of currently supported scene options.	 
	 */
	public static function createSceneOptions($environmentMapLocation, $shaderMaterialsLocation = NULL)
	{
		$aSceneOptions = array();
		if (!is_null($environmentMapLocation))
		{
			$aSceneOptions['environmentmap'] = $environmentMapLocation;
		}
		if (!is_null($shaderMaterialsLocation))
		{
			$aSceneOptions['shadermaterials'] = $shaderMaterialsLocation;
		}
		return $aSceneOptions;
	}
}
?>
