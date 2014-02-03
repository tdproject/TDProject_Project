<?php

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/Model/Interfaces/LightValue.php';

/**
 * This class is the data transfer object between the
 * model and the controller for the table task.
 *
 * Each class member reflects a database field and
 * the values of the related dataset.
 *
 * @package common
 * @author generator <core@techdivision.com>
 * @version $Revision: 1.2 $ $Date: 2007-12-06 15:39:17 $
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 */
class TDProject_Project_Common_ValueObjects_CumulatedLoggingLightValue
	extends TechDivision_Lang_Object
	implements TechDivision_Model_Interfaces_LightValue {

	protected $_user;

	protected $_minutes;

	/**
	 * The constructor intializes the DTO with the
	 * values passed as parameter.
	 *
	 * @return void
	 */
	public function __construct(
	    TDProject_Core_Common_ValueObjects_UserLightValue $user) {
	    $this->_user = $user;
        $this->_minutes = new TechDivision_Lang_Integer(0);
	}

	public function getUser()
	{
	    return $this->_user;
	}

	public function getUserName()
	{
	    return $this->getUser()->getUsername();
	}

	public function getMinutes()
	{
	    return $this->_minutes;
	}
}