<?php

/**
 * TDProject_Project_Block_Logging_View_Task_Element
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Abstract.php';
require_once 'TDProject/Project/Controller/Util/WebRequestKeys.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskViewData.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Logging_View_Task_Element
    extends TDProject_Core_Block_Abstract {

    /**
     * The DTO with the acutal task data.
     * @var TDProject_Project_Common_ValueObjects_TaskViewData
     */
    protected $_dto = null;

    /**
     * The parent element.
     * @var TDProject_Project_Block_Logging_View_Task_Element
     */
    protected $_parentElement = null;

    /**
     * Initialize the block with the
     * apropriate template and name.
     *
     * @param TechDivision_Collections_Interfaces_Collection $dtos
     * 		The Collection with the tasks
     * @param TDProject_Project_Block_Logging_View_Task_Element $parent
     * 		The parent element
     * @return void
     */
    public function __construct(
    	TechDivision_Controller_Interfaces_Context $context,
        TDProject_Project_Common_ValueObjects_TaskOverviewData $dto,
        TDProject_Project_Block_Logging_View_Task_Element $parentElement = null) {
        // set the VO
        $this->_dto = $dto;
        $this->_parentElement = $parentElement;
        // set the internal name
        $this->_setBlockName($this->_dto->getTaskId()->intValue());
        // set the template name
        $this->_setTemplate(
        	'www/design/project/templates/logging/view/task/element.phtml'
        );
        // call the parent constructor
        parent::__construct($context);
    }

	/**
     * (non-PHPdoc)
     * @see TDProject/Interfaces/Block#prepareLayout()
     */
    public function prepareLayout()
    {
        // add a Block for each subtask
        foreach ($this->_dto->getTasks() as $task) {
            // add the tasks element
            $this->addBlock(
                new TDProject_Project_Block_Logging_View_Task_Element(
                    $this->getContext(), $task, $this
                )
            );
        }
        // call the parent constructor
        parent::prepareLayout();
        // return the instance itself
        return $this;
    }

    /**
     * Returns the elements task.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskViewData
     * 		The requested task
     */
    public function getTask()
    {
        return $this->_dto;
    }

    /**
     * Returns TRUE if the element has to
     * be rendered selected, else FALSE.
     *
     * @return boolean TRUE if the element is the selected one, else FALSE
     */
    public function isSelected()
    {
    	// load the ID of the selected task from the Request
    	$id = $this->getRequest()->getParameter(
    		TDProject_Project_Controller_Util_WebRequestKeys::TASK_ID,
    		FILTER_VALIDATE_INT);
    	// cast it to an Integer
		if ($id != null) {
			$taskId = TechDivision_Lang_Integer::valueOf(
			    new TechDivision_Lang_String($id)
			);
		} else {
			$taskId = new TechDivision_Lang_Integer(0);
		}
		// check if the actual element has to be rendered as the selected
		return $this->getTask()->getTaskId()->equals($taskId);
    }

    /**
     * Return TRUE if the element is on the first level. The first level
     * is the level = 2 in nested set model, because root node will not
     * be rendered in logging task selection.
     *
     * @return boolean TRUE if the element is on first level, else FALSE
     */
    public function isFirstLevel()
    {
    	return $this->getTask()->getLevel()
    		->equals(new TechDivision_Lang_Integer(2));
    }

    /**
     * Return TRUE if the element has to be selected, else FALSE. An
     * element is NOT selectable if it has been set to 'finished' for
     * example.
     *
     * @return boolean TRUE if the element has to be selectable
     */
    public function isSelectable()
    {
    	// check if the selectable flag has to be ignored
    	$ignoreSelectable = $this->getContext()->getAttribute(
    		TDProject_Project_Controller_Util_WebRequestKeys::IGNORE_SELECTABLE
    	);
    	// if yes, return TRUE
    	if ($ignoreSelectable && $ignoreSelectable->booleanValue()) {
    		return true;
    	}
    	// if not, check task to be selectable or not
    	return $this->getTask()->isSelectable();
    }

    /**
     * Returns TRUE if the element has child nodes.
     *
     * @return boolean TRUE if the element has childs
     */
    public function hasChilds()
    {
    	// check if childs was found
    	if ($this->getTask()->getTasks()->size() > 0) {
    		return true;
    	}
    	return false;
    }

    /**
     * Renders the task name recursive.
     *
     * @return string The task name
     */
    public function renderName()
    {
		// initialize the tasks name
    	$name = $this->_dto->getName()->__toString();
    	// check if a parent task exists
    	if ($this->_parentElement == null) {
			return $name;
    	}
    	// return the task name prefixed with the parent name
    	return $this->_parentElement->renderName() . ' / ' . $name;
    }
}