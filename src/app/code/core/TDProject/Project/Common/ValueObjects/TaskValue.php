<?php

require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TechDivision/Model/Interfaces/Value.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskLightValue.php';
require_once "TDProject/Project/Common/ValueObjects/TaskLightValue.php";
require_once "TDProject/Project/Common/ValueObjects/TaskLightValue.php";
require_once "TDProject/Project/Common/ValueObjects/TaskTypeLightValue.php";


/**
 * This class is the data transfer object between the
 * model and the controller for the table task.
 *
 * Each class member reflects a database field and
 * the values of the related dataset.
 *
 * @package Common
 * @author generator <core@techdivision.com>
 * @version $Revision: 1.1 $ $Date: 2007-10-25 16:09:14 $
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 */
class TDProject_Project_Common_ValueObjects_TaskValue
	extends TDProject_Project_Common_ValueObjects_TaskLightValue
	implements TechDivision_Model_Interfaces_Value  {

	protected $_task;

	protected $_tasks;

	protected $_taskType;

	protected $_loggings;

	protected $_taskPerformances;

	/**
	 * The constructor intializes the DTO with the
	 * values passed as parameter.
	 *
	 * @param array $array Holds the array with the virtual members to pass to the AbstractDTO's constructor
	 * @return void
	 */
	public function __construct(
	    TDProject_Project_Common_ValueObjects_TaskValue $vo = null) {
		// call the parents constructor
		parent::__construct($vo);
		// initialize the Collection with the loggings and the task performances
		$this->_loggings = new TechDivision_Collections_ArrayList();
		$this->_taskPerformances = new TechDivision_Collections_ArrayList();
		// initialize the ValueObject with the passed data
		if (!empty($vo)) {
    		if (($related = $vo->getTask()) != null) {
    			$this->setTask($related->getLightValue());
    		}
    		$list = new TechDivision_Collections_ArrayList();
    		foreach ($vo->getTasks() as $dto) {
    			$list->add($dto->getLightValue());
    		}
    		$this->setTasks($list);
    		if (($related = $vo->getTaskType()) != null) {
    			$this->setTaskType($related->getLightValue());
    		}
			$taskPerformances = new TechDivision_Collections_ArrayList();
			foreach ($vo->getTaskPerformances() as $taskPerformance) {
				$taskPerformances->add($taskPerformance->getLightValue());
			}
    		$this->setTaskPerformances($taskPerformances);
		}
	}


	/**
	 * Sets the
	 *
	 * @param TDProject_Project_Common_ValueObjects_TaskLightValue Holds the
	 */
	public function setTask(
	    TDProject_Project_Common_ValueObjects_TaskLightValue $lvo = null) {
        $this->_task = $lvo;
	}

	/**
	 * Returns the
	 *
	 * @return TDProject_Project_Common_ValueObjects_TaskLightValue Holds the
	 */
	public function getTask()
	{
        return $this->_task;
	}

	/**
	 * Sets the
	 *
	 * @param TechDivision_Collections_Interfaces_Collection Holds the
	 */
	public function setTasks(
	    TechDivision_Collections_Interfaces_Collection $collection = null) {
        $this->_tasks = $collection;
	}

	/**
	 * Returns the
	 *
	 * @return TechDivision_Collections_Interfaces_Collection Holds the
	 */
	public function getTasks()
	{
        return $this->_tasks;
	}

	/**
	 * Sets the
	 *
	 * @param TDProject_Project_Common_ValueObjects_TaskTypeLightValue Holds the
	 */
	public function setTaskType(
	    TDProject_Project_Common_ValueObjects_TaskTypeLightValue $lvo = null) {
        $this->_taskType = $lvo;
	}

	/**
	 * Returns the
	 *
	 * @return TDProject_Project_Common_ValueObjects_TaskTypeLightValue Holds the
	 */
	public function getTaskType()
	{
        return $this->_taskType;
	}


	/**
	 * This method returns the LightValue
	 * version of this Value object.
	 *
	 * @return TDProject_Project_Common_ValueObjects_TaskLightValue
	 *  	The initialized LightValue
	 */
	public function getLightValue()
	{
		return new TDProject_Project_Common_ValueObjects_TaskLightValue($this);
	}

	public function setLoggings(
	    TDProject_Project_Common_ValueObjects_Collections_CumulatedLoggings $loggings) {
	    $this->_loggings = $loggings;
	}

	public function getLoggings()
	{
	    return $this->_loggings;
	}

	public function setTaskPerformances(
	    TechDivision_Collections_Interfaces_Collection $taskPerformances) {
	    $this->_taskPerformances = $taskPerformances;
	}

	public function getTaskPerformances()
	{
	    return $this->_taskPerformances;
	}

    public function getTotal()
    {
        $total = new TechDivision_Lang_Integer(0);

        foreach ($this->getTasks() as $task) {
            $total->add($task->getTotal());
        }

        // calculate the tasks total including the subtotals
        return $total->add($this->getSubtotal());
    }

    public function getSubtotal()
    {

        $subtotal = new TechDivision_Lang_Integer(0);

        foreach ($this->getLoggings() as $logging) {
            $subtotal->add($logging->getMinutes());
        }

       return $subtotal;
    }
}