<?php

/**
 * TDProject_Project_Model_Assembler_Template
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
class TDProject_Project_Model_Assembler_Template
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
        return new TDProject_Project_Model_Assembler_Template($container);
    }
        
    /**
     * Returns a VO with the data of the template
     * with the passed ID.
     * 
     * @param TechDivision_Lang_Integer $templateId
     * 		The template ID to return the VO for
     * @return TDProject_ERP_Common_ValueObjects_TemplateValue
     * 		The requested VO
     */
    public function getTemplateViewData(
        TechDivision_Lang_Integer $templateId =  null) {       
		// check if a template ID was passed
		if(!empty($templateId)) { 
		    // if yes, load the template
			$template = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
			    ->findByPrimaryKey($templateId);
		} else {
    		// if not, initialize a new template
    		$template = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
    		    ->epbCreate();		
		}
		// initialize and return the VO
		$dto = new TDProject_Project_Common_ValueObjects_TemplateViewData(
		    $template
		);
    	// set the tasks related to the template
		if (!empty($templateId)) {
    		$dto->setTasks(
    		    $this->getTaskOverviewData($templateId)
    		);
		}
		// return the initialized DTO
		return $dto;
    }
    
    /**
     * Load the available projects, assembles them into
     * TemplateLightValue and returns them as an ArrayList.
     * 
     * @return TechDivision_Collections_ArrayList
     * 		The assembled project enitities
     */
    public function getTemplateLightValues() {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // load the Collection with all available templates
        $collection = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
            ->findAll();
        // assemble the entities
        foreach ($collection as $template) {
            $list->add($template->getLightValue());
        }
        // return the ArrayList with the TemplateLightValue's
        return $list;
    }
    
    /**
     * Load the available projects, assembles them into
     * TemplateOverviewData and returns them as an ArrayList.
     * 
     * @return TechDivision_Collections_ArrayList
     * 		The assembled template enitities
     */
    public function getTemplateOverviewData() {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // load the Collection with all available templates
        $collection = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
            ->findAll();
        // assemble the entities
        foreach ($collection as $template) {
            $list->add(
            	new TDProject_Project_Common_ValueObjects_TemplateOverviewData($template)
            );
        }
        // return the ArrayList with the TemplateOverviewData elements
        return $list;
    }
    
    /**
     * Load the tasks of the template with the passed ID, 
     * assembles them into TaskOverviewData DTO's and 
     * returns them as an ArrayList.
     * 
     * @param TechDivision_Lang_Integer $templateId
     * 		The template ID to initialize the tasks for
     * @return TDProject_Project_Common_ValueObjects_Collections_Task
     * 		The assembled task enitities
     */
    public function getTaskOverviewData(
        TechDivision_Lang_Integer $templateId) {
        // initialize a new ArrayList
        $list = new TDProject_Project_Common_ValueObjects_Collections_Task();
        // load the template
        $template = TDProject_Project_Model_Utils_TemplateUtil::getHome($this->getContainer())
            ->findByPrimaryKey($templateId);
        // initialize the Assembler for the task DTO's
        $assembler = TDProject_Project_Model_Assembler_Task::create($this->getContainer());
        // iterate over the tasks related with the template
        foreach ($template->getTasks() as $task) {
            $list->add($assembler->getTaskViewData($task->getTaskId()));
        }
        // return the ArrayList with the TaskOverviewData DTO's
        return $list;
    }
}