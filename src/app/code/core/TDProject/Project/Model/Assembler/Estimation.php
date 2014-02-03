<?php

/**
 * TDProject_Project_Model_Assembler_Estimation
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
class TDProject_Project_Model_Assembler_Estimation
    extends TDProject_Project_Model_Assembler_Abstract {

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Assembler_Estimation($container);
    }

    /**
     * Returns a DTO initialized with the data of the
     * taks with the passed ID.
     *
     * @param TechDivision_Lang_Integer $taskIdFk
     * 		ID of the task to initialize the DTO with
     * @return TDProject_Project_Common_ValueObjects_TaskViewData
     * 		The DTO with the requested task data
     */
    public function getEstimationViewData(TechDivision_Lang_Integer $taskIdFk) {
		// initialize the DTO
		$dto = new TDProject_Project_Common_ValueObjects_EstimationViewData(
			TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
				->epbCreate()
		);
	    // load the estimations
		$estimations = TDProject_Project_Model_Utils_EstimationUtil::getHome($this->getContainer())
			->findAllByTaskIdFk($taskIdFk);
		// add the estimations to the Collection
		foreach ($estimations as $estimation) {
			// add the estimation
			$dto->getEstimations()->add(
				$lvo = new TDProject_Project_Common_ValueObjects_EstimationOverviewData(
					$estimation
				)
			);
			// set the username
			$lvo->setUsername(
				TDProject_Core_Model_Utils_UserUtil::getHome($this->getContainer())
					->findByPrimaryKey($estimation->getUserIdFk())->getUsername()
			);
		}
		// set the total average
		$dto->setAverage(
			new TechDivision_Lang_Integer(
				TDProject_Project_Model_Actions_Calculation::create($this->getContainer())
					->totalAverage($taskIdFk)
			)
		);
		// set the subtotal average
		$dto->setSubtotalAverage(
			new TechDivision_Lang_Integer(
				TDProject_Project_Model_Actions_Calculation::create($this->getContainer())
					->subtotalAverage($taskIdFk)
			)
		);
		// set the task ID
		$dto->setTaskIdFk($taskIdFk);
		// set the available units & complexities
		$dto->setUnits($this->getUnits());
		$dto->setComplexities($this->getComplexities());
		// return the initialized DTO
		return $dto;
    }

    /**
     * Initialize and return a Collection with the
     * units available to select for an estimation.
     *
     * @return TechDivision_Collections_ArrayList
     * 		The ArrayList with the available units
     */
  	public function getUnits()
  	{
  		// initialize the ArrayList
  		$list = new TechDivision_Collections_ArrayList();
  		// load the units
  		foreach (TDProject_Project_Common_Enums_Unit::load() as $unit) {
	  		$list->add(
	  			new TDProject_Project_Common_ValueObjects_UnitOverviewData(
		  			$unit->toString()
		  		)
	  		);
  		}
  		// return the ArrayList
  		return $list;
  	}

    /**
     * Initialize and return a Collection with the
     * complexities available to select for an estimation.
     *
     * @return TechDivision_Collections_ArrayList
     * 		The ArrayList with the available complexities
     */
  	public function getComplexities()
  	{
  		// initialize the ArrayList
  		$list =  new TechDivision_Collections_ArrayList();
  		// load the complexities
  		foreach (TDProject_Project_Common_Enums_Complexity::load() as $complexity) {
	  		$list->add(
	  			new TDProject_Project_Common_ValueObjects_ComplexityOverviewData(
		  			$complexity->toString()
		  		)
	  		);
  		}
  		// return the ArrayList
  		return $list;
  	}
}