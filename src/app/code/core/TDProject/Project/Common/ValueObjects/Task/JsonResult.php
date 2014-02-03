<?php

/**
 * TDProject_Project_Common_ValueObjects_Task_JsonResult
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Boolean.php';
require_once 'TechDivision/Lang/Integer.php';
require_once 'TechDivision/Model/Interfaces/LightValue.php';

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
class TDProject_Project_Common_ValueObjects_Task_JsonResult 
    implements TechDivision_Model_Interfaces_LightValue {
    
    /**
     * The ID of the task.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskId = null;
    
    /**
     * The status of the last action invoked.
     * @var TechDivision_Lang_Boolean
     */
    protected $_status = null;
    
    /**
     * Initializes the status with the
     * passed task ID.
     * 
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task the last action was invoked on
     */
    public function __construct(TechDivision_Lang_Integer $taskId)
    {
        $this->_taskId = $taskId;
        $this->_status = new TechDivision_Lang_Boolean(true);
    }
        
    /**
     * Sets the ID of the task.
     * 
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task
     * @return void
     */
    public function setTaskId(TechDivision_Lang_Integer $taskId) {
        $this->_taskId = $taskId;
    }
        
    /**
     * Returns the ID of the task.
     * 
     * @return TechDivision_Lang_Integer
     * 		The ID of the task
     */
    public function getTaskId()
    {
        return $this->_taskId;
    }
        
    /**
     * Sets the status of the last action invoked.
     * 
     * @param TechDivision_Lang_Boolean $status
     * 		TRUE if the last action invoked was successfull, else FALSE
     * @return void
     */
    public function setStatus(TechDivision_Lang_Boolean $status) {
        $this->_status = $status;
    }
        
    /**
     * Returns the status of the last action invoked.
     * 
     * @return TechDivision_Lang_Boolean
     * 		TRUE if the last action invoked was successfull, else FALSE
     */
    public function getStatus()
    {
        return $this->_status;
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
        // copy the values
        $stdClass->taskId = $this->getTaskId()->intValue();
        $stdClass->status = $this->getStatus()->booleanValue();
        // return the instance
        return $stdClass;
    }
    
    /**
     * Returns a JSON encoded representation of
     * the actual instance.
     * 
     * @return string The JSON representation
     */
    public function toJson()
    {
        return json_encode($this->toStdClass());
    }
}