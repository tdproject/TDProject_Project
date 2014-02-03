<?php

/**
 * TDProject_Project_Model_Actions_Task_Solr
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * This class provides the functionality to handle task in the Solr index.
 *
 * @category    TDProject
 * @package     TDProject_Project
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Model_Actions_Task_Solr extends TDProject_Core_Model_Actions_Abstract
{
    /**
     * Domain name of the Solr server.
     * @var string
     */

    const SOLR_SERVER_HOSTNAME = 'localhost';

    /**
     * Whether or not to run in secure mode.
     * @var boolean
     */
    const SOLR_SECURE = true;

    /**
     * HTTP Port to connection, if secure 8443, else 8983.
     * @var integer
     */
    const SOLR_SERVER_PORT = 8983;

    /**
     * HTTP Basic Authentication Username.
     * @var string
     */
    const SOLR_SERVER_USERNAME = 'admin';

    /**
     * HTTP Basic Authentication password
     * @var string
     */
    const SOLR_SERVER_PASSWORD = 'changeit';

    /**
     * HTTP connection timeout, the maximum time in seconds allowed for the 
     * HTTP data transfer operation. Default value is 30 seconds.
     * @var integer
     */
    const SOLR_SERVER_TIMEOUT = 10;

    /**
     * File name to a PEM-formatted private key + private certificate
     * (concatenated in that order).
     * @var string
     */
    const SOLR_SSL_CERT = 'certs/combo.pem';

    /**
     * File name to a PEM-formatted private certificate only.
     * @var string
     */
    const SOLR_SSL_CERT_ONLY = 'certs/solr.crt';

    /**
     * File name to a PEM-formatted private key .
     * @var string
     */
    const SOLR_SSL_KEY = 'certs/solr.key';

    /**
     * Password for PEM-formatted private key file.
     * @var string
     */
    const SOLR_SSL_KEYPASSWORD = 'StrongAndSecurePassword';

    /**
     * Name of file holding one or more CA certificates to verify peer with.
     * @var string
     */
    const SOLR_SSL_CAINFO = 'certs/cacert.crt';

    /**
     * Name of directory holding multiple CA certificates to verify peer with.
     * @var string
     */
    const SOLR_SSL_CAPATH = 'certs/';

    /**
     * The Solr client instance.
     * @var SolrClient
     */
    protected $_client = null;

    /**
     * Initializes the action with the passed container instance.
     * 
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return void
     */
    public function __construct(TechDivision_Model_Interfaces_Container $container)
    {
        // call parent constructor
        parent::__construct($container);
        // initialize the Solr options
        $options = array(
            'hostname' => self::SOLR_SERVER_HOSTNAME,
            'login' => self::SOLR_SERVER_USERNAME,
            'password' => self::SOLR_SERVER_PASSWORD,
            'port' => self::SOLR_SERVER_PORT
        );
        // initialize the Solr client
        $this->_client = new SolrClient($options);
    }

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Project_Model_Actions_Task_Solr
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Actions_Task_Solr($container);
    }

    /**
     * Creates a new Solr document with the data base on the passed task.
     * 
     * @param TDProject_Project_Common_ValueObjects_TaskValue $task
     *     The task to create the Solr document from
     * @return string The task name
     */
    public function createDocument(TDProject_Project_Common_ValueObjects_TaskValue $task)
    {
        // create a new Solr document
        $doc = new SolrInputDocument();
        // check if a parent task is available
        $parent = $task->getTask();
        // if it is, and the task ID is NOT the parent task ID
        if ($parent != null && !$task->getTaskId()->equals($task->getTaskIdFk())) {
            $doc->addField('category', $this->createDocument($parent));
        }
        // load the project
        $project = TDProject_Project_Model_Assembler_Project::create($this->getContainer())
            ->getProjectLightValueByTaskId($task->getTaskId());
        // add the data to the document
        $doc->addField('task_id', $task->getTaskId()->intValue());
        $doc->addField('name', $taskName = $task->getName()->stringValue());
        $doc->addField('description', $task->getDescription()->stringValue());
        $doc->addField('billable', $task->getBillable()->booleanValue());
        $doc->addField('costCenter', $task->getCostCenter()->intValue());
        $doc->addField('projectIdFk', $project->getProjectId()->intValue());
        $doc->addField('projectName', $project->getName()->stringValue());
        $doc->addField('taskTypeIdFk', $task->getTaskTypeIdFk()->intValue());
        $doc->addField('taskTypeName', $task->getTaskType()->getName()->stringValue());
        // add the Solr document to the index
        $this->_client->addDocument($doc);
        // commit the transaction
        $this->_client->commit();
        // return the task name
        return $taskName;
    }
    
}