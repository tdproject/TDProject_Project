<?php

/**
 * TDProject_Project_Model_Services_DomainProcessor
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
class TDProject_Project_Model_Services_DomainProcessor
    extends TDProject_Project_Model_Services_AbstractDomainProcessor
{

	/**
	 * This method returns the logger of the requested
	 * type for logging purposes.
	 *
     * @param string The log type to use
	 * @return TechDivision_Logger_Logger Holds the Logger object
	 * @throws Exception Is thrown if the requested logger type is not initialized or doesn't exist
	 * @deprecated 0.6.34 - 2011/12/19
	 */
    protected function _getLogger(
        $logType = TechDivision_Logger_System::LOG_TYPE_SYSTEM)
    {
    	return $this->getLogger();
    }   
    
    /**
     * This method returns the logger of the requested
     * type for logging purposes.
     *
     * @param string The log type to use
     * @return TechDivision_Logger_Logger Holds the Logger object
     * @since 0.6.35 - 2011/12/19
     */
    public function getLogger(
    	$logType = TechDivision_Logger_System::LOG_TYPE_SYSTEM)
    {
    	return $this->getContainer()->getLogger();
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getProjectViewData(TechDivision_Lang_Integer $projectId = null)
     */
	public function getProjectViewData(
	    TechDivision_Lang_Integer $projectId = null)
	{
    	try {
    		// assemble and return the initialized DTO
    		return TDProject_Project_Model_Assembler_Project::create($this->getContainer())
    		    ->getProjectViewData($projectId);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getProjectOverviewData()
     */
	public function getProjectOverviewData()
	{
	    try {
    		// assemble and return the initialized LVO's
    		return TDProject_Project_Model_Assembler_Project::create($this->getContainer())
    		    ->getProjectOverviewData();
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#saveProject(TDProject_Project_Common_ValueObjects_ProjectLightValue $lvo, TechDivision_Lang_Integer $userId)
     */
	public function saveProject(
        TDProject_Project_Common_ValueObjects_ProjectLightValue $lvo,
        TechDivision_Lang_Integer $userId)
    {
		try {
			// begin the transaction
			$this->beginTransaction();
			// save the project and return the ID
			$projectId = TDProject_Project_Model_Actions_Project::create($this->getContainer())
				->saveProject($lvo, $userId);
			// commit the transaction
			$this->commitTransaction();
			// return the project ID
			return $projectId;
		} catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#deleteProject(TechDivision_Lang_Integer $projectId)
     */
    public function deleteProject(TechDivision_Lang_Integer $projectId)
    {
        try {
            // start the transaction
            $this->beginTransaction();
            // load the project
            $project = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
                ->findByPrimaryKey($projectId);
            // set the deleted flag
            $project->setDeleted(new TechDivision_Lang_Integer(1));
            // update the project
            $project->update();
            // commit the transcation
            $this->commitTransaction();
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getTaskViewData(TechDivision_Lang_Integer $taskId = null)
     */
	public function getTaskViewData(
	    TechDivision_Lang_Integer $taskId = null)
    {
    	try {
    		// assemble and return the initialized DTO
    		return TDProject_Project_Model_Assembler_Task::create($this->getContainer())
    		    ->getTaskViewData($taskId);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#saveTask(TDProject_Project_Common_ValueObjects_TaskValue $vo)
     */
	public function saveTask(
        TDProject_Project_Common_ValueObjects_TaskValue $vo)
    {
		try {
			// begin the transaction
			$this->beginTransaction();
			// save the task and return the ID
			$taskId = TDProject_Project_Model_Actions_Task::create($this->getContainer())
				->saveTask($vo);
			// commit the transaction
			$this->commitTransaction();
			// return the task ID
			return $taskId;
		} catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#deleteTask(TechDivision_Lang_Integer $taskId)
     */
    public function deleteTask(TechDivision_Lang_Integer $taskId)
    {
        try {
            // start the transaction
            $this->beginTransaction();
            //delete task
            TDProject_Project_Model_Actions_Task::create($this->getContainer())
                ->deleteTask($taskId);
            // commit the transcation
            $this->commitTransaction();
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#saveTaskUser(TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo)
     */
	public function saveTaskUser(
        TDProject_Project_Common_ValueObjects_TaskUserLightValue $lvo)
    {
		try {
			// begin the transaction
			$this->beginTransaction();
			// save the logging entry
			$taskUserId = TDProject_Project_Model_Actions_Logging::create($this->getContainer())
				->saveTaskUser($lvo);
			// commit the transaction
			$this->commitTransaction();
			// return the ID of the task-user relation
			return $taskUserId;
		} catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getTemplateViewData(TechDivision_Lang_Integer $templateId = null)
     */
	public function getTemplateViewData(
	    TechDivision_Lang_Integer $templateId = null)
	{
    	try {
    		// assemble and return the initialized DTO
    		return TDProject_Project_Model_Assembler_Template::create($this->getContainer())
    		    ->getTemplateViewData($templateId);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getTemplateverviewData()
     */
	public function getTemplateOverviewData()
	{
	    try {
    		// assemble and return the initialized LVO's
    		return TDProject_Project_Model_Assembler_Template::create($this->getContainer())
    		    ->getTemplateLightValues();
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#saveTemplate(TDProject_Project_Common_ValueObjects_TemplateLightValue $lvo)
     */
	public function saveTemplate(
        TDProject_Project_Common_ValueObjects_TemplateLightValue $lvo)
    {
		try {
			// begin the transaction
			$this->beginTransaction();
			// lookup template ID
			$templateId = $lvo->getTemplateId();
			// store the template
			if ($templateId->equals(new TechDivision_Lang_Integer(0))) {
	            // create a new template
				$template = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
				    ->epbCreate();
				// set the data
				$template->setName($lvo->getName());
				$template->setDescription($lvo->getDescription());
				$templateId = $template->create();
				// initialize, fill and create the root task of the template
				$task = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
				    ->epbCreate();
				$task->setTaskIdFk(null);
				$task->setName(new TechDivision_Lang_String('Root'));
				$task->setDescription($lvo->getName());
				$task->setTaskTypeIdFk(new TechDivision_Lang_Integer(1));
				$task->setBillable(new TechDivision_Lang_Boolean(true));
				$taskId = $task->root();
				// initialize, fill and save the template-task relation
				$relation = TDProject_Project_Model_Utils_TemplateTaskUtil::getHome($this->getContainer())
				    ->epbCreate();
				$relation->setTemplateIdFk($templateId);
				$relation->setTaskIdFk($taskId);
				$relation->create();
			} else {
			    // update the template
				$template = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
				    ->findByPrimaryKey($templateId);
				$template->setName($lvo->getName());
				$template->setDescription($lvo->getDescription());
				$template->update();
			}
			// commit the transaction
			$this->commitTransaction();
			// return the template ID
			return $templateId;
		} catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#deleteTemplate(TechDivision_Lang_Integer $templateId)
     */
    public function deleteTemplate(TechDivision_Lang_Integer $templateId)
    {
        try {
            // start the transaction
            $this->beginTransaction();
            // load the project
            $template = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
                ->findByPrimaryKey($templateId);
            // set the deleted flag
            $template->setDeleted(new TechDivision_Lang_Integer(1));
            // update the template
            $template->update();
            // commit the transcation
            $this->commitTransaction();
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getLoggingViewData(TechDivision_Lang_Integer $userId, TechDivision_Lang_Integer $taskUserId = null)
     */
	public function getLoggingViewData(
	    TechDivision_Lang_Integer $userId,
	    TechDivision_Lang_Integer $taskUserId = null)
	{
    	try {
    		// assemble and return the initialized DTO
    		return TDProject_Project_Model_Assembler_Logging::create($this->getContainer())
    		    ->getLoggingViewData($userId, $taskUserId);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#deleteTaskUser(TechDivision_Lang_Integer $taskUserId)
     */
    public function deleteTaskUser(TechDivision_Lang_Integer $taskUserId)
    {
        try {
            // start the transaction
            $this->beginTransaction();
            // load the logging and update the task performance
            TDProject_Project_Model_Actions_Logging::create($this->getContainer())
                ->deleteTaskUser($taskUserId);
            // commit the transcation
            $this->commitTransaction();
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getLoggingOverviewData(TDProject_Core_Common_ValueObjects_QueryParameterData $dto)
     */
	public function getLoggingOverviewData(
		TDProject_Core_Common_ValueObjects_QueryParameterData $dto)
	{
    	try {
    		// assemble and return the initialized DTO
    		return TDProject_Project_Model_Assembler_Logging::create($this->getContainer())
    		    ->getLoggingOverviewData($dto);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getTaskOverviewDataByProjectId(TechDivision_Lang_Integer $projectId)
     */
	public function getTaskOverviewDataByProjectId(
	    TechDivision_Lang_Integer $projectId)
	{
    	try {
    		// assemble and return the initialized DTO
    		return TDProject_Project_Model_Assembler_Task::create($this->getContainer())
    		    ->getTaskOverviewDataByProjectId($projectId);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getEstimationViewData(TechDivision_Lang_Integer $taskId = null)
     */
	public function getEstimationViewData(
	    TechDivision_Lang_Integer $taskIdFk = null)
	{
    	try {
    		// assemble and return the initialized DTO
    		return TDProject_Project_Model_Assembler_Estimation::create($this->getContainer())
    		    ->getEstimationViewData($taskIdFk);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#saveEstimation(TDProject_Project_Common_ValueObjects_EstimationLightValue $lvo)
     */
	public function saveEstimation(
        TDProject_Project_Common_ValueObjects_EstimationLightValue $lvo)
    {
		try {
			// begin the transaction
			$this->beginTransaction();
			// save the estimation
			$estimationId = TDProject_Project_Model_Actions_Estimation::create($this->getContainer())
				->saveEstimation($lvo);
			// commit the transaction
			$this->commitTransaction();
			// return the estimation ID
			return $estimationId;
		} catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

    /**
     * (non-PHPdoc)
     * @see TDProject/ERP/Common/Delegates/Interfaces/DomainProcessorDelegate#relateProjectUser(TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo)
     */
    public function relateProjectUser(
        TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo)
    {
        try {
            // begin the transaction
            $this->beginTransaction();
            // create the relation
			TDProject_Project_Model_Actions_ProjectUser::create($this->getContainer())
				->createAndAllow($lvo);
            // commit the transaction
            $this->commitTransaction();
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

    /**
     * (non-PHPdoc)
     * @see TDProject/ERP/Common/Delegates/Interfaces/DomainProcessorDelegate#unrelateProjectUser(TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo)
     */
    public function unrelateProjectUser(
        TDProject_Project_Common_ValueObjects_ProjectUserLightValue $lvo)
    {
        try {
            // begin the transaction
            $this->beginTransaction();
            // delete the relation
			TDProject_Project_Model_Actions_ProjectUser::create($this->getContainer())
				->deleteAndDeny($lvo);
            // commit the transaction
            $this->commitTransaction();
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getLoggingOverviewDataByProjectId(TDProject_Core_Common_ValueObjects_QueryParameterData $dto)
     */
	public function getLoggingOverviewDataByProjectId(
		TDProject_Core_Common_ValueObjects_QueryParameterData $dto) 
	{
    	try {
    		// assemble and return the initialized DTO
    		return TDProject_Project_Model_Assembler_Logging::create($this->getContainer())
    		    ->getLoggingOverviewDataByProjectId($dto);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
	}

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#reorgProject(TechDivision_Lang_Integer $projectId)
     */
    public function reorgProject(TechDivision_Lang_Integer $projectId)
    {
        try {
            // start the transaction
            $this->beginTransaction();
            // reorganize the project
            TDProject_Project_Model_Actions_Project::create($this->getContainer())
            	->reorg($projectId);
            // commit the transcation
            $this->commitTransaction();
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#exportProjectCalculation(TechDivision_Lang_Integer $projectId, TechDivision_Lang_Integer $userId)
     */
    public function exportProjectCalculation(
    	TechDivision_Lang_Integer $projectId,
    	TechDivision_Lang_Integer $userId)
    {
        try {
            // start the transaction
            $this->beginTransaction();
            // export the project's tasks for calculations as Excel Sheet
            $calculation = TDProject_Project_Model_Actions_Project_CalculationExport::create($this->getContainer())
                ->export($projectId, $userId);
            // commit the transcation
            $this->commitTransaction();
            // return the calculation data
            return $calculation;
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#deleteProjectCycle(TechDivision_Lang_Integer $projectCycleId)
     */
    public function deleteProjectCycle(TechDivision_Lang_Integer $projectCycleId)
    {
        try {
            // start the transaction
            $this->beginTransaction();
            // load and deleete the project cycle
            TDProject_Project_Model_Utils_ProjectCycleUtil::getHome($this->getContainer())
                ->findByPrimaryKey($projectCycleId)
	            ->delete();
            // commit the transcation
            $this->commitTransaction();
        } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

    /**
     * (non-PHPdoc)
     * @see TDProject/Project/Common/Delegates/Interfaces/DomainProcessorDelegate#getSearchViewData(TechDivision_Collections_HashMap $params)
     */
    public function getSearchViewData(TechDivision_Collections_HashMap $params)
    {
        return TDProject_Project_Model_Assembler_Task_Solr::create($this->getContainer())
            ->getSearchViewData($params);
    }
    
}