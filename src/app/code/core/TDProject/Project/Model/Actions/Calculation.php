<?php

/**
 * TDProject_Project_Model_Actions_Calculation
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
class TDProject_Project_Model_Actions_Calculation
    extends TDProject_Core_Model_Actions_Abstract {

    /**
     * The units that can be selected with their values as seconds.
     * @var array
     */
    protected $_units = array(
    	TDProject_Project_Common_Enums_Unit::HOUR 	=> 3600,
    	TDProject_Project_Common_Enums_Unit::DAY 	=> 28800,
    	TDProject_Project_Common_Enums_Unit::WEEK 	=> 144000,
    	TDProject_Project_Common_Enums_Unit::MONTH 	=> 576000
    );

    /**
     * The complexities that can be selected.
     * @var array
     */
    protected $_complexities = array(
    	TDProject_Project_Common_Enums_Complexity::EASY 	=> 1,
    	TDProject_Project_Common_Enums_Complexity::MEDIUM	=> 1.5,
    	TDProject_Project_Common_Enums_Complexity::HIGH 	=> 2
    );

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Actions_Calculation($container);
    }

    /**
     * Calculates the estimation for the passed data and
     * saves it.
     *
     * @param TDProject_Project_Common_ValueObjects_EstimationLightValue $lvo
     * 		The LVO with the data to save
     * @return TechDivision_Lang_Integer
     * 		The ID of the saved estimation
     */
    public function calculate(
    	TDProject_Project_Common_ValueObjects_EstimationLightValue $lvo) {
   		// cast the given values to the base unity (minutes)
    	$unit = $this->_units[$lvo->getUnit()->stringValue()];
    	$complexity = $this->_complexities[$lvo->getComplexity()->stringValue()];
    	$quantity = $lvo->getQuantity()->intValue();
    	// calculation the values
    	$dmin =  $unit * $complexity * $quantity;
    	$dnorm = $dmin + (($complexity * $dmin) / 2);
    	$dmax = $dmin + $dnorm;
    	$davg = ($dmin + 4 * $dnorm + $dmax) / 6;
    	// store the values in the passed LVO
        $lvo->setMinimum(new TechDivision_Lang_Integer((int) $dmin));
        $lvo->setNormal(new TechDivision_Lang_Integer((int) $dnorm));
        $lvo->setMaximum(new TechDivision_Lang_Integer((int) $dmax));
        $lvo->setAverage(new TechDivision_Lang_Integer((int) $davg));
    }

    /**
     * Calculates the total average of time estimated for
     * the task with the passed ID AND his subtasks.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the total average for
     * @return integer The total average time estatimated
     * @deprecated Since 0.6.9
     * @see TDProject_Project_Model_Actions_Calculation::calculateTotals(TechDivision_Lang_Integer $taskId)
     */
    public function totalAverage(TechDivision_Lang_Integer $taskId)
    {
		// initialize the total average
		$totalAverage = 0;
		// load the childs
		$task = TDProject_Project_Model_Assembler_Task::create($this->getContainer())
			->getTaskViewData($taskId);
		// add the subtotal average for the given task
		$totalAverage += $this->subtotalAverage($taskId);
		// check if child tasks are available
		foreach ($task->getTasks() as $tsk) {
			// add the total average of each child task
			$totalAverage += $this->totalAverage($tsk->getTaskId());
		}
		// return the total average to the task
		return $totalAverage;
    }

    /**
     * Calculates and returns the subtotal average of time estimated for
     * the task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the subtotal average for
     * @return integer
     * 		The subtotal average time estatimated
     * @deprecated Since 0.6.9
     * @see TDProject_Project_Model_Actions_Calculation::calculateSubtotals(TechDivision_Lang_Integer $taskId)
     */
    public function subtotalAverage(TechDivision_Lang_Integer $taskId)
    {
	    // load the estimations
		$estimations = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
			->findAllByTaskIdFk($taskId);
		// initialize the subtotal
		$subtotal = 0;
		// add the subtotal
		foreach ($estimations as $estimation) {
			// and calculate the total average
			$subtotal += $estimation->getAverage()->intValue();
		}
		// subtract the subtotal by the given estimations
		if (($estimationsMade = $estimations->size()) > 0) {
		    return (int) round($subtotal / $estimationsMade);
		}
        // return zero if no estimations was found
		return $estimationsMade;
    }

    /**
     * Calculates the total minimum of time estimated for
     * the task with the passed ID AND his subtasks.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the total minimum for
     * @return integer The total minimum time estatimated
     * @deprecated Since 0.6.9
     * @see TDProject_Project_Model_Actions_Calculation::calculateTotals(TechDivision_Lang_Integer $taskId)
     */
    public function totalMinimum(TechDivision_Lang_Integer $taskId)
    {
		// initialize the total minimum
		$totalMinimum = 0;
		// load the childs
		$task = TDProject_Project_Model_Assembler_Task::create($this->getContainer())
			->getTaskViewData($taskId);
		// add the subtotal minimum for the given task
		$totalMinimum += $this->subtotalMinimum($taskId);
		// check if child tasks are available
		foreach ($task->getTasks() as $tsk) {
			// add the total average of each child task
			$totalMinimum += $this->totalMinimum($tsk->getTaskId());
		}
		// return the total minimum to the task
		return $totalMinimum;
    }

    /**
     * Calculates and returns the subtotal minimum of time estimated for
     * the task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the subtotal minimum for
     * @return integer
     * 		The subtotal minimum time estatimated
     * @deprecated Since 0.6.9
     * @see TDProject_Project_Model_Actions_Calculation::calculateSubtotals(TechDivision_Lang_Integer $taskId)
     */
    public function subtotalMinimum(TechDivision_Lang_Integer $taskId)
    {
	    // load the estimations
		$estimations = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
			->findAllByTaskIdFk($taskId);
		// initialize the subtotal
		$subtotal = 0;
		// add the subtotal
		foreach ($estimations as $estimation) {
			// and calculate the total minimum
			$subtotal += $estimation->getMinimum()->intValue();
		}
		// subtract the subtotal by the given estimations
		if (($estimationsMade = $estimations->size()) > 0) {
    		return (int) round($subtotal / $estimationsMade);
		}
        // return zero if no estimations was found
		return $estimationsMade;
    }

    /**
     * Calculates the total normal of time estimated for
     * the task with the passed ID AND his subtasks.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the total normal for
     * @return integer The total normal time estatimated
     * @deprecated Since 0.6.9
     * @see TDProject_Project_Model_Actions_Calculation::calculateTotals(TechDivision_Lang_Integer $taskId)
     */
    public function totalNormal(TechDivision_Lang_Integer $taskId)
    {
		// initialize the total normal
		$totalNormal = 0;
		// load the childs
		$task = TDProject_Project_Model_Assembler_Task::create($this->getContainer())
			->getTaskViewData($taskId);
		// add the subtotal normal for the given task
		$totalNormal += $this->subtotalNormal($taskId);
		// check if child tasks are available
		foreach ($task->getTasks() as $tsk) {
			// add the total normal of each child task
			$totalNormal += $this->totalNormal($tsk->getTaskId());
		}
		// return the total normal to the task
		return $totalNormal;
    }

    /**
     * Calculates and returns the subtotal normal of time estimated for
     * the task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the subtotal normal for
     * @return integer
     * 		The subtotal normal time estatimated
     * @deprecated Since 0.6.9
     * @see TDProject_Project_Model_Actions_Calculation::calculateSubtotals(TechDivision_Lang_Integer $taskId)
     */
    public function subtotalNormal(TechDivision_Lang_Integer $taskId)
    {
	    // load the estimations
		$estimations = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
			->findAllByTaskIdFk($taskId);
		// initialize the subtotal
		$subtotal = 0;
		// add the subtotal
		foreach ($estimations as $estimation) {
			// and calculate the total normal
			$subtotal += $estimation->getNormal()->intValue();
		}
		// subtract the subtotal by the given estimations
		if (($estimationsMade = $estimations->size()) > 0) {
		    return (int) round($subtotal / $estimationsMade);
		}
        // return zero if no estimations was found
		return $estimationsMade;
    }

    /**
     * Calculates the total maximum of time estimated for
     * the task with the passed ID AND his subtasks.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the total normal for
     * @return integer The total maximum time estatimated
     * @deprecated Since 0.6.9
     * @see TDProject_Project_Model_Actions_Calculation::calculateTotals(TechDivision_Lang_Integer $taskId)
     */
    public function totalMaximum(TechDivision_Lang_Integer $taskId)
    {
		// initialize the total maximum
		$totalMaximum = 0;
		// load the childs
		$task = TDProject_Project_Model_Assembler_Task::create($this->getContainer())
			->getTaskViewData($taskId);
		// add the subtotal maximum for the given task
		$totalMaximum += $this->subtotalMaximum($taskId);
		// check if child tasks are available
		foreach ($task->getTasks() as $tsk) {
			// add the total maximum of each child task
			$totalMaximum += $this->totalMaximum($tsk->getTaskId());
		}
		// return the total maximum to the task
		return $totalMaximum;
    }

    /**
     * Calculates and returns the subtotal maximum of time estimated for
     * the task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the subtotal maximum for
     * @return integer
     * 		The subtotal maximum time estatimated
     * @deprecated Since 0.6.9
     * @see TDProject_Project_Model_Actions_Calculation::calculateSubtotals(TechDivision_Lang_Integer $taskId)
     */
    public function subtotalMaximum(TechDivision_Lang_Integer $taskId)
    {
	    // load the estimations
		$estimations = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
			->findAllByTaskIdFk($taskId);
		// initialize the subtotal
		$subtotal = 0;
		// add the subtotal
		foreach ($estimations as $estimation) {
			// and calculate the total maximum
			$subtotal += $estimation->getMaximum()->intValue();
		}
		// subtract the subtotal by the given estimations
		if (($estimationsMade = $estimations->size()) > 0) {
			return (int) round($subtotal / $estimationsMade);
		}
		// return zero if no estimations was found
		return $estimationsMade;
    }

    /**
     * Calculates and returns the subtotal maximum of time estimated for
     * the task with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the subtotal maximum for
     * @return integer
     * 		The subtotal maximum time estatimated
     */
    public function calculateSubtotals(TechDivision_Lang_Integer $taskId)
    {
    	// initialize a StdClass instance
    	$subtotals = new StdClass();
    	// initialize the members
    	$minimum = 0;
    	$normal = 0;
    	$maximum = 0;
    	$average = 0;
    	// initialize the subtotals
    	$subtotals->minimum = $minimum;
    	$subtotals->normal = $normal;
    	$subtotals->maximum = $maximum;
    	$subtotals->average = $average;
    	// load the estimations
    	$estimations = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
    		->findAllByTaskIdFk($taskId);
    	// add the subtotals
    	foreach ($estimations as $estimation) {
    		// and calculate the subtotals
    		$minimum += $estimation->getMinimum()->intValue();
    		$normal += $estimation->getNormal()->intValue();
    		$maximum += $estimation->getMaximum()->intValue();
    		$average += $estimation->getAverage()->intValue();
    	}
    	// subtract the subtotal by the given estimations
	    if (($estimationsMade = $estimations->size()) > 0) {
	    	// add the calculated subtotals
	    	$subtotals->minimum += (int) round($minimum / $estimationsMade);
	    	$subtotals->normal += (int) round($normal / $estimationsMade);
	    	$subtotals->maximum += (int) round($maximum / $estimationsMade);
	    	$subtotals->average += (int) round($average / $estimationsMade);
	    }
	    // return empty values if no estimations was found
	    return $subtotals;
    }

    /**
     * Calculates the totals of time estimated for the task with the
     * passed ID AND his subtasks.
     *
     * @param TechDivision_Lang_Integer $taskId
     * 		The ID of the task to calculate the totals for
     * @return integer The totals time estatimated
     */
    public function calculateTotals(TechDivision_Lang_Integer $taskId)
    {
    	// initialize a StdClass instance
    	$totals = new StdClass();
    	// initialize the totals
    	$totals->minimum = 0;
    	$totals->normal = 0;
    	$totals->maximum = 0;
    	$totals->average = 0;
    	// load the childs
    	$task = TDProject_Project_Model_Assembler_Task::create($this->getContainer())
    		->getTaskViewData($taskId);
    	// calculate the subtotals
    	$subtotals = $this->calculateSubtotals($taskId);
		// add the subtotals for the given task
    	$totals->minimum += $subtotals->minimum;
    	$totals->normal += $subtotals->normal;
    	$totals->maximum += $subtotals->maximum;
    	$totals->average += $subtotals->average;
    	// check if child tasks are available
    	foreach ($task->getTasks() as $tsk) {
		    // calculate the childs totals
    		$calculated = $this->calculateTotals($tsk->getTaskId());
		    // add the totals of each child task
		    $totals->minimum += $calculated->minimum;
		    $totals->normal += $calculated->normal;
		    $totals->maximum += $calculated->maximum;
		    $totals->average += $calculated->average;
	    }
	    // return the totals to the task
	    return $totals;
    }
}