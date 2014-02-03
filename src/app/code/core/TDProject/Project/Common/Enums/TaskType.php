<?php


/**
 * TDProject_Project_Common_Enums_TaskType
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/Lang/String.php';
require_once 'TDProject/Project/Common/Exceptions/InvalidEnumException.php';

/**
 * This class is the enum type for all units
 * available for an estimation.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_Enums_TaskType
    extends TechDivision_Lang_Object {

    /**
     * Project.
     * @var string
     */
    const PROJECT = 1;

    /**
     * Subproject.
     * @var string
     */
    const SUBPROJECT = 2;

    /**
     * Extension.
     * @var string
     */
    const EXTENSION = 3;

    /**
     * Internal extension.
     * @var string
     */
    const EXTENSION_INTERNAL = 4;

    /**
     * External extension.
     * @var string
     */
    const EXTENSION_EXTERNAL = 5;

    /**
     * Activity.
     * @var string
     */
    const ACTIVITY = 6;

    /**
     * The available task types.
     * @var array
     */
    protected static $_taskTypes = array(
    	self::PROJECT,
    	self::SUBPROJECT,
    	self::EXTENSION,
    	self::EXTENSION_INTERNAL,
    	self::EXTENSION_EXTERNAL,
    	self::ACTIVITY,

    );

    /**
     * The instance task type.
     * @var integer
     */
    protected $_taskType = self::ACTIVITY;

    /**
     * Protected constructor to avoid direct initialization.
     *
     * @param string $taskTypes
     * 		The requested task type
     */
    protected function __construct($taskType)
    {
    	$this->_taskType = $taskType;
    }

    /**
     * Factory method to create a new task type
     * instance for the requested value.
     *
     * @param integer $taskType
     * 		The requested task type
     * @return TDProject_Project_Common_Enums_TaskType
     * 		The requested task type instance
     * @throws TDProject_Project_Common_Exceptions_InvalidEnumException
     * 		Is thrown if the task type with the requested value is not available
     */
    public static function create($taskType)
    {
    	// check if the requested value is valid
    	if (in_array($taskType, self::$_taskTypes)) {
    		return new TDProject_Project_Common_Enums_TaskType($taskType);
    	}
    	// throw an exception if not
    	throw new TDProject_Project_Common_Exceptions_InvalidEnumException(
    		'Invalid enum ' . $taskType . ' requested'
    	);
    }

    /**
     * Returns an ArrayList with all available task types.
     *
     *
     * @return TechDivision_Collections_ArrayList
     * 		The ArrayList with all task types
     */
    public static function load()
    {
    	// initialize the ArrayList
    	$list = new TechDivision_Collections_ArrayList();
    	// load all task types
    	for ($i = 0; $i < sizeof(self::$_taskTypes); $i++) {
    		$list->add(self::create(self::$_taskTypes[$i]));
    	}
    	// return the ArrayList
    	return $list;
    }

    /**
     * Returns the task type's string value.
  	 *
     * @return string The task type's value
     */
    public function getTaskType()
    {
    	return $this->_taskType;
    }

    /**
     * Returns the task type's string value.
  	 *
     * @return string The task types's value
     * @see TechDivision_Lang_Object::__toString()
     */
    public function __toString()
    {
    	return $this->getTaskType();
    }

    /**
     * Returns the task type's Integer value.
  	 *
     * @return TechDivision_Lang_String
     * 		The task type's value as Integer instance
     */
    public function toInteger()
    {
    	return new TechDivision_Lang_Integer($this->getTaskType());
    }

    protected function _equals(
    	TDProject_Project_Common_ValueObjects_TaskTypeLightValue $obj) {
    	return $obj->getTaskTypeId()->equals($this->toInteger());
    }

    public function equals(TechDivision_Lang_Object $obj)
    {
		return $this->_equals($obj);
    }
}