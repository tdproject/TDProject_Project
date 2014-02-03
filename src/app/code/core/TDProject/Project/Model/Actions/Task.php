<?php

/**
 * TDProject_Project_Model_Actions_Task
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
class TDProject_Project_Model_Actions_Task
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
        return new TDProject_Project_Model_Actions_Task($container);
    }

    /**
     * Copies the tree with the template tasks to the passed project.
     *
     * @param TDProject_Project_Model_Entities_Project $project
     * 		The project to copy the tree to
     * @return void
     */
	public function cloneTasks(
	    TDProject_Project_Model_Entities_Project $project) {
	    // load the template to clone the tasks from
	    $template = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
	        ->findByPrimaryKey($project->getTemplateIdFk());
        // copy the nodes recursively
    	foreach ($template->getTasks() as $toClone) {
    	    // copy the node recursively
    	    $taskId = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())->moveTree(
    	        $targetId = $toClone->getTaskId()->intValue(),
    	        $targetId,
    	        NESE_MOVE_AFTER,
    	        true
    	    );
			// create the project-task relation
			$relation = TDProject_Project_Model_Utils_ProjectTaskUtil::getHome($this->getContainer())->epbCreate();
			$relation->setProjectIdFk($project->getProjectId());
			$relation->setTaskIdFk(new TechDivision_Lang_Integer($taskId));
			$relation->create();
    	}
	}

	/**
	 * Saves the passed task and returns
	 * the ID.
	 *
	 * @param TDProject_Project_Common_ValueObjects_TaskValue $lvo
	 * 		The LVO with the data to save
	 * @return TechDivision_Lang_Integer
	 * 		The ID of the saved task
	 */
	public function saveTask(
		TDProject_Project_Common_ValueObjects_TaskValue $vo) {
		// lookup task ID
		$taskId = $vo->getTaskId();
		// store the task
		if ($taskId->equals(new TechDivision_Lang_Integer(0))) {
            // create a new task
			$task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())->epbCreate();
			// set the data
			$task->setTaskIdFk($vo->getTaskIdFk());
			$task->setTaskTypeIdFk($vo->getTaskTypeIdFk());
			$task->setName($vo->getName());
			$task->setDescription($vo->getDescription());
			$task->setBillable($vo->getBillable());
			$task->setCostCenter($vo->getCostCenter());
			$taskId = $task->create();
			// set the task ID in the VO
			$vo->setTaskId($taskId);
		} else {
		    // update the task
			$task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
			    ->findByPrimaryKey($taskId);
			$task->setTaskIdFk($vo->getTaskIdFk());
			$task->setTaskTypeIdFk($vo->getTaskTypeIdFk());
			$task->setName($vo->getName());
			$task->setDescription($vo->getDescription());
			$task->setBillable($vo->getBillable());
			$task->setCostCenter($vo->getCostCenter());
			$task->update();
		}
		// save the task's performance and return the ID of the saved task
		return $this->saveTaskPerformance($vo);
	}

	/**
	 * Saves the performance for the task with the passed data.
	 *
	 * @param TDProject_Project_Common_ValueObjects_TaskValue $vo
	 * @return TechDivision_Lang_Integer
	 * 		The ID of the task to save the performance for
	 */
	public function saveTaskPerformance(
		TDProject_Project_Common_ValueObjects_TaskValue $vo) {
		// load the task performance data
		foreach ($vo->getTaskPerformances() as $lvo) {
			// load the ID of the task's performance
			$taskPerformanceId = $lvo->getTaskPerformanceId();
			// if no ID is available create an new performance
			if ($taskPerformanceId->equals(new TechDivision_Lang_Integer(0))) {
				// set th task ID
				$lvo->setTaskIdFk($vo->getTaskId());
				// initialize the
				$lvo->setTotal(new TechDivision_Lang_Integer(0));
				$lvo->setMinimum(new TechDivision_Lang_Integer(0));
				$lvo->setNormal(new TechDivision_Lang_Integer(0));
				$lvo->setAverage(new TechDivision_Lang_Integer(0));
				$lvo->setMaximum(new TechDivision_Lang_Integer(0));
				// create the task's performance
				TDProject_Project_Model_Actions_TaskPerformance::create($this->getContainer())
					->createTaskPerformance($lvo);
			} else {
				// load the task performance entity
				$taskPerformance = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
					->findByPrimaryKey($lvo->getTaskPerformanceId());
				// set th task ID
				$lvo->setTaskIdFk($vo->getTaskId());
				// set the performance values itself
				$lvo->setTotal($taskPerformance->getTotal());
				$lvo->setMinimum($taskPerformance->getMinimum());
				$lvo->setNormal($taskPerformance->getNormal());
				$lvo->setAverage($taskPerformance->getAverage());
				$lvo->setMaximum($taskPerformance->getMaximum());
				// update the task's performance
				TDProject_Project_Model_Actions_TaskPerformance::create($this->getContainer())
					->updateTaskPerformance($lvo);
			}
		}
		// return the task's ID
		return $vo->getTaskId();
	}

	/**
	 * Calculate the total of the task with the passed
	 * ID and all his childs.
	 *
	 * @param TechDivision_Lang_Integer $taskId
	 * 		The ID of the task to calculate the total
	 * @return integer
	 * 		The total time spent on working on the tasks
	 */
	public function calculateTotal(TechDivision_Lang_Integer $taskId)
	{
		// initialize the total
		$total = 0;
		// load the task and his childs
		$task = TDProject_Project_Model_Assembler_Task::create($this->getContainer())
			->getTaskViewData($taskId);
		// add the total of each child task
		$total += TDProject_Project_Model_Actions_Logging::create($this->getContainer())
			->calculateByTaskId($taskId);
		// check if child tasks are available
		foreach ($task->getTasks() as $tsk) {
			$total += $this->calculateTotal($tsk->getTaskId());
		}
		// return the total average to the task
		return $total;
	}
	
	/**
	 * Checks whether the task with the given id is not a root-task 
	 * and deletes it.
	 * @param TechDivision_Lang_Integer $taskId
	 * @throws TDProject_Project_Common_Exceptions_DeleteRootTaskException
	 * if task was root-task
	 */
	
	public function deleteTask(TechDivision_Lang_Integer $taskId)
	{
	    // load the task
	    $task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
	    ->findByPrimaryKey($taskId);
	    
	    if ($this->isRoot($task)) {
	        throw new TDProject_Project_Common_Exceptions_DeleteRootTaskException(
	            'Task with ID ' . $taskId . ' is root node'
	        );   
	    }
	    // delete the task
	    $task->delete();	    
	}
	
	
	/**
	 * Checks whether the task represented by the given
	 * TaskViewData-object is root task.
	 * 
	 * @param TDProject_Project_Common_ValueObjects_TaskViewData $dto
	 * @return TechDivision_Lang_Boolean
	 */
	public function isRoot(
	    TDProject_Project_Model_Entities_Task $task)
	{
    	return $task->getTaskId()->equals($task->getTaskIdFk());
	}
}