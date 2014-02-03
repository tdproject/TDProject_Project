<?php

/**
 * TDProject_Project_Block_Project_View_UserSortComparator
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Collections/Interfaces/Comparator.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the company overview.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Project_View_UserSortComparator
	implements TechDivision_Collections_Interfaces_Comparator {

	/**
	 * The user ID's related with the project
	 * @var array
	 */
	protected $_selectedUsers = array();

	/**
	 * Initializes the internal array with ID's with the
	 * data from the passed Collection.
	 *
	 * @param TechDivision_Collections_Interfaces_Collection $selectedUsers
	 * 		Collection with the ID's of the related users
	 */
	public function __construct(
	    TechDivision_Collections_Interfaces_Collection $selectedUsers) {
		$this->_selectedUsers = $selectedUsers->toArray();
	}

	/**
     * (non-PHPdoc)
     * @see TechDivision/Collections/Interfaces/Comparator#compare($o1, $o2)
     */
    public function compare($o1, $o2) {
    	// check if the user ID's are in the array with the related ID's
    	$isUser1 = in_array(
    	    $o1->getUserId()->intValue(), $this->_selectedUsers
    	);
    	$isUser2 = in_array(
    	    $o2->getUserId()->intValue(), $this->_selectedUsers
    	);
    	// if user 1 IS in array and user 2 NOT
    	if ($isUser1 && !$isUser2) {
    		return -1;
    	}
    	// if user 2 IS in array and user 1 NOT
    	if ($isUser2 && !$isUser1) {
    		return 1;
    	}
    	// if user 1 AND user 2 are IN the array
    	if ($isUser1 && $isUser2) {
    		return 0;
    	}
    }
}