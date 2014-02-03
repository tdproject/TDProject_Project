<?php

/**
 * TDProject_Project_Model_Utils_TaskUtil
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Object.php';
require_once 'TDProject/Project/Model/Homes/TaskLocalHome.php';

/**
 * @package Model
 * @author Tim Wagner <tw@techdivision.com>
 * @version $Revision: 1.2 $ $Date: 2008-03-04 14:58:01 $
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 */
class TDProject_Project_Model_Utils_TaskUtil
    extends TechDivision_Lang_Object {

	/**
	 * Holds the LocalHome of the Task entity
	 * @var TDProject_Project_Model_Homes_TaskLocalHome
	 */
    protected static $_home = null;

	/**
	 * Returns the LocalHome of the Task entity
	 *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
	 * @return TDProject_Project_Model_Homes_TaskLocalHome
	 * 		The LocalHome of the Task entity
	 */
	public static function getHome(TechDivision_Model_Interfaces_Container $container)
	{
		if (self::$_home == null) {
    	    // initialize the column names
            $params = array(
                'task_id'         => 'id',
                'task_id_fk'      => 'rootid',
                'left_node'       => 'l',
                'right_node'      => 'r',
                'order_number'    => 'norder',
                'level'           => 'level',
                'name'            => 'name',
                'task_type_id_fk' => 'taskTypeIdFk',
                'description'     => 'description',
                'billable'        => 'billable',
                'cost_center'     => 'costCenter'
            );
            // create the instance and set the params
            self::$_home = $container->newInstance('TDProject_Project_Model_Homes_TaskLocalHome', array($container, $params));
    	    // set additional attributes
    	    self::$_home->setAttr(
    	        array(
                    'node_table'    => 'task',
                    'lock_table'    => 'task_lock'
    	        )
    	    );
		}
		// return the instance as singleton
		return self::$_home;
	}
}