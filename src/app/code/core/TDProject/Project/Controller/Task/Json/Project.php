<?php

/**
 * TDProject_Project_Controller_Task_Json_Template
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Controller/Util/GlobalForwardKeys.php';
require_once 'TDProject/Project/Controller/Task/Json.php';
require_once 'TDProject/Project/Controller/Util/WebRequestKeys.php';
require_once 'TDProject/Project/Controller/Util/WebSessionKeys.php';
require_once 'TDProject/Project/Controller/Util/MessageKeys.php';
require_once 'TDProject/Project/Controller/Util/ErrorKeys.php';
require_once 'TDProject/Project/Block/Task/Json.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Task_Json_Project
    extends TDProject_Project_Controller_Task_Json {

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to register tasks of the project with the ID passed
	 * as Request parameter in the Request.
	 *
	 * @return void
	 */
	public function __defaultAction()
	{
        try {
    	    // load the project ID to load the tasks for from the Request
    	    $id = $this->_getRequest()->getParameter(
    	        TDProject_Project_Controller_Util_WebRequestKeys::ID,
    	        FILTER_VALIDATE_INT
    	    );
    	    // cast the ID to an Integer
    	    $projectId = TechDivision_Lang_Integer::valueOf(
    	        new TechDivision_Lang_String($id)
    	    );
    	    // load the DTO with the project data
    	    $dto = $this->_getDelegate()
    	    	->getTaskOverviewDataByProjectId($projectId);
    		// register the project's tasks in the Request
    		$this->_getRequest()->setAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
                $dto
            );
        } catch(Exception $e) {
			// create and add and save the error
			$errors = new TechDivision_Controller_Action_Errors();
			$errors->addActionError(
			    new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                    $e->__toString()
                )
            );
			// adding the errors container to the Request
			$this->_saveActionErrors($errors);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
		}
		// set the ActionForward in the Context
		return $this->_findForward(
		    TDProject_Project_Controller_Task_Json::JSON
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
	    // add the Block with the ActionMessages and ActionErrors
	    $page->addBlock(new TDProject_Core_Block_Action($this->getContext()));
	    // register the Block in the Request
	    $this->_getRequest()->setAttribute($path, $page);
	}
}