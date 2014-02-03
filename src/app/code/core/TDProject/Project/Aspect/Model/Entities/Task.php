<?php

/**
 * TDProject_Project_Aspect_Model_Entities_TaskUser
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/AOP/Interfaces/Aspect.php';
require_once 'TechDivision/AOP/Interfaces/JoinPoint.php';

/**
 * This class is the Aspect used to handle the Solr functionality.
 *
 * @category    TDProject
 * @package     TDProject_Core
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Aspect_Model_Entities_Task
    extends TechDivision_Lang_Object
    implements TechDivision_AOP_Interfaces_Aspect
{

    /**
     * Dummy constructor to avoid object factory
     * initialization problems.
     * 
     * @return void
     */
    public function __construct()
    {
    	// dummy constructor	
    }

    /**
     * This method creates a new Solr Document when a task has been created.
     *
     * @param TechDivision_AOP_Interfaces_JoinPoint $joinPoint
     * 		The actual JoinPoint
     * @return void
     */
    public function createSolrDocument(
        TechDivision_AOP_Interfaces_JoinPoint $joinPoint)
    {    	
    	// load the task entity
    	$task = $joinPoint
    		->getMethodInterceptor()
            ->getAspectContainer()
            ->getAspectable();
    	// create the Solr document
    	TDProject_Project_Model_Actions_Task_Solr::create($task->getContainer())
    	    ->createDocument($task);
    }
}