<?php

/**
 * TDProject_Project_Block_Task_View
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Widget/Form/Ajax/Abstract.php';
require_once 'TDProject/Core/Block/Widget/Button.php';
require_once 'TDProject/Project/Block/Task/View/Loggings.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskViewData.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Task_View
    extends TDProject_Core_Block_Widget_Form_Ajax_Abstract {

    /**
     * The task ID.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskId = null;

    /**
     * The task performance ID.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskPerformanceId = null;

    /**
     * The parent task ID.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskIdFk = null;

    /**
     * The task type ID.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskTypeIdFk = null;

    /**
     * The project's name.
     * @var TechDivision_Lang_String
     */
    protected $_name = null;

    /**
     * The task's description.
     * @var TechDivision_Lang_String
     */
    protected $_description = null;

    /**
     * Flag to make the task billable or not.
     * @var TechDivision_Lang_Boolean
     */
    protected $_billable = null;

    /**
     * Flag to allow overbooking of task or not.
     * @var TechDivision_Lang_Boolean
     */
    protected $_allowOverbooking = null;

    /**
     * Flag to make the task finished or not.
     * @var TechDivision_Lang_Boolean
     */
    protected $_finished = null;

    /**
     * The available task types.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_taskTypes = null;

    /**
     * The task itself.
     * @var TDProject_Project_Common_ValueObjects_TaskValue
     */
    protected $_task = null;

    /**
     * Cost center to be debited.
     * @var TechDivision_Lang_Integer
     */
    protected $_costCenter = null;

    /**
     * Getter method for the task ID.
     *
     * @return TechDivision_Lang_Integer The task ID
     */
    public function getTaskId() {
        return $this->_taskId;
    }

    /**
     * Setter method for the task ID.
     *
     * @param integer $string The task ID
     * @return void
     */
    public function setTaskId($string)
    {
        $this->_taskId = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the task ID.
     *
     * @return TechDivision_Lang_Integer The task ID
     */
    public function getTaskPerformanceId() {
        return $this->_taskPerformanceId;
    }

    /**
     * Setter method for the task performance ID.
     *
     * @param integer $string The task performance ID
     * @return void
     */
    public function setTaskPerformanceId($string)
    {
        $this->_taskPerformanceId = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the ID of the parent task.
     *
     * @return TechDivision_Lang_Integer The ID of the parent task
     */
    public function getTaskIdFk()
    {
        return $this->_taskIdFk;
    }

    /**
     * Setter method for the ID of the parent task.
     *
     * @param integer $string The ID of the parent task
     * @return void
     */
    public function setTaskIdFk($string)
    {
        $this->_taskIdFk = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the ID of the project's task type.
     *
     * @return TechDivision_Lang_Integer The ID of the project's task type
     */
    public function getTaskTypeIdFk()
    {
        return $this->_taskTypeIdFk;
    }

    /**
     * Setter method for the ID of the project's task type.
     *
     * @param integer $string The ID of the project's task type
     * @return void
     */
    public function setTaskTypeIdFk($string)
    {
        $this->_taskTypeIdFk = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the project's name.
     *
     * @return TechDivision_Lang_String The project's name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Setter method for project's name.
     *
     * @param string $string The project's name
     * @return void
     */
    public function setName($string)
    {
        $this->_name = new TechDivision_Lang_String($string);
    }

    /**
     * Getter method for the project's description.
     *
     * @return TechDivision_Lang_String The project's description
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Setter method for project's description.
     *
     * @param string $string The project's description
     * @return void
     */
    public function setDescription($string)
    {
        $this->_description = new TechDivision_Lang_String($string);
    }

    /**
     * Getter method for the available projects.
     *
     * @return TechDivision_Lang_Integer The available projects
     */
    public function getTaskTypes()
    {
        return $this->_taskTypes;
    }

    /**
     * Setter method for the available projects.
     *
     * @param TechDivision_Collections_Interfaces_Collection $projects
     * 		The available projects
     * @return void
     */
    public function setTaskTypes(
        TechDivision_Collections_Interfaces_Collection $taskTypes) {
        $this->_taskTypes = $taskTypes;
    }

    /**
     * Getter method for billable flag.
     *
     * @return TechDivision_Lang_Boolean The billable flag
     */
    public function getBillable()
    {
        return $this->_billable;
    }

    /**
     * Setter method for the billable flag.
     *
     * @param string $string The billable flag
     * @return void
     */
    public function setBillable($string)
    {
        $this->_billable = TechDivision_Lang_Boolean::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method to allow overbooking.
     *
     * @return TechDivision_Lang_Boolean The flag to allow overbooking
     */
    public function getAllowOverbooking()
    {
        return $this->_allowOverbooking;
    }

    /**
     * Setter method to allow overbooking.
     *
     * @param string $string The flag to allow overbooking
     * @return void
     */
    public function setAllowOverbooking($string)
    {
        $this->_allowOverbooking = TechDivision_Lang_Boolean::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the finished flag.
     *
     * @return TechDivision_Lang_Boolean The finished flag
     */
    public function getFinished()
    {
        return $this->_finished;
    }

    /**
     * Setter method for the finished flag.
     *
     * @param string $string The finished flag
     * @return void
     */
    public function setFinished($string)
    {
        $this->_finished = TechDivision_Lang_Boolean::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the task itself.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskValue The task itself
     */
    public function getTask()
    {
        return $this->_task;
    }

    /**
     * Setter method for the task itself.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskValue task
     * 		The DTO with the task itself
     * @return void
     */
    public function setTask(
        TDProject_Project_Common_ValueObjects_TaskValue $task) {
        $this->_task = $task;
    }

    /**
     * Getter method for the cost center.
     *
     * @return TechDivision_Lang_Integer The cost center
     */
    public function getCostCenter()
    {
        return $this->_costCenter;
    }

    /**
     * Setter method for the cost center to be debited.
     *
     * @param string $string The cost center
     * 		The cost center to be debited
     * @return void
     */
    public function setCostCenter($string) {
        $this->_costCenter = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_Project_Block_Abstract_TaskUser::prepareLayout()
     */
    public function prepareLayout()
    {
    	// add the hidden fields
    	$this->addElement($this->getElement('hidden', 'name'));
        $this->addElement($this->getElement('hidden', 'taskId'));
        $this->addElement($this->getElement('hidden', 'taskTypeIdFk'));
        $this->addElement($this->getElement('hidden', 'taskPerformanceId'));
    	// initialize the fieldset
    	$fieldset = new TDProject_Core_Block_Widget_Fieldset(
    		$this->getContext(),
    		'task',
    		$this->translate('task.view.fieldset.label.edit-task')
        );
    	// add the fieldset elements
    	$fieldset
        	->addElement(
        	    $this->getElement(
        			'textarea',
        			'description',
        			$this->translate('task.view.label.description')
        	    )->setMandatory()
        	)
        	->addElement(
        	    $this->getElement(
        			'textfield',
        			'costCenter',
        			'Cost Center'
        	    )
        	)
        	->addElement(
        	    $this->getElement(
        	    	'checkbox',
        	    	'billable',
        	    	$this->translate('task.view.label.billable')
        	    )
        	)
        	->addElement(
        	    $this->getElement(
        	    	'checkbox',
        	    	'allowOverbooking',
        	    	$this->translate('task.view.label.allow-overbooking')
        	    )
        	)
        	->addElement(
        	    $this->getElement(
        	    	'checkbox',
        	    	'finished',
        	    	$this->translate('task.view.label.finished')
        	    )
        	)
	        ->addElement(
	            $this->getElement(
	            	'select',
	            	'taskTypeIdFk',
	            	$this->translate('task.view.label.task-type')
	            )->setOptions($this->getTaskTypes())
	        );
        // add the fieldset
        $this->addBlock($fieldset);
    	// initialize the fieldset for the given loggings
        /* TODO Removed to improve performance
         * @author Tim Wagner
         * @date 2012-02-17
    	 * $fieldsetLoggings = new TDProject_Core_Block_Widget_Fieldset(
    	 * 	$this->getContext(),
    	 * 	'loggings-made',
    	 * 	$this->translate('estimation.view.fieldset.label.loggings-made')
         * );
    	 * // add the block with the already loggings made
         * $fieldsetLoggings->addBlock(
         * 	new TDProject_Project_Block_Task_View_Loggings(
         * 	    $this, $this->getTask()
         * 	)
         * );
         * // add the fieldset with the loggings made
         * $this->addBlock($fieldsetLoggings);
         */
        // add the button to the Toolbar
        $this->getToolbar()->addButton(
        	$button = new TDProject_Core_Block_Widget_Button(
        	    $this->getContext(),
        	    'saveTask',
        	    $this->translate('task.view.button.label.save-task')
        	)
        );
        // set the buttons click event
        $button->setOnClick(
        	'$("#' . $this->getFormName() . '").submit(); return false;'
        );
	    // return the instance itself
	    return parent::prepareLayout();
    }

    /**
     * Resets all member variables to their
     * default values.
     *
     * @return void
     */
    function reset()
    {
        $this->_task = null;
    	$this->_taskId = new TechDivision_Lang_Integer(0);
    	$this->_taskPerformanceId = new TechDivision_Lang_Integer(0);
        $this->_taskIdFk = new TechDivision_Lang_Integer(0);
        $this->_taskTypeIdFk = new TechDivision_Lang_Integer(0);
        $this->_name = new TechDivision_Lang_String();
        $this->_description = new TechDivision_Lang_String();
        $this->_billable = new TechDivision_Lang_Boolean(false);
        $this->_allowOverbooking = new TechDivision_Lang_Boolean(false);
        $this->_finished = new TechDivision_Lang_Boolean(false);
        $this->_taskTypes = new TechDivision_Collections_ArrayList();
        $this->_costCenter = new TechDivision_Lang_Integer(0);
    }

    /**
     * Populates the ActionForm with the values
     * of the passed DTO.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskViewData $dto
     * 		The DTO with the data to initialize the ActionForm with
     * @return void
     */
    function populate(
        TDProject_Project_Common_ValueObjects_TaskViewData $dto) {
        $this->_task = $dto;
        $this->_taskId = $dto->getTaskId();
        $this->_taskIdFk = $dto->getTaskIdFk();
        $this->_taskTypeIdFk = $dto->getTaskTypeIdFk();
        $this->_name = $dto->getName();
        $this->_description = $dto->getDescription();
        $this->_billable = $dto->getBillable();
        $this->_costCenter = $dto->getCostCenter();
        $this->_taskTypes = $dto->getTaskTypes();
        // initialize the task performance values
        foreach ($dto->getTaskPerformances() as $lvo) {
        	$this->_taskPerformanceId = $lvo->getTaskPerformanceId();
        	$this->_allowOverbooking = $lvo->getAllowOverbooking();
        	$this->_finished = $lvo->getFinished();
        }
    }

    /**
     * Initializes and returns a new LVO
     * with the data from the ActionForm.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskValue
     * 		The initialized LVO
     */
    public function repopulate()
    {
		// initialize a new LVO
		$vo = new TDProject_Project_Common_ValueObjects_TaskValue();
		// filling it with the project data from the ActionForm
		$vo->setTaskId($this->getTaskId());
        // load the task ID of the parent task
		$taskIdFk = $this->getTaskIdFk();
		if (!$taskIdFk->equals(new TechDivision_Lang_Integer(0))) {
    		$vo->setTaskIdFk($taskIdFk);
		}
		$vo->setTaskTypeIdFk($this->getTaskTypeIdFk());
		$vo->setName($this->getName());
		$vo->setDescription($this->getDescription());
		$vo->setBillable($this->getBillable());
		$vo->setCostCenter($this->getCostCenter());
		// initialize the task's performance
		$lvo = new TDProject_Project_Common_ValueObjects_TaskPerformanceLightValue();
		// filling it with the project data from the ActionForm
		$lvo->setTaskPerformanceId($this->getTaskPerformanceId());
		$lvo->setAllowOverbooking($this->getAllowOverbooking());
		$lvo->setFinished($this->getFinished());
		// add the task's performance to the ArrayList
		$vo->getTaskPerformances()->add($lvo);
        // return the initialized LVO
		return $vo;
    }

    /**
     * This method checks if the values in the member variables
     * holds valiid data. If not, a ActionErrors container will
     * be initialized an for every incorrect value a ActionError
     * object with the apropriate error message will be added.
     *
     * @return ActionErrors
     * 		Returns a ActionErrors container with ActionError objects
     */
    function validate()
    {
        // initialize the ActionErrors
        $errors = new TechDivision_Controller_Action_Errors();
        // check if a taks name was entered
        if ($this->_name->length() == 0) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::NAME,
                    $this->translate('task-name.none')
                )
            );
        }
        // check if a task description was entered
        if ($this->_description->length() == 0) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::DESCRIPTION,
                    $this->translate('task-description.none')
                )
            );
        }
        // return the ActionErrors
        return $errors;
    }
}