<?php

/**
 * TDProject_Project_Block_Estimation_View
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/String.php';
require_once 'TechDivision/Lang/Integer.php';
require_once 'TechDivision/Lang/Float.php';
require_once 'TDProject/Core/Block/Widget/Form/Ajax/Abstract.php';
require_once 'TDProject/Core/Block/Widget/Button.php';
require_once 'TDProject/Project/Block/Estimation/View/List.php';
require_once 'TDProject/Project/Common/ValueObjects/EstimationLightValue.php';
require_once 'TDProject/Project/Common/ValueObjects/EstimationViewData.php';

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Estimation_View
    extends TDProject_Core_Block_Widget_Form_Ajax_Abstract {

    /**
     * The estimation ID.
     * @var TechDivision_Lang_Integer
     */
    protected $_estimationId = null;

    /**
     * The task ID the estimation is for.
     * @var TechDivision_Lang_Integer
     */
    protected $_taskIdFk = null;

    /**
     * The user ID who estimates.
     * @var TechDivision_Lang_Integer
     */
    protected $_userIdFk = null;

    /**
     * The estimation's description.
     * @var TechDivision_Lang_String
     */
    protected $_description = null;

    /**
     * The unit used for the estimation, can be minutes, hours or days.
     * @var TechDivision_Lang_String
     */
    protected $_unit = null;

    /**
     * The complexity the user's expects to solve the task, can be low, medium or high.
     * @var TechDivision_Lang_String
     */
    protected $_complexity = null;

    /**
     * The quantity of the user's estimation.
     * @var TechDivision_Lang_Integer
     */
    protected $_quantity = null;

    /**
     * The available complexities.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_complexities = null;

    /**
     * The available units.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_units = null;

    /**
     * The available estimations.
     * @var TechDivision_Collections_ArrayList
     */
    protected $_estimations = null;

    /**
     * The total average estimation time in minutes
     * for the selected task AND his subtask.
     * @var TechDivision_Lang_Integer
     */
    protected $_totalAverage = null;

    /**
     * The total average estimation time in minutes
     * for the selected task.
     * @var TechDivision_Lang_Integer
     */
    protected $_subtotalAverage = null;

    /**
     * Getter method for the estimation ID.
     *
     * @return TechDivision_Lang_Integer The estimation ID
     */
    public function getEstimationId()
    {
        return $this->_estimationId;
    }

    /**
     * Setter method for the estimation ID.
     *
     * @param integer $string The estimation ID
     * @return void
     */
    public function setEstimationId($string)
    {
        $this->_estimationId = TechDivision_Lang_Integer::valueOf(
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
     * Getter method for the ID of the user who estimates.
     *
     * @return TechDivision_Lang_Integer The ID of the user who estimates
     */
    public function getUserIdFk()
    {
        return $this->_userIdFk;
    }

    /**
     * Setter method for the ID of the user who estimates.
     *
     * @param integer $string The ID of the user who estimates
     * @return void
     */
    public function setUserIdFk($string)
    {
        $this->_userIdFk = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
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
     * Getter method for selected unit.
     *
     * @return TechDivision_Lang_String The selected unit
     */
    public function getUnit()
    {
        return $this->_unit;
    }

    /**
     * Setter method for the selected unit.
     *
     * @param string $string The selected unit
     * @return void
     */
    public function setUnit($string)
    {
    	$this->_unit = new TechDivision_Lang_String($string);
    }

    /**
     * Getter method for the available units.
     *
     * @return TechDivision_Lang_String The available complexity
     */
    public function getComplexity()
    {
        return $this->_complexity;
    }

    /**
     * Setter method for the selected complexity.
     *
     * @param string $string The selected complexity
     * @return void
     */
    public function setComplexity($string)
    {
    	$this->_complexity = new TechDivision_Lang_String($string);
    }

    /**
     * Getter method for the quantiy of units the user
     * expects for solving the task.
     *
     * @return TechDivision_Lang_Integer The quantiy of units
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * Setter method for the quantiy of units the user
     * expects for solving the task.
     *
     * @param string $string The quantiy of units
     * @return void
     */
    public function setQuantity($string)
    {
        $this->_quantity = TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String($string)
        );
    }

    /**
     * Getter method for the available units.
     *
     * @return TechDivision_Collections_Interfaces_Collection The available units
     */
    public function getUnits()
    {
        return $this->_units;
    }

    /**
     * Setter method for the available units.
     *
     * @param TechDivision_Collections_Interfaces_Collection $units
     * 		The available units
     * @return void
     */
    public function setUnits(
        TechDivision_Collections_Interfaces_Collection $units) {
        $this->_units = $units;
    }

    /**
     * Getter method for the available complexities.
     *
     * @return TechDivision_Collections_Interfaces_Collection The available complexities
     */
    public function getComplexities()
    {
        return $this->_complexities;
    }

    /**
     * Setter method for the available complexities.
     *
     * @param TechDivision_Collections_Interfaces_Collection $complexities
     * 		The available complexities
     * @return void
     */
    public function setComplexities(
        TechDivision_Collections_Interfaces_Collection $complexities) {
        $this->_complexities = $complexities;
    }

    /**
     * Getter method for the available estimations.
     *
     * @return TechDivision_Collections_Interfaces_Collection The available estimations
     */
    public function getEstimations()
    {
        return $this->_estimations;
    }

    /**
     * Setter method for the available estimations.
     *
     * @param TechDivision_Collections_Interfaces_Collection estimations
     * 		The available estimations
     * @return void
     */
    public function setEstimations(
        TechDivision_Collections_Interfaces_Collection $estimations) {
        $this->_estimations = $estimations;
    }

    /**
     * Getter method for the average amount of time in minutes.
     *
     * @return TechDivision_Lang_Integer
     * 		The average amount of time in minutes
     */
    public function getAverage()
    {
        return $this->_average;
    }

    /**
     * Setter method for the average amount of time in minutes.
     *
     * @param TechDivision_Lang_Integer $average
     * 		The average amount of time in minutes
     * @return void
     */
    public function setAverage(
        TechDivision_Lang_Integer $average) {
        $this->_average = $average;
    }

    /**
     * Getter method for the total average amount of time in minutes.
     *
     * @return TechDivision_Lang_Integer
     * 		The total average amount of time in minutes
     */
    public function getSubtotalAverage()
    {
        return $this->_subtotalAverage;
    }

    /**
     * Setter method for the total average amount of time in minutes.
     *
     * @param TechDivision_Lang_Integer totalAverage
     * 		The total average amount of time in minutes
     * @return void
     */
    public function setSubtotalAverage(
        TechDivision_Lang_Integer $subtotalAverage) {
        $this->_subtotalAverage = $subtotalAverage;
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_Project_Block_Abstract_TaskUser::prepareLayout()
     */
    public function prepareLayout()
    {
    	// initialize the hidden elements
    	$this->addElement($this->getElement('hidden', 'estimationId'));
        $this->addElement($this->getElement('hidden', 'taskIdFk'));
        $this->addElement($this->getElement('hidden', 'userIdFk'));
    	// initialize the fieldset for the given estimations
    	$fieldsetEstimations = new TDProject_Core_Block_Widget_Fieldset(
    		$this->getContext(),
    		'estimations-made',
    		$this->translate('estimation.view.fieldset.label.estimations-made')
        );
    	// add the block with the already given estimations
        $fieldsetEstimations->addBlock(
        	new TDProject_Project_Block_Estimation_View_List($this)
        );
        // add the fieldset with the given estimations
        $this->addBlock($fieldsetEstimations);
    	// initialize the fieldset
    	$fieldset = new TDProject_Core_Block_Widget_Fieldset(
    		$this->getContext(),
    		'make-estimation',
    		$this->translate('estimation.view.fieldset.label.make-estimation')
        );
    	// add the elements
    	$fieldset
        	->addElement(
        		$this->getElement(
        			'textarea',
        			'description',
        			$this->translate('estimation.view.label.description')
        		)	->setMandatory())
	        ->addElement(
	        	$this->getElement(
	        		'select',
	        		'unit',
	        		$this->translate('estimation.view.label.unit')
	        	)->setMandatory()->setOptions($this->getUnits()))
	        ->addElement(
	        	$this->getElement(
	        		'select',
	        		'complexity',
	        		$this->translate('estimation.view.label.complexity')
	        	)->setMandatory()->setOptions($this->getComplexities()))
        	->addElement(
        		$this->getElement(
        			'textfield',
        			'quantity',
        			$this->translate('estimation.view.label.quantity')
        		)->setMandatory()
            );
        // add the fieldset
        $this->addBlock($fieldset);
        // add the button to the Toolbar
        $this->getToolbar()->addButton(
        	$button = new TDProject_Core_Block_Widget_Button(
        	    $this->getContext(),
        	    'saveEstimation',
        	    $this->translate('estimation.view.button.label.save')
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
     * Populates the ActionForm with the values
     * of the passed DTO.
     *
     * @param The system user who tries to make the estimation
     * @param TDProject_Project_Common_ValueObjects_EstimationViewData $dto
     * 		The DTO with the data to initialize the ActionForm with
     * @return void
     */
    public function initialize(
    	TDProject_Core_Common_ValueObjects_System_UserValue $systemUser,
    	TDProject_Project_Common_ValueObjects_EstimationViewData $dto) {
    	// initialize the Collections
        $this->_complexities = $dto->getComplexities();
        $this->_units = $dto->getUnits();
        $this->_estimations = $dto->getEstimations();
        $this->_average = $dto->getAverage();
        $this->_subtotalAverage = $dto->getSubtotalAverage();
    }

    /**
     * Resets all member variables to their
     * default values.
     *
     * @return void
     */
    function reset()
    {
    	$this->_estimationId = new TechDivision_Lang_Integer(0);
        $this->_taskIdFk = new TechDivision_Lang_Integer(0);
        $this->_userIdFk = new TechDivision_Lang_Integer(0);
        $this->_description = new TechDivision_Lang_String();
        $this->_unit = new TechDivision_Lang_String();
        $this->_complexity = new TechDivision_Lang_String();
        $this->_quantity = new TechDivision_Lang_Integer(0);
        $this->_units = new TechDivision_Collections_ArrayList();
        $this->_complexities = new TechDivision_Collections_ArrayList();
        $this->_estimations = new TechDivision_Collections_ArrayList();
        $this->_average = new TechDivision_Lang_Integer(0);
        $this->_subtotalAverage = new TechDivision_Lang_Integer(0);
    }

    /**
     * Populates the ActionForm with the values
     * of the passed DTO.
     *
     * @param The system user who tries to make the estimation
     * @param TDProject_Project_Common_ValueObjects_EstimationViewData $dto
     * 		The DTO with the data to initialize the ActionForm with
     * @return void
     */
    function populate(
    	TDProject_Core_Common_ValueObjects_System_UserValue $systemUser,
    	TDProject_Project_Common_ValueObjects_EstimationViewData $dto) {
        $this->_estimationId = $dto->getEstimationId();
        $this->_taskIdFk = $dto->getTaskIdFk();
        $this->_userIdFk = $systemUser->getUserId();
        $this->_description = $dto->getDescription();
        $this->_unit = $dto->getUnit();
        $this->_complexity = $dto->getComplexity();
        $this->_complexities = $dto->getComplexities();
        $this->_units = $dto->getUnits();
        $this->_estimations = $dto->getEstimations();
        $this->_average = $dto->getAverage();
        $this->_subtotalAverage = $dto->getSubtotalAverage();
    }

    /**
     * Initializes and returns a new LVO
     * with the data from the ActionForm.
     *
     * @return TDProject_Project_Common_ValueObjects_EstimationLightValue
     * 		The initialized LVO
     */
    public function repopulate()
    {
		// initialize a new LVO
		$lvo = new TDProject_Project_Common_ValueObjects_EstimationLightValue();
		// filling it with the estimation data from the ActionForm
		$lvo->setEstimationId($this->getEstimationId());
    	$lvo->setTaskIdFk($this->getTaskIdFk());
		$lvo->setUserIdFk($this->getUserIdFk());
		$lvo->setDescription($this->getDescription());
		$lvo->setUnit($this->getUnit());
		$lvo->setComplexity($this->getComplexity());
		$lvo->setQuantity($this->getQuantity());
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
        // check if the estimation's quantity was entered
        if ($this->_quantity->equals(new TechDivision_Lang_Integer(0))) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::QUANTITY,
                    $this->translate('estimation-quantity.none')
                )
            );
        }
        // check if the estimation's description was entered
        if ($this->_description->length() == 0) {
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::DESCRIPTION,
                    $this->translate('estimation-description.none')
                )
            );
        }
        // return the ActionErrors
        return $errors;
    }
}