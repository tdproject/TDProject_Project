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
class TDProject_Project_Common_ValueObjects_TaskViewData 
    extends TDProject_Project_Common_ValueObjects_TaskValue 
    implements TechDivision_Model_Interfaces_Value {
    
    /**
     * The task types available in the system.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_taskTypes = null;
    
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
        // initialize the ValueObject with the passed data
        $this->_taskTypes = new TechDivision_Collections_ArrayList();
    }
        
    /**
     * Sets the task types available in the system.
     * 
     * @param TechDivision_Collections_Interfaces_Collection $taskTypes
     * 		The task types availble in the system
     * @return void
     */
    public function setTaskTypes(
        TechDivision_Collections_Interfaces_Collection $taskTypes) {
        $this->_taskTypes = $taskTypes;
    }
        
    /**
     * Returns the task types available in the system.
     * 
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The task types available in the system
     */
    public function getTaskTypes()
    {
        return $this->_taskTypes;
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
}