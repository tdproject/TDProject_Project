<?php

/**
 * TDProject_Project_Model_Assembler_Project
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
class TDProject_Project_Model_Assembler_Project
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
        return new TDProject_Project_Model_Assembler_Project($container);
    }

    /**
     * Returns a DTO with the data of the project
     * with the passed ID.
     *
     * @param TechDivision_Lang_Integer $projectId
     * 		The project ID to return the DTO for
     * @return TDProject_ERP_Common_ValueObjects_ProjectViewData
     * 		The requested DTO
     */
    public function getProjectViewData(
        TechDivision_Lang_Integer $projectId =  null) {
		// check if a project ID was passed
		if(!empty($projectId)) {
		    // if yes, load the project
			$project = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
			    ->findByPrimaryKey($projectId);
		} else {
    		// if not, initialize a new project
    		$project = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
    		    ->epbCreate();
		}
		// initialize the DTO
		$dto = new TDProject_Project_Common_ValueObjects_ProjectViewData(
		    $project
		);
		// set the available companies
		$dto->setCompanies(
		    TDProject_ERP_Model_Assembler_Company::create($this->getContainer())
		        ->getCompanyOverviewData()
		);
		// set the available templates
		$dto->setTemplates(
		    TDProject_Project_Model_Assembler_Template::create($this->getContainer())
		        ->getTemplateOverviewData()
		);
		// set the available projects
		$dto->setProjects(
		    $this->getProjectOverviewData()
		);
		// set the system users
		$dto->setUsers(
			TDProject_Core_Model_Assembler_User::create($this->getContainer())
				->getUserLightValues()
		);
		// set the user ID's of the related users
        foreach ($dto->getProjectUsers() as $projectUser) {
        	// set the ID's of the related users
        	$dto->getUserIdFk()->add($projectUser->getUserIdFk()->intValue());
        }
		// return the initialized DTO
		return $dto;
    }

    /**
     * Load the available projects, assembles them into
     * ProjectLightValues and returns them as an ArrayList.
     *
     * @return TechDivision_Collections_ArrayList
     * 		The assembled project enitities
     */
    public function getProjectOverviewData() {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // initialize the Home for loading the project's company
        $ch = TDProject_ERP_Model_Utils_CompanyUtil::getHome($this->getContainer());
        // load the Collection with all available projects
        $collection = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
            ->findAll();
        // assemble the entities
        foreach ($collection as $project) {
            // initialize the project
            $dto = new TDProject_Project_Common_ValueObjects_ProjectOverviewData(
                $project
            );
            // set the company
            $dto->setCompany(
                $ch->findByPrimaryKey($project->getCompanyIdFk())
            );
            // add the DTO to the ArrayList
            $list->add($dto);
        }
        // return the ArrayList with the ProjectLightValues
        return $list;
    }

    /**
     * Load the available projects, assembles them into
     * ProjectLightValues and returns them as an ArrayList.
     *
     * @return TechDivision_Collections_ArrayList
     * 		The assembled project enitities
     */
    public function getProjectLightValues() {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // load the Collection with all available projects
        $collection = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
            ->findAll();
        // assemble the entities
        foreach ($collection as $project) {
            $list->add($project->getLightValue());
        }
        // return the ArrayList with the ProjectLightValues
        return $list;
    }

    /**
     * Load the tasks of the project with the passed ID,
     * assembles them into TaskOverviewData DTO's and
     * returns them as an ArrayList.
     *
     * @param TechDivision_Lang_Integer $projectId
     * 		The project ID to initialize the tasks for
     * @return TDProject_Project_Common_ValueObjects_Collections_Task
     * 		The assembled task enitities
     */
    public function getTaskOverviewData(
        TechDivision_Lang_Integer $projectId) {
        // initialize a new ArrayList
        $list = new TDProject_Project_Common_ValueObjects_Collections_Task();
        // load the project
        $project = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
            ->findByPrimaryKey($projectId);
        // initialize the Assembler for the task DTO's
        $assembler = TDProject_Project_Model_Assembler_Task::create($this->getContainer());
        // iterate over the tasks related with the project
        foreach ($project->getTasks() as $task) {
            $list->add($assembler->getTaskViewData($task->getTaskId()));
        }
        // return the ArrayList with the TaskOverviewData DTO's
        return $list;
    }

    /**
     * This is a recursive method to find the root task
     * to load and return the related project LVO.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to load the project LVO for
     * @return TDProject_Project_Common_ValueObjects_ProjectLightValue
     * 		The project LVO the task with the passed ID is related to
     */
    public function getProjectLightValueByTaskId(
        TechDivision_Lang_Integer $taskId) {
        // load the task itself
        $task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
		    ->findByPrimaryKey($taskId);
		// load the ID of the parent task
		$taskIdFk = $task->getTaskIdFk();
		// check of a parent task exists
		if ($taskIdFk->equals($taskId)) {
            // load all project-task relations for the task with the passed ID
            $projectTasks =
                TDProject_Project_Model_Utils_ProjectTaskUtil::getHome($this->getContainer())
    		        ->findAllByTaskIdFk($taskId);
    		// load the task and return the project LVO
    		foreach ($projectTasks as $projectTask) {
    		    return $projectTask->getProject()->getLightValue();
    		}
    		// return an empty LVO if no relation was found
    		return TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
    		    ->epbCreate()
    		    ->getLightValue();
		}
		// task has a parent task
		return $this->getProjectLightValueByTaskId($taskIdFk);
    }

    /**
     * Load the available projects for the user with the passed ID,
     * assembles them into ProjectOverviewData DTO's and returns
     * them as an ArrayList.
     *
     * @param TechDivision_Lang_Integer $userId
     * 		The ID of the user to load the projects for
     * @return TechDivision_Collections_ArrayList
     * 		The assembled project enitities
     */
    public function getProjectOverviewDataByUserId(
        TechDivision_Lang_Integer $userId) {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // initialize the Home for loading the project's company
        $ch = TDProject_ERP_Model_Utils_CompanyUtil::getHome($this->getContainer());
        // load the Collection with all available projects
        $collection = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
            ->findAllByUserIdFk($userId);
        // assemble the entities
        foreach ($collection as $project) {
            // initialize the project
            $dto = new TDProject_Project_Common_ValueObjects_ProjectOverviewData(
                $project
            );
            // set the company
            $dto->setCompany(
                $ch->findByPrimaryKey($project->getCompanyIdFk())
            );
            // add the DTO to the ArrayList
            $list->add($dto);
        }
        // return the ArrayList with the ProjectLightValues
        return $list;
    }
}