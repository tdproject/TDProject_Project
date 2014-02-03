<?php

/**
 * TDProject_Project_Controller_Task_Json
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Controller/Util/GlobalForwardKeys.php';
require_once 'TDProject/Project/Controller/Task.php';
require_once 'TDProject/Project/Controller/Util/WebRequestKeys.php';
require_once 'TDProject/Project/Controller/Util/WebSessionKeys.php';
require_once 'TDProject/Project/Controller/Util/MessageKeys.php';
require_once 'TDProject/Project/Controller/Util/ErrorKeys.php';
require_once 'TDProject/Project/Block/Task/Json.php';
require_once 'TDProject/Project/Common/ValueObjects/Task/JsonResult.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Task_Json
    extends TDProject_Project_Controller_Task {

	/**
	 * The key for the ActionForward to the task view.
	 * @var string
	 */
	const JSON = "Json";

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to save the passed task data.
	 *
	 * @return void
	 */
	function saveAction()
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
                TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
                new TDProject_Project_Common_ValueObjects_Task_JsonResult(
                    $taskId
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
		// set the ActionForward in the Context
		return $this->_findForward(
		    TDProject_Project_Controller_Task_Json::JSON
		);
	}

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to delete the passed task data.
	 *
	 * @return void
	 */
	function deleteAction()
	{
        try {
            //get taskId from request
            $taskId = $this->_getActionForm()->getTaskId();        
			// delete the task and store the ID in the Request
            $this->_getDelegate()->deleteTask($taskId);
			$this->_getRequest()->setAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
                new TDProject_Project_Common_ValueObjects_Task_JsonResult(
                    $taskId
                )
            );
        } catch(TDProject_Project_Common_Exceptions_DeleteRootTaskException $drte) {
			//generate JSON-error and forward it
			$error = new TDProject_Project_Common_ValueObjects_Task_JsonError(
			    new TechDivision_Lang_String(
			        $this->translate('message.SystemMessage')
			    ), 
			    new TechDivision_Lang_String(
			        $this->translate('taskDelete.deletingRootTaskNotPossible')
			    )
			);
			$this->_getRequest()->setAttribute(
			    TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
			    $error
			);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Project_Controller_Task_Json::JSON
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
	 * This method is automatically invoked by the controller and implements
	 * the functionality to save the passed task data.
	 *
	 * @return void
	 */
	function renameAction()
	{
        try {
            // load the task
            $lvo = $this->_getDelegate()->getTaskViewData(
                $this->_getActionForm()->getTaskId()
            );
            // set the name
            $lvo->setName($this->_getActionForm()->getName());
            // save the task
            $taskId = $this->_getDelegate()->saveTask($lvo);
			// delete the task and store the ID in the Request
			$this->_getRequest()->setAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::JSON_RESULT,
                new TDProject_Project_Common_ValueObjects_Task_JsonResult(
                    $taskId
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
	    // register the Block in the Request
	    $this->_getRequest()->setAttribute($path, $page);
	}
}