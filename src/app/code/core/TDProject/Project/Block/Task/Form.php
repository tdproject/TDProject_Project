<?php

/**
 * TDProject_Project_Block_Task_Form
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/String.php';
require_once 'TechDivision/Lang/Integer.php';
require_once 'TechDivision/Lang/Boolean.php';
require_once 'TechDivision/Collections/Interfaces/Collection.php';
require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TDProject/Core/Block/AbstractForm.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskViewData.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskLightValue.php';

/**
 * This class implements the form functionality
 * for handling a task.
 *
 * @category    TProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Bastian Stangl <b.stangl@techdivision.com>
 */

class TDProject_Project_Block_Task_Form
    extends TDProject_Core_Block_AbstractForm {

    /**
     * The task ID.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskId = null;

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
     * The available task types.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_taskTypes = null;

    /**
     * Getter method for the task ID.
     *
     * @return TechDivision_Lang_Integer The task ID
     */
    public function getTaskId()
    {
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
     * Resets all member variables to their
     * default values.
     *
     * @return void
     */
    function reset()
    {
    	$this->_taskId = new TechDivision_Lang_Integer(0);
        $this->_taskIdFk = new TechDivision_Lang_Integer(0);
        $this->_taskTypeIdFk = new TechDivision_Lang_Integer(0);
        $this->_name = new TechDivision_Lang_String();
        $this->_description = new TechDivision_Lang_String();
        $this->_billable = new TechDivision_Lang_Boolean(false);
        $this->_taskTypes = new TechDivision_Collections_ArrayList();
    }

    /**
     * Populates the ActionForm with the values
     * of the passed DTO.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskViewData $dto
     * 		The DTO with the data to initialize the ActionForm with
     * @return void
     */
    function populate(TDProject_Project_Common_ValueObjects_TaskViewData $dto)
    {
        $this->_taskId = $dto->getTaskId();
        $this->_taskIdFk = $dto->getTaskIdFk();
        $this->_taskTypeIdFk = $dto->getTaskTypeIdFk();
        $this->_name = $dto->getName();
        $this->_description = $dto->getDescription();
        $this->_billable = $dto->getBillable();
        $this->_taskTypes = $dto->getTaskTypes();
    }

    /**
     * Initializes and returns a new LVO
     * with the data from the ActionForm.
     *
     * @return TDProject_Project_Common_ValueObjects_TaskLightValue
     * 		The initialized LVO
     */
    public function repopulate()
    {
		// initialize a new LVO
		$lvo = new TDProject_Project_Common_ValueObjects_TaskLightValue();
		// filling it with the project data from the ActionForm
		$lvo->setTaskId($this->getTaskId());
        // load the task ID of the parent task
		$taskIdFk = $this->getTaskIdFk();
		if (!$taskIdFk->equals(new TechDivision_Lang_Integer(0))) {
    		$lvo->setTaskIdFk($taskIdFk);
		}
		$lvo->setTaskTypeIdFk($this->getTaskTypeIdFk());
		$lvo->setName($this->getName());
		$lvo->setDescription($this->getDescription());
		$lvo->setBillable($this->getBillable());
        // return the initialized LVO
		return $lvo;
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