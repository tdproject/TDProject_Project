<?php

/**
 * TDProject_Project_Aspect_Controller_Logging_Task_Cache
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
 * This class is the Aspect used to cache a project's tasks.
 *
 * @category    TDProject
 * @package     TDProject_Core
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Aspect_Controller_Logging_Task_Cache
    extends TechDivision_Lang_Object
    implements TechDivision_AOP_Interfaces_Aspect {

    /**
     * The cache key prefix.
     * @var string
     */
    const CACHE_KEY = 'tdproject_project_model_entities_task';
    
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
     * Initializes the cache key and returns it.
     *
     * @param integer $id The project ID to cache the tasks for
     * @return string The cache key
     */
    public function getCacheKey($id)
    {
        return self::CACHE_KEY . '_' . $id;
    }

    /**
     * This method loads a project's tasks from the cache or
     * stores them if not available.
     *
     * @param TechDivision_AOP_Interfaces_JoinPoint $joinPoint
     * 		The actual JoinPoint
     * @return void
     */
    public function load(
        TechDivision_AOP_Interfaces_JoinPoint $joinPoint) {
        // load the Proxy instance
		$aspectable = $joinPoint
    		->getMethodInterceptor()
            ->getAspectContainer()
            ->getAspectable();
        // load the Application instance
        $app = TDProject_Factory::get();
        // load the project ID from the Request
        $projectId = $app->getRequest()
            ->getParameter(
                TDProject_Project_Controller_Util_WebRequestKeys::PROJECT_ID,
                FILTER_VALIDATE_INT
            );
        // initialize the cache key
        $cacheKey = $this->getCacheKey($projectId);
        // check if the tasks has already been cached
        if (($dto = $app->getCache()->load($cacheKey)) === false) {
            // let the Controller add the tasks to the request
            $joinPoint->proceed();
            // load the tasks from the request (added by the Controller)
            $dto = $app->getRequest()->getAttribute(
                TDProject_Project_Controller_Util_WebRequestKeys::VIEW_DATA
            );
            // store the tasks in the cache
            $app->getCache()->save($dto, $cacheKey);
            // return immediately
            return;
        }
		// register the project's tasks in the Request
		$app->getRequest()->setAttribute(
            TDProject_Project_Controller_Util_WebRequestKeys::VIEW_DATA,
            $dto
        );
        // set the ActionForward in the Context
        return $aspectable->forward(
            TDProject_Project_Controller_Logging_Task::TASK
        );
    }

    /**
     * This method deletes the project's tasks from the cache.
     *
     * @param TechDivision_AOP_Interfaces_JoinPoint $joinPoint
     * 		The actual JoinPoint
     * @return void
     */
    public function clean(
        TechDivision_AOP_Interfaces_JoinPoint $joinPoint) {
        // load the Proxy instance
		$aspectable = $joinPoint
    		->getMethodInterceptor()
            ->getAspectContainer()
            ->getAspectable();
        // load the Application instance
        $app = TDProject_Factory::get();
        // load the project ID to remove the task for from the Request
        $projectId = $app->getRequest()
            ->getParameter(
                TDProject_Project_Controller_Util_WebRequestKeys::PROJECT_ID,
                FILTER_VALIDATE_INT
            );
        // if project ID can't be found return
        if ($projectId == null) {
        	return;
        }
        // initialize the cache key
        $cacheKey = $this->getCacheKey($projectId);
        // remove the tasks of the project with the passed ID from the chache
        $app->getCache()->remove($cacheKey);
    }
}