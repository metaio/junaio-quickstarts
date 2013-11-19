<?php

/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @author     metaio GmbH
 **/
require_once("arel_object.class.php");
require_once("arel_rotation.class.php");

/**
 * 
 * Arel Object Model3D Element.
 *
 */
class ArelObjectModel3D extends ArelObject
{
	private $onscreen = NULL;
	private $transformParent = NULL;
	private $translation = array(0,0,0);
	private $rotation = NULL;
	private $scale = array(1,1,1);
	private $occlusion = NULL;
	private $model = NULL;
	private $texture = NULL;
	private $movie = NULL;
	private $coordinateSystemID = NULL;
	private $shaderMaterial = NULL;	
	private $transparency = NULL;
	private $renderorderposition = NULL;
	private $picking = NULL;
	private $screenAnchor = NULL;
	private $screenAnchorFlag = NULL;

	/**
	 * Create the model
	 * @param String $id
	 */
	public function __construct($id)
	{
		parent::__construct($id);
		
		$this->rotation = new ArelRotation();
	}	
			
	/**
	 * Get Objects currently defined parent
	 * @return String the currently defined parent
	 */
	public function getTransformParent(){
		return $this->transformParent;
	}
	
	/**
	 * Set Objects current object
	 * @param String the parent object
	 */
	public function setTransformParent($transformParent){
		$this->transformParent = $transformParent;
	}

	/**
	 * Get Objects Translation
	 * @return Array An array given all translation parameters x,y,z
	 */
	public function getTranslation(){
		return $this->translation;
	}
	/**
	 * Set Objects Translation
	 * @param Array $translation An array given all translation parameters x,y,z
	 */
	public function setTranslation($translation){
		$this->translation = $translation;
	}
	
	/**
	 * Get Objects Rotation
	 * @return ArelRotation Rotation of the object
	 */
	public function getRotation(){
		return $this->rotation;
	}

	/**
	 * Set Objects Rotation
	 * @param ArelRotation $rotation Provides the rotation information. Can be defined as euler (rad / deg), quaternion, axisangle or matrix 
	 */
	public function setRotation($rotation){
		$this->rotation = $rotation;		
	}
	
	/**
	 * Get Objects Scale
	 * @return Array Scale of the object
	 */
	public function getScale(){
		return $this->scale;
	}

	/**
	 * Set Objects Scale
	 * @param Array An array providing scale values along three axis (x, y, z)
	 */
	public function setScale($scale){
		$this->scale = $scale;
	}

	/**
	 * Get whether the Object is an occlusion Object
	 * @return Boolean true if Object is an occlusion model
	 */  
	public function isOccluding(){
		return $this->occlusion;
	}

	/**
	 * Set the Objects to be an occlusion Object
	 * @param Boolean $occlusion true if Object is supposed to be an occlusion model
	 */ 
	public function setOccluding($occlusion){
		$this->occlusion = $occlusion;
	}

	/**
	 * Get the path to the model file (md2) or zip for obj/md2s
	 *  @see getMovie()
	 *  @see getTexture()
	 * @return String model path
	 */  
	public function getModel(){
		return $this->model;
	}

	/**
	 * Set the path to the model file (md2) or zip for obj/md2s
	 * @see setMovie()
	 * @see setTexture()
	 * @param String $model path to the model resource (geometry) as md2 or obj (zipped)
	 */ 
	public function setModel($model){
		$this->model = $model;
	}

	/**
	 * Get the path to the texture file (jpg/png) which is mapped on the model - can be undefined if zipped obj or md2 used or movie is set
	 * @see getModel()
	 * @see getMovie()
	 * @return String texture path
	 */  
	public function getTexture(){
		return $this->texture;
	}

	/**
	 * Set the path to the texture file (jpg/png) which is mapped on the model - not required if zipped obj or md2 used or movie is set
	 * @see setModel()
	 * @see setTexture()
	 * @param String $texture path to the model's texture
	 */ 
	public function setTexture($texture){
		$this->texture = $texture;
	}

	/**
	 * Get the path to the movie file(3g2) mapped on the 3D model - can be undefined if zipped obj or md2 used or texture is set
	 * @see getModel()
	 * @see getTexture()
	 * @return String movie path
	 */  
	public function getMovie(){
		return $this->movie;
	}

	/**
	 * Set the path to the movie file (3g2) to be mapped on the model
	 * @see setModel()
	 * @see setTexture()
	 * @see createFromMovie()
	 * @param String $movie path to a movie that shall be mapped on the 3D model 
	 */ 
	public function setMovie($movie){
		$this->movie = $movie;
	}

	/**
	 * Get the ID of the coordinateSystem the object is currently attached to (only valid feedback for GLUE channels/obejcts)
	 * @return int the coordinateSystem ID the object is bound to
	 */  
	public function getCoordinateSystemID(){
		return $this->coordinateSystemID;
	}

	/**
	 * Set the ID of the coordinateSystem the object is currently attached to (only valid feedback for GLUE channels/obejcts)
	 * @param int $coordinateSystemID the coordinateSystem ID the object is bound to
	 */ 
	public function setCoordinateSystemID($coordinateSystemID){
		$this->coordinateSystemID = $coordinateSystemID;
	}
	
	/**
	 * Get Objects currently defined shader material
	 * @return String the currently defined shader material 
	 */  
	public function getShaderMaterial(){
		return $this->shaderMaterial;
	}

	/**
	 * Set Objects shader material. For this to work, you also have to define global shaderMaterials
	 * @param String URL to the shader Materials file
	 */ 
	public function setShaderMaterial($shaderMaterial){
		$this->shaderMaterial = $shaderMaterial;
	}
	
    /**
     * Sets the coordinates of the object relative to the screen anchor passed as the first argument
     * @param int $screenAnchor Constant defining the screen anchor where the object will be placed @see ArelAnchor
     */
    public function setScreenAnchor($screenAnchor) {
        $this->screenAnchor = $screenAnchor;
    }

    /**
     * Get the screen anchor where the object is placed
     * @return int Anchor constant of the screen position @see ArelAnchor
     */
    public function getScreenAnchor() {
        return $this->screenAnchor;
    }

    /**
     * Sets the flags that will be used to modify the object behavior when placed relative to the screen
     * @param int $screenAnchorFlag Constant defining the behavior of the object @see ArelAnchor
     */
    public function setScreenAnchorFlag($screenAnchorFlag) {
        $this->screenAnchorFlag = $screenAnchorFlag;
    }

    /**
     * Get the screen anchor flag used to modify the objects behavior when it is placed relative to the screen
     * @return int Anchor constant of the screen position @see ArelAnchor
     */
    public function getScreenAnchorFlag() {
        return $this->screenAnchorFlag;
    }

   	/**
	 * Get the transparency of the 3D model.
	 * @return Float The transparency value, where 1 corresponds to an invisible model and 0 corresponds to a fully opaque model).
	 */
	public function getTransparency(){
		return $this->transparency;
	}
	
	/**
	 * Set the transparency of the 3D model.
	 * @param Float $transparency The transparency value, where 1 corresponds to an invisible model and 0 corresponds to a fully opaque model).
	 */
	public function setTransparency($transparency){
		$this->transparency = $transparency;
	}

	/**
	 * Get the position where the object will be rendered. The z-Buffer will be ignored.

	 * @return int Get the z-Buffer position of where the object shall be rendered. The "calculated" z-Buffer will be ignored. 
	 */
	public function getRenderorderPosition(){
		return $this->renderorderposition;
	}

	/**
	 * Set the position where the object will be rendered. The z-Buffer will be ignored.

	 * @param int $renderorderposition set the z-Buffer position of where the object shall be rendered. The "calculated" z-Buffer will be ignored. 
	 */
	public function setRenderOrderPosition($renderorderposition){
		$this->renderorderposition = $renderorderposition;
	}

	/**
	 * Use this method to determine whether an object can be picked or not (clicked)
	 * @return Boolean true if picking is enabled, false otherwise
	 */
	public function isPickingEnabled(){
		return $this->picking;
	}

	/**
	 * Use this method to declare whether an object can be picked or not (clicked)
	 * @param Boolean $picking true to enable picking of this model, false to disable it 
	 */
	public function setPickingEnabled($picking){
		$this->picking = $picking;
	}
	
	/**
	 * Create an Image 3D Model based on an image provided.
	 * @param String $_id object id
	 * @param String $_imagePath path to the image that shall be rendered
	 * @static
	 */
	public static function createFromImage($_id, $_imagePath)
	{
		$obj = new ArelObjectModel3D($_id);
		$obj->setTexture($_imagePath);
		
		return $obj;
	}

	/**
	 * Create a Movie 3D Model based on an the movie file provided.
	 * @param String $_id object id
	 * @param String $_moviePath path to the image that shall be rendered
	 * @static
	 */
	public static function createFromMovie($_id, $_moviePath)
	{
		$obj = new ArelObjectModel3D($_id);
		$obj->setMovie($_moviePath);
		
		return $obj;
	}
	
	/**
	 * Create an 3D Model based on model and texture (can also only have modelPath if the model is a zipped obj or md2 including the texture) 
	 * @param String $_id object id
	 * @param String $_modelPath path to the model's texture
	 * @param String $_texturePath path to the model's texture
	 * @static
	 */
	public static function create($_id, $_modelPath, $_texturePath)
	{
		$obj = new ArelObjectModel3D($_id);
		$obj->setModel($_modelPath);
		$obj->setTexture($_texturePath);
		
		return $obj;
	}
}
?>
