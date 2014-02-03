<?php

/**
 * TDProject_Project_Block_Logging_Form
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'Zend/Date.php';
require_once 'TechDivision/Lang/String.php';
require_once 'TechDivision/Lang/Integer.php';
require_once 'TechDivision/Lang/Boolean.php';
require_once 'TechDivision/Collections/Interfaces/Collection.php';
require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TDProject/Core/Common/ValueObjects/System/UserValue.php';
require_once 'TDProject/Core/Block/AbstractForm.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskUserLightValue.php';
require_once 'TDProject/Project/Common/ValueObjects/LoggingViewData.php';

/**
 * This class implements the form functionality
 * for handling a task.
 *
 * @category    TProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Bastian Stangl <b.stangl@techdivision.com>
 */

class TDProject_Project_Block_Logging_Form
    extends TDProject_Core_Block_AbstractForm {

    /**
     * The ID of the task-user relation to log for.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskUserId = null;

    /**
     * The project ID to log for.
     * @var TechDivision_Lang_Integer
     */
    protected $_projectIdFk = null;

    /**
     * The task ID to log for.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskIdFk = null;

    /**
     * The datetime to start working on the task.
     * @var TechDivision_Lang_String
     */
    protected $_from = null;

    /**
     * The datetime finish working on the task.
     * @var TechDivision_Lang_String
     */
    protected $_until = null;

    /**
     * Description of the work done.
     * @var TechDivision_Lang_String
     */
    protected $_description = null;

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
     * Getter method for the ID of the task-user relation to log for.
     *
     * @return TechDivision_Lang_Integer The ID of the task-user relation to log for
     */
    public function getTaskUserId()
    {
        return $this->_taskUserId;
    }

    /**
     * Setter method for the ID of the task-user relation to log for.
     *
     * @param integer $string The ID of the task-user relation to log for
     * @return void
     */
    public function setTaskUserId($string)
    {
        $this->_taskUserId = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the project ID to log for.
     *
     * @return TechDivision_Lang_Integer The project ID to log for
     */
    public function getProjectIdFk()
    {
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
     * Getter method for the task ID to log for.
     *
     * @return TechDivision_Lang_Integer The task ID to log for
     */
    public function getTaskIdFk()
    {
        return $this->_taskIdFk;
    }

    /**
     * Setter method for the task ID to log for.
     *
     * @param integer $string The task ID to log for
     * @return void
     */
    public function setTaskIdFk($string)
    {
        $this->_taskIdFk = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the datetime to start working on the task.
     *
     * @return TechDivision_Lang_String The datetime to start working on the task
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * Setter method for the datetime to start working on the task.
     *
     * @param string $string The datetime to start working on the task
     * @return void
     */
    public function setFrom($string)
    {
        $this->_from = new TechDivision_Lang_String($string);
    }

    /**
     * Getter method for the datetime finish working on the task.
     *
     * @return TechDivision_Lang_String The datetime finish working on the task
     */
    public function getUntil()
    {
        return $this->_until;
    }

    /**
     * Setter method for the datetime finish working on the task.
     *
     * @param string $string The datetime finish working on the task
     * @return void
     */
    public function setUntil($string)
    {
        $this->_until = new TechDivision_Lang_String($string);
    }

    /**
     * Getter method for the description of the work done.
     *
     * @return TechDivision_Lang_String The description of the work done
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Setter method for the escription of the work done.
     *
     * @param string $string The the description of the work done
     * @return void
     */
    public function setDescription($string)
    {
        $this->_description = new TechDivision_Lang_String($string);
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
     * Resets all member variables to their
     * default values.
     *
     * @return void
     */
    function preset()
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
        $this->_description = new TechDivision_Lang_String();
        $this->_loggings = new TechDivision_Collections_ArrayList();
        $this->_projects = new TechDivision_Collections_ArrayList();
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
    	return $this->_toDate(
    		new TechDivision_Lang_Integer(
    			$this->_fromDate($date)->intValue() + $seconds->intValue()
    		)
    	);
    }

    /**
     * Resets all member variables to their
     * default values.
     *
     * @return void
     */
    function reset()
    {
    	$this->_taskUserId = new TechDivision_Lang_Integer(0);
    	$this->_projectIdFk = new TechDivision_Lang_Integer(0);
    	$this->_taskIdFk = new TechDivision_Lang_Integer(0);
        $this->_from = new TechDivision_Lang_String(date('Y-m-d H:i:s'));
        $this->_until = new TechDivision_Lang_String(
            date('Y-m-d H:i:s', time() + 1800)
        );
        $this->_description = new TechDivision_Lang_String();
        $this->_loggings = new TechDivision_Collections_ArrayList();
        $this->_projects = new TechDivision_Collections_ArrayList();
    }

    /**
     * Populates the ActionForm with the values
     * of the passed DTO.
     *
     * @param TDProject_Project_Common_ValueObjects_LoggingViewData $dto
     * 		The DTO with the data to initialize the ActionForm with
     * @return void
     */
    function populate(
        TDProject_Project_Common_ValueObjects_LoggingViewData $dto) {
        $this->_taskUserId = $dto->getTaskUserId();
        $this->_projectIdFk = $dto->getProjectIdFk();
        $this->_taskIdFk = $dto->getTaskIdFk();
        $this->_from = $this->_toDate($dto->getFrom());
        $this->_until = $this->_toDate($dto->getUntil());
        $this->_description = $dto->getDescription();
        $this->_loggings = $dto->getLoggings();
        $this->_projects = $dto->getProjects();
    }

    /**
     * Initializes and returns a new LVO
     * with the data from the ActionForm.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskUserLightValue
     * 		The initialized LVO
     */
    public function repopulate(
        TDProject_Core_Common_ValueObjects_System_UserValue $systemUser) {
		// initialize a new LVO
		$lvo = new TDProject_Project_Common_ValueObjects_TaskUserLightValue();
		// filling it with the project data from the ActionForm
		$lvo->setTaskUserId($this->getTaskUserId());
		$lvo->setUserIdFk($systemUser->getUserId());
		$lvo->setUsername($systemUser->getUsername());
		$lvo->setTaskIdFk($this->getTaskIdFk());
		$lvo->setFrom($this->_fromDate($this->getFrom()));
		$lvo->setUntil($this->_fromDate($this->getUntil()));
		$lvo->setDescription($this->getDescription());
		$lvo->setIssue(new TechDivision_Lang_String());
		// calculate the from/until date
		$from = new Zend_Date($this->getFrom()->stringValue());
		$until = new Zend_Date($this->getUntil()->stringValue());
		// calculate the difference in seconds
		$diff = $until->sub($from);
		// calculate the difference in minutes
		$toBook = new TechDivision_Lang_Integer($diff->toValue());
		$toBook->divide(new TechDivision_Lang_Integer(60));
		// set the minutes to book/account
		$lvo->setToBook($toBook);
		$lvo->setToAccount($toBook);
        // return the initialized LVO
		return $lvo;
    }

    /**
     * Converts the passed date to a timestamp
     *
     * @param TechDivision_Lang_String $string
     * 		The human readable date
     * @return TechDivision_Lang_Integer
     * 		The timestamp representation
     */
    protected function _fromDate(TechDivision_Lang_String $string)
    {
        $date = new Zend_Date($string->stringValue());
        return TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($date->getTimestamp())
        );
    }

	/**
	 * Converts the passed timestamp to a date.
	 *
	 * @param TechDivision_Lang_Integer $integer
	 * 		The timestamp to convert
	 * @param string The date format to return
	 * @return TechDivision_Lang_String
	 * 		The human readable date
	 */
	protected function _toDate(
	    TechDivision_Lang_Integer $integer, $format = 'Y-m-d H:i:s') {
		$date = date($format, $integer->intValue());
		return new TechDivision_Lang_String($date);
	}

    /**
     * This method checks if the values in the member variables
     * holds valiid data. If not, a ActionErrors container will
     * be initialized an for every incorrect value a ActionError
     * object with the apropriate error message will be added.
     *
     * @return ActionErrors
     * 		Returns a ActionErrors container with ActionError objects
     */
    function validate()
    {
        // initialize the ActionErrors
        $errors = new TechDivision_Controller_Action_Errors();
        // check if a valid start date was entered
        if (!$this->_validateDatetime($this->_from)) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::FROM,
                    $this->translate('logging-from.invalid')
                )
            );
        }
        // check if a valid end date was entered
        if (!$this->_validateDatetime($this->_until)) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::UNTIL,
                    $this->translate('logging-until.invalid')
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
        // return the ActionErrors
        return $errors;
    }
}