<?php

/**
 * TDProject_Project_Common_Exceptions_TaskFinishedException
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category    TProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_Exceptions_TaskFinishedException
	extends Exception {

	/**
	 * Factory method to create
	 * a new instance.
	 *
	 * @return TDProject_Project_Common_Exceptions_TaskFinishedException
	 * 		The exception instance
	 */
	public static function create()
	{
		return new TDProject_Project_Common_Exceptions_TaskOverbookedException();
	}

	/**
	 * Sets the exception message.
	 *
	 * @param string $message
	 * 		The exception's message
	 */
	public function setMessage($message) {
		$this->message = $message;
		return $this;
	}

	/**
	 * Throws the exception.
	 *
	 * @throws Throws the Exception itself
	 * @return void
	 */
	public function throwSelf()
	{
		throw $this;
	}
}