<?php

/**
 * TDProject_Project_Common_Email_Logging_AverageOverbooked
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Formatter/Date.php';
require_once 'TDProject/Core/Common/Email/Abstract.php';
require_once 'TDProject/Core/Common/ValueObjects/UserLightValue.php';
require_once 'TDProject/Core/Common/ValueObjects/SettingLightValue.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskUserLightValue.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskLightValue.php';
require_once 'TDProject/Project/Common/Enums/UserRole.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_Email_Logging_AverageOverbooked
	extends TDProject_Core_Common_Email_Abstract {

	/**
	 * The application name used in the mail.
	 * @var string
	 */
	const APPLICATION_NAME = 'TDProject, V2.0';

	/**
	 * Array with the receipients.
	 * @var array
	 */
	protected $_to = array('t.wagner@techdivision.com' => 'Tim Wagner');

	/**
     * The logging data necessary to render the template.
     * @var TDProject_Project_Common_ValueObjects_TaskUserLightValue
	 */
	protected $_lvo = null;

	/**
     * The system settings.
     * @var TDProject_Core_Common_ValueObjects_SettingLightValue
	 */
	protected $_setting = null;

	/**
	 * The project the task belongs to.
	 * @var TDProject_Project_Common_ValueObjects_ProjectLightValue
	 */
	protected $_project = null;

	/**
	 * The user that tries to book.
	 * @var TDProject_Core_Common_ValueObjects_UserLightValue
	 */
	protected $_user = null;

	/**
	 * The task the logging entry belongs to.
	 * @var TDProject_Project_Common_ValueObjects_TaskLightValue
	 */
	protected $_task = null;

	/**
	 * The total after the passed logging entry has been booked.
	 * @var integer
	 */
	protected $_total = 0;

	/**
	 * The average estimation time for the task.
	 * @var integer
	 */
	protected $_average = 0;

    /**
     * Initialize the block with the
     * apropriate template and name.
     *
     * @return void
     */
    public function __construct()
    {
        // set the template name
        $this->setTemplateHtml(
        	'www/design/project/templates/email/logging/average_overbooked.phtml'
        );
        $this->setTemplateText(
        	'www/design/project/templates/email/logging/average_overbooked.txt.phtml'
        );
    }

    /**
     * Sets the logging data necessary to render the template.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo
     * 		The logging data
     * @return TDProject_Project_Common_Email_Logging_MaximumOverbooked
     * 		The instance itself
     */
    public function setTaskUser(
    	TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo) {
    	$this->_lvo = $lvo;
    	return $this;
    }

    /**
     * Sets the logging data necessary to render the template.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskUserLightValue
     * 		The logging data
     */
    public function getTaskUser()
    {
    	return $this->_lvo;
    }

    /**
     * Sets the system settings, necessary for the mail sender.
     *
     * @param TDProject_Core_Common_ValueObjects_SettingLightValue $setting
     * 		The system settings
     * @return TDProject_Project_Common_Email_Logging_MaximumOverbooked
     * 		The instance itself
     */
    public function setSetting(
    	TDProject_Core_Common_ValueObjects_SettingLightValue $setting) {
    	$this->_setting = $setting;
    	return $this;
    }

    /**
     * The system settings, necessary for the mail sender
     *
     * @return TDProject_Core_Common_ValueObjects_SettingLightValue
     * 		The logging data
     */
    public function getSetting()
    {
    	return $this->_setting;
    }

    /**
     * Sets the project the task belongs to.
     *
     * @param TDProject_Project_Common_ValueObjects_ProjectLightValue $project
     * 		The project
     * @return TDProject_Project_Common_Email_Logging_MaximumOverbooked
     * 		The instance itself
     */
    public function setProject(
    	TDProject_Project_Common_ValueObjects_ProjectLightValue $project) {
    	$this->_project = $project;
    	return $this;
    }

    /**
     * The project the task belongs to.
     *
     * @return TDProject_Project_Common_ValueObjects_ProjectLightValue
     * 		The project data
     */
    public function getProject()
    {
    	return $this->_project;
    }

    /**
     * The user that tries to book.
     *
     * @param TDProject_Core_Common_ValueObjects_UserLightValue $user
     * 		The user that tries to book
     * @return TDProject_Project_Common_Email_Logging_MaximumOverbooked
     * 		The instance itself
     */
    public function setUser(TDProject_Core_Common_ValueObjects_UserLightValue $user)
    {
    	$this->_user = $user;
    	return $this;
    }

    /**
     * The user that tries to book.
     *
     * @return TDProject_Core_Common_ValueObjects_UserLightValue
     * 		The user that tries to book
     */
    public function getUser()
    {
    	return $this->_user;
    }

    /**
     * The task the logging entry belongs to.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskLightValue $task
     * 		The task the logging entry belongs to
     * @return TDProject_Project_Common_Email_Logging_MaximumOverbooked
     * 		The instance itself
     */
    public function setTask(TDProject_Project_Common_ValueObjects_TaskLightValue $task)
    {
    	$this->_task = $task;
    	return $this;
    }

    /**
     * The task the logging entry belongs to.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskLightValue
     * 		The task the logging entry belongs to
     */
    public function getTask()
    {
    	return $this->_task;
    }

    /**
     * Returns the seconds already booked on the task,
     * before the user tries to book his logging entry.
     *
     * @return integer
     * 		The seconds alread booked
     */
    public function getTotalBefore()
    {
    	// load the until and from date
    	$until = $this->getTaskUser()->getUntil()->intValue();
    	$from = $this->getTaskUser()->getFrom()->intValue();
    	// subtract the booking time from the total
    	return $this->getTotal() - ($until - $from);
    }

    /**
     * The total after the passed logging
     * entry has been booked.
     *
     * @param integer $total
     * 		The total
     * @return TDProject_Project_Common_Email_Logging_MaximumOverbooked
     * 		The instance itself
     */
    public function setTotal($total)
    {
    	$this->_total = $total;
    	return $this;
    }

    /**
     * The total after the passed logging
     * entry has been booked.
     *
     * @return $total
     * 		The total
     */
    public function getTotal()
    {
    	return $this->_total;
    }

    /**
     * The average estimation time for the task.
     *
     * @param integer $average
     * 		The average estimation time for the task
     * @return TDProject_Project_Common_Email_Logging_AverageOverbooked
     * 		The instance itself
     */
    public function setAverage($average)
    {
    	$this->_average = $average;
    	return $this;
    }

    /**
     * The average estimation time for the task.
     *
     * @return integer
     * 		The average estimation time for the task
     */
    public function getAverage()
    {
    	return $this->_average;
    }

    /**
     * Returns the start date/time of the logging entry.
     *
     * @return string
     * 		The start date/time
     */
    public function getTaskUserFrom()
    {
    	return TDProject_Core_Formatter_Date::get()
    		->format($this->getTaskUser()->getFrom())
    		->stringValue();
    }

    /**
     * Returns the until date/time of the logging entry.
     *
     * @return string
     * 		The until date/time
     */
    public function getTaskUserUntil()
    {
    	return TDProject_Core_Formatter_Date::get()
    		->format($this->getTaskUser()->getUntil())
    		->stringValue();
    }

    /**
     * Adds an array with the receipients
     * of the mail.
     *
     * @param array $to
     * 		The array with the receipients
     * @return TDProject_Project_Common_Email_Logging_AverageOverbooked
     * 		The instance itself
     */
    public function setTo(array $to)
    {
    	// if receipients was passed, set them
    	if (sizeof($to) > 0) {
    		$this->_to = $to;
    	}
    	return $this;
    }

	/**
	 * (non-PHPdoc)
	 * @see TDProject_Core_Interfaces_Email::getFrom()
	 */
    public function getFrom()
    {
    	return array(
    		$this->getSetting()->getEmailSupport()->stringValue() => self::APPLICATION_NAME
    	);
    }

	/**
	 * (non-PHPdoc)
	 * @see TDProject_Core_Interfaces_Email::getTo()
	 */
    public function getTo()
    {
    	return $this->_to;
    }

	/**
	 * (non-PHPdoc)
	 * @see TDProject_Core_Interfaces_Email::getSubject()
	 */
    public function getSubject()
    {
    	return '[' . self::APPLICATION_NAME . '] - Task Average überbucht';
    }

    /**
     * Returns the Float with seconds, as hour.
     *
     * @param integer $seconds
     * 		The minutes to render as hours
     * @return float The hours
     */
    public function asHours($seconds)
    {
    	return sprintf("%01.2f", $seconds / 3600);
    }
}