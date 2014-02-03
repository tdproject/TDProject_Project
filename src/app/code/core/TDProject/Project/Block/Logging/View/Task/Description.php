<?php

/**
 * TDProject_Project_Block_Logging_View_Task_Description
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Logging_View_Task_Description
    extends TDProject_Core_Block_Abstract
{

	/**
	 * (non-PHPdoc)
	 * @see TDProject_Core_Block_Abstract::toHtml()
	 */
    public function toHtml()
    {
    	// check if the block was already rendered
    	if ($this->isRendered()) {
    		return;
    	}
    	// mark the block as already rendered
    	$this->setRendered();
        // translate and render the block
    	echo $this->getContext()->getAttribute('taskDescription');
    }
}