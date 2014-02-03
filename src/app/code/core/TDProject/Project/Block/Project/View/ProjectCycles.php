<?php

/**
 * TDProject_Project_Block_Project_View_ProjectCycles
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
class TDProject_Project_Block_Project_View_ProjectCycles
    extends TDProject_Core_Block_Abstract
{

    /**
     * Initialize the block with the
     * apropriate template and name.
     *
     * @return void
     */
    public function __construct(
        TechDivision_Controller_Interfaces_Context $context)
    {
        // set the internal name
        $this->_setBlockName('projectCycles');
        // set the template name
        $this->_setTemplate(
        	'www/design/project/templates/project/view/project_cycles.phtml'
        );
        // call the parent constructor
        parent::__construct($context);
    }

    /**
     * Returns the project's cylces.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		Collection with the project's cycles
     */
    public function getProjectCycles()
    {
        return $this->getContext()->getActionForm()->getProjectCycles();
    }
    
    /**
     * Creates and returns the URL to delte the passed
     * project cycle.
     * 
     * @param TDProject_Project_Common_ValueObjects_ProjectCycleLightValue $projectCycle
     * 		The project cycle to create the delete URL for
     */
    public function getDeleteUrl(
    	TDProject_Project_Common_ValueObjects_ProjectCycleLightValue $projectCycle)
    {
    	// create the params
    	$params = array(
    		'path' => '/project',
    		'method' => 'deleteProjectCycle',
    		'projectId' => $projectCycle->getProjectIdFk(),
    		'projectCycleId' => $projectCycle->getProjectCycleId()
    	);
    	// create and return the URL
    	return $this->getUrl($params);
    }
}