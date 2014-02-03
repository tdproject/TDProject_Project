<?php

/**
 * TDProject_Project_Common_ValueObjects_Collections_Logging
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Common/ValueObjects/Collections/ArrayList.php';

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
class TDProject_Project_Common_ValueObjects_Collections_Logging
    extends TDProject_Core_Common_ValueObjects_Collections_ArrayList  {
    	
    /**
     * This method adds the passed object with the passed key
     * to the ArrayList.
     *
     * @param TDProject_Project_Common_ValueObjects_LoggingOverviewData $dto 
     * 		The DTO that should be added to the Collection
     * @return TDProject_Project_Common_ValueObjects_Collections_Logging 
     * 		The instance
     */
    public function add(TDProject_Project_Common_ValueObjects_LoggingOverviewData $dto)
    {
		// set the item in the array
        $this->_items[$this->_count++] = $dto;
		// return the instance
		return $this;
    }
}