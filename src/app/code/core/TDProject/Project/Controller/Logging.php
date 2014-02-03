<?php

/**
 * TDProject_Project_Controller_Logging
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
class TDProject_Project_Controller_Logging
    extends TDProject_Project_Controller_Abstract {

	/**
	 * The key for the ActionForward to the logging widget.
	 * @var string
	 */
	const LOGGING_VIEW = "LoggingView";

	/**
	 * The key for the ActionForward to the logging overview.
	 * @var string
	 */
	const LOGGING_OVERVIEW = "LoggingOverview";

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load a list with with all projects.
	 *
	 * @return void
	 */
	public function __defaultAction()
	{
		try {
			// replace the default ActionForm
			$this->getContext()->setActionForm(
				new TDProject_Project_Block_Logging_Overview(
				    $this->getContext()
				)
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
		    TDProject_Project_Controller_Logging::LOGGING_OVERVIEW
		);
	}

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load the logging data with the id passed in the
	 * Request for editing it.
	 *
	 * @return void
	 */
	public function editAction()
	{
        try {
            // load the logging ID from the request
            if (($id = $this->_getRequest()->getAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::TASK_USER_ID)) == null) {
                $id = $this->_getRequest()->getParameter(
                    TDProject_Project_Controller_Util_WebRequestKeys::TASK_USER_ID
                );
            }
            // initialize the ActionForm with the data from the DTO
            $this->_getActionForm()->populate(
                $dto = $this->_getDelegate()->getLoggingViewData(
                    $this->_getSystemUser()->getUserId(),
                    TechDivision_Lang_Integer::valueOf(
                        new TechDivision_Lang_String($id)
                    )
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
        return $this->_findForward(
            TDProject_Project_Controller_Logging::LOGGING_VIEW
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
            $this->_getActionForm()->initialize(
                $this->_getDelegate()->getLoggingViewData(
	            	$this->_getSystemUser()->getUserId()
	            )
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
            TDProject_Project_Controller_Logging::LOGGING_VIEW
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
            // save the passed logging entry
            $taskUserId = $this->_getDelegate()->saveTaskUser(
                $actionForm->repopulate($this->_getSystemUser())
            );
            // store the ID of the created logging entry in the request
            $this->_getRequest()->setAttribute(
            	TDProject_Project_Controller_Util_WebRequestKeys::TASK_USER_ID,
            	$taskUserId->intValue()
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
            // reset the ActionForm
            $actionForm->preset();
        } 
        catch(TDProject_Project_Common_Exceptions_InvalidTaskException $e) {
            // create, add and save the error
            $errors = new TechDivision_Controller_Action_Errors();
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                    $this->translate('Task ID existiert nicht!')
                )
            );
            // add the errors container to the Request
			$this->_saveActionErrors($errors);
    		// set the ActionForward in the Context
    		return $this->create();
        } 
        catch(TDProject_Project_Common_Exceptions_TaskOverbookedException $toe) {
            // create, add and save the error
            $errors = new TechDivision_Controller_Action_Errors();
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                    $this->translate('Task kann nicht weiter überbucht werden!')
                )
            );
            // add the errors container to the Request
			$this->_saveActionErrors($errors);
        } 
        catch(TDProject_Project_Common_Exceptions_TaskFinishedException $toe) {
            // create, add and save the error
            $errors = new TechDivision_Controller_Action_Errors();
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                    $this->translate('Task wurde bereits geschlossen!')
                )
            );
            // add the errors container to the Request
			$this->_saveActionErrors($errors);
        } 
        catch(TDProject_Project_Common_Exceptions_ProjectCycleClosedException $pcce) {
        	// initialize the parameters for the translation
        	$params = new TechDivision_Collections_ArrayList();
        	$params->add($pcce->getProjectName());
        	$params->add($pcce->getClosingDate());
            // create, add and save the error
            $errors = new TechDivision_Controller_Action_Errors();
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                    $this->translate('logging.project-cycle.closed', null, $params)
                )
            );
            // add the errors container to the Request
			$this->_saveActionErrors($errors);
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
     * This method is automatically invoked by the controller and implements
     * the functionality to delete the task-user logging entry with the ID
     * passed as request parameter.
     *
	 * @return void
     */
    public function deleteAction()
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
			// reset the ActionForm
            $this->_getActionForm()->reset();
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
     * (non-PHPdoc)
     * @see Zend/Acl/Resource/Interface::getResourceId()
     */
    public function getResourceId()
    {
		// try to load the resource ID as Request parameter
		$resourceId = $this->_getRequest()
		    ->getParameter(
    		    TechDivision_Controller_Action_Controller::ACTION_PATH,
    		    FILTER_SANITIZE_STRING
    		);
        // if not available, check for Request attribute
        if ($resourceId == null) {
            $resourceId = $this->_getRequest()->getParameter(
                TechDivision_Controller_Action_Controller::ACTION_PATH
            );
        }
	    // try to load the project ID from the Request
	    $projectIdFk = $this->_getRequest()->getParameter('projectIdFk');
        // if a project ID was found, append it to the resource ID
	    if (!empty($projectIdFk)) {
	        $resourceId .= "/$projectIdFk";
	    }
        // return the resource ID
        return $resourceId;
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
	    // initialize the page and add the Block
	    $page = new TDProject_Core_Block_Page($this->getContext());
	    $page->setPageTitle($this->_getPageTitle());
	    $page->addBlock($this->getContext()->getActionForm());
	    // register the Block in the Request
	    $this->_getRequest()->setAttribute($path, $page);
	}
}