<?php

/**
 * TDProject_Project_Block_Logging_Widget
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
class TDProject_Project_Block_Logging_Widget
    extends TDProject_Core_Block_Abstract {

    /**
     * The DTO with the dashboard logging data.
     *
     * @var TDProject_Project_Common_ValueObjects_LoggingViewData
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
        $this->_setBlockName('task');
        // set the template name
        $this->_setTemplate(
        	'www/design/project/templates/logging/widget.phtml'
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
        $this->_dto = $this->getRequest()->getAttribute(
            TDProject_Core_Controller_Util_WebRequestKeys::VIEW_DATA
        );
        // call the parent constructor
        parent::prepareLayout();
        // return the instance itself
        return $this;
    }

    /**
     * Returns the logging entries.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     */
    public function getLoggings()
    {
        return $this->_dto->getLoggings();
    }

    /**
     * Returns the hours worked this day.
     *
     * @return double The hours worked this day
     */
    public function getHoursThisDay()
    {
        return sprintf(
        	'%01.2f',
        	round($this->_dto->getSecondsThisDay()->intValue() / 3600, 2)
        );
    }

    /**
     * Returns the hours worked this week.
	 *
	 * @return double The hours worked this week
     */
    public function getHoursThisWeek()
    {
        return sprintf(
        	'%01.2f',
        	round($this->_dto->getSecondsThisWeek()->intValue() / 3600, 2)
        );
    }

    /**
     * Returns the hours worked this month.
	 *
	 * @return double The hours worked this month
     */
    public function getHoursThisMonth()
    {
        return sprintf(
        	'%01.2f',
        	round($this->_dto->getSecondsThisMonth()->intValue() / 3600, 2)
        );
    }

    /**
     * Returns the billable hours worked this month.
	 *
	 * @return double The billable hours worked this month
     */
    public function getBillableHoursThisMonth()
    {
        return sprintf(
        	'%01.2f',
        	round(
        	    $this->_dto->getBillableSecondsThisMonth()->intValue() / 3600, 
        	    2
        	)
        );
    }

    /**
     * Returns the user's performance this month.
	 *
	 * @return TechDivision_Lang_Integer The user's performance this month
     */
    public function getPerformance()
    {
        return $this->_dto->getPerformance();
    }

    /**
     * Returns the URL to delete the logging entry.
	 *
     * @param $dto TDProject_Project_Common_ValueObjects_LoggingOverviewData
     * 		The DTO to return the delete URL for
     */
    public function getLoggingDeleteUrl(
    	TDProject_Project_Common_ValueObjects_LoggingOverviewData $dto) {

        // initialize the params to add to the URL
        $params = array(
            TechDivision_Controller_Action_Controller::ACTION_PATH =>
            	'/logging/widget',
            TDProject_Application::PACKAGE_MODULE =>
            	'Project',
            TDProject_Application::REPLACE_PACKAGE =>
            	0,
            TDProject_Project_Controller_Util_WebRequestKeys::METHOD =>
            	'delete',
            TDProject_Project_Controller_Util_WebRequestKeys::TASK_USER_ID =>
                $dto->getTaskUserId(),
        );
        // create and return the URL
        return $this->getUrl($params);
    }
}