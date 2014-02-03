<?php

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/Model/Interfaces/LightValue.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the table task.
 *
 * Each class member reflects a database field and
 * the values of the related dataset.
 *
 * @package common
 * @author generator <core@techdivision.com>
 * @version $Revision: 1.2 $ $Date: 2007-12-06 15:39:17 $
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 */		
class TDProject_Project_Common_ValueObjects_TaskLightValue 
	extends TechDivision_Lang_Object
	implements TechDivision_Model_Interfaces_LightValue {	
		
	protected $_taskId;
		
	protected $_taskIdFk;
		
	protected $_taskTypeIdFk;
		
	protected $_left;
		
	protected $_right;
		
	protected $_level;
		
	protected $_orderNumber;
		
	protected $_name;
		
	protected $_description;
		
	protected $_billable;
		
	protected $_costCenter;

	/**
	 * The constructor intializes the DTO with the
	 * values passed as parameter.
	 * 
	 * @param array $array Holds the array with the virtual members to pass to the AbstractDTO's constructor
	 * @return void
	 */
	public function __construct(
	    TDProject_Project_Common_ValueObjects_TaskLightValue $lvo = null) {
		if (!empty($lvo)) {
			$this->setTaskId($lvo->getTaskId());
			$this->setTaskIdFk($lvo->getTaskIdFk());
			$this->setTaskTypeIdFk($lvo->getTaskTypeIdFk());
			$this->setLeft($lvo->getLeft());
			$this->setRight($lvo->getRight());
			$this->setLevel($lvo->getLevel());
			$this->setOrderNumber($lvo->getOrderNumber());
			$this->setName($lvo->getName());
			$this->setDescription($lvo->getDescription());
			$this->setBillable($lvo->getBillable());
			$this->setCostCenter($lvo->getCostCenter());
		}
	}
	
	
	/**
	 * Returns the value of the class member taskId.
	 *
	 * @return Integer Holds the value of the class member taskId
	 */
	public function getTaskId() 
	{
		return $this->_taskId;
	}
	
	/**
	 * Sets the value for the class member taskId.
	 *
	 * @param Integer $Integer Holds the value for the class member taskId
	 */
	public function setTaskId(TechDivision_Lang_Integer $taskId = null) 
	{
		$this->_taskId = $taskId;
	}

	/**
	 * Returns the value of the class member taskIdFk.
	 *
	 * @return Integer Holds the value of the class member taskIdFk
	 */
	public function getTaskIdFk() 
	{
		return $this->_taskIdFk;
	}
	
	/**
	 * Sets the value for the class member taskIdFk.
	 *
	 * @param Integer $Integer Holds the value for the class member taskIdFk
	 */
	public function setTaskIdFk(TechDivision_Lang_Integer $taskIdFk = null) 
	{
		$this->_taskIdFk = $taskIdFk;
	}

	/**
	 * Returns the value of the class member taskTypeIdFk.
	 *
	 * @return Integer Holds the value of the class member taskTypeIdFk
	 */
	public function getTaskTypeIdFk() 
	{
		return $this->_taskTypeIdFk;
	}
	
	/**
	 * Sets the value for the class member taskTypeIdFk.
	 *
	 * @param Integer $Integer Holds the value for the class member taskTypeIdFk
	 */
	public function setTaskTypeIdFk(TechDivision_Lang_Integer $taskTypeIdFk = null) 
	{
		$this->_taskTypeIdFk = $taskTypeIdFk;
	}

	/**
	 * Returns the value of the class member left.
	 *
	 * @return Integer Holds the value of the class member left
	 */
	public function getLeft() {
		return $this->_left;
	}
	
	/**
	 * Sets the value for the class member left.
	 *
	 * @param Integer $Integer Holds the value for the class member left
	 */
	public function setLeft(TechDivision_Lang_Integer $left = null) 
	{
		$this->_left = $left;
	}

	/**
	 * Returns the value of the class member right.
	 *
	 * @return Integer Holds the value of the class member right
	 */
	public function getRight() 
	{
		return $this->_right;
	}
	
	/**
	 * Sets the value for the class member right.
	 *
	 * @param Integer $Integer Holds the value for the class member right
	 */
	public function setRight(TechDivision_Lang_Integer $right = null) 
	{
		$this->_right = $right;
	}

	/**
	 * Returns the value of the class member level.
	 *
	 * @return Integer Holds the value of the class member level
	 */
	public function getLevel() 
	{
		return $this->_level;
	}
	
	/**
	 * Sets the value for the class member level.
	 *
	 * @param Integer $Integer Holds the value for the class member level
	 */
	public function setLevel(TechDivision_Lang_Integer $level = null) 
	{
		$this->_level = $level;
	}

	/**
	 * Returns the value of the class member order number.
	 *
	 * @return Integer Holds the value of the class member order number
	 */
	public function getOrderNumber() 
	{
		return $this->_orderNumber;
	}
	
	/**
	 * Sets the value for the class member order number.
	 *
	 * @param Integer $Integer Holds the value for the class member order number
	 */
	public function setOrderNumber(TechDivision_Lang_Integer $orderNumber = null) 
	{
		$this->_orderNumber = $orderNumber;
	}

	/**
	 * Returns the value of the class member name.
	 *
	 * @return String Holds the value of the class member name
	 */
	public function getName() 
	{
		return $this->_name;
	}
	
	/**
	 * Sets the value for the class member name.
	 *
	 * @param String $String Holds the value for the class member name
	 */
	public function setName(TechDivision_Lang_String $name) 
	{
		$this->_name = $name;
	}

	/**
	 * Returns the value of the class member description.
	 *
	 * @return String Holds the value of the class member description
	 */
	public function getDescription() 
	{
		return $this->_description;
	}
	
	/**
	 * Sets the value for the class member description.
	 *
	 * @param String $String Holds the value for the class member description
	 */
	public function setDescription(TechDivision_Lang_String $description) 
	{
		$this->_description = $description;
	}

	/**
	 * Returns the value of the class member billable.
	 *
	 * @return Boolean Holds the value of the class member billable
	 */
	public function getBillable() 
	{
		return $this->_billable;
	}
	
	/**
	 * Sets the value for the class member billable.
	 *
	 * @param Boolean $Boolean Holds the value for the class member billable
	 */
	public function setBillable(TechDivision_Lang_Boolean $billable) 
	{
		$this->_billable = $billable;
	}

	/**
	 * Returns the value of the class member cost center.
	 *
	 * @return Integer Holds the value of the class member cost center
	 */
	public function getCostCenter() 
	{
		return $this->_costCenter;
	}	
	
	/**
	 * Sets the value for the class member cost center.
	 *
	 * @param Integer $Integer Holds the value for the class member cost center
	 */
	public function setCostCenter(TechDivision_Lang_Integer $costCenter = null) 
	{
		$this->_costCenter = $costCenter;
	}	
}