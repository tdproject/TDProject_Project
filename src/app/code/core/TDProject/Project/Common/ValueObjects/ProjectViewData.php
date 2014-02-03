<?php

/**
 * TDProject_Project_Common_ValueObjects_ProjectViewData
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
require_once 'TDProject/Project/Common/ValueObjects/ProjectValue.php';

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
class TDProject_Project_Common_ValueObjects_ProjectViewData
    extends TDProject_Project_Common_ValueObjects_ProjectValue
    implements TechDivision_Model_Interfaces_Value
{

    /**
     * The projects available in the system.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_projects = null;

    /**
     * The companies available in the system.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_companies = null;

    /**
     * The tasks related with the project.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_tasks = null;

    /**
     * The templates available for creating the project's tasks.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_templates = null;

    /**
     * The available system users.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_users = null;

    /**
     * The available project-user relations.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_userIdFk = null;
    
    /**
     * The project cycle's closing date as UNIX timestamp.
     * @var TechDivision_Lang_Integer
     */
    protected $_closingDate = null;

    /**
     * The constructor intializes the DTO with the
     * values passed as parameter.
     *
     * @param TDProject_Project_Model_Entities_Project $project
     * 		The project to initialize the DTO with
     * @return void
     */
    public function __construct(
        TDProject_Project_Common_ValueObjects_ProjectValue $vo = null)
    {
        // call the parents constructor
        parent::__construct($vo);
        // initialize the ValueObject with the passed data
        $this->_projects = new TechDivision_Collections_ArrayList();
        $this->_companies = new TechDivision_Collections_ArrayList();
        $this->_tasks = new TechDivision_Collections_ArrayList();
        $this->_templates = new TechDivision_Collections_ArrayList();
        $this->_loggings = new TechDivision_Collections_ArrayList();
        $this->_users = new TechDivision_Collections_ArrayList();
        $this->_userIdFk = new TechDivision_Collections_ArrayList();
    }

    /**
     * Sets the available projects.
     *
     * @param TechDivision_Collections_Interfaces_Collection $projects
     * 		The projects available in the system
     * @return void
     */
    public function setProjects(
        TechDivision_Collections_Interfaces_Collection $projects)
    {
        $this->_projects = $projects;
    }

    /**
     * Returns the available projects.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The projects available in the system
     */
    public function getProjects()
    {
        return $this->_projects;
    }

    /**
     * Sets the available companies.
     *
     * @param TechDivision_Collections_Interfaces_Collection $companies
     * 		The companies available in the system
     * @return void
     */
    public function setCompanies(
        TechDivision_Collections_Interfaces_Collection $companies)
    {
        $this->_companies = $companies;
    }

    /**
     * Returns the available companies.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The companies available in the system
     */
    public function getCompanies()
    {
        return $this->_companies;
    }

    /**
     * Sets the tasks related to the project.
     *
     * @param TechDivision_Collections_Interfaces_Collection $tasks
     * 		The related tasks
     * @return void
     */
    public function setTasks(
        TechDivision_Collections_Interfaces_Collection $tasks)
    {
        $this->_tasks = $tasks;
    }

    /**
     * Returns the tasks related to the project.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The related tasks
     */
    public function getTasks()
    {
        return $this->_tasks;
    }

    /**
     * Sets the templates available for creating the project's tasks.
     *
     * @param TechDivision_Collections_Interfaces_Collection $templates
     * 		The templates available for creating the project's tasks
     * @return void
     */
    public function setTemplates(
        TechDivision_Collections_Interfaces_Collection $templates)
    {
        $this->_templates = $templates;
    }

    /**
     * Returns the templates available for creating the project's tasks.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The templates available for creating the project's tasks
     */
    public function getTemplates()
    {
        return $this->_templates;
    }

    /**
     * Sets the available system users.
     *
     * @param TechDivision_Collections_Interfaces_Collection $users
     * 		The available system users
     * @return void
     */
    public function setUsers(
        TechDivision_Collections_Interfaces_Collection $users)
    {
        $this->_users = $users;
    }

    /**
     * Returns the available system users.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The available system users
     */
    public function getUsers()
    {
        return $this->_users;
    }

    /**
     * Sets the Collection with the project-user relations.
     *
     * @param TechDivision_Collections_Interfaces_Collection $userIdFk
     * 		The Collection with the project-user relations
     * @return void
     */
    public function setUserIdFk(
        TechDivision_Collections_Interfaces_Collection $userIdFk)
    {
        $this->_userIdFk = $userIdFk;
    }

    /**
     * Returns the Collection with the project-user relations.
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The Collection with the project-user relations
     */
    public function getUserIdFk()
    {
        return $this->_userIdFk;
    }

    /**
     * Sets the project cycle's closing date as UNIX timestamp.
     *
     * @param TechDivision_Lang_Integer $closingDate
     * 		The project cycle's closing date as UNIX timestamp
     * @return void
     */
    public function setClosingDate(TechDivision_Lang_Integer $closingDate)
    {
        $this->_closingDate = $closingDate;
    }

    /**
     * Returns the project cycle's closing date as UNIX timestamp.
     *
     * @return TechDivision_Lang_Integer 
     * 		The project cycle's closing date as UNIX timestamp
     */
    public function getClosingDate()
    {
        return $this->_closingDate;
    }
}