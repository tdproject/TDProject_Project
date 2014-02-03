<?php

/**
 * TDProject_Project_Model_Widget_Converter_Task
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
 * @copyright  	Copyright (c) 2011 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Model_Widget_Converter_Task
    implements TDProject_Report_Model_Widget_Interfaces_Converter
{
    
    /**
     * The container instance.
     * @var TechDivision_Model_Interfaces_Container
     */
    protected $_container = null;
	
	/**
	 * Default constructor to avoid ReflectionException.
	 * 
	 * @param TechDivision_Model_Interfaces_Container $container The container instance
	 * @return void
	 */
	public function __construct(TechDivision_Model_Interfaces_Container $container)
	{
		$this->_container = $container;
	}
    
    /**
     * Returns the container instance.
     * 
     * @return TechDivision_Model_Interfaces_Container
     *     The container instance
     */
    public function getContainer()
    {
        return $this->_container;
    }
	
    /**
     * Sets the passed value converted in an integer that represents 
     * the selected user value.
     * 
     * @param TDProject_Report_Model_Widget_Interfaces_ReportField $reportField The report field instance
     * @see TDProject_Report_Model_Widget_Interfaces_Converter::convert()
     */
    public function convert(
    	TDProject_Report_Model_Widget_Interfaces_ReportField $reportField)
    {
    	// load the old/new keys from the passed report field
    	$oldKey = $reportField->getOldKey();
    	// explode keys for task + project ID
    	list ($taskId, $projectId) = explode(',', $reportField->getNewKey()->stringValue());
    	// create an Integer representation
    	$value = new TechDivision_Lang_Integer(intval($reportField->getValue($oldKey)));
        // replace the old parameter (task ID)
        $reportField->removeValue($oldKey)->setValue(new TechDivision_Lang_String($taskId), $value);
     	// load the tasks project
     	$projectTasks = TDProject_Project_Model_Utils_ProjectTaskUtil::getHome($this->getContainer())
     		->findAllByTaskIdFk($value);
        // and set the project ID as parameter
     	foreach ($projectTasks as $projectTask) {
        	$reportField->setValue(
        		new TechDivision_Lang_String($projectId),
        		$projectTask->getProjectIdFk()
        	);
     	}
    }
}