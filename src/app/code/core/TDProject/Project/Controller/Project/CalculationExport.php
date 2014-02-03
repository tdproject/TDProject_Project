<?php

/**
 * TDProject_Project_Controller_Project_CalculationExport
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
class TDProject_Project_Controller_Project_CalculationExport
    extends TDProject_Project_Controller_Abstract {

	/**
	 * The key for the ActionForward to the calculation export.
	 * @var unknown_type
	 */
	const CALCULATION_EXPORT = "CalculationExport";

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to export the project's tasks as Excel sheet.
	 *
	 * @return void
	 */
	public function __defaultAction()
	{
    	try {
            // load the project ID from the request
        	$projectId = $this->_getRequest()->getParameter(
                TDProject_Project_Controller_Util_WebRequestKeys::PROJECT_ID,
                FILTER_VALIDATE_INT
            );
    		// reorganize the project
    		$dto = $this->_getDelegate()->exportProjectCalculation(
                TechDivision_Lang_Integer::valueOf(
                    new TechDivision_Lang_String($projectId)
                ),
                $this->_getSystemUser()->getUserId()
    		);
    		// set the DTO with the data in the Request
    		$this->_getRequest()->setAttribute(
    			TDProject_Project_Controller_Util_WebRequestKeys::VIEW_DATA,
    			$dto
    		);
    	} catch(Exception $e) {
    		// create, add and save the error
    		$errors = new TechDivision_Controller_Action_Errors();
    		$errors->addActionError(new TechDivision_Controller_Action_Error(
    			TDProject_Statistic_Controller_Util_ErrorKeys::SYSTEM_ERROR,
    			$e->__toString())
    		);
    		// adding the errors container to the Request
    		$this->_saveActionErrors($errors);
    		// set the ActionForward in the Context
		    return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
	    }
        // return to the project detail page
        return $this->_findForward(
            TDProject_Project_Controller_Project::CALCULATION_EXPORT
        );
	}

	/**
	 * Tries to load the Block class specified as path parameter
	 * in the ActionForward. If a Block was found and the class
	 * can be instanciated, the Block was registered to the Request
	 * with the path as key.
	 *
	 * @param TechDivision_Controller_Action_Forward $actionForward
	 * 		The ActionForward to initialize the Block for
	 * @return void
	 */
	protected function _getBlock(
	    TechDivision_Controller_Action_Forward $actionForward) {
	    // check if the class required to initialize the Block is included
	    if (!class_exists($path = $actionForward->getPath())) {
	        return;
	    }
	    // initialize the messages and add the Block
	    $page = new $path($this->getContext());
	    // register the Block in the Request
	    $this->_getRequest()->setAttribute($path, $page);
	}
}