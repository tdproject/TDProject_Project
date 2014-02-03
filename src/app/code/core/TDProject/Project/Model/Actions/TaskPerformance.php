<?php

/**
 * TDProject_Project_Model_Actions_TaskPerformance
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
class TDProject_Project_Model_Actions_TaskPerformance
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
        return new TDProject_Project_Model_Actions_TaskPerformance($container);
    }

	/**
	 * Initialize the task performance.
	 *
	 * @param TechDivision_Lang_Integer $taskId
	 * 		The task performance to create
	 * @return TechDivision_Lang_Integer The ID of the task's performance
	 */
	public function createTaskPerformance(
		TDProject_Project_Common_ValueObjects_TaskPerformanceLightValue $lvo) {
		// create a new task performance entity
		$taskPerformance = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
			->epbCreate();
		// set the data
		$taskPerformance->setTaskIdFk($lvo->getTaskIdFk());
		$taskPerformance->setTotal($lvo->getTotal());
		$taskPerformance->setNormal($lvo->getNormal());
		$taskPerformance->setMinimum($lvo->getMinimum());
		$taskPerformance->setAverage($lvo->getAverage());
		$taskPerformance->setMaximum($lvo->getMaximum());
		$taskPerformance->setAllowOverbooking($lvo->getAllowOverbooking());
		$taskPerformance->setFinished($lvo->getFinished());
		// save the task performance and return the ID
		return $taskPerformance->create();

	}

	/**
	 * Update the task performance.
	 *
	 * @param TDProject_Project_Common_ValueObjects_TaskPerformanceLightValue $lvo
	 * 		The task performance data to update
	 * @return void
	 */
	public function updateTaskPerformance(
		TDProject_Project_Common_ValueObjects_TaskPerformanceLightValue $lvo) {
		// load the task performance entity
		$taskPerformance = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
			->findByPrimaryKey($lvo->getTaskPerformanceId());
		// set the data
		$taskPerformance->setTaskIdFk($lvo->getTaskIdFk());
		$taskPerformance->setTotal($lvo->getTotal());
		$taskPerformance->setMinimum($lvo->getMinimum());
		$taskPerformance->setNormal($lvo->getNormal());
		$taskPerformance->setAverage($lvo->getAverage());
		$taskPerformance->setMaximum($lvo->getMaximum());
		$taskPerformance->setAllowOverbooking($lvo->getAllowOverbooking());
		$taskPerformance->setFinished($lvo->getFinished());
		// save the task performance
		$taskPerformance->update();

	}

    /**
     * Adds the passed seconds, usually based on a new booking, to the
     * performance of the task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskIdFk The task to add the seconds to
     * @param TechDivision_Lang_Integer $secondsToAdd The seconds to add
     * @throws Exception Is thrown if the task can't be found
     */
    public function addTimeToTotal(
    	TechDivision_Lang_Integer $taskIdFk,
    	TechDivision_Lang_Integer $secondsToAdd) {
		// try to load the tasks performance
    	$taskPerformances = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
    		->findAllByTaskIdFk($taskIdFk);
		// iterate over the tasks performance and update it
    	foreach ($taskPerformances as $taskPerformance) {
	    	$taskPerformance->getTotal()->add($secondsToAdd);
	    	return $taskPerformance->update();
    	}
		// throw an Exception if the task can't be found
    	throw new Exception("Performance for task $taskIdFk not found");
    }
}