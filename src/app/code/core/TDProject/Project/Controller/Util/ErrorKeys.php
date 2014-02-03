<?php

/**
 * TDProject_Project_Controller_Util_ErrorKeys
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Util_ErrorKeys
{
	/**
	 * Private constructor for marking
	 * the class as utiltiy.
	 *
	 * @return void
	 */
	private final function __construct() { /* Class is a utility class */ }

	/**
	 * The key for the system error.
	 * @var string
	 */
	const SYSTEM_ERROR = "systemError";

	/**
	 * The key for a missing or invalid name.
	 * @var string
	 */
	const NAME = "name";

	/**
	 * The key for a missing or invalid description.
	 * @var string
	 */
	const DESCRIPTION = "description";

	/**
	 * The key for a missing or invalid from datetime.
	 * @var string
	 */
	const FROM = "from";

	/**
	 * The key for a missing or invalid until datetime.
	 * @var string
	 */
	const UNTIL = "until";

	/**
	 * The key for the error message that the estimation has to be unique.
	 * @var string
	 */
	const ESTIMATION_NOT_UNIQUE = 'estimationNotUnique';

	/**
	 * The key for a missing or invalid quantity.
	 * @var string
	 */
	const QUANTITY = "quantity";

	/**
	 * The key for a missing project ID.
	 * @var string
	 */
	const PROJECT_ID_FK = "projectIdFk";
	
	/**
	 * Error key for an invalid project cycle's closing date.
	 * @var string
	 */
	const CLOSING_DATE = "closingDate";
}