<?php

/**
 * TDProject_Project_Block_Logging_View_Loggings
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
class TDProject_Project_Block_Logging_View_Loggings
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
        $this->_setBlockName('loggings');
        // set the template name
        $this->_setTemplate(
        	'www/design/project/templates/logging/view/loggings.phtml'
        );
        // call the parent constructor
        parent::__construct($context);
    }

    /**
     * Returns the last 10 logging entries of the actual user.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		Collection with the last 10 loggings
     */
    public function getLoggings()
    {
        return $this->getContext()->getActionForm()->getLoggings();
    }
}