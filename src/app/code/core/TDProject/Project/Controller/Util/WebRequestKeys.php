<?php

/**
 * TDProject_Project_Controller_Util_WebRequestKeys
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Common/Util/WebRequestKeys.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Controller_Util_WebRequestKeys
	extends TDProject_Common_Util_WebRequestKeys {

	/**
	 * The Request parameter key with the project ID.
	 * @var string
	 */
	const PROJECT_ID = "projectId";

	/**
	 * The Request parameter key with the template ID.
	 * @var string
	 */
	const TEMPLATE_ID = "templateId";

	/**
	 * The Request parameter key for the Controller method to invoke.
	 * @var string
	 */
	const METHOD = "method";

	/**
	 * The Request parameter key with the task ID.
	 * @var string
	 */
	const TASK_ID = "taskId";

	/**
	 * The Request parameter key for the result of a JSON operation.
	 * @var string
	 */
	const JSON_RESULT = "jsonResult";

	/**
	 * The Request parameter key with some ID value.
	 * @var string
	 */
	const ID = "id";

	/**
	 * The Request parameter key with the ID of a task-user relation.
	 * @var string
	 */
	const TASK_USER_ID = "taskUserId";

	/**
	 * The Request parameter key with the estimation ID.
	 * @var string
	 */
	const ESTIMATION_ID = "estimationId";

	/**
	 * The Request parameter key with a task ID foreign key.
	 * @var string
	 */
	const TASK_ID_FK = "taskIdFk";

	/**
	 * The Request parameter key with a user ID foreign key.
	 * @var string
	 */
	const USER_ID_FK = "userIdFk";
	
	/**
	 * The Request parameter key for the name of an input field.
	 * @var string
	 */
	const INPUT_NAME = "inputName";
	
	/**
	 * The Request parameter key for the flag to ignore a task's selectable flag.
	 * @var string
	 */
	const IGNORE_SELECTABLE = "ignoreSelectable";

	/**
	 * The Request parameter key with the project cycle ID.
	 * @var string
	 */
	const PROJECT_CYCLE_ID = "projectCycleId";
}