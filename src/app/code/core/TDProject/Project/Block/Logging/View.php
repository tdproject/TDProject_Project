<?php

/**
 * TDProject_Project_Block_Logging_View
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'Zend/Locale/Format.php';
require_once 'TDProject/Project/Block/Abstract/TaskUser.php';
require_once 'TDProject/Project/Block/Logging/View/Loggings.php';
require_once 'TDProject/Project/Block/Logging/View/Tasks.php';
require_once 'TDProject/Project/Controller/Util/WebRequestKeys.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Checkbox.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Select.php';
require_once 'TDProject/Project/Common/ValueObjects/LoggingViewData.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Logging_View
	extends TDProject_Project_Block_Abstract_TaskUser {

    /**
     * The project ID to log for.
     * @var TechDivision_Lang_Integer
     */
    protected $_projectIdFk = null;

    /**
     * The last 10 loggings of the user.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_loggings = null;

    /**
     * The available projects.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_projects = null;

    /**
     * Getter method for the project ID to log for.
     *
     * @return TechDivision_Lang_Integer The project ID to log for
     */
    public function getProjectIdFk() {
        return $this->_projectIdFk;
    }

    /**
     * Setter method for the project ID to log for.
     *
     * @param integer $string The project ID to log for
     * @return void
     */
    public function setProjectIdFk($string)
    {
        $this->_projectIdFk = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the last 10 loggings of the user.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The last 10 loggings of the user
     */
    public function getLoggings()
    {
        return $this->_loggings;
    }

    /**
     * Setter method for the last 10 loggings of the user.
     *
     * @param TechDivision_Collections_Interfaces_Collection $loggings
     * 		The last 10 loggings of the user
     * @return void
     */
    public function setLoggings(
        TechDivision_Collections_Interfaces_Collection $loggings) {
        $this->_loggings = $loggings;
    }

    /**
     * Getter method for the available projects.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The available projects
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
     * (non-PHPdoc)
     * @see TDProject_Project_Block_Abstract_Task::setFrom()
     */
    public function setFrom($string)
    {
    	$this->_from = new TechDivision_Lang_String($string);
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_Project_Block_Abstract_Task::setUntil()
     */
    public function setUntil($string)
    {
    	$this->_until = new TechDivision_Lang_String($string);
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_Project_Block_Abstract_TaskUser::getDeleteUrl()
     */
    public function getDeleteUrl()
    {
    	return '?path=' .
    	    $this->getPath() .
    	    '&method=delete&taskUserId=' .
    	    $this->getTaskUserId();
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Interfaces/Block#prepareLayout()
     */
    public function prepareLayout()
    {   	
    	// add the JavaScript source for the date calculation
    	$this->addBlock(
    		$this->newInstance(
    	    	'TDProject_Core_Block_Widget_Script',
    			array(
    				$this,
    	    		'loggingView',
    				$this->getDesignUrl('project/js/logging/view.js')
    			)
    		)
    	);
    	// initialize the tabs
    	$tabs = $this->addTabs(
    		'tabs',
    		$this->translate('logging.view.tabs.label.logging')
    	);
        // add the tab for the company data
        $tab = $tabs->addTab(
        	'logging',
        	$this->translate('logging.view.tab.label.logging')
        );
       	// add fieldset actual logging entries
        $tab->addFieldset(
        	'logged',
        	$this->translate('logging.view.fieldset.label.logged')
        )
    	->addBlock(
    	    new TDProject_Project_Block_Logging_View_Loggings(
    	        $this->getContext()
    	    )
    	);
        // add fieldset for new logging entries
        $tab->addFieldset(
        	'logging',
        	$this->translate('logging.view.fieldset.label.logging')
        )
    	->addElement(
    	    $this->getElement(
    	    	'select',
    	    	'projectIdFk',
    	    	$this->translate('logging.view.label.project')
    	    )->setOptions($this->getProjects())->setDummyOption()->setMandatory()
    	)
    	->addBlock(
    	    new TDProject_Project_Block_Logging_View_Tasks(
    	        $this->getContext()
    	    )
    	)
    	->addElement(
    	    $this->getElement(
    	    	'textfield',
    	    	'from',
    	    	$this->translate('logging.view.label.from')
    	    )->setMandatory()
    	)
    	->addElement(
    	    $this->getElement(
    	    	'textfield',
    	    	'until',
    	    	$this->translate('logging.view.label.until')
    	    )->setMandatory()
    	)
    	->addElement(
    	    $this->getElement(
    	    	'textfield',
    	    	'toBook',
    	    	$this->translate('logging.view.label.to-book')
    	    )->setMandatory()
    	)
    	->addElement(
    	    $this->getElement(
    	    	'textfield',
    	    	'toAccount',
    	    	$this->translate('logging.view.label.to-account')
    	    )->setMandatory()
    	)
    	->addElement(
    	    $this->getElement(
    	    	'textfield',
    	    	'issue',
    	    	'Issue'
    	    )
    	)
    	->addElement(
    	    $this->getElement(
    	    	'textarea',
    	    	'description',
    	    	$this->translate('logging.view.label.description')
    	    )->setMandatory()
    	);
	    // return the instance itself
	    return parent::prepareLayout();
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
    	$this->_projectIdFk = new TechDivision_Lang_Integer(0);
        $this->_projects = new TechDivision_Collections_ArrayList();
        $this->_loggings = new TechDivision_Collections_ArrayList();
        $this->_from = Zend_Date::now();
        $this->_until = Zend_Date::now()->addSecond(1800);
        $this->_toBook = new TechDivision_Lang_Integer(30);
        $this->_toAccount = new TechDivision_Lang_Integer(30);
    }

    /**
     * Recalculate the passed date for the seconds
     * passed as parameter.
	 *
     * @param TechDivision_Lang_String $date
     * 		The date to recalculate
     * @param TechDivision_Lang_Integer $seconds
     * 		The seconds to recalculate the date for
     * @return The recalculated date
     */
    protected function _recalculate(
    	TechDivision_Lang_String $date,
    	TechDivision_Lang_Integer $seconds) {
    	$dt = new Zend_Date($date);
    	$dt->addSecond($seconds->intValue());
    	return $dt->__toString();
    }

    /**
     * Resets all member variables to their
     * default values.
     *
     * @return void
     */
    public function preset()
    {
    	$this->_taskUserId = new TechDivision_Lang_Integer(0);
        $this->_from = new TechDivision_Lang_String($this->getUntil());
        // raise the previous until date for 30 minutes
        $this->_until = new TechDivision_Lang_String(
        	$this->_recalculate(
        		$this->getUntil(),
        		new TechDivision_Lang_Integer(1800)
        	)
        );
        $this->_toBook = new TechDivision_Lang_Integer(30);
        $this->_toAccount = new TechDivision_Lang_Integer(30);
        $this->_description = new TechDivision_Lang_String();
        $this->_issue = new TechDivision_Lang_String();
        $this->_loggings = new TechDivision_Collections_ArrayList();
        $this->_projects = new TechDivision_Collections_ArrayList();
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_Project_Block_Abstract_TaskUser::populate()
     */
    public function populate(
        TDProject_Project_Common_ValueObjects_LoggingViewData $dto)
    {
    	parent::populate($dto);
    	// initialize the project ID
        $this->_projectIdFk = $dto->getProjectIdFk();
        // overwrite the dates
        $this->_from = new Zend_Date(
            $dto->getFrom()->intValue(), Zend_Date::TIMESTAMP
        );
        $this->_until = new Zend_Date(
            $dto->getUntil()->intValue(), Zend_Date::TIMESTAMP
        );
        // convert the seconds to book/account into minutes 
        $this->_toBook = $this->fromSecondsToMinutes($this->_toBook);
        $this->_toAccount = $this->fromSecondsToMinutes($this->_toAccount);        
        // initialize the ActionForm
        $this->initialize($dto);
    }

    /**
     * Populates the ActionForm with the values
     * of the passed DTO.
     *
     * @param TDProject_Project_Common_ValueObjects_LoggingViewData $dto
     * 		The DTO with the data to initialize the ActionForm with
     * @return void
     */
    public function initialize(
        TDProject_Project_Common_ValueObjects_LoggingViewData $dto) {
    	// initialize the Collections
        $this->_projects = $dto->getProjects();
        $this->_loggings = $dto->getLoggings();
    }

    /**
     * Initializes and returns a new LVO
     * with the data from the ActionForm.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskUserLightValue
     * 		The initialized LVO
     */
    public function repopulate(
        TDProject_Core_Common_ValueObjects_System_UserValue $systemUser)
    {
		// initialize a new LVO
		$lvo = new TDProject_Project_Common_ValueObjects_TaskUserLightValue();
		// filling it with the project data from the ActionForm
		$lvo->setTaskUserId($this->getTaskUserId());
		$lvo->setUserIdFk($systemUser->getUserId());
		$lvo->setUsername($systemUser->getUsername());
		$lvo->setTaskIdFk($this->getTaskIdFk());
		$lvo->setIssue($this->getIssue());
		$lvo->setDescription($this->getDescription());
		// create a new date instance
		$from = new Zend_Date($this->getFrom());
		// convert the date and set the timestamp in the LVO
		$lvo->setFrom(
			new TechDivision_Lang_Integer((integer) $from->getTimestamp())
		);
		// create a new date instance
		$until = new Zend_Date($this->getUntil());
		// convert the date and set the timestamp in the LVO
		$lvo->setUntil(
			new TechDivision_Lang_Integer((integer) $until->getTimestamp())
		);
        // convert the minutes to book/account into seconds 
		$lvo->setToBook($this->fromMinutesToSeconds(clone $this->getToBook()));
		$lvo->setToAccount($this->fromMinutesToSeconds(clone $this->getToAccount()));	
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
        // check if a project was selected
        if ($this->_projectIdFk->equals(new TechDivision_Lang_Integer(0))) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::PROJECT_ID_FK,
                    $this->translate('logging-project.none')
                )
            );
        }
        // check if a task description was entered
        if ($this->_description->length() == 0) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                TDProject_Project_Controller_Util_ErrorKeys::DESCRIPTION,
                    $this->translate('logging-description.none')
                )
            );
        }
		// initialize booleans for checking dates
		$isValidFrom = true;
		$isValidUntil = true;
		// check if a valid from date was entered
		if (!Zend_Locale_Format::checkDateFormat($this->_from)) {
			$isValidFrom = false;
			$errors->addActionError(
				new TechDivision_Controller_Action_Error(
	                TDProject_Project_Controller_Util_ErrorKeys::FROM,
                    $this->translate('logging-from.invalid')
                )
			);
		}
		// check if a valid from date was entered
		if (!Zend_Locale_Format::checkDateFormat($this->_until)) {
			$isValidUntil = false;
			$errors->addActionError(
				new TechDivision_Controller_Action_Error(
	                TDProject_Project_Controller_Util_ErrorKeys::UNTIL,
                    $this->translate('logging-until.invalid')
                )
			);
		}
		// check if valid dates are passed
		if ($isValidFrom && $isValidUntil) {
			// if yes, check if the given until date is later than the from date
			$from = new Zend_Date($this->getFrom());
			if ($from->isLater(new Zend_Date($this->getUntil()))) {
				$errors->addActionError(
					new TechDivision_Controller_Action_Error(
		                TDProject_Project_Controller_Util_ErrorKeys::UNTIL,
	                    $this->translate('logging-until.not-later')
	                )
				);
			}
		}
		// return the ActionErrors
		return $errors;
	}
}