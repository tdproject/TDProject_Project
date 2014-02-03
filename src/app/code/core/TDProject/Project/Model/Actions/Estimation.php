<?php

/**
 * TDProject_Project_Model_Actions_Estimation
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
class TDProject_Project_Model_Actions_Estimation
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
        return new TDProject_Project_Model_Actions_Estimation($container);
    }

    /**
     * Calculates the estimation for the passed data and
     * saves it.
     *
     * @param TDProject_Project_Common_ValueObjects_EstimationLightValue $lvo
     * 		The LVO with the data to save
     * @return TechDivision_Lang_Integer
     * 		The ID of the saved estimation
     * @throws TDProject_Project_Common_Exceptions_EstimationUniqueException
     * 		Is thrown if the already has made an estimation for the task
     */
    public function saveEstimation(
    	TDProject_Project_Common_ValueObjects_EstimationLightValue $lvo) {
		// lookup estimation ID
		$estimationId = $lvo->getEstimationId();
		// check if already an estimation of the user for the task exists
		$estimations = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
			->findAllByTaskIdFkAndUserIdFk(
				$taskIdFk = $lvo->getTaskIdFk(),
				$userIdFk = $lvo->getUserIdFk()
			);
		// if yes, throw an exception
		if ($estimations->size() > 0) {
			throw new TDProject_Project_Common_Exceptions_EstimationUniqueException(
				"Estimation for task $taskIdFk and user $userIdFk already exists"
			);
		}
		// calculate the estimation's values
		TDProject_Project_Model_Actions_Calculation::create($this->getContainer())
			->calculate($lvo);
		// store the task
		if ($estimationId->equals(new TechDivision_Lang_Integer(0))) {
            // create a new estimation
			$estimation = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
				->epbCreate();
			// set the data
	        $estimation->setUserIdFk($lvo->getUserIdFk());
	        $estimation->setTaskIdFk($lvo->getTaskIdFk());
	    	$estimation->setDescription($lvo->getDescription());
	        $estimation->setUnit($lvo->getUnit());
	        $estimation->setComplexity($lvo->getComplexity());
	        $estimation->setQuantity($lvo->getQuantity());
	        $estimation->setMinimum($lvo->getMinimum());
	        $estimation->setNormal($lvo->getNormal());
	        $estimation->setMaximum($lvo->getMaximum());
	        $estimation->setAverage($lvo->getAverage());
			// create the estimation
			$estimationId = $estimation->create();
		} else {
		    // update the estimation
			$estimation = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
			    ->findByPrimaryKey($estimationId);
			// set the data
	        $estimation->setUserIdFk($lvo->getUserIdFk());
	        $estimation->setTaskIdFk($lvo->getTaskIdFk());
	    	$estimation->setDescription($lvo->getDescription());
	        $estimation->setUnit($lvo->getUnit());
	        $estimation->setComplexity($lvo->getComplexity());
	        $estimation->setQuantity($lvo->getQuantity());
	        $estimation->setMinimum($lvo->getMinimum());
	        $estimation->setNormal($lvo->getNormal());
	        $estimation->setMaximum($lvo->getMaximum());
	        $estimation->setAverage($lvo->getAverage());
			// update the estimation
	        $estimation->update();
		}
		// update the task performance
		$this->updateTaskPerformance($lvo);
		// return the ID of the saved estimation
		return $estimationId;
    }

    /**
     * This method updates the task performance after creating
     * a new estimation.
     *
     * @param TDProject_Project_Common_ValueObjects_EstimationLightValue $estimation
     * 		The estimation to update the task performance for
     * @return void
     */
    public function updateTaskPerformance(
    	TDProject_Project_Common_ValueObjects_EstimationLightValue $estimation) {
		// load the available task performanc entries
    	$taskPerformances = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer())
    		->findAllByTaskIdFk($estimation->getTaskIdFk());
		// if acutally no one exists, create a new one and return
    	if ($taskPerformances->size() == 0) {
    		TDProject_Project_Model_Actions_TaskPerformance_Reorg::create($this->getContainer())
    			->createByTaskIdFk($estimation->getTaskIdFk());
    	} else {
    		// update the found task performance
	    	foreach ($taskPerformances as $taskPerformance) {
		    	TDProject_Project_Model_Actions_TaskPerformance_Reorg::create($this->getContainer())
		    		->updateByTaskIdFk($taskPerformance->getTaskIdFk());
	    	}
    	}
    }
}