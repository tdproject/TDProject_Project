<?php

/**
 * TDProject_Project_Model_Homes_TaskLocalHome
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * This class provides methods needed to
 * access the data from the database.
 *
 * @package Model
 * @author Tim Wagner <tw@techdivision.com>
 * @version $Revision: 1.2 $ $Date: 2008-03-04 14:58:01 $
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 */
class TDProject_Project_Model_Homes_TaskLocalHome extends DB_NestedSet_DB {

    /**
     * The Container instance to handle the database access.
     * @var TechDivision_Model_Interfaces_Container
     */
    protected $_container = null;

    /**
     * Constructor
     *
     * @param TechDivision_Model_Interfaces_Container The container instance
     * @param array $params Database column fields which should be returned
     * @return void
     */
    public function __construct(TechDivision_Model_Interfaces_Container $container, $params = array())
    {
	    // load the Container instance
	    $this->_setContainer($container);
        // initialize the driver
        parent::__construct(
            $this->_container->getMasterManager()->getDataSource()->getConnectionString(),
            $params
        );
		// set the sort mode
		$this->setSortMode(NESE_SORT_PREORDER);
    }

    /**
     * Sets the Container for handling the entities.
     *
     * @param TechDivision_Model_Interfaces_Container $container
     * 		The Container instance
     */
    protected function _setContainer(
        TechDivision_Model_Interfaces_Container $container) {
        $this->_container = $container;
    }

    /**
     * Returns the Container instance.
     *
     * @return TechDivision_Model_Interfaces_Container
     * 		The Container instance
     */
    public function getContainer()
    {
    	return $this->_container;
    }

    /**
     * (non-PHPdoc)
     * @see TechDivision_Model_Interfaces_LocalHome::getEntityAlias()
     */
    public function getEntityAlias()
    {
    	return 'TDProject_Project_Model_Entities_Task';
    }

    /**
     * (non-PHPdoc)
     * @see TechDivision_Model_Interfaces_LocalHome::getMappingAlias()
     */
    public function getMappingAlias()
    {
    	return 'TDProject_Project_Model_Mappings_TaskMapping';
    }

    /**
     * (non-PHPdoc)
     * @see TechDivision_Model_Interfaces_LocalHome::getCacheTags()
     */
    public function getCacheTags()
    {
    	return $this->epbCreate()->getCacheTags();
    }

    /**
     * @see DB_NestedSet::_secSort($nodeSet)
     */
    function _secSort($nodeSet)
    {
    	// nothing to do - empty tree
    	if (empty($nodeSet)) {
    		return $nodeSet;
    	}

    	$retArray = array();

    	/*
    	 * Bugfix to avoid unwanted notices
    	 * @author Tim Wagner
    	 * @date 2011-08-10
    	 */
    	foreach ($nodeSet AS $nodeID=>$node) {
    		if(is_object($node)) {
    			$parent = $node->l;
    		} else {
    			$parent = $node['l'];
    		}
    		$deepArray[$parent][$nodeID] = $node;
    	}

    	$reset = true;

    	foreach ($deepArray AS $parentID=>$children) {
    		$retArray = $this->_secSortCollect($children, $deepArray, $reset);
    		$reset = false;
   	 	}

    	return $retArray;
    }

    /**
     * This method returns a initialized Task entity.
     *
     * @param TechDivision_Lang_Integer $pk
     * 		Holds the primary key of the entity
     * @return TDProject_Project_Model_Entities_Task
     * 		The initialized task entity
     */
	public function findByPrimaryKey(TechDivision_Lang_Integer $pk)
	{
	    return $this->epbCreate()->load($pk->intValue());
	}

	/**
	 * Returns all tasks.
	 *
	 * @return TechDivision_Collection_ArrayList
	 * 		The ArrayList with the tasks
	 */
	public function findAll()
	{		
		// initialize a new cached collection for the tasks
		$list = new TDProject_Project_Model_Entities_Task_Collection($this->getContainer());
	    // iterate over all nodes and assemble the tasks
	    foreach ($this->getAllNodes() as $node) {
	        $task = new TDProject_Project_Model_Entities_Task();
	        $task->connect($this->getContainer());
	        $task->load($node->id);
	        $list->add($task);
	    }
	    // return the ArrayList with the tasks
	    return $list;
	}

	/**	
	 * Returns the tasks for the project with the passed ID recursively
	 * with all childs.
	 *
	 * @param TechDivision_Lang_Integer $projectIdFk
	 * 		The ID of the project to return the tasks for
	 * @return TDProject_Project_Model_Entities_Task_Collection
	 * 		The collection with the tasks
	 */
	public function findAllByProjectIdFkBranched(TechDivision_Lang_Integer $projectId)
	{		
		// initialize a new cached collection for the tasks
		$list = new TDProject_Project_Model_Entities_Task_Collection($this->getContainer());
		// load the task related with the passed project
	   	foreach ($this->findAllByProjectIdFk($projectId) as $task) {
			// iterate over the children and assemble the tasks
			if (is_array($children = $this->getBranch($task->getTaskId()->intValue()))) {
				foreach ($children as $node) {
		            $task = new TDProject_Project_Model_Entities_Task();
		            $task->connect($this->getContainer());
		            $task->load($node->id);
		            $list->add($task);
				}
			}
	   	}
		// return the ArrayList with the tasks
		return $list;
	}

	/**
	 * Returns the tasks (childs) with the passed task ID.
	 *
	 * @param TechDivision_Lang_Integer $taskIdFk
	 * 		The task ID to return the childs for
	 * @return TDProject_Project_Model_Entities_Task_Collection
	 * 		The collection with the tasks
	 */
	public function findAllByTaskIdFk(TechDivision_Lang_Integer $taskIdFk) 
	{	
		// initialize a new cached collection for the tasks
		$list = new TDProject_Project_Model_Entities_Task_Collection($this->getContainer());
		// iterate over the children and assemble the tasks
		if (is_array($children = $this->getChildren($taskIdFk->intValue()))) {
			foreach ($children as $node) {
	            $task = new TDProject_Project_Model_Entities_Task();
	            $task->connect($this->getContainer());
	            $task->load($node->id);
	            $list->add($task);
			}
		}
		// return the collection with the tasks
		return $list;
	}

	/**
	 * Returns the tasks with the passed project ID.
	 *
	 * @param TechDivision_Lang_Integer $projectIdFk
	 * 		The ID of the project to return the tasks for
	 * @return TDProject_Project_Model_Entities_Task_Collection
	 * 		The collection with the tasks
	 */
	public function findAllByProjectIdFk(TechDivision_Lang_Integer $projectIdFk) 
	{	
		// initialize a new cached collection for the tasks
		$list = new TDProject_Project_Model_Entities_Task_Collection($this->getContainer());
		// load the project-task relations
		$mappings = TDProject_Project_Model_Utils_ProjectTaskUtil::getHome($this->getContainer())
		    ->findAllByProjectIdFk($projectIdFk);
		// iterate over all relations, load and assemble the tasks
		foreach ($mappings as $mapping) {
            $list->add($this->findByPrimaryKey($mapping->getTaskIdFk()));
		}
		// return the collection with the tasks
		return $list;
	}

	/**
	 * Returns the tasks with the passed template ID.
	 *
	 * @return TDProject_Project_Model_Entities_Task_Collection
	 * 		The collection with the tasks
	 */
	public function findAllByTemplateIdFk(TechDivision_Lang_Integer $templateIdFk) 
	{	
		// initialize a new cached collection for the tasks
		$list = new TDProject_Project_Model_Entities_Task_Collection($this->getContainer());
		// load the template-task relations
		$mappings = TDProject_Project_Model_Utils_TemplateTaskUtil::getHome($this->getContainer())
		    ->findAllByTemplateIdFk($templateIdFk);
		// iterate over all relations, load and assemble the tasks
		foreach ($mappings as $mapping) {
            $list->add($this->findByPrimaryKey($mapping->getTaskIdFk()));
		}
		// return the ArrayList with the tasks
		return $list;
	}

	/**
	 * This method creates an empty Task entity and
	 * returns it.
	 *
	 * @return TDProject_Project_Model_Entities_Task
	 * 		Returns an empty Task entity
	 */
	public function epbCreate()
	{
		// create a new entity and return it
		return $this->getContainer()->register($this->getEntityAlias());
	}

    /**
     * This method creates a new Task object as root node.
     *
     * @param TaskLightValue Holds an LightValue object with the data
     * @return integer Returns the primary key of the new task
     */
    public function root(TDProject_Project_Common_ValueObjects_TaskLightValue $lvo)
    {
        // initialize the values
        $values = array(
            'task_type_id_fk' => $lvo->getTaskTypeIdFk()->intValue(),
            'name' => $lvo->getName()->stringValue(),
            'description' => $lvo->getDescription()->stringValue(),
            'billable' => $lvo->getBillable()->booleanValue(),
            'cost_center' => $lvo->getCostCenter()->intValue()
        );
        // create a root node
        $taskId = $this->createRootNode($values);
        if ($taskId === false) {
            throw new TDProject_Project_Model_Exceptions_TaskCreateException(
                'Error when trying to create root task'
            );
        }
        // return the ID of the created task
        return (int) $taskId;
    }

    /**
     * This method creates a new Task object as child node.
     *
     * @param TaskLightValue Holds an LightValue object with the data
     * @return integer Returns the primary key of the new task
     */
    public function create(TDProject_Project_Common_ValueObjects_TaskLightValue $lvo)
    {
        // initialize the values
        $values = array(
            'task_type_id_fk' => $lvo->getTaskTypeIdFk()->intValue(),
            'name' => $lvo->getName()->stringValue(),
            'description' => $lvo->getDescription()->stringValue(),
            'billable' => $lvo->getBillable()->booleanValue(),
            'cost_center' => $lvo->getCostCenter()->intValue()
        );
        // create the child node
        $taskId = $this->createSubNode($lvo->getTaskIdFk()->intValue(), $values);
        if ($taskId === false) {
            throw new TDProject_Project_Model_Exceptions_TaskCreateException(
                'Error when trying to create child task with ID ' . $pk->intValue()
            );
        }
        // return the ID of the created task
        return $taskId;
    }

    /**
     * This method updates a existing Task object.
     *
     * @param TaskLightValue Holds an LightValue object with the data
     * @return integer Returns the primary key of the updated object
     */
    public function update(TDProject_Project_Common_ValueObjects_TaskLightValue $lvo)
    {
        // initialize the values
        $values = array(
            'task_type_id_fk' => $lvo->getTaskTypeIdFk()->intValue(),
            'name' => $lvo->getName()->stringValue(),
            'description' => $lvo->getDescription()->stringValue(),
            'billable' => $lvo->getBillable()->booleanValue(),
            'cost_center' => $lvo->getCostCenter()->intValue()
        );
        // update the node
        if (!$this->updateNode($lvo->getTaskId()->intValue(), $values)) {
            throw new TDProject_Project_Model_Exceptions_TaskUpdateException(
                'Error when trying to update task with ID ' . $pk->intValue()
            );
        }
    }

    /**
     * This method deletes a existing Task object.
     *
     * @param integer The Id of the object that should be deleted
     * @return integer Returns the primary key of the updated object
     */
    public function delete(TechDivision_Lang_Integer $pk)
    {
        // delete the node
        if (!$this->deleteNode($pk->intValue())) {
            throw new TDProject_Project_Model_Exceptions_TaskDeleteException(
                'Error when trying to delete task with ID ' . $pk->intValue()
            );
        }
    }
}