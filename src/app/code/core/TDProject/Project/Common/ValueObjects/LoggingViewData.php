<?php

/**
 * TDProject_Project_Common_ValueObjects_LoggingViewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/Collections/Interfaces/Collection.php';
require_once 'TechDivision/Collections/ArrayList.php';
require_once 'TechDivision/Model/Interfaces/LightValue.php';
require_once 'TDProject/Project/Common/ValueObjects/TaskUserLightValue.php';

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
class TDProject_Project_Common_ValueObjects_LoggingViewData 
    extends TDProject_Project_Common_ValueObjects_TaskUserValue 
    implements TechDivision_Model_Interfaces_LightValue {
        
    /**
     * The ID of the project the task belongs to.
     * @var TechDivision_Lang_Integer
     */
    protected $_projectIdFk = null;
        
    /**
     * The seconds of the actual day, the user has tasks logged.
     * @var TechDivision_Lang_Integer
     */
    protected $_secondsThisDay = null;
        
    /**
     * The seconds of the actual week, the user has tasks logged.
     * @var TechDivision_Lang_Integer
     */
    protected $_secondsThisWeek = null;
        
    /**
     * The seconds of the actual month, the user has tasks logged.
     * @var TechDivision_Lang_Integer
     */
    protected $_secondsThisMonth = null;
        
    /**
     * The billable seconds of the actual month, the user has tasks logged.
     * @var TechDivision_Lang_Integer
     */
    protected $_billableSecondsThisMonth = null;
    
    /**
     * The user's performance in percent.
     * @var TechDivision_Lang_Integer
     */
    protected $_performance = null;
    
    /**
     * The tasks related with the project.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_loggings = null;
    
    /**
     * The available projects.
     * @var TechDivision_Collections_Interfaces_Collection
     */
    protected $_projects = null;
    
    /**
     * The constructor to intializes the DTO.
     *
     * @param TDProject_Project_Common_ValueObjects_TaskUserValue $vo
     * 		The logging entry to initialize the DTO with
     * @return void
     */
    public function __construct(
    	TDProject_Project_Common_ValueObjects_TaskUserValue $vo) {
    	// call the constructor of the parent class
    	parent::__construct($vo);
        // initialize the members
        $this->_projectIdFk= new TechDivision_Lang_Integer(0);
        $this->_secondsThisDay = new TechDivision_Lang_Integer(0);
        $this->_secondsThisWeek = new TechDivision_Lang_Integer(0);
        $this->_secondsThisMonth = new TechDivision_Lang_Integer(0);
        $this->_billableSecondsThisMonth = new TechDivision_Lang_Integer(0);
        $this->_performance = new TechDivision_Lang_Integer(0);
        $this->_loggings = new TechDivision_Collections_ArrayList();
        $this->_projects = new TechDivision_Collections_ArrayList();
    }
        
    /**
     * Sets a Collection with the last 10 loggings of the user.
     * 
     * @param TechDivision_Collections_Interfaces_Collection $tasks
     * 		The last 10 loggings
     * @return void
     */
    public function setLoggings(
        TechDivision_Collections_Interfaces_Collection $loggings) {
        $this->_loggings = $loggings;
    }
        
    /**
     * Returns a Collection with the last 10 loggings of the user.
     * 
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The last 10 loggings
     */
    public function getLoggings()
    {
        return $this->_loggings;
    }
        
    /**
     * Sets a Collection with the available projects.
     * 
     * @param TechDivision_Collections_Interfaces_Collection $projects
     * 		The available projects
     * @return void
     */
    public function setProjects(
        TechDivision_Collections_Interfaces_Collection $projects) {
        $this->_projects = $projects;
    }
        
    /**
     * Returns a Collection with the available projects.
     * 
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The available projects
     */
    public function getProjects()
    {
        return $this->_projects;
    }
    
    /**
     * Sets the seconds of the actual day, the user
     * has tasks logged.
     * 
     * @param TechDivision_Lang_Integer $seconds
     * 		The seconds for the logged tasks
     * @return void
     */
    public function setSecondsThisDay(TechDivision_Lang_Integer $seconds) 
    {
        $this->_secondsThisDay = $seconds;    
    }
    
    /**
     * Returns the seconds of the actual day, the user
     * has tasks logged.
     * 
     * @return TechDivision_Lang_Integer $seconds
     * 		The seconds for the logged tasks
     */
    public function getSecondsThisDay()
    {
        return $this->_secondsThisDay;
    }
    
    /**
     * Sets the seconds of the actual week, the user
     * has tasks logged.
     * 
     * @param TechDivision_Lang_Integer $seconds
     * 		The seconds for the logged tasks
     * @return void
     */
    public function setSecondsThisWeek(TechDivision_Lang_Integer $seconds) 
    {
        $this->_secondsThisWeek = $seconds;    
    }
    
    /**
     * Returns the seconds of the actual week, the user
     * has tasks logged.
     * 
     * @return TechDivision_Lang_Integer $seconds
     * 		The seconds for the logged tasks
     */
    public function getSecondsThisWeek()
    {
        return $this->_secondsThisWeek;
    }
    
    /**
     * Sets the seconds of the actual month, the user
     * has tasks logged.
     * 
     * @param TechDivision_Lang_Integer $seconds
     * 		The seconds for the logged tasks
     * @return void
     */
    public function setSecondsThisMonth(TechDivision_Lang_Integer $seconds) 
    {
        $this->_secondsThisMonth = $seconds;    
    }
    
    /**
     * Returns the seconds of the actual month, the user
     * has tasks logged.
     * 
     * @return TechDivision_Lang_Integer $seconds
     * 		The seconds for the logged tasks
     */
    public function getSecondsThisMonth()
    {
        return $this->_secondsThisMonth;
    }
    
    /**
     * Sets the project ID the task belongs to.
     * 
     * @param TechDivision_Lang_Integer $projectIdFk
     * 		The project ID the task belongs to
     * @return void
     */
    public function setProjectIdFk(TechDivision_Lang_Integer $projectIdFk) 
    {
        $this->_projectIdFk = $projectIdFk;    
    }
    
    /**
     * Returns the project ID the task belongs to.
     * 
     * @return TechDivision_Lang_Integer $seconds
     * 		The project ID the task belongs to
     */
    public function getProjectIdFk()
    {
        return $this->_projectIdFk;
    }
    
    /**
     * Sets the billable seconds of the actual month, the user
     * has tasks logged.
     * 
     * @param TechDivision_Lang_Integer $seconds
     * 		The billable seconds for the logged tasks
     * @return void
     */
    public function setBillableSecondsThisMonth(
        TechDivision_Lang_Integer $seconds) {
        $this->_billableSecondsThisMonth = $seconds;    
    }
    
    /**
     * Returns the billable seconds of the actual month, the user
     * has tasks logged.
     * 
     * @return TechDivision_Lang_Integer $seconds
     * 		The billable seconds for the logged tasks
     */
    public function getBillableSecondsThisMonth() {
        return $this->_billableSecondsThisMonth;
    }
    
    /**
     * Sets the user's actual monthly performance in percent.
     * 
     * @param TechDivision_Lang_Integer $performance
     * 		The user's performance in percent
     * @return void
     */
    public function setPerformance(TechDivision_Lang_Integer $performance) 
    {
        $this->_performance = $performance;    
    }
    
    /**
     * Returns the user's actual monthly performance in percent.
     * 
     * @return TechDivision_Lang_Integer
     * 		The user's performance in percent
     */
    public function getPerformance()
    {
        return $this->_performance;
    }
}