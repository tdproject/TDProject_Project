<?php

/**
 * TDProject_Project_Block_Logging_Form_Widget
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Project/Block/Logging/Form.php';

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

class TDProject_Project_Block_Logging_Form_Widget
    extends TDProject_Project_Block_Logging_Form {

    /**
     * The string with the data to log.
     * @var TechDivision_Lang_String
     */
    protected $_toLog = null;

    /**
     * Setter method for the concatenated string with
     * the values to log.
     *
     * @param string $string The string with the values to log
     */
    public function setToLog($string)
    {
        $this->_toLog = new TechDivision_Lang_String($string);
    }

    /**
     * Getter method for the concatenated string with
     * the values to log.
     *
     * @return TechDivision_Lang_String The string with the values to log
     */
    public function getToLog()
    {
        return $this->_toLog;
    }

    /**
     * Resets all member variables to their
     * default values.
     *
     * @return void
     */
    function reset()
    {
    	// reset the parent form
    	parent::reset();
        // initialize the template with dummy values
        $values = array(
            0,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s', time() + 1800),
            'Kurzbeschreibung'
        );
        // concatenate and set the values
        $this->_toLog = new TechDivision_Lang_String(implode('/', $values));
    }

    /**
     * Explodes the passed string with the concatenated
     * logging values and sets the internal members.
     *
     * @return void
     */
    protected function _explode()
    {
        // explode the string
        $values = explode('/', $this->getToLog());
        // check if the expected 4 values was found
        if (sizeof($values) !== 4) {
            return;
        }
        // set the internal values
        $this->setTaskIdFk($values[0]);
        $this->setFrom($values[1]);
        $this->setUntil($values[2]);
        $this->setDescription($values[3]);
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
        // explode and validate the values
        $this->_explode();
        return parent::validate();
    }
}