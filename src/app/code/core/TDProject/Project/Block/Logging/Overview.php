<?php

/**
 * TDProject_Project_Block_Logging_Overview
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Formatter/Date.php';
require_once 'TDProject/Core/Block/Widget/Form/Abstract/Overview.php';
require_once 'TDProject/Core/Block/Widget/Grid/Ajax.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Actions/Ajax.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Logging_Overview
	extends TDProject_Core_Block_Widget_Form_Abstract_Overview {

	/**
	 * Returns the URL with the data source to
	 * load the JSON encoded array with the data
	 * to render.
	 *
	 * @return TechDivision_Lang_String
	 * 		The URL for loading the data
	 */
	public function getDataSource()
	{
		return new TechDivision_Lang_String(
			$this->getUrl(
				array(
					'namespace' => 'TDProject',
					'module' => 'Project',
					'path' => '/logging/json',
				)
			)
		);
	}

    /**
     * (non-PHPdoc)
     * @see TDProject_Core_Interfaces_Block_Widget_Form_Overview::prepareGrid()
     */
    public function prepareGrid()
    {
    	// instanciate the grid
    	$grid = new TDProject_Core_Block_Widget_Grid_Ajax(
    	    $this,
    	    'grid',
    	    $this->translate('logging.overview.grid.label.loggings')
    	);
    	// set the AJAX data source for the grid
    	$grid->setDataSource($this->getDataSource());
    	// add the columns
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'taskUserId',
    	    	$this->translate('logging.overview.grid.label.id'),
    	        5
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'projectName',
    	    	$this->translate('logging.overview.grid.label.project'),
    	        20
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'username',
    	    	$this->translate('logging.overview.grid.label.username'),
    	        20
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'taskName',
    	    	$this->translate('logging.overview.grid.label.task-name'),
    	        25
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'from',
    	    	$this->translate('logging.overview.grid.label.from'),
    	        15
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'until',
    	    	$this->translate('logging.overview.grid.label.until'),
    	        15
    	    )
    	);
		// add the row based Actions
    	$grid
    		->setDeleteUrl(
    		    array(
    		    	'path' => '/logging',
    		    	'method' => 'delete',
    		    	'taskUserId' => ''
    		    )
    		)
    		->addDeleteAction(
    		    $this->getDefaultTab(),
    		    'deleteLogging',
    		    $this->translate('logging.overview.grid.action.label.delete')
    		)
    		->setEditUrl(
    		    array(
    		    	'path' => '/logging',
    		    	'method' => 'edit',
    		    	'taskUserId' => ''
    		    )
    		)
    		->addEditAction(
    		    $this->getDefaultTab(),
    		    'editLogging',
    		    $this->translate('logging.overview.grid.action.label.edit')
    		);
    	// return the initialized instance
    	return $grid;
    }
}