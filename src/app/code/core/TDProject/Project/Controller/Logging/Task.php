<?php

/**
 * TDProject_Project_Controller_Logging_Task
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
require_once 'TDProject/Project/Block/Logging/View/Task.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Logging_Task
    extends TDProject_Project_Controller_Logging {

	/**
	 * The key for the ActionForward to the view to render the task.
	 * @var string
	 */
	const TASK = 'Task';
	
	const TASK_DESCRIPTION = 'TaskDescription';

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
    	        TDProject_Project_Controller_Util_WebRequestKeys::PROJECT_ID,
    	        FILTER_VALIDATE_INT
    	    );
    	    // cast the ID to an Integer
    	    $projectId = TechDivision_Lang_Integer::valueOf(
    	        new TechDivision_Lang_String($id)
    	    );
    	    // load the DTO with the project data
    	    $dto = $this->_getDelegate()
    	        ->getTaskOverviewDataByProjectId($projectId);
    	    // try to load the name of the input field from the request
    	   	$inputName = $this->_getRequest()->getParameter(
    	        TDProject_Project_Controller_Util_WebRequestKeys::INPUT_NAME,
    	        FILTER_SANITIZE_STRING
    	    );
    	   	// if not found set the default one
    	   	if ($inputName == null) {
    	   		$inputName = 'taskIdFk';
    	   	}
    	    // try to load the name of the input field from the request
    	   	$ignoreSelectable = $this->_getRequest()->getParameter(
    	        TDProject_Project_Controller_Util_WebRequestKeys::IGNORE_SELECTABLE,
    	        FILTER_VALIDATE_BOOLEAN
    	    );
    	   	// if not found set the default one
    	   	if ($ignoreSelectable == null) {
    	   		$ignoreSelectable = false;
    	   	}
    	   	// set the name of the input field in the context
    	   	$this->getContext()->setAttribute(
    	   		TDProject_Project_Block_Logging_View_Task::INPUT_NAME, 
    	   		new TechDivision_Lang_String($inputName)
    	   	);
    	   	// set the flag to ignore the selectable flag or not
    	   	$this->getContext()->setAttribute(
    	   		TDProject_Project_Controller_Util_WebRequestKeys::IGNORE_SELECTABLE,
    	   		new TechDivision_Lang_Boolean($ignoreSelectable)
    	   	);
    		// register the project's tasks in the Request
    		$this->_getRequest()->setAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::VIEW_DATA,
                $dto->getTasks()
            );
        } 
        catch(Exception $e) {
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
		    TDProject_Project_Controller_Logging_Task::TASK
		);
	}
	
	/**
	 * Loads a task description and adds it to the Context.
	 * 
	 * @return void
	 */
	public function loadDescriptionAction()
	{
		try {
			// get the ID of the task to load the description for
			$taskId = $this->_getRequest()->getParameter(
				TDProject_Project_Controller_Util_WebRequestKeys::TASK_ID,
				FILTER_VALIDATE_INT
			);
			// load the task data
			$dto = $this->_getDelegate()->getTaskViewData(
				new TechDivision_Lang_Integer($taskId)
			);
			// add the task description to the Context
			$this->getContext()->setAttribute(
				'taskDescription', 
				$dto->getDescription()
			);
		}
		catch(Exception $e) {
			// log the exception
			$this->getLogger()->error($e->__toString());
			// create a user friendly error message
			$message = $this->translate(
				"task.load-error", 
				"No description available for task with ID $taskId"
			);
			
			// add the error message to the Context
			$this->getContext()->setAttribute('taskDescription', $message);
		}
		// set the ActionForward in the Context
		return $this->_findForward(
		    TDProject_Project_Controller_Logging_Task::TASK_DESCRIPTION
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