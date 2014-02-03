<?php

/**
 * TDProject_Project_Block_Logging_View_Tasks
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Abstract.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Logging_View_Tasks
    extends TDProject_Core_Block_Abstract {

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
        	'www/design/project/templates/logging/view/tasks.phtml'
        );
        // call the parent constructor
        parent::__construct($context);
    }

    /**
     * Returns the ID of the task to load the
     * subtasks for.
     *
     * @return TechDivision_Lang_Integer
     * 		The ID of the task to load the subtasks for
     */
    public function getTaskIdFk()
    {
        return $this->getContext()->getActionForm()->getTaskIdFk();
    }
}