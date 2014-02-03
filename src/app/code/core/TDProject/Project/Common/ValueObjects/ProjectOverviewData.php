<?php

/**
 * TDProject_Project_Common_ValueObjects_ProjectOverviewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/ERP/Common/ValueObjects/CompanyLightValue.php';
require_once 'TDProject/Project/Common/ValueObjects/ProjectLightValue.php';
require_once 'TDProject/Core/Interfaces/Block/Widget/Element/Select/Option.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the project overview.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Common_ValueObjects_ProjectOverviewData
    extends TDProject_Project_Common_ValueObjects_ProjectLightValue
    implements TDProject_Core_Interfaces_Block_Widget_Element_Select_Option {

    /**
     * The company ordered the project.
     * @var TDProject_ERP_Common_ValueObjects_CompanyLightValue
     */
    protected $_company = null;

    /**
     * Sets the company ordered the project.
     *
     * @param TDProject_ERP_Common_ValueObjects_CompanyLightValue $company
     * 		The company ordered the project
     * @return void
     */
    public function setCompany(
        TDProject_ERP_Common_ValueObjects_CompanyLightValue $company) {
        $this->_company = $company;
    }

    /**
     * Returns the company ordered the project.
     *
     * @return TDProject_ERP_Common_ValueObjects_CompanyLightValue
     * 		The company ordered the project
     */
    public function getCompany() {
        return $this->_company;
    }

    /**
     * Returns the company's name.
     *
     * @return TechDivision_Lang_String
     * 		The company name
     */
    public function getCompanyName() {
    	return $this->getCompany()->getName();
    }

	/**
	 * (non-PHPdoc)
	 * @see TDProject_Core_Interfaces_Block_Widget_Element_Select_Option::getOptionValue()
	 */
   	public function getOptionValue() {
   		return $this->getProjectId();
   	}

   	/**
   	 * (non-PHPdoc)
   	 * @see TDProject_Core_Interfaces_Block_Widget_Element_Select_Option::getOptionLabel()
   	 */
   	public function getOptionLabel() {
   		return $this->getName();
   	}

   	/**
   	 * (non-PHPdoc)
   	 * @see TDProject_Core_Interfaces_Block_Widget_Element_Select_Option::isSelected()
   	 */
   	public function isSelected(TechDivision_Lang_Object $value = null) {
   		if ($value != null) {
   			return $this->getProjectId()->equals($value);
   		}
   		return false;
   	}
}