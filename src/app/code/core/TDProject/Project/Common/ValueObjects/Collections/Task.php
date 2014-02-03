<?php

/**
 * TDProject_Project_Common_ValueObjects_Collections_Task
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Common/ValueObjects/Collections/Abstract.php';

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
class TDProject_Project_Common_ValueObjects_Collections_Task
    extends TDProject_Core_Common_ValueObjects_Collections_Abstract  {

    /**
     * This method adds the passed object with the passed key
     * to the ArrayList.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskLightValue $dto
     * 		The DTO that should be added to the Collection
     * @return TDProject_Project_Common_ValueObjects_Collections_Task
     * 		The instance
     */
    public function add(TDProject_Project_Common_ValueObjects_TaskLightValue $dto)
    {
		// set the item in the array
        $this->_items[$this->_count++] = $dto;
		// return the instance
		return $this;
    }

    /**
     * Returns a JSON encoded representation of the
     * ArrayList and its items.
  	 *
  	 * @return string The JSON representation
     */
    public function toJson()
    {
        // initialize a new array
        $list = array();
        // iterate over the items
        foreach ($this->_items as $dto) {
            $list[] = $dto->toStdClass();
        }
        // return the JSON representation
        return json_encode($list);
    }
}