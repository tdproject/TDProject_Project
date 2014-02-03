<?php

/**
 * TDProject_Project_Block_Template_View_Tasks
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Abstract.php';
require_once 'TDProject/Project/Block/Template/View/Tasks/Task.php';

/**
 * @category    TDProject
 * @package     TDProject_Core
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Template_View_Tasks
    extends TDProject_Core_Block_Abstract {

    /**
     * The DTO with the acutal company data.
     * @var TDProject_Project_Common_ValueObjects_TemplateViewData
     */
    protected $_dto = null;

    /**
     * Initialize the block with the
     * apropriate template and name.
     *
     * @return void
     */
    public function __construct(
        TechDivision_Controller_Interfaces_Context $context) {
        // set the internal name
        $this->_setBlockName('tasks');
        // set the template name
        $this->_setTemplate(
        	'www/design/project/templates/template/view/tasks.phtml'
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
        $this->_dto = $this->getApp()->getRequest()->getAttribute(
            TDProject_Core_Controller_Util_WebRequestKeys::VIEW_DATA
        );
        // add the Block to edit the task
        $this->addBlock(
            new TDProject_Project_Block_Template_View_Tasks_Task(
                $this->getContext()
            )
        );
        // call the parent constructor
        parent::prepareLayout();
        // return the instance itself
        return $this;
    }

    /**
     * Returns the ID of the actual template as integer.
     *
     * @return integer The template ID
     */
    public function getTemplateId()
    {
        return $this->_dto->getTemplateId()->intValue();
    }
}