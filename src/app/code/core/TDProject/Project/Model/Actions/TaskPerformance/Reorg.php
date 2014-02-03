<?php

/**
 * TDProject_Project_Model_Actions_TaskPerformance_Reorg
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
class TDProject_Project_Model_Actions_TaskPerformance_Reorg
    extends TDProject_Core_Model_Actions_Abstract {

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Actions_TaskPerformance_Reorg($container);
    }

	/**
	 * Reorganizes the project with the passed ID. Actually only the
	 * performance of the projects tasks will be reorganized.
	 *
	 * @param TechDivision_Lang_Integer $projectId The ID of the project to be reorganzized
	 * @return void
	 */
    public function reorgByProjectId(TechDivision_Lang_Integer $projectId)
    {
    	// load the tasks
    	$tasks = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
    		->findAllByProjectIdFkBranched($projectId);
		// count the size of the projects tasks
    	$tasksFound = $tasks->size();
		// load the task performance home
    	$home = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer());
		// iterate over the tasks
    	foreach ($tasks as $counter => $task) {
			// load the task's performance
    		$taskPerformances =
    			$home->findAllByTaskIdFk($taskId = $task->getTaskId());
			// check the found size
    		$foundPerformances = $taskPerformances->size();
			// if not performance can be found, create it
			if ($foundPerformances == 0) {
				// create a new performance entry
				$this->createByTaskIdFk($taskId);
			}
			// else, update it
			else {
	    		// update the performance
				$this->updateByTaskIdFk($taskId);
			}
    	}
    }

    /**
     * Initializes the task performance with the task ID and
     * by the estimations and bookings, if available.
     *
     * @param TechDivision_Lang_Integer $taskIdFk
     * 		Task ID to initialize the performance for
     * @return TechDivision_Lang_Integer
     * 		The ID of the created task performance
     */
    public function createByTaskIdFk(TechDivision_Lang_Integer $taskIdFk)
    {
    	// calculate the total of all logging entries of the passed task and his childs
    	$total = TDProject_Project_Model_Actions_Task::create($this->getContainer())
    		->calculateTotal($taskIdFk);
    	// calculate the totals
    	$totals = TDProject_Project_Model_Actions_Calculation::create($this->getContainer())
    		->calculateTotals($taskIdFk);
		// create a new entity
    	$taskPerformance = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
    		->epbCreate();
		// initialize it with the values
    	$taskPerformance->setTaskIdFk($taskIdFk);
    	$taskPerformance->setTotal(new TechDivision_Lang_Integer($total));
    	$taskPerformance->setMinimum(new TechDivision_Lang_Integer($totals->minimum));
    	$taskPerformance->setNormal(new TechDivision_Lang_Integer($totals->normal));
    	$taskPerformance->setAverage(new TechDivision_Lang_Integer($totals->average));
    	$taskPerformance->setMaximum(new TechDivision_Lang_Integer($totals->maximum));
    	$taskPerformance->setAllowOverbooking(new TechDivision_Lang_Boolean(false));
    	$taskPerformance->setFinished(new TechDivision_Lang_Boolean(false));
		// save it and return the ID
    	return $taskPerformance->create();
    }

    /**
     * Updates the task performance with the task ID by
     * the estimations and bookings, if available.
     *
     * @param TechDivision_Lang_Integer $taskIdFk
     * 		Task ID to initialize the performance for
     * @return void
     */
    public function updateByTaskIdFk(TechDivision_Lang_Integer $taskIdFk)
    {
    	// calculate the total of all logging entries of the passed task and his childs
    	$total = TDProject_Project_Model_Actions_Task::create($this->getContainer())
    		->calculateTotal($taskIdFk);
    	// calculate the totals
    	$totals = TDProject_Project_Model_Actions_Calculation::create($this->getContainer())
    		->calculateTotals($taskIdFk);
		// load the available task performance
    	$taskPerformances = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
    		->findAllByTaskIdFk($taskIdFk);
 		// iterate over the task performances and update the values
    	foreach ($taskPerformances as $taskPerformance) {
			// initialize it with the values and update the entity
	    	$taskPerformance->setTotal(new TechDivision_Lang_Integer($total));
	    	$taskPerformance->setMinimum(new TechDivision_Lang_Integer($totals->minimum));
	    	$taskPerformance->setNormal(new TechDivision_Lang_Integer($totals->normal));
	    	$taskPerformance->setAverage(new TechDivision_Lang_Integer($totals->average));
	    	$taskPerformance->setMaximum(new TechDivision_Lang_Integer($totals->maximum));
	    	$taskPerformance->update();
    	}
    }
}