<?php

/**
 * TDProject_Project_Model_Assembler_Logging
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Model_Assembler_Logging
    extends TDProject_Project_Model_Assembler_Abstract {

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Assembler_Logging($container);
    }

    /**
     * Returns a DTO initialized with the logging data of the
     * user with the passed ID.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		ID of the user to initialize the DTO with
     * @param TechDivision_Lang_Integer $taskUserId
     * 		ID of the logging data to load for editing
     * @return TDProject_Project_Common_ValueObjects_LoggingViewData
     * 		The DTO with the requested logging data
     */
    public function getLoggingViewData(
    	TechDivision_Lang_Integer $userId,
    	TechDivision_Lang_Integer $taskUserId = null) {
    	// check if a logging ID was passed
    	if (empty($taskUserId)) {
	        // initialize the DTO
	        $dto = new TDProject_Project_Common_ValueObjects_LoggingViewData(
	        	TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
	        		->epbCreate()
	        );
    	} else {
	    	// initialize the DTO
	        $dto = new TDProject_Project_Common_ValueObjects_LoggingViewData(
	        	TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
	        		->findByPrimaryKey($taskUserId)
	        );
	        // set the project ID
	        $dto->setProjectIdFk(
	        	TDProject_Project_Model_Assembler_Project::create($this->getContainer())
	        		->getProjectLightValueByTaskId($dto->getTaskIdFk())
	        		->getProjectId()
	        );
    	}
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
	    // if yes, load the project
		$taskUsers = TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
		    ->findAllForDashboard($userId);
		// set the available projects
		$dto->setProjects(
			TDProject_Project_Model_Assembler_Project::create($this->getContainer())
				->getProjectOverviewDataByUserId($userId)
		);
        // assemble the last 10 logging entries
        foreach ($taskUsers as $taskUser) {
            $list->add($this->_getLoggingOverviewData($taskUser));
        }
        // set the hours for the day, week and month
        $dto->setSecondsThisDay($this->getSecondsThisDay($userId));
        $dto->setSecondsThisWeek($this->getSecondsThisWeek($userId));
        $dto->setSecondsThisMonth($this->getSecondsThisMonth($userId));
        $dto->setPerformance($this->getPerformanceThisMonth($userId));
        $dto->setBillableSecondsThisMonth(
            $this->getBillableSecondsThisMonth($userId)
        );
        // add the list with the logging entries
        $dto->setLoggings($list);
        // return the initialized DTO
        return $dto;
    }

    /**
     * Returns an ArrayList with DTO's initialized with the logging data
     * of the user with the passed ID.
     *
     * @param TDProject_Core_Common_ValueObjects_QueryParameterData $dto
     * 		Parameters to load the logging data for incl. the user ID
     * @return TechDivision_Collections_ArrayList
     * 		The ArrayList with the intialized DTO's with the logging data
     */
    public function getLoggingOverviewData(
        TDProject_Core_Common_ValueObjects_QueryParameterData $dto) {
        // initialize a new ArrayList
        $list = new TDProject_Project_Common_ValueObjects_Collections_Logging();
        //  initialize the search term
        $search = new TechDivision_Lang_String('%');
        // concatenate the search term
        if (!$dto->getSearch()->equals($search)) {
        	$search = $search->concat($dto->getSearch());
        	$search = $search->concat(new TechDivision_Lang_String('%'));
        }
	    // load the logging entries for the user with the passed ID
		$taskUsers = TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
		    ->findAllByUserIdFk(
		    	$dto->getUserId(),
		    	$search,
		    	$dto->getSortColumn(),
		    	$dto->getSortDir(),
		    	$dto->getStart(),
		    	$dto->getLength()
		    );
        // seet the total records and the records to display
        $list->setTotalRecords($taskUsers->getTotalRecords());
        $list->setTotalDisplayRecords($list->getTotalRecords());
        // assemble the last 10 logging entries
        foreach ($taskUsers as $taskUser) {
            $list->add($this->_getLoggingOverviewData($taskUser));
        }
        // return the ArrayList with the initialized DTO's
        return $list;
    }

    /**
     * Returns the timestamp representation for the passed
     * date as Integer.
     *
     * @param Zend_Date $date
     * 		The date to return the timestamp representation
     * @return TechDivision_Lang_Integer
     * 		The Integer representation of the timestamp
     */
    protected function _getTimestamp(Zend_Date $date)
    {
        return TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String(
                $date->getTimestamp()
            )
        );
    }

    /**
     * Cumulates the seconds of the passed loggings.
     *
     * @param TechDivision_Collections_Interfaces_Collection $collection
     * 		The loggings to cumulate the seconds for
     * @return TechDivision_Lang_Integer
     * 		The cumulated seconds
     */
    protected function _assemble(
        TechDivision_Collections_Interfaces_Collection $collection) {
        // initialize the seconds
		$seconds = 0;
		// create a new Action instance
		$action = TDProject_Project_Model_Actions_Logging::create($this->getContainer());
		// iterate over the loggings
		foreach ($collection as $taskUser) {
		    $seconds += $action->calculate($taskUser)->intValue();
		}
        // return the seconds
		return new TechDivision_Lang_Integer($seconds);
    }

    /**
     * Calculates and returns the cumulated seconds for all tasks that have
     * loggings for the actual day and for the user with the passed ID.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to return the seconds for
     * @return TechDivision_Lang_Integer
     * 		The cumulated seconds
     */
    public function getSecondsThisDay(TechDivision_Lang_Integer $userId)
    {
        // set the start date
    	$from = $this->_getTimestamp(
    		Zend_Date::now()
    		    ->setTime('00:00:00', 'H:m:s')
        );
        // set the until date to now
    	$until = $this->_getTimestamp(Zend_Date::now());
    	// calculate the date of the weeks first day
		return $this->_assemble(
		    TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
		        ->findAllForCalculation($userId, $from, $until)
		);
    }

    /**
     * Calculates and returns the cumulated seconds for all tasks that have
     * loggings for the actual week and for the user with the passed ID.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to return the seconds for
     * @return TechDivision_Lang_Integer
     * 		The cumulated seconds
     */
    public function getSecondsThisWeek(TechDivision_Lang_Integer $userId)
    {
    	// calculate the date of the weeks first day
    	$from = $this->_getTimestamp(
    		Zend_Date::now()
    		    ->setTime('00:00:00', 'H:m:s')
    		    ->setWeekday(1)
    	);
    	// calculate the date of the weeks first day
    	$until = $this->_getTimestamp(
    		Zend_Date::now()
    		    ->setTime('00:00:00', 'H:m:s')
    		    ->setWeekday(7)
    		    ->addDay(1)
    	);
	    // load the tasks have loggings for the user and the acutal week
		return $this->_assemble(
		    TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
		        ->findAllForCalculation($userId, $from, $until)
		);
    }

    /**
     * Calculates and returns the cumulated seconds for all tasks that have
     * loggings for the actual month and for the user with the passed ID.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to return the seconds for
     * @return TechDivision_Lang_Integer
     * 		The cumulated seconds
     */
    public function getSecondsThisMonth(TechDivision_Lang_Integer $userId)
    {
    	// calculate the date of the month's first day
    	$from = $this->_getTimestamp(
    		Zend_Date::now()
	    		->setTime('00:00:00', 'H:m:s')
	    		->setDay(1)
	    );
        // set the until date
	    $until = $this->_getTimestamp(Zend_Date::now());
	    // load the tasks have loggings for the user and the acutal month
		return $this->_assemble(
		    TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
		        ->findAllForCalculation($userId, $from, $until)
		);
    }

    /**
     * Calculates and returns the cumulated billable seconds for all tasks
     * that have loggings for the actual month and for the user with the
     * passed ID.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to return the seconds for
     * @return TechDivision_Lang_Integer
     * 		The cumulated billable seconds
     */
    public function getBillableSecondsThisMonth(
        TechDivision_Lang_Integer $userId) {
    	// calculate the date of the month's first day
    	$from = $this->_getTimestamp(
    		Zend_Date::now()
	    		->setTime('00:00:00', 'H:m:s')
	    		->setDay(1)
	    );
    	// calculate the date for today
    	$until = $this->_getTimestamp(Zend_Date::now());
	    // load the billable tasks have loggings
	    // for the user and the actual month
		return $this->getBillableSecondsByMonth($userId, $from, $until);
    }

    /**
     * Calculates and returns the cumulated billable seconds for all tasks
     * that have loggings for the actual month and for the user with the
     * passed ID.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to return the seconds for
     * @param TechDivision_Lang_Integer $from
     * 		The month start date as UNIX timestamp
     * @param TechDivision_Lang_Integer $until
     * 		The month end date as UNIX timestamp
     * @return TechDivision_Lang_Integer
     * 		The cumulated billable seconds
     */
    public function getBillableSecondsByMonth(
        TechDivision_Lang_Integer $userId,
        TechDivision_Lang_Integer $from,
        TechDivision_Lang_Integer $until) {
	    // load the billable tasks have loggings for the user and the acutal month
		return $this->_assemble(
		    TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
		        ->findAllForBillableCalculation($userId, $from, $until)
		);
    }

    /**
     * Returns the performance of the user with the passed ID
     * in relation to the contracted hours a month.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to calculate the performance for
     * @return TechDivision_Lang_Integer
     * 		The performance in percent
     */
    public function getPerformanceThisMonth(
        TechDivision_Lang_Integer $userId) {
    	// calculate the date of the month's first day
    	$from = $this->_getTimestamp(
    		Zend_Date::now()
	    		->setTime('00:00:00', 'H:m:s')
	    		->setDay(1)
	    );
    	// calculate the date for today
    	$until = $this->_getTimestamp(Zend_Date::now());
        // load the billable seconds
    	return $this->getPerformanceByMonth($userId, $from, $until);
    }

    /**
     * Returns the performance of the user with the passed ID
     * in relation to the contracted hours a month.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to calculate the performance for
     * @param Zend_Date $date
     * 		The month as Zend_Date in format YYYY-mm to get the performance for
     * @return TechDivision_Lang_Integer
     * 		The performance in percent
     */
    public function getPerformanceByMonth(
        TechDivision_Lang_Integer $userId,
        TechDivision_Lang_Integer $from,
        TechDivision_Lang_Integer $until) {
    	// load the user
		$user = TDProject_Core_Model_Utils_UserUtil::getHome($this->getContainer())
			->findByPrimaryKey($userId);
	    // load the contracted hours
	    $contractedHours = $user->getContractedHours()->intValue();
        // load the billable seconds
    	$billableSeconds =
    	    $this->getBillableSecondsByMonth(
    	        $userId,
    	        $from,
    	        $until
    	    )->intValue();
    	// calculate the contracted seconds
        if (($contractedSeconds = $contractedHours * 3600) < 1) {
            // preceed with a default value of 160 working hours a day
            $contractedSeconds = 576000;
    	}
	    // calculate the performance itself
	    $performance = round(($billableSeconds * 100) / $contractedSeconds);
	    // return the performance
	    return new TechDivision_Lang_Integer((int) $performance);
    }

    /**
     * Assembles and initializes the last 10 logging entries
     * for the user with the passed ID.
     *
     * @param TDProject_Project_Model_Entities_TaskUser $taskUser
     * 		The task-user relation to return the logging entries for
     * @return TDProject_Project_Common_ValueObjects_LoggingOverviewData
     * 		The assembled DTO
     */
    protected function _getLoggingOverviewData(
        TDProject_Project_Model_Entities_TaskUser $taskUser) {
        // initialize the DTO
        $dto = new TDProject_Project_Common_ValueObjects_LoggingOverviewData(
            $taskUser
        );
        // set the LVO with the project data
        $dto->setProject(
            TDProject_Project_Model_Assembler_Project::create($this->getContainer())
            	->getProjectLightValueByTaskId($taskUser->getTaskIdFk())
        );
        // set the LVO with the user data
        $dto->setUser(
            TDProject_Core_Model_Utils_UserUtil::getHome($this->getContainer())
		    	->findByPrimaryKey($taskUser->getUserIdFk())
		    	->getLightValue()
        );
        // return the DTO
        return $dto;
    }

    /**
     * Returns an ArrayList with DTO's initialized with the logging data
     * of the project with the passed ID.
     *
     * @param TDProject_Core_Common_ValueObjects_QueryParameterData $dto
     * 		Parameters to load the logging data for incl. the project ID
     * @return TechDivision_Collections_ArrayList
     * 		The ArrayList with the intialized DTO's with the logging data
     */
    public function getLoggingOverviewDataByProjectId(
        TDProject_Core_Common_ValueObjects_QueryParameterData $dto) {
        // initialize a new ArrayList
        $list = new TDProject_Project_Common_ValueObjects_Collections_Logging();
        //  initialize the search term
        $search = new TechDivision_Lang_String('%');
        // concatenate the search term
        if (!$dto->getSearch()->equals($search)) {
        	$search = $search->concat($dto->getSearch());
        	$search = $search->concat(new TechDivision_Lang_String('%'));
        }
	    // load the logging entries for the project with the passed ID
		$taskUsers = TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
		    ->findAllByProjectIdFk(
		    	$dto->getCustomParam('projectId'),
		    	$search,
		    	$dto->getSortColumn(),
		    	$dto->getSortDir(),
		    	$dto->getStart(),
		    	$dto->getLength()
		    );
        // seet the total records and the records to display
        $list->setTotalRecords($taskUsers->getTotalRecords());
        $list->setTotalDisplayRecords($list->getTotalRecords());
        // assemble the last 10 logging entries
        foreach ($taskUsers as $taskUser) {
            $list->add($this->_getLoggingOverviewData($taskUser));
        }
        // return the ArrayList with the initialized DTO's
        return $list;
    }

    /**
     * Returns an ArrayList with DTO's initialized with the logging data
     * of the task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		ID of the task to initialize the DTO with
     * @return TechDivision_Collections_ArrayList
     * 		The ArrayList with the intialized DTO's with the logging data
     */
    public function getCumulatedLoggings(TechDivision_Lang_Integer $taskId)
    {
        // initialize a new ArrayList
        $list = new TDProject_Project_Common_ValueObjects_Collections_CumulatedLoggings();
	    // load the loggings for the task
		$taskUsers = TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
		    ->findAllByTaskIdFk($taskId);
        // assemble the logging entries
        foreach ($taskUsers as $taskUser) {
            $list->add($this->_getLoggingOverviewData($taskUser));
        }
        // return the ArrayList with the initialized DTO's
        return $list;
    }
}