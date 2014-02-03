<?php

/**
 * TDProject_Project_Controller_Logging_Json
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Controller/Util/GlobalForwardKeys.php';
require_once 'TDProject/Project/Controller/Logging.php';
require_once 'TDProject/Project/Controller/Util/WebRequestKeys.php';
require_once 'TDProject/Project/Controller/Util/WebSessionKeys.php';
require_once 'TDProject/Project/Controller/Util/MessageKeys.php';
require_once 'TDProject/Project/Controller/Util/ErrorKeys.php';
require_once 'TDProject/Project/Block/Logging/Json.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Logging_Json
    extends TDProject_Project_Controller_Logging {

	/**
	 * The key for the ActionForward to the JSON encoder.
	 * @var string
	 */
	const JSON = "Json";

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load a list with with all projects.
	 *
	 * @return void
	 */
	public function __defaultAction()
	{
		try {
            // load the logging overview data to render the page
            $this->_getRequest()->setAttribute(
            	TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
            	$this->_getDelegate()
            	    ->getLoggingOverviewData($this->getQueryParams())
            );
		} catch(Exception $e) {
			// create and add and save the error
			$errors = new TechDivision_Controller_Action_Errors();
			$errors->addActionError(new TechDivision_Controller_Action_Error(
                TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                $e->__toString())
            );
			// adding the errors container to the Request
			$this->_saveActionErrors($errors);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
		}
		// go to the standard page
		return $this->_findForward(
		    TDProject_Project_Controller_Logging_Json::JSON
		);
	}
	
	/**
	 * Load source date and minutes to add from the request, creates a new
	 * date and adds it to the request again.
	 * 
	 * @return void
	 */
    public function calculateDateAction()
    {
    	try {
    		// load the source date and the minutes to add from the request
	    	$sourceDate = $this->_getRequest()->getParameter('sourceDate');
	    	$minutesToAdd = $this->_getRequest()->getParameter('minutesToAdd');
	    	// create the date and add the minutes
	    	$targetDate = new Zend_Date($sourceDate);
	    	$targetDate->addMinute($minutesToAdd);
			// add the target date back to the request
	    	$this->_getRequest()->setAttribute(
	    		TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
	    		new TDProject_Project_Common_ValueObjects_JsonValue(
	    			$targetDate->get(Zend_Date::DATETIME_MEDIUM)
	    		)
	    	);
    	} 
    	catch(Exception $e) {
			// create and add and save the error
			$errors = new TechDivision_Controller_Action_Errors();
			$errors->addActionError(new TechDivision_Controller_Action_Error(
                TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                $e->__toString())
            );
			// adding the errors container to the Request
			$this->_saveActionErrors($errors);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
    	}
		// go to the standard page
		return $this->_findForward(
		    TDProject_Project_Controller_Logging_Json::JSON
		);
    }
	
	/**
	 * Load source and target date from the request, calculates the difference
	 * in minutes and adds it to the request again.
	 * 
	 * @return void
	 */
    public function calculateMinutesAction()
    {
    	try {
	    	// load source and target date from the request
	    	$sourceDate = new Zend_Date(
	    		$this->_getRequest()->getParameter('sourceDate')
	    	);
	    	$targetDate = new Zend_Date(
	    		$this->_getRequest()->getParameter('targetDate')
	    	);
	    	// calculate the difference in seconds
	    	$diff = $targetDate->sub($sourceDate);
	    	// calculate the difference in minutes
	    	$minutes = new TechDivision_Lang_Integer($diff->toValue());
	    	$minutes->divide(new TechDivision_Lang_Integer(60));
			// add the target date back to the request
	    	$this->_getRequest()->setAttribute(
	    		TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
	    		new TDProject_Project_Common_ValueObjects_JsonValue(
	    			$minutes->intValue()
	    		)
	    	);
    	} 
    	catch(Exception $e) {
			// create and add and save the error
			$errors = new TechDivision_Controller_Action_Errors();
			$errors->addActionError(new TechDivision_Controller_Action_Error(
                TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                $e->__toString())
            );
			// adding the errors container to the Request
			$this->_saveActionErrors($errors);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
    	}
		// go to the standard page
		return $this->_findForward(
		    TDProject_Project_Controller_Logging_Json::JSON
		);    	
    }

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load a list with with all logging entries for
	 * the project with the passed ID.
	 *
	 * @return void
	 */
	public function projectAction()
	{
		try {
			// load the project ID from the Request
			$projectId = TechDivision_Lang_Integer::valueOf(
				new TechDivision_Lang_String(
					$this->_getRequest()->getParameter(
						TDProject_Project_Controller_Util_WebRequestKeys::PROJECT_ID,
						FILTER_VALIDATE_INT
					)
				)
			);
			// load the query parameters and append the project ID
			$queryParams = $this->getQueryParams()->addCustomParam(
				TDProject_Project_Controller_Util_WebRequestKeys::PROJECT_ID,
				$projectId
			);
            // load the logging overview data to render the page
            $this->_getRequest()->setAttribute(
            	TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
            	$this->_getDelegate()
            	    ->getLoggingOverviewDataByProjectId($queryParams)
            );
		} catch(Exception $e) {
			// create and add and save the error
			$errors = new TechDivision_Controller_Action_Errors();
			$errors->addActionError(new TechDivision_Controller_Action_Error(
                TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                $e->__toString())
            );
			// adding the errors container to the Request
			$this->_saveActionErrors($errors);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
		}
		// go to the standard page
		return $this->_findForward(
		    TDProject_Project_Controller_Logging_Json::JSON
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
	    // if yes, create a new instance
	    $reflectionClass = new ReflectionClass($path);
	    $page = $reflectionClass->newInstance($this->getContext());
	    // register the Block in the Request
	    $this->_getRequest()->setAttribute($path, $page);
	}
}