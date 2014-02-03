<?php

/**
 * TDProject_Project_Controller_Project
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Controller/Util/GlobalForwardKeys.php';
require_once 'TDProject/Project/Controller/Abstract.php';
require_once 'TDProject/Project/Controller/Util/WebRequestKeys.php';
require_once 'TDProject/Project/Controller/Util/WebSessionKeys.php';
require_once 'TDProject/Project/Controller/Util/MessageKeys.php';
require_once 'TDProject/Project/Controller/Util/ErrorKeys.php';
require_once 'TDProject/Project/Block/Task/View.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Task
    extends TDProject_Project_Controller_Abstract {

	/**
	 * The key for the ActionForward to the task overview.
	 * @var string
	 */
	const TASK_OVERVIEW = "TaskOverview";

	/**
	 * The key for the ActionForward to the task view.
	 * @var string
	 */
	const TASK_VIEW = "TaskView";

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load a list with with all tasks of the project
	 * with the ID passed as Request parameter.
	 *
	 * @return void
	 */
	public function __defaultAction()
	{
		try {
            // load the project ID from the request
            if (($projectId = $this->_getRequest()->getAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::PROJECT_ID)) == null) {
                $projectId = $this->_getRequest()->getParameter(
                    TDProject_Project_Controller_Util_WebRequestKeys::PROJECT_ID
                );
            }
            // load the task overview data for the project with the passed ID
            $dtos = $this->_getDelegate()->getTaskOverviewData(
                TechDivision_Lang_Integer::valueOf(
                    new TechDivision_Lang_String($projectId)
                )
            );
            // get the DTO with the data to render the page
            $this->_getRequest()->setAttribute('dtos', $dtos);
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
		    TDProject_Project_Controller_Task::TASK_OVERVIEW
		);
	}

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load the task data with the ID passed in the
	 * Request for editing it.
	 *
	 * @return void
	 */
	public function editAction()
	{
        try {
            // load the task ID from the request
            if (($taskId = $this->_getRequest()->getAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::TASK_ID)) == null) {
                $taskId = $this->_getRequest()->getParameter(
                    TDProject_Project_Controller_Util_WebRequestKeys::TASK_ID
                );
            }
            // initialize the ActionForm with the data from the DTO
            $this->_getActionForm()->populate(
                $dto = $this->_getDelegate()->getTaskViewData(
                    TechDivision_Lang_Integer::valueOf(
                        new TechDivision_Lang_String($taskId)
                    )
                )
            );
            // register the DTO in the Request
            $this->_getRequest()->setAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::VIEW_DATA,
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
        // return to the task detail page
        return $this->_findForward(
            TDProject_Project_Controller_Task::TASK_VIEW
        );
	}

    /**
     * This method is automatically invoked by the controller and implements
     * the functionality to create a new task.
     *
	 * @return void
     */
    public function createAction()
    {
        try {
            // initialize the ActionForm with the data from the DTO
            $this->_getActionForm()->setTaskTypes(
                $this->_getDelegate()->getTaskViewData()->getTaskTypes()
            );
        } catch(Exception $e) {
            // create, add and save the error
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
        // return to the task detail page
        return $this->_findForward(
            TDProject_Project_Controller_Task::TASK_VIEW
        );
    }

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to save the passed task data.
	 *
	 * @return void
	 */
	public function saveAction()
	{
		try {
		    // load the ActionForm
		    $actionForm = $this->_getActionForm();
		    // validate the ActionForm with the task data
            $actionErrors = $actionForm->validate();
            if (($errorsFound = $actionErrors->size()) > 0) {
                $this->_saveActionErrors($actionErrors);
                return $this->createAction();
            }
            // save the passed task
            $taskId = $this->_getDelegate()->saveTask(
                $actionForm->repopulate()
            );
			// save the task and store the ID in the Request
			$this->_getRequest()->setAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::TASK_ID,
                $taskId->intValue()
            );
			// create the affirmation message
	        $actionMessages = new TechDivision_Controller_Action_Messages();
            $actionMessages->addActionMessage(
                new TechDivision_Controller_Action_Message(
                    TDProject_Project_Controller_Util_MessageKeys::AFFIRMATION,
                    $this->translate('taskUpdate.successfull')
                )
            );
            // save the ActionMessages in the request
            $this->_saveActionMessages($actionMessages);
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
		// return to the task detail page
        return $this->editAction();
	}

	/**
     * This method is automatically invoked by the controller and implements
     * the functionality to delete the task with the ID passed as Request
     * parameter.
     *
	 * @return void
     */
    public function deleteAction()
    {
        try {
            // load the task ID from the request
        	$taskId = $this->_getRequest()->getParameter(
                TDProject_Project_Controller_Util_WebRequestKeys::TASK_ID,
                FILTER_VALIDATE_INT
            );
            // delete the task
            $this->_getDelegate()->deleteTask(
                TechDivision_Lang_Integer::valueOf(
                    new TechDivision_Lang_String($taskId)
                )
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
        // return to the task overview page
        return $this->__defaultAction();
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
	    // register the Block in the Request
	    $this->_getRequest()->setAttribute($path, $this->getContext()->getActionForm());
	}
}