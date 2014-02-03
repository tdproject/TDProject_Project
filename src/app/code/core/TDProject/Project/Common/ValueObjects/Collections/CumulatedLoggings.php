<?php

/**
 * TDProject_Project_Common_ValueObjects_Collections_Loggings
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

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
class TDProject_Project_Common_ValueObjects_Collections_CumulatedLoggings
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
    public function add(
        TDProject_Project_Common_ValueObjects_LoggingOverviewData $dto) {
		// load the user ID
        $userId = $dto->getUser()->getUserId()->intValue();
		// calculate the booked minutes
        $minutes = $this->_calculateMinutes($dto);
		// check if the user's booked time is already initialized
        if (!array_key_exists($userId, $this->_items)) {
    		// set the item in the array
            $this->_items[$userId] =
                new TDProject_Project_Common_ValueObjects_CumulatedLoggingLightValue(
                    $dto->getUser()
                );
        }
		// add the calculated minutes to the user
        $this->_items[$userId]->getMinutes()->add($minutes);
		// return the instance
		return $this;
    }

    /**
     * Calculates, rounds up and returns the difference in minutes.
     *
     * @param TDProject_Project_Common_ValueObjects_LoggingOverviewData
     * 		The logging entry to calculate the minutes for
     * @return integer The difference in minutes
     */
    protected function _calculateMinutes(
        TDProject_Project_Common_ValueObjects_LoggingOverviewData $dto) {
        // load the booked time
        $seconds = $dto->getToBook()->intValue();
		// return the minutes
    	return TechDivision_Lang_Integer::valueOf(
    	    new TechDivision_Lang_String(ceil($seconds / 60))
    	);
    }
}