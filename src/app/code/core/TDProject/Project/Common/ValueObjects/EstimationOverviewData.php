<?php

/**
 * TDProject_Project_Common_ValueObjects_EstimationOverviewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/String.php';
require_once 'TechDivision/Model/Interfaces/LightValue.php';
require_once 'TDProject/Project/Common/ValueObjects/EstimationLightValue.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the estimation view.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_EstimationOverviewData
	extends TDProject_Project_Common_ValueObjects_EstimationLightValue
    implements TechDivision_Model_Interfaces_LightValue {
    
    /**
     * The username of the user who made the estimation.
     * @var TechDivision_Lang_String
     */
    protected $_username = null;
    
    /**
     * The constructor intializes the DTO with the
     * values passed as parameter.
     *
     * @param TDProject_Project_Model_Entities_Estimation $estimation
     * 		The estimation to initialize the DTO with
     * @return void
     */
    public function __construct(TDProject_Project_Model_Entities_Estimation $estimation)
    {
        // call the parents constructor
        parent::__construct($estimation);
        // initialize the ValueObject with the passed data
        $this->_username = new TechDivision_Lang_String();
    }
    
    /**
     * Sets the username of the user who gives the estimation.
     * 
     * @param TechDivision_Lang_String $userName
     * 		The user's username
     * @return void
     */
    public function setUsername(TechDivision_Lang_String $username)
    {
    	$this->_username = $username;
    }
    
    /**
     * Returns the username of the user who gives the estimation.
     * 
     * @return TechDivision_Lang_String
     * 		The user's username
     */
    public function getUsername()
    {
    	return $this->_username;
    }
}