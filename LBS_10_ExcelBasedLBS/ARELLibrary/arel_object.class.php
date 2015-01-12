<?php
/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @author     metaio GmbH
 **/
require_once("arel_popup.class.php");

/**
 * 
 * Arel Object Element. This class is not be instantiated, but is a virtual class.
 * @see ArelObjectModel3D 
 * @see ArelObjectPOI
 *
 */
class ArelObject
{
	private $id = NULL;
	private $title = NULL;
	private $popup = NULL;
	private $location = NULL;
	private $iconPath = NULL;
	private $thumbnailPath = NULL;
	private $minaccuracy = NULL;
	private $maxdistance = NULL;
	private $mindistance = NULL;
	private $parameters = array();
	private $visibility = NULL;
	
	/**
	*		
	*/
	protected function __construct($id)
	{
		$this->id = $id; 
	}
	
	/**
	 * Get Object ID.
	 * @return String ID of the Object
	 */
	public function getID(){
		return $this->id;
	}

	/**
	 * Set Object ID.
	 * @param String $id alphanummeric string defining the Object ID.
	 */
	public function setID($id){
		$this->id = $id;
	}
	
	/**
	 * Get Object Title.
	 * @param String Title of the Object
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 * Set Object Title.
	 * @param String $title Title of the Object
	 */
	public function setTitle($title){
		$this->title = $title;
	}

	/**
	 * Get Object Popup information.
	 * @return arelObjectPopup information for the Object information box 
	 */
	public function getPopup(){
		return $this->popup;
	}

	/**
	 * Set Object Popup information.
	 * @param arelObjectPopup $popup information for the Object information box
	 */
	public function setPopup($popup){
		$this->popup = $popup;
	}

	/**
	 * Get Object Location information (location-based Scenes only).
	 * @return Array coordination of the Object as latitude, longitude, altitude
	 */
	public function getLocation(){
		return $this->location;
	}

	/**
	 * Set Object Location information (location-based Scenes only).
	 * @param Array $location coordination of the Object as latitude, longitude, altitude
	 */
	public function setLocation($location){
		$this->location = $location;
	}

	/**
	 * Get Object icon path for the image as being displayed in the MapView (location-based Scenes only).
	 * @return String path where to retrieve the map icon from
	 */
	public function getIcon(){
		return $this->iconPath;
	}

	/**
	 * Set Object icon path for the image as being displayed in the MapView (location-based Scenes only).
	 * @param String $iconPath path where to retrieve the map icon from
	 */
	public function setIcon($iconPath){
		$this->iconPath = $iconPath;
	}

	/**
	 * Get Object thumbnail path for the image as being displayed in the ListView (location-based Scenes only).
	 * @return String path where to retrieve the list thumb from
	 */
	public function getThumbnail(){
		return $this->thumbnailPath;
	}

	/**
	 * Set Object thumbnail path for the image as being displayed in the ListView (location-based Scenes only).
	 * @param String $thumbnailPath path where to retrieve the list thumb from
	 */
	public function setThumbnail($thumbnailPath){
		$this->thumbnailPath = $thumbnailPath;
	}

	/**
	 * Get the minimum accuracy of the sensors to display the Object (location-based Scenes only).
	 * @return int Value in m (will be 1 for displaying an Object only if a LLA Marker is scanned)  
	 */
	public function getMinAccuracy(){
		return $this->minaccuracy;
	}

	/**
	 * Set the minimum accuracy of the sensors to display the Object (location-based Scenes only).
	 * @param int $minaccuracy Value in m (set 1 for displaying an Object only if a LLA Marker is scanned)  
	 */
	public function setMinAccuracy($minaccuracy){
		$this->minaccuracy = $minaccuracy;
	}

	/**
	 * Get the maximum distance to the Object to display it (location-based Scenes only).
	 * @return int maxdistance Value in m  
	 */
	public function getMaxDistance(){
		return $this->maxdistance;
	}

	/**
	 * Set the maximum distance to the Object to display it (location-based Scenes only).OBJECT_STATE_LOADING
	 * @param int $maxdistance Value in m  
	 */
	public function setMaxDistance($maxdistance){
		$this->maxdistance = $maxdistance;
	}

	/**
	 * Get the maximum distance to the Object to display it (location-based Scenes only).
	 * @return int mindistance Value in m  
	 */
	public function getMinDistance(){
		return $this->mindistance;
	}

	/**
	 * Set the maximum distance to the Object to display it (location-based Scenes only).
	 * @param int $mindistance mindistance Value in m  
	 */
	public function setMinDistance($mindistance){
		$this->mindistance = $mindistance;
	}

	/** Get all parameters for the object
	 * @return Array object with KEY => VALUE 
	 */
	public function getParameters(){
		return $this->parameters;
	}

	/**
	 * Use this method to set parameters for an object
	 * @param Array $parameters parameters of an object e.g. {"test" : 1, "url": "www.junaio.com"}
	 */
	public function setParameters($parameters){
		$this->parameters = $parameters;
	}
	
	/**
	 * Add single Parameter to an object
	 * @param String $key Key of the parameter
	 * @param String $value Value of the parameter
	 */
	public function addParameter($key, $value){
		$this->parameters[$key] = $value;
	}
	
	/**
	 * Set the visibility for an Object for MapView, ListView, LiveView and Radar. For GLUE, only LiveView is supported.
	 * @param Boolean $liveview set true if the Object should be shown in Live View, false if hidden, undefined is unchanged
	 * @param Boolean $maplist set true if the Object should be shown on the Map and in the List, false if hidden, undefined is unchanged
	 * @param Boolean $radar set true if the Object should be shown on the radar, false if hidden, undefined is unchanged
	 */
	public function setVisibility($liveview, $maplist, $radar){
		$this->visibility = array("liveview" => $liveview, "maplist" => $maplist, "radar" => $radar);
	}
	
	/**
	 * use this method to determine whether an object can be picked or not (clicked)
	 * @param Array visibility information of the Object {"liveview": bool, "maplist": bool, "radar": bool}  
	 */
	public function getVisibility(){
		return $this->visibility;
	}
}
