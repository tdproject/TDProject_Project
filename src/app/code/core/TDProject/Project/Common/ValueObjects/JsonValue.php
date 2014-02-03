<?php

/**
 * TDProject_Project_Common_ValueObjects_JsonValue
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * Implementation of a generic JSON value.
 * 
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_JsonValue
{
	
	/**
	 * The value to be encoded into a JSON value.
	 * @var mixed
	 */
	public $value = null;
	
	/**
	 * Constructor with the value to set.
	 * 
	 * @param mixed $value The value to be converted into JSON
	 * @return void
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}
	
	/**
	 * Encodes and returns the value into JSON.
	 * 
	 * @return string The JSON encoded value
	 */
	public function toJson()
	{
		return json_encode($this);
	}
}