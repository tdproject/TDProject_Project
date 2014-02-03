<?php

/**
 * TDProject_Project_Controller_Solr
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
 * 		Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Task_Search extends TDProject_Project_Controller_Abstract
{
    
    /**
     * The key for the ActionForward to the search overview.
     * @var string
     */
    const SEARCH_OVERVIEW = "SearchOverview";

    /**
     * This method is automatically invoked by the controller and implements
     * the functionality to load a list with with all tasks of the project
     * with the ID passed as Request parameter.
     *
     * @return void
     */
    public function __defaultAction()
    {
        try {
            
            
            $params = new TechDivision_Collections_HashMap();
            
            $q = $this->_getRequest()->getParameter('q', FILTER_SANITIZE_STRING);
            
            if (empty($q)) {
                $q = '*:*';
            }

            $params->add('q', $q);
            
            $fq = $this->_getRequest()->getParameterValues('fq');
            
            $this->getLogger()->debug(var_export($fq, true));
            
            if (!empty($fq)) {
                $params->add('fq', $fq);
            }
            
            $dto = $this->_getDelegate()->getSearchViewData($params);
            
            $this->getContext()->setAttribute('searchViewData', $dto);
            
        } catch (Exception $e) {
            // create and add and save the error
            $errors = new TechDivision_Controller_Action_Errors();
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                    $e->__toString()
                )
            );
            // adding the errors container to the Request
            $this->_saveActionErrors($errors);
            // set the ActionForward in the Context
            return $this->_findForward(
                TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
            );
        }
        // go to the standard page
        return $this->_findForward(
            TDProject_Project_Controller_Task_Search::SEARCH_OVERVIEW
        );
    }

    /**
     * This method is automatically invoked by the controller and implements
     * the functionality to reorganize the Solr documents.
     *
     * @return void
     */
    public function reorgAction()
    {
        try {
            
            $this->_getDelegate()->reorg();
            
        } catch (Exception $e) {
            // create and add and save the error
            $errors = new TechDivision_Controller_Action_Errors();
            $errors->addActionError(
                new TechDivision_Controller_Action_Error(
                    TDProject_Project_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                    $e->__toString()
                )
            );
            // adding the errors container to the Request
            $this->_saveActionErrors($errors);
            // set the ActionForward in the Context
            return $this->_findForward(
                TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
            );
        }
        // go to the standard page
        return $this->_findForward(
            TDProject_Project_Controller_Task_Search::SEARCH_OVERVIEW
        );       
    }
    
}