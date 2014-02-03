<?php


/**
 * TDProject_Project_Common_Enums_Unit
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/Lang/String.php';
require_once 'TDProject/Project/Common/Exceptions/InvalidEnumException.php';

/**
 * This class is the enum type for all units
 * available for an estimation.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_Enums_Unit 
    extends TechDivision_Lang_Object {
    
    /**
     * Hour unit.
     * @var string
     */
    const HOUR = 'hour';
    
    /**
     * Day unit.
     * @var string
     */
    const DAY = 'day';
    
    /**
     * Week unit.
     * @var string
     */
    const WEEK = 'week';
    
    /**
     * Month unit.
     * @var string
     */
    const MONTH = 'month';
    
    /**
     * The available units.
     * @var array
     */
    protected static $_units = array(
    	self::HOUR, 
    	self::DAY, 
    	self::WEEK, 
    	self::MONTH
    );
    
    /**
     * The instance unit value.
     * @var string
     */
    protected $_unit = '';
    
    /**
     * Protected constructor to avoid direct initialization.
     * 
     * @param string $unit
     * 		The requested unit
     */
    protected function __construct($unit) {
    	$this->_unit = $unit;
    }
    
    /**
     * Factory method to create a new unit
     * instance for the requested value.
     * 
     * @param string $unit
     * 		The requested unit
     * @return TDProject_Project_Common_Enums_Unit
     * 		The requested unit instance
     * @throws TDProject_Project_Common_Exceptions_InvalidEnumException
     * 		Is thrown if the unit with the requested value is not available
     */
    public static function create($unit)
    {
    	// check if the requested value is valid
    	if (in_array($unit, self::$_units)) {
    		return new TDProject_Project_Common_Enums_Unit($unit);
    	}
    	// throw an exception if not
    	throw new TDProject_Project_Common_Exceptions_InvalidEnumException(
    		'Invalid enum ' . $unit . ' requested'
    	);
    }
    
    /**
     * Returns an ArrayList with all available units.
     * 
     * 
     * @return TechDivision_Collections_ArrayList
     * 		The ArrayList with all units
     */
    public static function load()
    {
    	// initialize the ArrayList
    	$list = new TechDivision_Collections_ArrayList();
    	// load all units
    	for ($i = 0; $i < sizeof(self::$_units); $i++) {
    		$list->add(self::create(self::$_units[$i]));	
    	}
    	// return the ArrayList
    	return $list;
    }
    
    /**
     * Returns the unit's string value.
  	 *
     * @return string The unit's value
     */
    public function getUnit()
    {
    	return $this->_unit;
    }
    
    /**
     * Returns the unit's string value.
  	 *
     * @return string The unit's value
     * @see TechDivision_Lang_Object::__toString()
     */
    public function __toString()
    {
    	return $this->getUnit();
    }
    
    /**
     * Returns the unit's String value.
  	 *
     * @return TechDivision_Lang_String 
     * 		The unit's value as String instance
     */
    public function toString()
    {
    	return new TechDivision_Lang_String($this->getUnit());
    }
}