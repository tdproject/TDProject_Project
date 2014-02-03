<?php

/**
 * TDProject_Project_Common_ValueObjects_EstimationViewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Integer.php';
require_once 'TechDivision/Collections/Interfaces/Collection.php';
require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TechDivision/Model/Interfaces/Value.php';
require_once 'TDProject/Project/Common/ValueObjects/EstimationValue.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the estimation view.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_EstimationViewData
	extends TDProject_Project_Common_ValueObjects_EstimationValue
    implements TechDivision_Model_Interfaces_Value {

    /**
     * The units available in the system.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_units = null;

    /**
     * The complexities available in the system.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_complexities = null;

    /**
     * The estimations for the Task.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_estimations = null;

    /**
     * The total average amount of time in minutes, based on
     * the estimations of the given task AND his child tasks.
     * @var TechDivision_Lang_Integer
     */
    protected $_average = null;

    /**
     * The total average amount of time in minutes,
     * based on the estimations of the given task.
     * @var TechDivision_Lang_Integer
     */
    protected $_subtotalAverage = null;

    /**
     * The constructor intializes the DTO with the
     * values passed as parameter.
     *
     * @param TDProject_Project_Model_Entities_Estimation $estimation
     * 		The estimation to initialize the DTO with
     * @return void
     */
    public function __construct(TDProject_Project_Model_Entities_Estimation $estimation = null)
    {
        // call the parents constructor
        parent::__construct($estimation);
        // initialize the ValueObject with the passed data
        $this->_units = new TechDivision_Collections_ArrayList();
        $this->_complexities = new TechDivision_Collections_ArrayList();
        $this->_estimations = new TechDivision_Collections_ArrayList();
        $this->_average = new TechDivision_Lang_Integer(0);
        $this->_subtotalAverage = new TechDivision_Lang_Integer(0);
    }

    /**
     * Sets the units available in the system.
     *
     * @param TechDivision_Collections_Interfaces_Collection $units
     * 		The units availble in the system
     * @return void
     */
    public function setUnits(
        TechDivision_Collections_Interfaces_Collection $units) {
        $this->_units = $units;
    }

    /**
     * Returns the units available in the system.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The units available in the system
     */
    public function getUnits()
    {
        return $this->_units;
    }

    /**
     * Sets the complexities available in the system.
     *
     * @param TechDivision_Collections_Interfaces_Collection $complexities
     * 		The complexities availble in the system
     * @return void
     */
    public function setComplexities(
        TechDivision_Collections_Interfaces_Collection $complexities) {
        $this->_complexities = $complexities;
    }

    /**
     * Returns the complexities available in the system.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The complexities available in the system
     */
    public function getComplexities()
    {
        return $this->_complexities;
    }

    /**
     * Sets the estimations for the Task.
     *
     * @param TechDivision_Collections_Interfaces_Collection $estimations
     * 		The estimations for the Task
     * @return void
     */
    public function setEstimations(
        TechDivision_Collections_Interfaces_Collection $estimations) {
        $this->_estimations = $estimations;
    }

    /**
     * Returns the estimations for the Task.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The estimations for the Task
     */
    public function getEstimations()
    {
        return $this->_estimations;
    }

    /**
     * Sets the average amount of time in minutes, based
     * on the estimations given by the task AND his child
     * tasks.
     *
     * @param TechDivision_Lang_Float $average
     * 		The average amount
     * @return void
     */
    public function setAverage(
    	TechDivision_Lang_Integer $average) {
        $this->_average = $average;
    }

    /**
     * The average amount of time in minutes, based
     * on the estimations given by the task AND his
     * child tasks.
     *
     * @return TechDivision_Lang_Integer
     * 		The average amount
     */
    public function getAverage()
    {
    	return $this->_average;
    }

    /**
     * Sets the total average amount of time in minutes,
     * based on the estimations given by the task.
     *
     * @param TechDivision_Lang_Integer $subtotalAverage
     * 		The total average amount
     * @return void
     */
    public function setSubtotalAverage(
    	TechDivision_Lang_Integer $subtotalAverage) {
        $this->_subtotalAverage = $subtotalAverage;
    }

    /**
     * The total average amount of time in minutes,
     * based on the estimations given by the task.
     *
     * @return TechDivision_Lang_Integer
     * 		The total average amount
     */
    public function getSubtotalAverage()
    {
    	return $this->_subtotalAverage;
    }
}