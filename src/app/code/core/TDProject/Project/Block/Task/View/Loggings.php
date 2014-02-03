<?php

/**
 * TDProject_Project_Block_Task_View_Loggings
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Abstract.php';

/**
 * @category    TDProject
 * @package     TDProject_Core
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Task_View_Loggings
    extends TDProject_Core_Block_Abstract {

    /**
     * The ActionForm with the data.
     * @var TDProject_Project_Block_Estimation_View
     */
    protected $_form = null;

    /**
     * The task with the subtasks and the cumulated logging information.
     * @var TDProject_Project_Common_ValueObjects_TaskValue
     */
    protected $_task = null;

    /**
     * The tasks total.
     * @var TechDivision_Lang_Integer
     */
    protected $_total = null;

    /**
     * Initialize the block with the
     * apropriate template and name.
     *
     * @return void
     */
    public function __construct(
        TDProject_Project_Block_Task_View $form,
        TDProject_Project_Common_ValueObjects_TaskValue $task) {
        // call the parent constructor
        parent::__construct($form->getContext());
        // set the form
        $this->_form = $form;
        $this->_task = $task;
        $this->_total = $task->getTotal();
        // set the internal name
        $this->_setBlockName($this->_task->getName()->stringValue());
        // set the template name
        $this->_setTemplate(
        	'www/design/project/templates/task/view/loggings.phtml'
        );
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_Interfaces_Block::prepareLayout()
     */
    public function prepareLayout()
    {
		// load all child tasks
        foreach ($this->getTask()->getTasks() as $task) {
			// add the blocks for the childs
            $this->addBlock(
                $block = new TDProject_Project_Block_Task_View_Loggings(
                    $this->_form, $task
                )
            );
        }
		// return teh block instance itself
        return parent::prepareLayout();
    }

    /**
     * Calculates the tasks total.
     *
     * @return TDProject_Project_Block_Task_View_Loggings The instance itself
     */
    public function calculateTotal()
    {
    	// iterate over the subtasks
        foreach ($this->getTask()->getTasks() as $task) {
            $this->_total->add($child->calculateTotal()->getTotal());
        }
        // calculate the tasks total including the subtotals
        $this->_total->add($this->getSubtotal());
		// the block instance
        return $this;
    }

    /**
     * Returns the task's total as hours.
     *
     * @return TechDivision_Lang_Float The hours itself
     */
    public function getTotal()
    {
        // calculate the tasks total including the subtotals
        return $this->toHours($this->_total);
    }

    /**
     * Returns the hours of the passed logging entry.
     *
     * @param TDProject_Project_Common_ValueObjects_CumulatedLoggingLightValue $lvo
     * 		The logging entry to return the hours for
     * @return TechDivision_Lang_Float
     * 		The hours itself
     */
    public function getHours(
        TDProject_Project_Common_ValueObjects_CumulatedLoggingLightValue $lvo) {
        return $this->toHours($lvo->getMinutes());
    }

    /**
     * Calculates the hours for the passed minutes.
     *
     * @param TechDivision_Lang_Integer $minutes The minutes to calculate the hours for
     * @return TechDivision_Lang_Float The calculated hours
     */
    public function toHours(TechDivision_Lang_Integer $minutes)
    {
        return new TechDivision_Lang_Float(round($minutes->intValue() / 60, 2));
    }

    /**
     * Returns the tasks loggings.
     *
     * @return TDProject_Project_Common_ValueObjects_Collections_CumulatedLoggings
     * 		A Collection with the tasks cumulated loggings
     */
    public function getLoggings()
    {
        return $this->getTask()->getLoggings();
    }

    /**
     * The task to render the loggings for.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskValue
     * 		The task itself
     */
    public function getTask()
    {
    	return $this->_task;
    }
}