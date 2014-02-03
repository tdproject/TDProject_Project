<?php

/**
 * TDProject_Project_Block_Project_View_Estimations_List
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Abstract.php';

/**
 * @category    TDProject
 * @package     TDProject_Core
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Estimation_View_List
    extends TDProject_Core_Block_Abstract {

    /**
     * The ActionForm with the data.
     * @var TDProject_Project_Block_Estimation_View
     */
    protected $_form = null;

    /**
     * Initialize the block with the
     * apropriate template and name.
     *
     * @return void
     */
    public function __construct(
        TDProject_Project_Block_Estimation_View $form) {
        // call the parent constructor
        parent::__construct($form->getContext());
        // set the form
        $this->_form = $form;
        // set the internal name
        $this->_setBlockName('estimation');
        // set the template name
        $this->_setTemplate(
        	'www/design/project/templates/estimation/view/list.phtml'
        );
    }

    /**
     * The estimations to render
     *
     * @return TechDivision_Collections_Interfaces_Collection
     * 		The Collection with the estimations
     */
    public function getEstimations()
    {
    	return $this->_form->getEstimations();
    }

    /**
     * The average amount of time in minutes, based on
     * the estimations of the selected task AND his
     * child tasks.
     *
     * @return TechDivision_Lang_Integer
     */
    public function getAverage()
    {
    	return $this->_form->getAverage();
    }

    /**
     * The total average amount of time in minutes, based
     * on the estimations of the selected task.
     *
     * @return TechDivision_Lang_Integer
     */
    public function getSubtotalAverage()
    {
    	return $this->_form->getSubtotalAverage();
    }

    /**
     * Returns the Float with seconds, as hour.
     *
     * @param TechDivision_Lang_Integer $seconds
     * 		The minutes to render as hours
     * @return float The hours
     */
    public function asHours(TechDivision_Lang_Integer $seconds)
    {
    	return sprintf("%01.2f", $seconds->intValue() / 3600);
    }
}