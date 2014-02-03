<?php

/**
 * TDProject_Project_Model_Actions_Project
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

set_include_path(get_include_path() . PATH_SEPARATOR . 'PHPExcel');

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Model_Actions_Project
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
        return new TDProject_Project_Model_Actions_Project($container);
    }

	/**
	 * Saves the passed project and returns
	 * the ID.
	 *
	 * @param TDProject_Project_Common_ValueObjects_ProjectLightValue $lvo
	 * 		The LVO with the data to save
	 * @param TechDivision_Lang_Integer $userId 
	 *      The ID of the user to relate with the project
	 * @return TechDivision_Lang_Integer
	 * 		The ID of the saved project
	 */
	public function saveProject(
        TDProject_Project_Common_ValueObjects_ProjectLightValue $lvo, 
	    TechDivision_Lang_Integer $userId)
    {
		// lookup project ID
		$projectId = $lvo->getProjectId();
		// store the project
		if ($projectId->equals(new TechDivision_Lang_Integer(0))) {
            // create a new project
			$project = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
			    ->epbCreate();
			// set the data
			$project->setProjectIdFk($lvo->getProjectIdFk());
			$project->setCompanyIdFk($lvo->getCompanyIdFk());
			$project->setTemplateIdFk($lvo->getTemplateIdFk());
			$project->setName($lvo->getName());
			$projectId = $project->create();
			// save the project-user relation and extend the ACL
			TDProject_Project_Model_Actions_ProjectUser::create($this->getContainer())
			    ->relate($project, $userId);
			// clone the tasks from the selected template
			TDProject_Project_Model_Actions_Task::create($this->getContainer())
			    ->cloneTasks($project);
		} else {
		    // update the project
			$project = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
			    ->findByPrimaryKey($projectId);
			$project->setProjectIdFk($lvo->getProjectIdFk());
			$project->setCompanyIdFk($lvo->getCompanyIdFk());
			$project->setName($lvo->getName());
			$project->update();
			// check if a project cycle's closing date has been passed
			if ($lvo->getClosingDate() != null) {
			    // create a new project cycle
				$projectCycle = TDProject_Project_Model_Utils_ProjectCycleUtil::getHome($this->getContainer())
				    ->epbCreate();
				// create a new project cycle
				$projectCycle->setProjectIdFk($projectId);
				$projectCycle->setUserIdFk($userId);
				$projectCycle->setCycleClosed($lvo->getClosingDate());
				$projectCycle->setCreatedAt(new TechDivision_Lang_Integer(time()));
				$projectCycle->create();
			}
		}
		// create the ACL and return the project ID
		return $projectId;
	}

	/**
	 * Reorganizes the performance of a project's tasks.
	 *
	 * @param TechDivision_Lang_Integer $projectId
	 * 		The ID of the project to reorganize the tasks for
	 * @return void
	 */
	public function reorg(TechDivision_Lang_Integer $projectId)
	{
		TDProject_Project_Model_Actions_TaskPerformance_Reorg::create($this->getContainer())
			->reorgByProjectId($projectId);
	}
}