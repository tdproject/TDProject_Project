<?php

/**
 * TDProject_Project_Model_Actions_ProjectUser_Rule
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Model/Actions/Rule.php';
require_once 'TDProject/Core/Model/Utils/RuleUtil.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Core
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Model_Actions_ProjectUser_Rule
    extends TDProject_Core_Model_Actions_Rule {

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Actions_ProjectUser_Rule($container);
    }
    
    /**
     * Create the ACL rule for the passed project-user relation.
     * 
     * @param TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo
     * 		The project-user relation to allow
     * @return void
     */
    public function allowProjectUser(
        TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo) {
        // initialize the resource key 
        $resourceKey = '/logging/' . $lvo->getProjectIdFk();
        // initialize the resource
        $resource = new Zend_Acl_Resource($resourceKey);
        // load the users default role
        $role = TDProject_Core_Model_Assembler_Role::create($this->getContainer())
            ->getAclRole($lvo->getUserIdFk());
        // load the rules
        $rules = TDProject_Core_Model_Utils_RuleUtil::getHome($this->getContainer())
            ->findAllByRoleIdFkAndResource(
                $role->getRoleId(),
                new TechDivision_Lang_String($resourceKey)
            );
        // set the data and create the 
        $this->setResource($resource)->setRole($role)->allow();
    }
    
    /**
     * Remove the ACL rule for the passed project-user relation.
     * 
     * @param TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo
     * 		The project-user relation to deny
     * @return void
     */
    public function denyProjectUser(
        TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo) {
        // initialize the resource
        $resource = new TechDivision_Lang_String(
        	'/logging/' . $lvo->getProjectIdFk()
        );
        // load the users default role
        $role = TDProject_Core_Model_Assembler_Role::create($this->getContainer())
            ->getAclRole($lvo->getUserIdFk());
        // load the rules
        $rules = TDProject_Core_Model_Utils_RuleUtil::getHome($this->getContainer())
            ->findAllByRoleIdFkAndResource($role->getRoleId(), $resource);
        // delete the rules
        foreach ($rules as $rule) {
            $rule->delete();
        }
        // load the container and remove the ACL's from the cache
        $this->getContainer()->getCache()->remove(TDProject_Core_Model_Actions_Acl::CACHE_KEY);
    }
}