<?php

/**
 * TDProject_Project_Controller_Logging_Widget
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
require_once 'TDProject/Project/Block/Logging/Widget.php';
require_once 'TDProject/Project/Common/Exceptions/InvalidTaskException.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Logging_Widget
    extends TDProject_Project_Controller_Abstract {

	/**
	 * The key for the ActionForward to the logging widget.
	 * @var string
	 */
	const LOGGING_VIEW = "LoggingView";

   /**
     * This method is automatically invoked by the controller and implements
     * the functionality to create a new task.
     *
	 * @return void
     */
    function createAction()
    {
        try {
            // initialize the ActionForm with the data from the DTO
            $this->_getActionForm()->populate(
                $dto = $this->_getDelegate()->getLoggingViewData(
                    $this->_getSystemUser()->getUserId()
                )
            );
            // register the DTO in the Request
            $this->_getRequest()->setAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::VIEW_DATA,
                $dto
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
        // return to the logging detail page
        return $this->_findForward(
            TDProject_Project_Controller_Logging_Widget::LOGGING_VIEW
        );
    }

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
            // save the passed logging entry
            $this->_getDelegate()->saveTaskUser(
                $actionForm->repopulate($this->_getSystemUser())
            );
			// create the affirmation message
	        $actionMessages = new TechDivision_Controller_Action_Messages();
            $actionMessages->addActionMessage(
                new TechDivision_Controller_Action_Message(
                    TDProject_Project_Controller_Util_MessageKeys::AFFIRMATION,
                    $this->translate('loggingUpdate.successfull')
                )
            );
            // save the ActionMessages in the request
            $this->_saveActionMessages($actionMessages);
        } catch(TDProject_Project_Common_Exceptions_InvalidTaskException $e) {
            // create and add and save the error
            $errors = new TechDivision_Controller_Action_Errors();
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                    $this->translate('Task ID existiert nicht!')
                )
            );
            // adding the errors container to the Request
			$this->_saveActionErrors($errors);
    		// set the ActionForward in the Context
    		return $this->createAction();
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
		return $this->createAction();
	}

	/**
     * This method is automatically invoked by the controller and implements
     * the functionality to delete the task-user logging entry with the ID
     * passed as request parameter.
     *
	 * @return void
     */
    function deleteAction()
    {
        try {
            // load the task-user relation ID from the request
        	$taskUserId = $this->_getRequest()->getParameter(
                TDProject_Project_Controller_Util_WebRequestKeys::TASK_USER_ID,
                FILTER_VALIDATE_INT
            );
            // delete the task-user relation
            $this->_getDelegate()->deleteTaskUser(
                TechDivision_Lang_Integer::valueOf(
                    new TechDivision_Lang_String($taskUserId)
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
        // return to the logging detail page
        return $this->createAction();
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