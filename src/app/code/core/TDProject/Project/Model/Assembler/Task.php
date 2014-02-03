<?php

/**
 * TDProject_Project_Model_Assembler_Task
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
class TDProject_Project_Model_Assembler_Task
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
        return new TDProject_Project_Model_Assembler_Task($container);
    }

    /**
     * Returns a DTO initialized with the data of the
     * task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		ID of the task to initialize the DTO with
     * @param boolean
     * 		TRUE if cumulated loggings (performance) should be load, else FALSE
     * @return TDProject_Project_Common_ValueObjects_TaskViewData
     * 		The DTO with the requested task data
     */
    public function getTaskViewData(
    	TechDivision_Lang_Integer $taskId = null,
    	$loadCumulatedLoggings = true) {
        // initialize a new ArrayList
        $list = new TDProject_Project_Common_ValueObjects_Collections_Task();
		// check if a task ID was passed
		if (!empty($taskId)) {
		    // if yes, load the project
			$task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
			    ->findByPrimaryKey($taskId);
		} else {
    		// if not, initialize a new project
    		$task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
    		    ->epbCreate();
		}
        // initialize the DTO
        $dto = new TDProject_Project_Common_ValueObjects_TaskViewData(
            $task
        );
        // set the available task types
        $dto->setTaskTypes(
		    TDProject_Project_Model_Assembler_TaskType::create($this->getContainer())
		        ->getTaskTypeOverviewData()
        );
        // load the cumulated logging information
		/* TODO Removed to improve performance
		 * @author Tim Wagner
		 * @date 2012-02-17
		 * if ($loadCumulatedLoggings) {
	     *    $dto->setLoggings(
	     *        TDProject_Project_Model_Assembler_Logging::create($this->getContainer())
	     *            ->getCumulatedLoggings($taskId)
	     *    );
		 * }
		 */
        // assemble the subtasks
        foreach ($task->getTasks() as $subtask) {
            $list->add(
                $this->getTaskViewData(
                	$subtask->getTaskId(),
                	$loadCumulatedLoggings
                )
            );
        }
        // add the list with the subtasks
        $dto->setTasks($list);
        // return the initialized DTO
        return $dto;
    }

    /**
     * Returns a DTO initialized with the data of the
     * task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		ID of the task to initialize the DTO with
     * @return TDProject_Project_Common_ValueObjects_TaskOverviewData
     * 		The DTO with the requested task data
     */
    public function getTaskOverviewData(TechDivision_Lang_Integer $taskId)
    {
        // initialize a new ArrayList
        $list = new TDProject_Project_Common_ValueObjects_Collections_Task();
		// if yes, load the project
		$task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
		    ->findByPrimaryKey($taskId);
        // initialize the DTO
        $dto = new TDProject_Project_Common_ValueObjects_TaskOverviewData(
            $task
        );
        // check if the task is selectable for booking or not
        $taskType = TDProject_Project_Common_Enums_TaskType::create(
        	TDProject_Project_Common_Enums_TaskType::ACTIVITY
        );
        // if not an activity, deactivate it by default
        if (!$taskType->equals($task->getTaskType())) {
        	$dto->setSelectable(false);
        }
        // check if the task has already been finished
        else {
        	// load the task's performances
        	$taskPerformances =  $task->getTaskPerformances();
			// if a performance was found check if the task has already been finished
        	if ($taskPerformances->size() > 0) {
		        foreach ($taskPerformances as $taskPerformance) {
		        	if ($taskPerformance->getFinished()->booleanValue()) {
		        		$dto->setSelectable(false);
		        	}
		        }
        	}
        }
        // assemble the subtasks
        foreach ($task->getTasks() as $subtask) {
            $list->add(
                $this->getTaskOverviewData($subtask->getTaskId())
            );
        }
        // add the list with the subtasks
        $dto->setTasks($list);
        // return the initialized DTO
        return $dto;
    }

    /**
     * Returns a DTO initialized with the data of the
     * task for the project with the passed ID.
     *
     * @param TechDivision_Lang_Integer $projectId
     * 		ID of the project to initialize the DTO for
     * @return TDProject_Project_Common_ValueObjects_TaskOverviewData
     * 		The DTO with the requested task data
     */
    public function getTaskOverviewDataByProjectId(
    	TechDivision_Lang_Integer $projectId) {
    	// load the project
    	$project = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
    	    ->findByPrimaryKey($projectId);
    	// load and initialize the tasks
    	foreach ($project->getTasks() as $task) {
    		return $this->getTaskOverviewData($task->getTaskId());
    	}
    }

    /**
     * Load the available tasks, assembles them into
     * TaskLightValues and returns them as an ArrayList.
     *
     * @return TechDivision_Collections_ArrayList
     * 		The assembled task enitities
     */
    public function getTaskLightValues() {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // load the Collection with all available tasks
        $collection = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
            ->findAll();
        // assemble the entities
        foreach ($collection as $task) {
            $list->add($task->getLightValue());
        }
        // return the ArrayList with the TaskLightValue's
        return $list;
    }
}