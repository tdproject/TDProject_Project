<?php

/**
 * TDProject_Project_Block_Logging_View_Task
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Abstract.php';
require_once 'TDProject/Project/Block/Logging/View/Task/Element.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Logging_View_Task
    extends TDProject_Core_Block_Abstract {

	/**
	 * Context key for the input name to render.
	 * @var string
	 */
	const INPUT_NAME = 'inputName';
	
	/**
	 * The default name for the task selector input field.
	 * @var string
	 */
	const DEFAULT_INPUT_NAME = 'taskIdFk';
	
    /**
     * Collection with the tasks.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_dtos = null;

    /**
     * Initialize the block with the
     * apropriate template and name.
     *
     * @return void
     */
    public function __construct(
        TechDivision_Controller_Interfaces_Context $context) 
    {
    	// check if a block name is set in the context
    	$blockName = $context->getAttribute(self::INPUT_NAME);
    	// if NO block name is set, use the default one
    	if ($blockName == null) {
    		$blockName = self::DEFAULT_INPUT_NAME;
    	}
        // set the internal name
        $this->_setBlockName($blockName);
        // set the template name
        $this->_setTemplate(
        	'www/design/project/templates/logging/view/task.phtml'
        );
        // call the parent constructor
        parent::__construct($context);
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Interfaces/Block#prepareLayout()
     */
    public function prepareLayout()
    {
        // load the DTO from the request
        $this->_dtos = $this->getApp()->getRequest()->getAttribute(
            TDProject_Core_Controller_Util_WebRequestKeys::VIEW_DATA
        );
        // add a Block for each subtask
        foreach ($this->_dtos as $task) {
            // add the tasks element for the TreeView
            $this->addBlock(
                new TDProject_Project_Block_Logging_View_Task_Element(
                    $this->getContext(), $task
                )
            );
        }
        // call the parent constructor
        parent::prepareLayout();
        // return the instance itself
        return $this;
    }

    /**
     * Returns the tasks related with the
     * project.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		Collection with the tasks
     */
    public function getTasks()
    {
        return $this->_dtos;
    }
}