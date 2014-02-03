<?php

/**
 * TDProject_Project_Common_ValueObjects_TaskViewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Collections/Interfaces/Collection.php';
require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TechDivision/Model/Interfaces/LightValue.php';
require_once 'TDProject/Project/Model/Entities/Task.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskValue.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the task overview.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_TaskOverviewData
    extends TDProject_Project_Common_ValueObjects_TaskLightValue
    implements TechDivision_Model_Interfaces_LightValue {

	/**
	 * Collection with the child tasks.
	 * @var TDProject_Project_Common_ValueObjects_Collections_Task
	 */
	protected $_tasks = null;

	/**
	 * Flag to mark the task selectable for booking or not
	 * @var boolean
	 */
	protected $_selectable = true;

    /**
     * The constructor intializes the DTO with the
     * values passed as parameter.
     *
     * @param TDProject_Project_Model_Entities_Task $task
     * 		The task to initialize the DTO with
     * @return void
     */
    public function __construct(TDProject_Project_Model_Entities_Task $task)
    {
        // call the parents constructor
        parent::__construct($task);
		// initialize the Collection with the loggings
		$this->_tasks = new TDProject_Project_Common_ValueObjects_Collections_Task();
    }

	/**
	 * Sets the Collection with subtasks.
	 *
	 * @param TechDivision_Collections_Interfaces_Collection Holds the subtasks
	 */
	public function setTasks(
	    TechDivision_Collections_Interfaces_Collection $collection = null) {
        $this->_tasks = $collection;
	}

	/**
	 * Returns the Collection with subtasks.
	 *
	 * @return TechDivision_Collections_Interfaces_Collection Holds the subtasks
	 */
	public function getTasks()
	{
        return $this->_tasks;
	}

	/**
	 * Sets the flag if the task is selectable for booking or not.
	 *
	 * @param boolean $selecable TRUE if the task is selectable, else FALSE
	 */
	public function setSelectable($selectable = true)
	{
		return $this->_selectable = $selectable;
	}

	/**
	 * Returns the flag if the task is selectable for booking or not.
	 *
	 * @return boolean TRUE if the task is selectable, else FALSE
	 */
	public function isSelectable()
	{
		return $this->_selectable;
	}

	/**
	 * Returns the class recursively encoded in JSON format.
	 *
	 * @return string The class JSON encoded
	 * @see TDProject_Project_Common_ValueObjects_TaskOverviewData::toStdClass()
	 */
	public function toJson()
	{
		return json_encode($this->toStdClass());
	}

    /**
     * Cast's the instance to a StdClass object.
     *
     * @return StdClass The casted instance
     */
    public function toStdClass()
    {
        // initialize a new StdClass object
        $stdClass = new StdClass();
        // initialize a new StdClass object for the ID
        $attr = new StdClass();
        $attr->id = $this->getTaskId()->intValue();
        // copy the values
        $stdClass->attr = $attr;
        $stdClass->data = $this->getName()->stringValue();
        // transform the children
        $children = array();
        // iterate over the items
        foreach ($this->getTasks() as $dto) {
            $children[] = $dto->toStdClass();
        }
        $stdClass->children = $children;
        // return the instance
        return $stdClass;
    }

    /**
     * Cast's the instance to a StdClass object.
     *
     * @return StdClass The casted instance
     */
    public function toSimpleClass()
    {
        // initialize a new StdClass object
        $stdClass = new StdClass();
        // copy the values
        $stdClass->taskId = $this->getTaskId()->intValue();
        $stdClass->name = $this->getName()->stringValue();
        $stdClass->selectable = $this->isSelectable();
        
        // transform the children
        $children = array();
        // iterate over the items
        foreach ($this->getTasks() as $dto) {
            $children[] = $dto->toSimpleClass();
        }
        $stdClass->children = $children;
        // return the instance
        return $stdClass;
    }
    /*
    
    public function toSimpleClass()
    {
    	// initialize a new StdClass object
    	$stdClass = new StdClass();
    	// copy the values
    	$stdClass->taskId = $this->getTaskId()->intValue();;
    	$stdClass->name = $this->getName()->stringValue();
    	// transform the children
    	$children = array();
    	// iterate over the items
    	foreach ($this->getTasks() as $dto) {
    		$children[] = $dto->toSimpleClass();
    	}
    	$stdClass->children = $children;
    	// return the instance
    	return $stdClass;
    }
    */
}