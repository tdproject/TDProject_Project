<?php

/**
 * TDProject_Project_Block_Task_Solr
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Block_Task_Search extends TDProject_Core_Block_Abstract {

    /**
     * Initialize the block with the
     * apropriate template and name.
     *
     * @return void
     */
    public function __construct(
        TechDivision_Controller_Interfaces_Context $context) {
        // set the template name
        $this->_setTemplate('www/design/project/templates/task/search.phtml');
        // call the parent constructor
        parent::__construct($context);
    }

    public function getSearchUrl($facetName, $facetValue)
    {
        
        if (!is_array($fq = $this->getRequest()->getParameterValues('fq'))) {
            $fq = array();
        }
        
        // initialize the array with parameters
        $params = array(
            'path' => '/task/search',
            'q' => $this->getRequest()->getParameter('q', FILTER_SANITIZE_STRING),
            'fq' => array_merge($fq, array("$facetName:$facetValue"))
        );
        // create and return the URL
        return $this->getUrl($params);
    }

}