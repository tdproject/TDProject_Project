<?php

/**
 * TDProject_Project_Block_Template_View
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Controller/Action/Error.php';
require_once 'TechDivision/Controller/Action/Errors.php';
require_once 'TechDivision/Collections/Interfaces/Collection.php';
require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TechDivision/Collections/Dictionary.php';
require_once 'TDProject/Core/Controller/Util/ErrorKeys.php';
require_once 'TDProject/Core/Block/Widget/Element/Input/Hidden.php';
require_once 'TDProject/Project/Block/Abstract/Template.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Checkbox.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Select.php';
require_once 'TDProject/Project/Block/Template/View/Tasks.php';

/**
 * @category    TDProject
 * @package     TDProject_Core
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Template_View
	extends TDProject_Project_Block_Abstract_Template {

    /**
     * (non-PHPdoc)
     * @see TDProject_Project_Block_Abstract_Template::getDeleteUrl()
     */
    public function getDeleteUrl()
    {
    	return '?path=' .
    	    $this->getPath() .
    	    '&method=delete&templateId=' .
    	    $this->getTemplateId();
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Interfaces/Block#prepareLayout()
     */
    public function prepareLayout()
    {
    	// initialize the tabs
    	$tabs = $this->addTabs(
    		'tabs', 
    		$this->translate('template.view.tabs.label.template')
    	);
        // add the tab for the template data
        $tabs->addTab(
        	'template', 
        	$this->translate('template.view.tab.label.template')
        )
    	->addFieldset(
    		'template', 
    		$this->translate('template.view.label.template')
        )
		->addElement(
		    $this->getElement(
		    	'textfield', 
		    	'name', 
		    	$this->translate('template.view.label.name')
		    )->setMandatory()
		)
		->addElement(
		    $this->getElement(
		    	'textarea', 
		    	'description', 
		    	$this->translate('template.view.label.description')
		    )->setMandatory()
		);
		// add the tab for the tasks if in edit mode
    	if (!$this->getTemplateId()->equals(new TechDivision_Lang_Integer(0))) {
		    // add the tab for the template tasks
			$tabs->addTab(
				'tasks', 
				$this->translate('template.view.tab.label.tasks')
			)
	        ->addBlock(
	            new TDProject_Project_Block_Template_View_Tasks(
	                $this->getContext()
	            )
	        );
    	}
	    // return the instance itself
	    return parent::prepareLayout();
    }

    /**
     * This method checks if the values in the member variables
     * holds valiid data. If not, a ActionErrors container will
     * be initialized an for every incorrect value a ActionError
     * object with the apropriate error message will be added.
     *
     * @return ActionErrors Returns a ActionErrors container with ActionError objects
     */
    public function validate()
    {
        // initialize the ActionErrors
        $errors = new TechDivision_Controller_Action_Errors();
        // check if a name was entered
        if ($this->_name->length() == 0) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::NAME,
                    $this->translate('template-name.none')
                )
            );
        }
        // check if a description was entered
        if ($this->_description->length() == 0) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::DESCRIPTION,
                    $this->translate('template-description.none')
                )
            );
        }
        // return the ActionErrors
        return $errors;
    }
}