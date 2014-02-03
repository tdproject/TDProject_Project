<?php

/**
 * TDProject_Project_Controller_Template
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
require_once 'TDProject/Project/Block/Template/View.php';
require_once 'TDProject/Project/Block/Template/Overview.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Template
    extends TDProject_Project_Controller_Abstract {

	/**
	 * The key for the ActionForward to the template overview.
	 * @var string
	 */
	const TEMPLATE_OVERVIEW = "TemplateOverview";

	/**
	 * The key for the ActionForward to the template view.
	 * @var string
	 */
	const TEMPLATE_VIEW = "TemplateView";

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load a list with with all templates.
	 *
	 * @return void
	 */
	public function __defaultAction()
	{
		try {
			// replace the default ActionForm
			$this->getContext()->setActionForm(
				new TDProject_Project_Block_Template_Overview(
				    $this->getContext()
				)
			);
            // load the template data to render the page
            $this->_getRequest()->setAttribute(
            	TDProject_Project_Controller_Util_WebRequestKeys::OVERVIEW_DATA,
            	$this->_getDelegate()->getTemplateOverviewData()
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
		    TDProject_Project_Controller_Template::TEMPLATE_OVERVIEW
		);
	}

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load the template data with the id passed in the
	 * Request for editing it.
	 *
	 * @return void
	 */
	public function editAction()
	{
        try {
            // load the template ID from the request
            if (($templateId = $this->_getRequest()->getAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::TEMPLATE_ID)) == null) {
                $templateId = $this->_getRequest()->getParameter(
                    TDProject_Project_Controller_Util_WebRequestKeys::TEMPLATE_ID
                );
            }
            // initialize the ActionForm with the data from the DTO
            $this->_getActionForm()->populate(
                $dto = $this->_getDelegate()->getTemplateViewData(
                    TechDivision_Lang_Integer::valueOf(
                        new TechDivision_Lang_String($templateId)
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
        // return to the template detail page
        return $this->_findForward(
            TDProject_Project_Controller_Template::TEMPLATE_VIEW
        );
	}

    /**
     * This method is automatically invoked by the controller and implements
     * the functionality to create a new template.
     *
	 * @return void
     */
    public function createAction()
    {
        // return to the template detail page
        return $this->_findForward(
            TDProject_Project_Controller_Template::TEMPLATE_VIEW
        );
    }

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to save the passed template data.
	 *
	 * @return void
	 */
	public function saveAction()
	{
		try {
		    // load the ActionForm
		    $actionForm = $this->_getActionForm();
		    // validate the ActionForm with the project data
            $actionErrors = $actionForm->validate();
            if (($errorsFound = $actionErrors->size()) > 0) {
                $this->_saveActionErrors($actionErrors);
                return $this->createAction();
            }
            // save the passed template
            $templateId = $this->_getDelegate()->saveTemplate(
                $actionForm->repopulate()
            );
			// save the template and store the ID in the Request
			$this->_getRequest()->setAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::TEMPLATE_ID,
                $templateId->intValue()
            );
			// create the affirmation message
	        $actionMessages = new TechDivision_Controller_Action_Messages();
            $actionMessages->addActionMessage(
                new TechDivision_Controller_Action_Message(
                    TDProject_Project_Controller_Util_MessageKeys::AFFIRMATION,
                    $this->translate('templateUpdate.successfull')
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
		// return to the template detail page
        return $this->editAction();
	}

	/**
     * This method is automatically invoked by the controller and implements
     * the functionality to delete the passed project.
     *
	 * @return void
     */
    public function deleteAction()
    {
        try {
            // load the template ID from the request
        	$templateId = $this->_getRequest()->getParameter(
                TDProject_Project_Controller_Util_WebRequestKeys::TEMPLATE_ID,
                FILTER_VALIDATE_INT
            );
            // delete the template
            $this->_getDelegate()->deleteTemplate(
                TechDivision_Lang_Integer::valueOf(
                    new TechDivision_Lang_String($templateId)
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
        // return to the template overview page
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
	    // initialize the page and add the Block
	    $page = new TDProject_Core_Block_Page($this->getContext());
	    $page->setPageTitle($this->_getPageTitle());
	    $page->addBlock($this->getContext()->getActionForm());
	    // register the Block in the Request
	    $this->_getRequest()->setAttribute($path, $page);
	}
}