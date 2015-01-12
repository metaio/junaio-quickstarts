<?php

/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @author     metaio GmbH
 **/

/**
 * @brief ArelRotation is used to represent 3D rotations
 *
 */
class ArelRotation
{
	/**
	 * @brief Defines Rotation to be Euler rotation with degree values	
	 */
	const ROTATION_EULERDEG = "eulerdeg";
	
	/**
	 * @brief Defines Rotation to be Euler rotation with radians values
	*/
	const ROTATION_EULERRAD = "eulerrad";
	
	/**
	 * @brief Defines Rotation to be AxisAngle (x,y,z,angle in radians)
	*/
	const ROTATION_AXISANGLE = "axisangle";
	
	/**
	 * @brief Defines Rotation Matrix (m0..m8)
	*/
	const ROTATION_MATRIX = "matrix";
	
	/**
	 * @brief Defines Rotation to be Quaternion (q1..q4)
	*/
	const ROTATION_QUATERNION = "quaternion";
	
	private $rotationType = ArelRotation::ROTATION_EULERDEG;
	private $rotation = array(0,0,0);
	
	/**
	 * @brief Construct a rotation object
	 * @param String $type one of ArelRotation::ROTATION_EULERDEG, ArelRotation::ROTATION_EULERRAD, ArelRotation::ROTATION_AXISANGLE, ArelRotation::ROTATION_MATRIX or ArelRotation::ROTATION_QUATERNION
	 * @param Array $values Rotation values according to the type specified 
	 */
	public function __construct($type = NULL, $values = NULL)
	{
		if(!empty($type))
			$this->rotationType = $type;
			
		if(!empty($values))
			$this->rotation = $values;		
	}
	
	/**
	 * @brief Get the rotation values 
	 * @return Array Rotation values
	 */
	public function getRotationValues(){
		return $this->rotation;
	}

	/**
	 * @brief Set the rotation values
	 * @param Array $rotationValueArray Rotation values according to the type specified
	 */
	public function setRotationValues($rotationValueArray){
		$this->rotation = $rotationValueArray;
	}
	
	/**
	 * @brief Get the rotation type
	 * @return String one of ArelRotation::ROTATION_EULERDEG, ArelRotation::ROTATION_EULERRAD, ArelRotation::ROTATION_AXISANGLE, ArelRotation::ROTATION_MATRIX or ArelRotation::ROTATION_QUATERNION
	 */
	public function getRotationType(){
		return $this->rotationType;
	}
	
	/**
	 * @brief Set rotation type
	 * @param String one of ArelRotation::ROTATION_EULERDEG, ArelRotation::ROTATION_EULERRAD, ArelRotation::ROTATION_AXISANGLE, ArelRotation::ROTATION_MATRIX or ArelRotation::ROTATION_QUATERNION
	 */
	public function setRotationType($rotationType){
		$this->rotationType = $rotationType;		
	}
}
