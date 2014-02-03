<?php


/**
 * TDProject_Project_Common_Enums_Complexity
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
 * This class is the enum type for all complexities
 * available for an estimation.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_Enums_Complexity
    extends TechDivision_Lang_Object {
    
    /**
     * Easy complexity.
     * @var string
     */
    const EASY = 'easy';
    
    /**
     * Medium complexity.
     * @var string
     */
    const MEDIUM = 'medium';
    
    /**
     * High complexity.
     * @var string
     */
    const HIGH = 'high';
    
    /**
     * The available complexities.
     * @var array
     */
    protected static $_complexities = array(
    	self::EASY, 
    	self::MEDIUM, 
    	self::HIGH
    );
    
    /**
     * The instance complexity value.
     * @var string
     */
    protected $_complexity = '';
    
    /**
     * Protected constructor to avoid direct initialization.
     * 
     * @param string $complexity
     * 		The requested complexity
     */
    protected function __construct($complexity) {
    	$this->_complexity = $complexity;
    }
    
    /**
     * Factory method to create a new complexity
     * instance for the requested value.
     * 
     * @param string $complexity
     * 		The requested complexity
     * @return TDProject_Project_Common_Enums_Complexity
     * 		The requested complexity instance
     * @throws TDProject_Project_Common_Exceptions_InvalidEnumException
     * 		Is thrown if the complexity with the requested value is not available
     */
    public static function create($complexity)
    {
    	// check if the requested value is valid
    	if (in_array($complexity, self::$_complexities)) {
    		return new TDProject_Project_Common_Enums_Complexity($complexity);
    	}
    	// throw an exception if not
    	throw new TDProject_Project_Common_Exceptions_InvalidEnumException(
    		'Invalid enum ' . $complexity . ' requested'
    	);
    }
    
    /**
     * Returns an ArrayList with all available complexities.
     * 
     * 
     * @return TechDivision_Collections_ArrayList
     * 		The ArrayList with all complexities
     */
    public static function load()
    {
    	// initialize the ArrayList
    	$list = new TechDivision_Collections_ArrayList();
    	// load all complexities
    	for ($i = 0; $i < sizeof(self::$_complexities); $i++) {
    		$list->add(self::create(self::$_complexities[$i]));	
    	}
    	// return the ArrayList
    	return $list;
    }
    
    /**
     * Returns the complexities string value.
  	 *
     * @return string The complexity value
     */
    public function getComplexity()
    {
    	return $this->_complexity;
    }
    
    /**
     * Returns the complexities string value.
  	 *
     * @return string The complexity value
     * @see TechDivision_Lang_Object::__toString()
     */
    public function __toString()
    {
    	return $this->getComplexity();
    }
    
    /**
     * Returns the complexities String value.
  	 *
     * @return TechDivision_Lang_String 
     * 		The complexity value as String instance
     */
    public function toString()
    {
    	return new TechDivision_Lang_String($this->getComplexity());
    }
}