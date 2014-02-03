<?php

/**
 * TDProject_Project_Model_Actions_Logging
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
class TDProject_Project_Model_Actions_Logging
    extends TDProject_Core_Model_Actions_Abstract {

    /**
     * ID of database role.
     * @var integer
     */
    const ADMINISTRATOR_ROLE_ID = 1;
    
    /**
     * ID of the projectmanager role.
     * @var integer
     */
    const PROJECTMANAGER_ROLE_ID = 5;

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Actions_Logging($container);
    }

	/**
	 * This method returns the logger of the requested
	 * type for logging purposes.
	 *
     * @param string The log type to use
	 * @return TechDivision_Logger_Logger Holds the Logger object
	 * @throws Exception Is thrown if the requested logger type is not initialized or doesn't exist
	 * @deprecated 0.6.34 - 2011/12/19
	 */
    protected function _getLogger(
        $logType = TechDivision_Logger_System::LOG_TYPE_SYSTEM)
    {
    	return $this->getLogger();
    }

    /**
     * This method returns the logger of the requested
     * type for logging purposes.
     *
     * @param string The log type to use
     * @return TechDivision_Logger_Logger Holds the Logger object
     * @since 0.6.35 - 2011/12/19
     */
    public function getLogger(
    	$logType = TechDivision_Logger_System::LOG_TYPE_SYSTEM)
    {
    	return $this->getContainer()->getLogger();
    }
    
    /**
     * The divisor to round up to. By default the value is
     * 900 seconds what equals 15 minutes.
     * 
     * @return TechDivision_Lang_Integer The divisor itself
     */
    public function getDivisor()
    {
    	return new TechDivision_Lang_Integer(900);
    }
    
    /**
     * Round up the passed value by 900 secondes (15 minutes).
     * 
     * @param TechDivision_Lang_Integer $integer The value to round up
     * @return TechDivision_Lang_Integer The result rounded up
     */
    public function roundUp(
    	TechDivision_Lang_Integer $integer)
    {	
    	// calculate the remainder of the module operation
    	$remainder = $integer->modulo($divisor = $this->getDivisor());
    	// if the remainder is > zero, round up the time to account
    	if ($remainder->greaterThan(new TechDivision_Lang_Integer(0))) {
    		$integer->add($divisor->subtract($remainder));
    	}
    	// return the result
    	return $integer;
    }

    /**
     * Completes the passed logging entry with task and project and
     * saves it.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo
     * 		The LVO to complete and save
     * @return TechDivision_Lang_Integer
     * 		The ID of the saved logging entry
     * @throws TDProject_Project_Common_Exceptions_InvalidTaskException
     * 		Is thrown if the task does not exists
     */
    public function saveTaskUser(
    	TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo) {
		// check if the task exists
		try {
    		$task = $taskUser = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
   			    ->findByPrimaryKey($taskIdFk = $lvo->getTaskIdFk());
		} catch(TechDivision_Model_Interfaces_Exception $e) {
			throw new TDProject_Project_Common_Exceptions_InvalidTaskException(
		        'Task with ID ' . $taskIdFk . ' does not exists'
		    );
		}
		// set the task name
		$lvo->setTaskName($task->getName());
		// load the project
		$project = TDProject_Project_Model_Assembler_Project::create($this->getContainer())
		    ->getProjectLightValueByTaskId($lvo->getTaskIdFk());
		// set the project ID and name
		$lvo->setProjectName($project->getName());
		$lvo->setProjectIdFk($project->getProjectId());
		// check if task is overbooked
		/* TODO Removed to improve performance
		 * @author Tim Wagner
		 * @date 2012-02-17
		 * $this->isBookable($lvo);
		 */
		// lookup the ID of the logging entry
		$taskUserId = $lvo->getTaskUserId();
		// calculate the costs for the user and the number of seconds
		$costs = $this->calculateCosts($lvo->getUserIdFk(), clone $lvo->getToBook());
		// the divisior to round up the time to account (15 minutes)
		$divisor = new TechDivision_Lang_Integer(900);
		// store the task
		if ($taskUserId->equals(new TechDivision_Lang_Integer(0))) {
	        // create a new task-user relation
			$taskUser = TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())->epbCreate();
			// set the data
			$taskUser->setTaskIdFk($lvo->getTaskIdFk());
			$taskUser->setUserIdFk($lvo->getUserIdFk());
			$taskUser->setUsername($lvo->getUsername());
			$taskUser->setFrom($lvo->getFrom());
			$taskUser->setUntil($lvo->getUntil());
			$taskUser->setDescription($lvo->getDescription());
			$taskUser->setTaskName($lvo->getTaskName());
			$taskUser->setProjectIdFk($lvo->getProjectIdFk());
			$taskUser->setProjectName($lvo->getProjectName());
			$taskUser->setToBook($lvo->getToBook());
			$taskUser->setToAccount($this->roundUp($lvo->getToAccount()));
			$taskUser->setCosts($costs);
			// check if an issue number has been set
			if ($lvo->getIssue()->equals(new TechDivision_Lang_String())) {
				$taskUser->setIssue(null);
			}
			else {
				$taskUser->setIssue($lvo->getIssue());
			}
			// set the timestamps
			$createdAt = new TechDivision_Lang_Integer(time());
			$taskUser->setCreatedAt($createdAt);
			$taskUser->setUpdatedAt($createdAt);
			// create a new entry
			$taskUserId = $taskUser->create();
		} else {
		    // update the task-user relation
			$taskUser = TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
			    ->findByPrimaryKey($taskUserId);
			$taskUser->setTaskIdFk($lvo->getTaskIdFk());
			$taskUser->setUserIdFk($lvo->getUserIdFk());
			$taskUser->setUsername($lvo->getUsername());
			$taskUser->setFrom($lvo->getFrom());
			$taskUser->setUntil($lvo->getUntil());
			$taskUser->setDescription($lvo->getDescription());
			$taskUser->setTaskName($lvo->getTaskName());
			$taskUser->setProjectIdFk($lvo->getProjectIdFk());
			$taskUser->setProjectName($lvo->getProjectName());
			$taskUser->setToBook($lvo->getToBook());
			$taskUser->setToAccount($this->roundUp($lvo->getToAccount()));
			$taskUser->setCosts($costs);
			// check if an issue number has been set
			if ($lvo->getIssue()->equals(new TechDivision_Lang_String())) {
				$taskUser->setIssue(null);
			}
			else {
				$taskUser->setIssue($lvo->getIssue());
			}
			// update the timestamp
			$taskUser->setUpdatedAt(new TechDivision_Lang_Integer(time()));
			// update the existing entry
			$taskUser->update();
		}
		// add the seconds to the task performance
		/* TODO Removed to improve performance
		 * @author Tim Wagner
		 * @date 2012-02-17
		 * TDProject_Project_Model_Actions_TaskPerformance::create($this->getContainer())
		 * 	 ->addTimeToTotal($taskIdFk, $this->calculate($lvo));
		 */
		// return the ID of the saved entry
		return $taskUserId;
    }

    /**
     * Calculate the costs for the user with the passed ID and
     * the time in second.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to calculate the costs for
     * @param TechDivision_Lang_Integer $seconds
     * 		The number of seconds to calculate the costs for
     * @return TechDivision_Lang_Integer
     * 		The calculated costs
     */
    public function calculateCosts(
    	TechDivision_Lang_Integer $userId,
    	TechDivision_Lang_Integer $seconds)
    {
		// load the user
		$user = TDProject_Core_Model_Utils_UserUtil::getHome($this->getContainer())
		    ->findByPrimaryKey($userId);
		// calculate the hours
		$hours = $seconds->intValue() / 3600;
		// calculate the costs
    	$costs = $hours * $user->getRate()->intValue();
    	// return the costs
    	return new TechDivision_Lang_Integer((int) $costs);
    }

    /**
     * Calculates the secondes for the passed logging entry.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo
     * 		The logging entry
     * @return TechDivision_Lang_Integer The seconds used
     */
    public function calculate(
        TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo) {
		return new TechDivision_Lang_Integer(
			$lvo->getUntil()->intValue() - $lvo->getFrom()->intValue()
		);
    }

    /**
     * Checks if the passed logging entry is already overbooked.
     *
     * A task IS overbooked, if the user tries to book additional
     * time on the task AND the total average will we exeeded, or
     * the total maximum will be exeeded.
     *
     * If the total average will be exeeded, the user's booking
     * will only result in write a warning log message and
     * send a mail to the systems administrator.
     *
     * If the total maximum will be exeeded, the user's booking
     * will rejected by throwing an exception.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo
     * 		The logging entry to be checked
     */
    public function isBookable(
    	TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo)
    {
    	// check if the project's cycle has already been closed
    	$projectCycles = TDProject_Project_Model_Utils_ProjectCycleUtil::getHome($this->getContainer())
    		->findAllByProjectIdFkAndCycleClosed($lvo->getProjectIdFk(), $lvo->getFrom());
		// if a CLOSED project cycle has been found throw an exception
    	foreach ($projectCycles as $projectCycle) {
    		// create the project closing date and load project name
    		$projectName = $lvo->getProjectName();
    		$closingDate = date('Y-m-d H:i:s', $projectCycle->getCycleClosed()->intValue());
			// log an error message
	    	$this->getLogger()->error(
	    		$msg = "Cycle for project $projectName has already been closed till $closingDate", 
	    		__LINE__,
	    		__METHOD__
	    	);
			// initialize and throw an Exception
			TDProject_Project_Common_Exceptions_ProjectCycleClosedException::create()
				->setMessage($msg)
				->setProjectName($projectName)
				->setClosingDate($closingDate)
				->throwSelf();
    	}
		// try to load the task performance
    	$taskPerformances = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
    		->findAllByTaskIdFk($taskIdFk = $lvo->getTaskIdFk());
		// if no task performance was found
    	if ($taskPerformances->size() == 0) {
			// log a message
    		$this->_getLogger()->debug(
    			"Can't find performance for task $taskIdFk"
    		);
			// initialize the task performance
    		$taskPerformanceId = TDProject_Project_Model_Actions_TaskPerformance_Reorg::create($this->getContainer())
    			->createByTaskIdFk($lvo->getTaskIdFk());
			// load the task performance initialized before
    		$taskPerformance = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
    			->findByPrimaryKey($taskPerformanceId);
			// add the task performance to the ArrayList
    		$taskPerformances->add($taskPerformance);
    	}
		// update the task performance
    	foreach ($taskPerformances as $taskPerformance) {
			// calculate the total average calculation
    		$average = $taskPerformance->getAverage()->intValue();
			// calculate the total maximum calculation
			$maximum = $taskPerformance->getMaximum()->intValue();
    		// calculate the total after storing the logging entry
    		$total = $taskPerformance->getTotal()
    			->add($this->calculate($lvo))->intValue();
    		// check if task overbooking is explicitely allowed
    		if ($taskPerformance->getAllowOverbooking()->booleanValue() &&
    			$taskPerformance->getFinished()->booleanValue() === false) {
    			// write a informational log message only
    			$this->_getLogger()->info(
	    			'Overbooking of task with ID ' . $lvo->getTaskIdFk() .
	    			' IS explicitely allowed and task is NOT finished'
    			);
    		}
			// check if the task has already been finished
    		elseif ($taskPerformance->getFinished()->booleanValue()) {
				// write an error message
				$this->_getLogger()->error(
					$msg = 'Task with ID ' . $lvo->getTaskIdFk()
					. ' is marked as finished, addtional bookings'
					. ' are NOT allowed'
				);
				// initialize and throw an Exception
				TDProject_Project_Common_Exceptions_TaskFinishedException::create()
					->setMessage($msg)
					->throwSelf();
    		}
			// if the task's total maximum estimation is overbooked
			elseif ($maximum > 0 && $total > $maximum) {
				// write an error message
				$this->_getLogger()->error(
					$msg = 'Overbooking task with ID ' . $lvo->getTaskIdFk()
					. ' (max. estimation: ' . $maximum . ') by '
					. ($total - $maximum) . ' seconds is NOT allowed'
				);
				// send a mail
				$this->_sendMaximumOverbooked($lvo, $total, $maximum);
				// initialize and throw an Exception
				TDProject_Project_Common_Exceptions_TaskOverbookedException::create()
					->setMessage($msg)
					->setMaximum($maximum)
					->setTotal($total)
					->throwSelf();
			}
			// if the task's total average estimation is overbooked
			elseif ($average > 0 && $total > $average) {
				// write a warning log message
				$this->_getLogger()->warning(
					'Task with ID ' . $lvo->getTaskIdFk() .
					'(avg. estimation: ' . $average . ') will be overbooked by ' .
					$total - $average . ' seconds'
				);
				// send a mail
				$this->_sendAverageOverbooked($lvo, $total, $average);
			}
			// if task will NOT be overbooked
			else {
				// write a informational log message only
				$this->_getLogger()->info(
					'Task with ID ' . $lvo->getTaskIdFk() .
					'(avg. estimation: ' . $average . ') will NOT be overbooked by adding ' .
					$total - $average . ' seconds'
				);
			}
    	}
    }

    /**
     * Calculates the seconds for the logging entries
     * for the task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the seconds of the logging entries for
	 * @return integer The seconds
     */
    public function calculateByTaskId(
    	TechDivision_Lang_Integer $taskId) {
    	// initialize the seconds
    	$total = 0;
    	// load all logging entries
       	$taskUsers = TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
            	->findAllByTaskIdFk($taskId);
		// calculate the seconds for all logging entries
        foreach ($taskUsers as $taskUser) {
        	$total += $this->calculate($taskUser)->intValue();
        }
        // return the seconds
        return $total;
    }

    /**
     * Sends a mail with a message that the passed logging
     * entry will overbook the maximum estimation time.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo
     * 		The logging entry that will overbook the task
     * @param integer $total
     * 		The total after the passed logging entry has been booked
     * @param integer $maximum
     * 		The total maximum estimation time for the task
     * @return void
     */
    protected function _sendMaximumOverbooked(
    	TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo,
    	$total,
    	$maximum) {
    	// load the user that tries to book
    	$user = $this->_getUser($lvo->getUserIdFk())->getLightValue();
    	// load the task to book the logging entry for
    	$task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
    		->findByPrimaryKey($lvo->getTaskIdFk())
    		->getLightValue();
    	// load the system settings
    	$settings = TDProject_Core_Model_Utils_SettingUtil::getHome($this->getContainer())
    		->findAll();
    	// load the project
    	$project = TDProject_Project_Model_Assembler_Project::create($this->getContainer())
    		->getProjectLightValueByTaskId($lvo->getTaskIdFk());
    	// load the project users
    	$projectUsers = TDProject_Project_Model_Utils_ProjectUserUtil::getHome($this->getContainer())
    		->findAllByProjectIdFk($project->getProjectId());

    	//create RoleLocalHome-instance to access users' roles
    	$roleLocalHome = TDProject_Core_Model_Utils_RoleUtil::getHome($this->getContainer());
    	//create user-roles to be compared
    	$administratorRole = new TechDivision_Lang_Integer(
    	    self::ADMINISTRATOR_ROLE_ID
    	);
    	$projectManagerRole = new TechDivision_Lang_Integer(
    	    self::PROJECTMANAGER_ROLE_ID
    	);

    	// initialize the HashMap for the project admins
    	$receipients = new TechDivision_Collections_HashMap();
    	foreach ($projectUsers as $projectUser) {
    	    //get the user's id
    	    $userId = $this->_getUser($projectUser->getUserIdFk())->getUserId();
    	    //get the user's role, can only be one entry
    	    $roles = $roleLocalHome->findAllByUserIdFk($userId);
    	    $userRole = $roles->get(0)->getRoleIdFk();
    	    //check if the user has one of the roles that should receive a mail
    	    if (($userRole->equals($administratorRole))
    	        || ($userRole->equals($projectManagerRole))) {
    	        $receipients->add(
    	            $this->_getUser($projectUser->getUserIdFk())->getEmail(),
    	            $this->_getUser($projectUser->getUserIdFk())->getUsername()
    	        );

    	        $this->_getLogger()->debug(
    	            $this->_getUser($projectUser->getUserIdFk())->getUsername().
    	            " added to the list of receipients"
    	        );
    	    }
    	}
    	// iterate over the settings (ALWAYS has to be one exactly!!!)
    	foreach ($settings as $setting) {
	    	// initialize the mail content
	    	$content = new TDProject_Project_Common_Email_Logging_MaximumOverbooked();
	    	$content
	    		->setSetting($setting)
	    		->setTo($receipients->toIndexedArray())
	    		->setProject($project)
	    		->setUser($user)
	    		->setTask($task)
	    		->setTaskUser($lvo)
	    		->setTotal($total)
	    		->setMaximum($maximum);
	    	// send the mail
	    	TDProject_Core_Common_Services_Email::create()
	    		->send($setting, $content);
    	}
    }

    /**
     * Sends a mail with a message that the passed logging
     * entry will overbook the maximum estimation time.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo
     * 		The logging entry that will overbook the task
     * @param integer $total
     * 		The total after the passed logging entry has been booked
     * @param integer $average
     * 		The total average estimation time for the task
     * @return void
     */
    protected function _sendAverageOverbooked(
    	TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo, $total, $average) {
    	// load the user that tries to book
    	$user = $this->_getUser($lvo->getUserIdFk())->getLightValue();
    	// load the task to book the logging entry for
    	$task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
    		->findByPrimaryKey($lvo->getTaskIdFk())
    		->getLightValue();
    	// load the system settings
    	$settings = TDProject_Core_Model_Utils_SettingUtil::getHome($this->getContainer())
    		->findAll();
    	// load the project
    	$project = TDProject_Project_Model_Assembler_Project::create($this->getContainer())
    		->getProjectLightValueByTaskId($lvo->getTaskIdFk());
    	// load the project users
    	$projectUsers = TDProject_Project_Model_Utils_ProjectUserUtil::getHome($this->getContainer())
    		->findAllByProjectIdFk($project->getProjectId());

    	//create RoleLocalHome-instance to access users' roles
    	$roleLocalHome = TDProject_Core_Model_Utils_RoleUtil::getHome($this->getContainer());
    	//create user-roles to be compared
    	$administratorRole = new TechDivision_Lang_Integer(
    	    self::ADMINISTRATOR_ROLE_ID
    	);
    	$projectManagerRole = new TechDivision_Lang_Integer(
    	    self::PROJECTMANAGER_ROLE_ID
    	);

    	// initialize the HashMap for the project admins
    	$receipients = new TechDivision_Collections_HashMap();

    	foreach ($projectUsers as $projectUser) {
    	    //get the user's id
    	    $userId = $this->_getUser($projectUser->getUserIdFk())->getUserId();
    	    //get the user's role, can only be one entry
    	    $roles = $roleLocalHome->findAllByUserIdFk($userId);
    	    $userRole = $roles->get(0)->getRoleIdFk();
    	    //check if the user has one of the roles that should receive a mail
    	    if (($userRole->equals($administratorRole))
    	        || ($userRole->equals($projectManagerRole))) {
			    $receipients->add(
    				$this->_getUser($projectUser->getUserIdFk())->getEmail(),
    				$this->_getUser($projectUser->getUserIdFk())->getUsername()
			    );
			    $this->_getLogger()->debug(
			        $this->_getUser($projectUser->getUserIdFk())->getUsername()
			       	    ." added to the list of receipients"
			    );
    	    }
    	}
    	// iterate over the settings (ALWAYS has to be one exactly!!!)
    	foreach ($settings as $setting) {
	    	// initialize the mail content
	    	$content = new TDProject_Project_Common_Email_Logging_AverageOverbooked();
	    	$content
	    		->setSetting($setting)
	    		->setTo($receipients->toIndexedArray())
	    		->setProject($project)
	    		->setUser($user)
	    		->setTask($task)
	    		->setTaskUser($lvo)
	    		->setTotal($total)
	    		->setAverage($average);
	    	// send the mail
	    	TDProject_Core_Common_Services_Email::create($this->getContainer())
	    		->send($setting, $content);
    	}
    }

    /**
     * Helper method to load the user LVO for the user with the
     * passed ID.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to load
     * @return TDProject_Core_Common_ValueObjects_UserLightValue
     * 		The requested LVO
     */
    protected function _getUser(TechDivision_Lang_Integer $userId)
    {
    	// load the user with the passed ID
    	return TDProject_Core_Model_Utils_UserUtil::getHome($this->getContainer())
    		->findByPrimaryKey($userId);
    }

    /**
     * Loads the task-user relation and updates the task performance.
     *
     * @param TechDivision_Lang_Integer $taskUserId
     * 		The task-user relation ID to delete
     * @return void
     */
    public function deleteTaskUser(TechDivision_Lang_Integer $taskUserId)
    {
        // load the task-user relation
        $taskUser = TDProject_Project_Model_Utils_TaskUserUtil::getHome($this->getContainer())
            ->findByPrimaryKey($taskUserId);
        // store the task ID to update the performance for
        $taskIdFk = $taskUser->getTaskIdFk();
        // delete the task-user relation
        $taskUser->delete();
        // reorg the task performance
        /* TODO Removed to improve performance
         * @author Tim Wagner
         * @date 2012-02-17
         * TDProject_Project_Model_Actions_TaskPerformance_Reorg::create($this->getContainer())
         *     ->updateByTaskIdFk($taskIdFk);
         */
    }
}