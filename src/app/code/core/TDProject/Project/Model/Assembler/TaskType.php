<?php

/**
 * TDProject_Project_Model_Assembler_TaskType
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Model_Assembler_TaskType 
    extends TDProject_Project_Model_Assembler_Abstract {

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Assembler_TaskType($container);
    }
    /**
     * Returns an ArrayList with all task types 
     * assembled as DTO's.
	 *
     * @return TechDivision_Collections_ArrayList
     * 		The assembled task type enitities
     */
    public function getTaskTypeOverviewData() 
    {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // load the task types
        $taskTypes = TDProject_Project_Model_Utils_TaskTypeUtil::getHome($this->getContainer())
            ->findAll();
        // assemble the entities
        foreach ($taskTypes as $taskType) {
            $list->add(
            	new TDProject_Project_Common_ValueObjects_TaskTypeOverviewData($taskType)
            );
        }
        // return the ArrayList with the TaskTypeLightValues
        return $list;
    }

    /**
     * Returns an ArrayList with all task types 
     * assembled as LVO's.
	 *
     * @return TechDivision_Collections_ArrayList
     * 		The assembled task type enitities
     */
    public function getTaskTypeLightValues() 
    {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // load the task types
        $taskTypes = TDProject_Project_Model_Utils_TaskTypeUtil::getHome($this->getContainer())
            ->findAll();
        // assemble the entities
        foreach ($taskTypes as $taskType) {
            $list->add($taskType->getLightValue());
        }
        // return the ArrayList with the TaskTypeLightValues
        return $list;
    }
}