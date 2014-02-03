<?php

/**
 * TDProject_Project_Common_Exceptions_ProjectCycleClosedException
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
class TDProject_Project_Common_Exceptions_ProjectCycleClosedException
	extends Exception
{
	
	/**
	 * The project cycle's closing date.
	 * @var integer
	 */
	protected $_closingDate = 0;
	
	/**
	 * The project name.
	 * @var string
	 */
	protected $_projectName = '';

	/**
	 * Factory method to create
	 * a new instance.
	 *
	 * @return TDProject_Project_Common_Exceptions_ProjectCycleClosedException
	 * 		The exception instance
	 */
	public static function create()
	{
		return new TDProject_Project_Common_Exceptions_ProjectCycleClosedException();
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
	 * Sets the project cycle's closing date.
	 *
	 * @param integer $closing date
	 * 		The project cycle's closing date as UNIX timestamp
	 */
	public function setClosingDate($closingDate)
	{
		$this->_closingDate = $closingDate;
		return $this;
	}
	
	/**
	 * Returns the project cycle's closing date.
	 * 
	 * @return integer The closing date es UNIX timestamp
	 */
	public function getClosingDate()
	{
		return $this->_closingDate;
	}

	/**
	 * Sets the project's name.
	 *
	 * @param string $message
	 * 		The project's name
	 */
	public function setProjectName($projectName)
	{
		$this->_projectName = $projectName;
		return $this;
	}
	
	/**
	 * Returns the project's name.
	 * 
	 * @return string
	 */
	public function getProjectName()
	{
		return $this->_projectName;
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