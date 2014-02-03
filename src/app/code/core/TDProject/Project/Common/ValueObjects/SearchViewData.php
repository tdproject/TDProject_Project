<?php

/**
 * TDProject_Project_Common_ValueObjects_SearchViewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * This class is the data transfer object between the
 * model and the controller for the task overview.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_SearchViewData 
    extends TechDivision_Lang_Object
    implements TechDivision_Model_Interfaces_Value
{

    /**
     * The found tasks.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_tasks = null;

    /**
     * The facets for the found tasks.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_facets = null;

    /**
     * The constructor intializes the DTO with the
     * values passed as parameter.
     * 
     * @return void
     */
    public function __construct()
    {
        // initialize the members
        $this->_tasks = new TechDivision_Collections_ArrayList();
        $this->_facets = new TechDivision_Collections_ArrayList();
    }

    /**
     * Sets the tasks.
     * 
     * @param TechDivision_Collections_Interfaces_Collection $tasks
     * 		The tasks
     * @return void
     */
    public function setTasks(
        TechDivision_Collections_Interfaces_Collection $tasks)
    {
        $this->_tasks = $tasks;
    }

    /**
     * Returns the tasks.
     * 
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The tasks
     */
    public function getTasks()
    {
        return $this->_tasks;
    }

    /**
     * Sets the facets for the found tasks.
     * 
     * @param TechDivision_Collections_Interfaces_Collection $facets
     * 		The facets
     * @return void
     */
    public function setFacets(
        TechDivision_Collections_Interfaces_Collection $facets)
    {
        $this->_facets = $facets;
    }

    /**
     * Returns the facets for the found tasks.
     * 
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The facets
     */
    public function getFacets()
    {
        return $this->_facets;
    }

}