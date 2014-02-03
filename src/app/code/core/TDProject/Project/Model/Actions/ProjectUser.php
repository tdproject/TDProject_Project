<?php

/**
 * TDProject_Project_Model_Actions_ProjectUser
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
class TDProject_Project_Model_Actions_ProjectUser
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
        return new TDProject_Project_Model_Actions_ProjectUser($container);
    }

	/**
	 * Creates a new relation for the passed project and user and returns
	 * the ID.
	 *
	 * @param TDProject_Project_Model_Entities_Project $project
	 * 		The project data to create the relation for
	 * @param TechDivision_Lang_Integer $userId
	 * 		The user ID to create the relation for
	 * @return TechDivision_Lang_Integer
	 * 		The ID of the saved project-user relation
	 */
	public function relate(
        TDProject_Project_Common_ValueObjects_ProjectLightValue $project,
        TechDivision_Lang_Integer $userId) {
        // create a new empty LVO 
        $lvo = TDProject_Project_Model_Utils_ProjectUserUtil::getHome($this->getContainer())
            ->epbCreate();
        // set the project and user ID
        $lvo->setProjectIdFk($project->getProjectId());
        $lvo->setUserIdFk($userId);
        // save the relation and create the ID
        return $this->createAndAllow($lvo);
	}
	
	/**
	 * Creates the passed project-user relation and allows
	 * logging on the project.
	 * 
	 * @param TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo
	 * 		The data to create the relation for
	 * @return TechDivision_Lang_Integer The ID of the created relation
	 */
	public function createAndAllow(
	    TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo) {	    
        // initialize a new company-address relation
        $projectUser = TDProject_Project_Model_Utils_ProjectUserUtil::getHome($this->getContainer())
            ->epbCreate();
        // set and save the data
        $projectUser->setProjectIdFk($projectId = $lvo->getProjectIdFK());
        $projectUser->setUserIdFk($userId = $lvo->getUserIdFk());
        // create the relation and return the ID
        $projectUserId = $projectUser->create();
		// save the ruleset for the system user
		TDProject_Project_Model_Actions_ProjectUser_Rule::create($this->getContainer())
		    ->allowProjectUser($projectUser);
	    // return the ID
	    return $projectUserId;
	}
	
	/**
	 * Deletes the passed project-user relation and denys
	 * logging on the project.
	 * 
	 * @param TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo
	 * 		The data to create the relation for
	 * @return void
	 */
	public function deleteAndDeny(
	    TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo) {	
        // load the project-user relations
        $projectUserRelations = 
            TDProject_Project_Model_Utils_ProjectUserUtil::getHome($this->getContainer())
                ->findAllByProjectIdFkAndUserIdFk(
                	$lvo->getProjectIdFK(),
                	$lvo->getUserIdFk()
                );
        // delete the relations
        foreach ($projectUserRelations as $projectUser) {
        	$projectUser->delete();
        }
		// save the ruleset for the system user
		TDProject_Project_Model_Actions_ProjectUser_Rule::create($this->getContainer())
		    ->denyProjectUser($lvo);
	}
}