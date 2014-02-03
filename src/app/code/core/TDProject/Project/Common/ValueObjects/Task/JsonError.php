<?php

/**
 * TDProject_Project_Common_ValueObjects_Task_JsonResult
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Boolean.php';
require_once 'TechDivision/Lang/Integer.php';
require_once 'TechDivision/Lang/String.php';
require_once 'TechDivision/Model/Interfaces/LightValue.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the task overview.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2011 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Markus Berwanger <m.berwanger@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_Task_JsonError
    implements TechDivision_Model_Interfaces_LightValue
{
    /**
    * The status of the last action invoked.
    * @var TechDivision_Lang_Boolean
    */
    protected $_status = null;
    
    /**
     * The error message.
     * @var TechDivision_Lang_String
     */
    protected $_errorMessage = null;

    /**
    * The error header.
    * @var TechDivision_Lang_String
    */
    protected $_errorHeader = null;
    
    /**
     * Initialize the error-message with the given entries.
     * 
     * @param TechDivision_Lang_String $errorHeader
     * 		The error-header that should be used
     * @param TechDivision_Lang_String $errorMessage
     * 		The error-message that should be used
     */

    public function __construct(
        TechDivision_Lang_String $errorHeader, 
        TechDivision_Lang_String $errorMessage)
    {
        //an error is always a negative result
        $this->_status = new TechDivision_Lang_Boolean(false);
        
        $this->_errorHeader = $errorHeader;
        $this->_errorMessage = $errorMessage;
    }
    
    /**
     * Sets the error-header
     * @param TechDivision_Lang_String $errorHeader
     * @return void
     */
    public function setErrorHeader(TechDivision_Lang_String $errorHeader) 
    {
        $this->_errorHeader = $errorHeader;
    }
       
    /**
     * Returns the stored error-header
     * @return TechDivision_Lang_String $error-header
     */
    public function getErrorHeader()
    {
        return $this->_errorHeader;
    }
    
    /**
    * Sets the error-message
    * @param TechDivision_Lang_String $errorMessage
    * @return void
    */
    public function setErrorMessage(TechDivision_Lang_String $errorMessage) 
    {
        $this->_errorMessage = $errorMessage;
    }
     
    /**
     * Returns the stored error-message
     * @return TechDivision_Lang_String error-message
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }
    
    /**
     * Cast's the instance to a StdClass object.
     * 
     * @return StdClass The casted instance
     */
    public function toStdClass()
    {
         // initialize a new StdClass object
        $stdClass = new StdClass();
        // copy the values
        $stdClass->status = $this->_status->booleanValue();
        $stdClass->errorHeader = $this->getErrorHeader()->stringValue();
        $stdClass->errorMessage = $this->getErrorMessage()->stringValue();
        // return the instance
        return $stdClass;
    }
    
    /**
     * Returns a JSON encoded representation of
     * the actual instance.
     * 
     * @return string The JSON representation
     */
    public function toJson()
    {
        return json_encode($this->toStdClass());
    }
}