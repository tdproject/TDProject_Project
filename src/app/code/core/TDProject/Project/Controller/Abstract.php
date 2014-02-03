<?php

/**
 * TDProject_Project_Controller_Abstract
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
abstract class TDProject_Project_Controller_Abstract
    extends TDProject_Core_Controller_Abstract 
{

	/**
     * Initializes the Action with the Context for the
     * actual request.
	 *
     * @param TechDivision_Controller_Interfaces_Context $context
     * 		The Context for the actual Request
     * @return void
	 */
	public function __construct(
        TechDivision_Controller_Interfaces_Context $context) 
    {
        // call the parent method
        parent::__construct($context);
		 // initialize the default page title
		 $this->_setPageTitle(
		     new TechDivision_Lang_String('TDProject, v2.0 - Project')
		 );
	}

	/**
	 * This method returns the delegate for calling
	 * the backend functions.
	 *
	 * @return TDProject_Project_Model_Services_DomainProcessor
	 * 		The requested processor
	 */
	protected function _getDelegate()
	{
        return TDProject_Project_Common_Delegates_DomainProcessorDelegateUtil::getDelegate($this->getApp());
	}
}