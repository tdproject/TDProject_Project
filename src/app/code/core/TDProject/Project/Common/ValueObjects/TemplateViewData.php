<?php

/**
 * TDProject_Project_Common_ValueObjects_TemplateViewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Collections/Interfaces/Collection.php';
require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TechDivision/Model/Interfaces/Value.php';
require_once 'TDProject/Project/Model/Entities/Template.php';
require_once 'TDProject/Project/Common/ValueObjects/TemplateValue.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the project handling.
 *
 * Each class member reflects a database field and
 * the values of the related dataset.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_TemplateViewData 
    extends TDProject_Project_Common_ValueObjects_TemplateValue 
    implements TechDivision_Model_Interfaces_Value {
    
    /**
     * The tasks related with the project.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_tasks = null;
    
    /**
     * The constructor intializes the DTO with the
     * values passed as parameter.
     *
     * @param TDProject_Project_Model_Entities_Template $template 
     * 		The template to initialize the DTO with
     * @return void
     */
    public function __construct(TDProject_Project_Model_Entities_Template $template)
    {
        // call the parents constructor
        parent::__construct($template);
        // initialize the ValueObject with the passed data
        $this->_tasks = new TechDivision_Collections_ArrayList();
    }
        
    /**
     * Sets the tasks related to the template.
     * 
     * @param TechDivision_Collections_Interfaces_Collection $tasks
     * 		The related tasks
     * @return void
     */
    public function setTasks(
        TechDivision_Collections_Interfaces_Collection $tasks) {
        $this->_tasks = $tasks;
    }
        
    /**
     * Returns the tasks related to the template.
     * 
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The related tasks
     */
    public function getTasks()
    {
        return $this->_tasks;
    }
}