<?php

/**
 * TDProject_Project_Block_Project_View
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
require_once 'TDProject/Project/Block/Abstract/Project.php';
require_once 'TDProject/Project/Common/ValueObjects/ProjectViewData.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Checkbox.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Select.php';
require_once 'TDProject/Project/Block/Project/View/Tasks.php';
require_once 'TDProject/Project/Block/Project/View/Estimations.php';
require_once 'TDProject/Project/Block/Project/View/UserSortComparator.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Project_View
	extends TDProject_Project_Block_Abstract_Project {

    /**
     * The available users.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_users = null;

    /**
     * The selected project-user relations.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_userIdFk = null;

    /**
     * The project cycle's closing date.
     * @var TechDivision_Lang_String
     */
    protected $_closingDate = null;

    /**
     * The Collection with the project's cycles.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_projectCycles = null;

    /**
     * Getter method for the available projects.
     *
     * @return TechDivision_Lang_Integer The available projects
     */
    public function getProjects()
    {
        return $this->_projects;
    }

    /**
     * Setter method for the available projects.
     *
     * @param TechDivision_Collections_Interfaces_Collection $projects
     * 		The available projects
     * @return void
     */
    public function setProjects(
        TechDivision_Collections_Interfaces_Collection $projects) {
        $this->_projects = $projects;
    }

    /**
     * Getter method for the available companies.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The available companies
     */
    public function getCompanies()
    {
        return $this->_companies;
    }

    /**
     * Setter method for the available companies.
     *
     * @param TechDivision_Collections_Interfaces_Collection $companies
     * 		The available companies
     * @return void
     */
    public function setCompanies(
        TechDivision_Collections_Interfaces_Collection $companies) {
        $this->_companies = $companies;
    }

    /**
     * Getter method for the templates available for creating
     * the project's tasks.
     *
     * @return TechDivision_Lang_Integer
     * 		The templates available for creating the project's tasks
     */
    public function getTemplates()
    {
        return $this->_templates;
    }

    /**
     * Setter method for the templates available for creating the
     * project's tasks.
     *
     * @param TechDivision_Collections_Interfaces_Collection $templates
     * 		The templates available for creating the project's tasks
     * @return void
     */
    public function setTemplates(
        TechDivision_Collections_Interfaces_Collection $templates) {
        $this->_templates = $templates;
    }

    /**
     * Getter method for the available users.
     *
     * @return TechDivision_Lang_Integer The available users
     */
    public function getUsers()
    {
        return $this->_users;
    }

    /**
     * Setter method for the available users.
     *
     * @param TechDivision_Collections_Interfaces_Collection $users The available users
     * @return void
     */
    public function setUsers(
        TechDivision_Collections_Interfaces_Collection $users) {
        $this->_users = $users;
    }

    /**
     * Getter method for the selected project-user relations.
     *
     * @return array The selected project-user relations
     */
    public function getUserIdFk()
    {
        return $this->_userIdFk;
    }

    /**
     * Setter method for the selected project-user relations.
     *
     * @param array $userIdFk The selected project-user relations.
     * @return void
     */
    public function setUserIdFk(array $userIdFk)
    {
    	foreach ($userIdFk as $value) {
        	$this->_userIdFk->add((integer) $value);
    	}
    }

    /**
     * Getter method for the project cycle's closing date.
     *
     * @return array The project cycle's closing date
     */
    public function getClosingDate()
    {
        return $this->_closingDate;
    }

    /**
     * Setter method for the project cycle's closing date.
     *
     * @param array $closingDate The project cycle's closing date
     * @return void
     */
    public function setClosingDate($closingDate)
    {
    	$this->_closingDate = new TechDivision_Lang_String($closingDate);
    }
    
    /**
     * Returns the project cycles.
     * 
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The Collection with the project cycles
     */
    public function getProjectCycles()
    {
    	return $this->_projectCycles;
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_ERP_Block_Abstract_Project::getDeleteUrl()
     */
    public function getDeleteUrl()
    {
    	return '?path=' .
    	    $this->getPath() .
    	    '&method=delete&projectId=' .
    	    $this->getProjectId();
    }

	/**
	 * Return URL to invoke the reorganization.
	 *
	 * @return string The reorganization URL
	 */
	public function getReorgUrl()
	{
        return $this->getUrl(
            array(
            	'path' => '/project',
            	'method' => 'reorg',
            	'projectId' => $this->getProjectId()
            )
        );
	}

	/**
	 * Return URL to invoke the export.
	 *
	 * @return string The export URL
	 */
	public function getExportUrl()
	{
        return $this->getUrl(
            array(
            	'path' => '/project/calculationExport',
            	'projectId' => $this->getProjectId()
            )
        );
	}

    /**
     * Sorts the users according if the user is related
     * with the project or not.
     *
     * @return TDProject_Project_Block_Project_View
     * 		The instance itself
     */
    protected function _sortUsers()
    {
    	// sort the addresses
        TechDivision_Collections_CollectionUtils::sort(
    		$this->_users,
        	new TDProject_Project_Block_Project_View_UserSortComparator(
        		$this->getUserIdFk()
        	)
        );
        // return the instance
        return $this;
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Interfaces/Block#prepareLayout()
     */
    public function prepareLayout()
    {
		// add the toolbar's default buttons
		$this->getToolbar()
			/* ->addButton(
			    new TDProject_Project_Block_Widget_Button_Reorg(
			        $this,
			        $this->translate(
			        	'widget.button.reorg',
			            TDProject_Core_Block_Widget_Abstract::MESSAGE_RESOURCES
			        )
			    )
			) */
			->addButton(
			    new TDProject_Project_Block_Widget_Button_Export(
			        $this,
			        $this->translate(
			        	'widget.button.export',
			            TDProject_Core_Block_Widget_Abstract::MESSAGE_RESOURCES
			        )
			    )
			);
    	// initialize the tabs
    	$tabs = $this->addTabs(
    		'project',
    		$this->translate('project.view.tabs.label.project')
    	);
        // add the tab for the project data
        $tab = $tabs->addTab(
        	'project',
        	$this->translate('project.view.tab.label.project')
        );
        // add the fieldset for the project data
        $fieldset = $tab->addFieldset(
        	'project',
        	$this->translate('project.view.fieldset.label.project')
        );
        // add the fields
        $fieldset->addElement(
            $this->getElement(
            	'textfield',
            	'name',
            	$this->translate('project.view.label.name')
            )->setMandatory()
        )
    	->addElement(
    	    $this->getElement(
    	    	'select',
    	    	'projectIdFk',
    	    	$this->translate('project.view.label.parent-project')
    	    )->setOptions($this->getProjects())->setDummyOption()
    	)
     	->addElement(
     	    $this->getElement(
     	    	'select',
     	    	'companyIdFk',
     	    	$this->translate('project.view.label.company')
     	    )->setOptions($this->getCompanies())
     	);
		// add the tabs for the reports and the tasks if in edit mode
    	if (!$this->getProjectId()->equals(new TechDivision_Lang_Integer(0))) {
    		// add a fieldset and a field for the project cycle closing date
    		$tab->addFieldset(
    			'project-cycle',
    			$this->translate('project.view.fieldset.label.project-cycle')
    		)->addElement(
	    		$this->getElement(
	    			'textfield',
	    			'closingDate',
	    			$this->translate('project.view.label.project-cycle.closing-date')
	    		)
	    	);
	       	// add fieldset actual logging entries
	        $tab->addFieldset(
	        	'project-cycles',
	        	$this->translate('project.view.fieldset.label.project-cycles')
	        )->addBlock(
	    	    new TDProject_Project_Block_Project_View_ProjectCycles(
	    	        $this->getContext()
	    	    )
	    	);
			// add the tab for the user data
		    $tabAddress = $tabs->addTab(
		    	'user',
		    	$this->translate('project.view.tab.label.user')
		    )
		    ->addGrid($this->_prepareUserGrid());
		    // add the tab for the project's loggings
			$tabs->addTab(
				'logging',
				$this->translate('project.view.label.logging')
			)
			->addGrid($this->_prepareLoggingGrid());
		    // add the tab for the projects tasks
			$tabs->addTab(
				'task',
				$this->translate('project.view.tab.label.tasks')
			)
	        ->addBlock(
	            new TDProject_Project_Block_Project_View_Tasks(
	                $this->getContext()
	            )
	        );
		    // add the tab for the projects estimations
			$tabs->addTab(
				'estimation',
				$this->translate('project.view.tab.label.estimations')
			)
	        ->addBlock(
	            new TDProject_Project_Block_Project_View_Estimations(
	                $this->getContext()
	            )
	        );
    	} else {
    		// if a new project will be created, add the template selector
    		$fieldset->addElement(
    		    $this->getElement(
    		    	'select',
    		    	'templateIdFk',
    		    	$this->translate('project.view.label.template')
    		    )->setOptions($this->getTemplates()));
    	}
	    // return the instance itself
	    return parent::prepareLayout();
    }

    /**
     * Initializes and returns the grid for the users.
     *
     * @return TDProject_Core_Block_Widget_Grid
     * 		The initialized grid
     */
    protected function _prepareUserGrid()
    {
    	// instanciate the grid
    	$grid = new TDProject_Core_Block_Widget_Grid(
    	    $this,
    	    'userGrid',
    	    $this->translate('project.view.grid.label.user')
    	);
    	// set the collection with the data to render
    	$grid->setCollection($this->_sortUsers()->getUsers());
    	// add the columns
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column_Checkbox(
    	    	'userIdFk', '', 5
    	    )
    	)
		->setCheckedUrl(
			'?path=/project/ajax&method=relateProjectUser&projectId=' .
		    $this->getProjectId()
		)
		->setUncheckedUrl(
			'?path=/project/ajax&method=unrelateProjectUser&projectId=' .
		    $this->getProjectId()
		)
		->setProperty('userId')
		->setSourceCollection($this->getUserIdFk())
		->setTargetProperty('userId');
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'userId',
    	    	$this->translate(
    	    		'project.view.grid.user.column.label.id'
    	    	),
    	        5
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'username',
    	    	$this->translate(
    	    		'project.view.grid.user.column.label.username'
    	    	),
    	        70
    	    )
    	);
    	// return the initialized instance
    	return $grid;
    }

    /**
     * Initializes and returns the grid for the logging entries.
     *
     * @return TDProject_Core_Block_Widget_Grid
     * 		The initialized grid
     */
    protected function _prepareLoggingGrid()
    {
	    // instanciate the grid
	    $grid = new TDProject_Core_Block_Widget_Grid_Ajax(
		    $this,
		    'loggingGrid',
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
        	    $this->translate('logging.overview.grid.label.projectName'),
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
	    // return the initialized instance
	    return $grid;
    }

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
			    	'method' => 'project',
			    	'projectId' => $this->getProjectId()->intValue()
			    )
		    )
	    );
    }

    /**
     * Resets all member variables to their
     * default values.
     *
     * @return void
     */
    public function reset()
    {
    	parent::reset();
        $this->_projects = new TechDivision_Collections_ArrayList();
        $this->_companies = new TechDivision_Collections_ArrayList();
        $this->_templates = new TechDivision_Collections_ArrayList();
        $this->_userIdFk = new TechDivision_Collections_ArrayList();
        $this->_users = new TechDivision_Collections_ArrayList();
        $this->_closingDate = new TechDivision_Lang_String();
        $this->_projectCycles = new TechDivision_Collections_ArrayList();
    }

    /**
     * Populates the ActionForm with the values
     * of the passed DTO.
     *
     * @param TDProject_Project_Common_ValueObjects_ProjectViewData $dto
     * 		The DTO with the data to initialize the ActionForm with
     * @return void
     */
    public function initialize(
        TDProject_Project_Common_ValueObjects_ProjectViewData $dto) {
        $this->_projects = $dto->getProjects();
        $this->_companies = $dto->getCompanies();
        $this->_templates = $dto->getTemplates();
        $this->_users = $dto->getUsers();
        $this->_userIdFk = $dto->getUserIdFk();
        $this->_closingDate = $dto->getClosingDate();
        $this->_projectCycles = $dto->getProjectCycles();
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_Project_Block_Abstract_Project::populate()
     */
    public function populate(
        TDProject_Project_Common_ValueObjects_ProjectViewData $dto) {
    	parent::populate($dto);
        $this->initialize($dto);
    }

    /**
     * Initializes and returns a new LVO
     * with the data from the ActionForm.
     *
     * @return TDProject_Project_Common_ValueObjects_ProjectLightValue
     * 		The initialized LVO
     */
    public function repopulate()
    {
		// initialize a new LVO
		$lvo = new TDProject_Project_Common_ValueObjects_ProjectViewData();
		// filling it with the project data from the ActionForm
		$lvo->setProjectId($this->getProjectId());
		$lvo->setCompanyIdFk($this->getCompanyIdFk());
		$lvo->setTemplateIdFk($this->getTemplateIdFk());
        // load the project ID of the parent project
		$projectIdFk = $this->getProjectIdFk();
		if (!$projectIdFk->equals(new TechDivision_Lang_Integer(0))) {
		    $lvo->setProjectIdFk($projectIdFk);
		}
		$lvo->setName($this->getName());
		$lvo->setUserIdFk($this->getUserIdFk());
		// check if a closing date has been set
		if (!$this->getClosingDate()->equals(new TechDivision_Lang_String())) {
			// create a new date instance
			$closingDate = new Zend_Date($this->getClosingDate());
			// convert the closing date and set the timestamp in the LVO
			$lvo->setClosingDate(
				new TechDivision_Lang_Integer((integer) $closingDate->getTimestamp())
			);
		}
        // return the initialized LVO
		return $lvo;
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
        // check if a username was entered
        if ($this->_name->length() == 0) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::NAME,
                    $this->translate('project-name.none')
                )
            );
        }
		// check if a valid from date was entered
		if (!$this->_closingDate->equals(new TechDivision_Lang_String()) && 
			!Zend_Locale_Format::checkDateFormat($this->_closingDate)) {
			$errors->addActionError(
				new TechDivision_Controller_Action_Error(
	                TDProject_Project_Controller_Util_ErrorKeys::CLOSING_DATE,
                    $this->translate('project-closing-date.invalid')
                )
			);
		}
        // return the ActionErrors
        return $errors;
    }
}