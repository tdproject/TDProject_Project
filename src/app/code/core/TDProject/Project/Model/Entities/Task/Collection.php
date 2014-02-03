<?php

/**
 * TDProject_Project_Model_Entities_Task_Collection
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * Abstract implementation of a Collection for entities that supports caching.
 *
 * @category   	TDProject
 * @package     TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Model_Entities_Task_Collection
	extends TechDivision_Model_Collections_Abstract
{
		
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Collections_Abstract::getLocalHome()
	 */
    public function getLocalHome()
    {
    	return TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer());
    }
	
	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Model_Interfaces_Collections_Cachable::load()
	 */
	public function load()
	{
		// initialize the items if cached values are available
		foreach ($this->_cacheKeys as $counter => $primaryKey) {
			$this->_items[$counter] = $this->get($counter);
		}
		// return the instance
		return $this;	
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_Collections_AbstractCollection::get()
	 */
	public function get($key)
	{
		// first check if the entity is available in the internal array
		if (parent::exists($key)) {
			return parent::get($key);
		}
		// load the cache key
		$cacheKey = new TechDivision_Lang_Integer((int) $this->_cacheKeys[$key]);
		// return the entity from the Container
		return $this->getLocalHome()->findByPrimaryKey($cacheKey);
	}
}