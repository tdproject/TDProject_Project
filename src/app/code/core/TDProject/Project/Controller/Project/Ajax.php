<?php

/**
 * TDProject_Project_Controller_Project_Ajax
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TDProject/Core/Common/ValueObjects/System/UserValue.php';
require_once 'TDProject/Core/Controller/Util/GlobalForwardKeys.php';
require_once 'TDProject/Project/Controller/Abstract.php';
require_once 'TDProject/Project/Controller/Util/WebRequestKeys.php';
require_once 'TDProject/Project/Controller/Util/MessageKeys.php';
require_once 'TDProject/Project/Controller/Util/ErrorKeys.php';
require_once 'TDProject/Project/Common/ValueObjects/ProjectUserLightValue.php';

/**
 * @category   	TDProject
 * @package    	TDProject_ERP
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Project_Ajax
    extends TDProject_Project_Controller_Abstract {

    /**
     * This method is automatically invoked by the controller and implements
     * the functionality to relate a user with the actual project.
     *
     * @return ActionForward Returns a ActionForward
     */
    public function relateProjectUserAction()
    {
        try {
            // load the user ID from the request
            $userId = TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String(
                    $this->_getRequest()->getParameter(
                    	TDProject_Project_Controller_Util_WebRequestKeys
                    	    ::USER_ID_FK,
                    	FILTER_VALIDATE_INT
                    )
                )
            );
            // load the project ID from the request
            $projectId = TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String(
                    $this->_getRequest()->getParameter(
                    	TDProject_Project_Controller_Util_WebRequestKeys
                    	    ::PROJECT_ID,
                    	FILTER_VALIDATE_INT
                    )
                )
            );
            // initialize a new LVO
            $lvo = new TDProject_Project_Common_ValueObjects_ProjectUserLightValue();
            $lvo->setProjectIdFk($projectId);
            $lvo->setUserIdFk($userId);
            // save the project-user relations
            $this->_getDelegate()->relateProjectUser($lvo);
            // create the affirmation message
	        $actionMessages = new TechDivision_Controller_Action_Messages();
            $actionMessages->addActionMessage(
                new TechDivision_Controller_Action_Message(
                    TDProject_Project_Controller_Util_MessageKeys::AFFIRMATION,
                    $this->translate('projectUserRelate.successfull')
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
        // return the ActionForward for rendering the system messages
        return $this->_findForward(
			TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_MESSAGES
		);
    }

    /**
     * This method is automatically invoked by the controller and implements
     * the functionality to unrelate a user with the actual project.
     *
     * @return ActionForward Returns a ActionForward
     */
    public function unrelateProjectUserAction()
    {
        try {
            // load the user ID from the request
            $userId = TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String(
                    $this->_getRequest()->getParameter(
                    	TDProject_Project_Controller_Util_WebRequestKeys
                    	    ::USER_ID_FK,
                    	FILTER_VALIDATE_INT
                    )
                )
            );
            // load the project ID from the request
            $projectId = TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String(
                    $this->_getRequest()->getParameter(
                    	TDProject_Project_Controller_Util_WebRequestKeys
                    	    ::PROJECT_ID,
                    	FILTER_VALIDATE_INT
                    )
                )
            );
            // initialize a new LVO
            $lvo = new TDProject_Project_Common_ValueObjects_ProjectUserLightValue();
            $lvo->setProjectIdFk($projectId);
            $lvo->setUserIdFk($userId);
            // delete the project-user relations
            $this->_getDelegate()->unrelateProjectUser($lvo);
            // create the affirmation message
	        $actionMessages = new TechDivision_Controller_Action_Messages();
            $actionMessages->addActionMessage(
                new TechDivision_Controller_Action_Message(
                    TDProject_Project_Controller_Util_MessageKeys::AFFIRMATION,
                    $this->translate('projectUserUnrelate.successfull')
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
        // return the ActionForward for rendering the system messages
        return $this->_findForward(
			TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_MESSAGES
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