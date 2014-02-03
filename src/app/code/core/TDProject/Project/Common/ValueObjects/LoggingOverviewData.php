<?php

/**
 * TDProject_Project_Common_ValueObjects_ProjectOverviewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Model/Interfaces/Value.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskUserValue.php';
require_once 'TDProject/Project/Common/ValueObjects/ProjectLightValue.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the project overview.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_LoggingOverviewData 
    extends TDProject_Project_Common_ValueObjects_TaskUserValue 
    implements TechDivision_Model_Interfaces_Value {
    
    /**
     * The project of the task.
     * @var TDProject_ERP_Common_ValueObjects_ProjectLightValue
     */
    protected $_project= null;
    
    /**
     * The user of the task.
     * @var TDProject_Core_Common_ValueObjects_UserLightValue
     */
    protected $_user= null;
        
    /**
     * Sets the project of the logged task.
     * 
     * @param TDProject_Project_Common_ValueObjects_ProjectLightValue $project
     * 		The project of the logged task
     * @return void
     */
    public function setProject(
        TDProject_Project_Common_ValueObjects_ProjectLightValue $project) {
        $this->_project = $project;
    }
        
    /**
     * Returns the project of the logged task.
     * 
     * @return TDProject_Project_Common_ValueObjects_ProjectLightValue
     * 		The project of the logged task
     */
    public function getProject()
    {
        return $this->_project;
    }
        
    /**
     * Sets the user of the logged task.
     * 
     * @param TDProject_Core_Common_ValueObjects_UserLightValue $user
     * 		The user of the logged task
     * @return void
     */
    public function setUser(
        TDProject_Core_Common_ValueObjects_UserLightValue $user) {
        $this->_user = $user;
    }
        
    /**
     * Returns the user of the logged task.
     * 
     * @return TDProject_ERP_Common_ValueObjects_ProjectLightValue
     * 		The user of the logged task
     */
    public function getUser()
    {
        return $this->_user;
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
        $stdClass->taskUserId = $this->getTaskUserId()->intValue();
        $stdClass->projectName = $this->getProjectName()->stringValue();
        $stdClass->username = $this->getUsername()->stringValue();
        $stdClass->taskName = $this->getTaskName()->stringValue();
        $stdClass->from = $this->getFrom()->intValue();
        $stdClass->until = $this->getUntil()->intValue();
        // return the instance
        return $stdClass;
    }
    
    public function toArray()
    {
        // initialize and return the data as array
        return array(
	        $this->getTaskUserId()->intValue(),
	        $this->getProjectName()->stringValue(),
	        $this->getUsername()->stringValue(),
	        $this->getTaskName()->stringValue(),
	        TDProject_Core_Formatter_Date::get()->format($this->getFrom())->stringValue(),
	        TDProject_Core_Formatter_Date::get()->format($this->getUntil())->stringValue()
        );
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