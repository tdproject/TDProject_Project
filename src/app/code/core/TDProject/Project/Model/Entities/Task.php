<?php

/**
 * TDProject_Project_Model_Entities_Task
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * This class represents an object oriented way to manipulate data in the database.
 *
 * @package Model
 * @author Tim Wagner <tw@techdivision.com>
 * @version $Revision: 1.2 $ $Date: 2008-03-04 14:58:01 $
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 */
class TDProject_Project_Model_Entities_Task
	extends TDProject_Project_Common_ValueObjects_TaskValue
	implements TechDivision_Model_Interfaces_Entity
{

	/**
     * Holds the container instance with the manager.
     * @var Container
     */
    protected $_container = null;

	/**
     * Standardconstructor that initializes the database manager.
     *
     */
	public function __construct()
	{
	    // initialize the values
		$this->setTaskId(new TechDivision_Lang_Integer(0));
		$this->setTaskIdFk(new TechDivision_Lang_Integer(0));
		$this->setTaskTypeIdFk(new TechDivision_Lang_Integer(0));
		$this->setLeft(new TechDivision_Lang_Integer(0));
		$this->setRight(new TechDivision_Lang_Integer(0));
		$this->setLevel(new TechDivision_Lang_Integer(0));
		$this->setOrderNumber(new TechDivision_Lang_Integer(0));
		$this->setName(new TechDivision_Lang_String());
		$this->setDescription(new TechDivision_Lang_String());
		$this->setBillable(new TechDivision_Lang_Boolean(false));
		$this->setCostCenter(new TechDivision_Lang_Integer(0));
	}
		
	/**
	 * Disconnects the entiy bean from the referenced container.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		$this->disconnect();
	}
		
	/**
	 * @see TechDivision_Model_Interfaces_Bean::getClass()
	 */
	public function getClass()
	{
		return get_class($this);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Bean::connect()
	 */
	public function connect(
		TechDivision_Model_Interfaces_Container $container)
	{
		$this->_container = $container;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Bean::disconnect()
	 */
	public function disconnect()
	{
		$this->_container = null;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Bean::getContainer()
	 */
	public function getContainer()
	{
	 	return $this->_container;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Entity::getEntityAlias()
	 */
	public function getEntityAlias()
	{
		return 'TDProject_Project_Model_Entities_Task';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Entity::getMappingAlias()
	 */
	public function getMappingAlias()
	{
		return;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Entity::getPrimaryKey()
	 */
    public function getPrimaryKey()
    {
        return $this->_taskId;
    }
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Bean::getCacheKey()
	 */
    public function getCacheKey()
    {
    	return $this->getPrimaryKey();
    }
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Bean::getCacheTags()
	 */
    public function getCacheTags()
    {
    	return array(strtolower($this->getEntityAlias()));
    }

    /**
     * Populates the instance with the data of the
     * passed nested set node.
     *
     * @param DB_NestedSet_Node $node
     * 		The node with the data to initialize the instance with
     */
    protected function _populate(DB_NestedSet_Node $node)
    {
        $this->setTaskId(
            TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String($node->id)
            )
        );
        $this->setTaskIdFk(
            TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String($node->rootid)
            )
        );
        $this->setLeft(
            TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String($node->l)
            )
        );
        $this->setRight(
            TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String($node->r)
            )
        );
        $this->setOrderNumber(
            TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String($node->norder)
            )
        );
        $this->setLevel(
            TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String($node->level)
            )
        );
        if (!empty($node->taskTypeIdFk)) {
            $this->setTaskTypeIdFk(
                TechDivision_Lang_Integer::valueOf(
                    new TechDivision_Lang_String($node->taskTypeIdFk)
                )
            );
        }
        $this->setName(new TechDivision_Lang_String($node->name));
        $this->setDescription(new TechDivision_Lang_String($node->description));
        $this->setBillable(new TechDivision_Lang_Boolean($node->billable));
        $this->setCostCenter(
            TechDivision_Lang_Integer::valueOf(
                new TechDivision_Lang_String($node->costCenter)
            )
        );
        // return the instance
        return $this;
    }

	/**
	 * Returns the parent task.
	 *
	 * @return TDProject_Project_Model_Entities_Task
	 * 		Holds The parent task if available
	 */
	public function getTask() {
		// if member is null return
		if ($this->_taskIdFk == null) {
			return null;
		}
		// if member has value 0
		if ($this->_taskIdFk->intValue() === 0) {
			return null;
		}
		// else get the related data and return it
        return TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
            ->findByPrimaryKey($this->_taskIdFk);
	}

	/**
	 * Returns the nodes children.
	 *
	 * @return TechDivision_Collections_ArrayList
	 * 		The ArrayList with the children
	 */
	public function getTasks() {
		// if member is null return
		if ($this->_taskId->intValue() === 0) {
			return new TechDivision_Collections_ArrayList();
		}
		return TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
		    ->findAllByTaskIdFk($this->_taskId);
	}

	/**
	 * Returns the task's type.
	 *
	 * @return TDProject_Project_Model_Entities_TaskType
	 * 		The task's type
	 */
	public function getTaskType() {
	    // check if a task type is set
	    if (empty($this->_taskTypeIdFk)) {
	        return null;
	    }
		// if member is null return
		if ($this->_taskTypeIdFk->intValue() === 0) {
			return null;
		}
		// else get the related data and return it
        return TDProject_Project_Model_Utils_TaskTypeUtil::getHome($this->getContainer())
            ->findByPrimaryKey($this->_taskTypeIdFk);
	}

	/**
	 * Returns the task's performance.
	 *
	 * @return TechDivision_Collection_ArrayList
	 * 		The Collection with the task's performances
	 */
	public function getTaskPerformances()
	{
		// return the Collection with the task's performances
        return TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
            ->findAllByTaskIdFk($this->_taskId);
	}

	/**
	 * Returns the TDProject_Project_Common_ValueObjects_TaskLightValue object.
	 *
	 * @return TDProject_Project_Common_ValueObjects_TaskLightValue
	 * 		Holds the LightValue object with the data
	 */
	public function getLightValue() {
		return new TDProject_Project_Common_ValueObjects_TaskLightValue($this);
	}

	/**
	 * Returns the TDProject_Project_Common_ValueObjects_TaskValue object.
	 *
	 * @param boolean $refresh
	 * 		Holds the flag to identified, that the data should be refreshed from the database
	 * @return TDProject_Project_Common_ValueObjects_TaskValue
	 * 		Holds the Value object with the data
	 */
	public function getValue() {
		return new TDProject_Project_Common_ValueObjects_TaskValue($this);
	}

    /**
     * @see TechDivision_Model_Interfaces_Entity::load($pk)
     */
    public function load($pk)
    {
    	
    	if ($pk instanceof TechDivision_Lang_Integer) {
    		$pk = $pk->intValue();
    	}
    	
    	// initialize the local home
    	$localHome = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer());
		// throw an exception if the node can't be loaded
    	if (($node = $localHome->pickNode($pk)) === false) {
            throw new TDProject_Project_Model_Exceptions_TaskFindException("Can't load task with ID $pk");
    	}
    	// load the node
        return $this->_populate($node);
    }

    /**
     * @see TechDivision_Model_Interfaces_Entity::create()
     */
    public function root()
    {
    	// initialize the local home
    	$localHome = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer());
        $this->setTaskId(
            new TechDivision_Lang_Integer($localHome->root($this))
        );
        return $this->getTaskId();
    }

    /**
     * @see TechDivision_Model_Interfaces_Entity::create()
     */
    public function create()
    {
    	// initialize the local home
    	$localHome = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer());
        $this->setTaskId(
            new TechDivision_Lang_Integer($localHome->create($this))
        );
        return $this->getTaskId();
    }

    /**
     * @see TechDivision_Model_Interfaces_Entity::update()
     */
    public function update()
    {
    	TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
    		->update($this);
    }

    /**
     * @see TechDivision_Model_Interfaces_Entity::delete()
     */
    public function delete() {
        TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
        	->delete($this->getTaskId());
    }
}