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
class TDProject_Project_Model_Assembler_Task_Solr extends TDProject_Project_Model_Assembler_Task
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
     * @return TDProject_Project_Model_Assembler_Task_Solr
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Assembler_Task_Solr($container);
    }

    /**
     * Runs a query with the passed string and returns the result.
     * 
     * @param TechDivision_Collections_HashMap $params A Collection with the search params
     * @return string The query result
     */
    public function getSearchViewData(TechDivision_Collections_HashMap $params)
    {
        
        try {
            
            $message = $this->getContainer()->lookup('TDProject_Project_Model_Message_Solr', md5('test'));
            $message->onMessage('Test');
            
            $solrQuery = new SolrQuery();
            
            if ($params->exists('q')) {
                $solrQuery->setQuery($params->get('q'));
            }
            
            $solrQuery->setQuery('*:*');
            
            $solrQuery->setFacet(true);
            $solrQuery->setFacetMinCount(1);

            if ($params->exists('fq')) {
                foreach ($params->get('fq') as $facetQuery) {
                    $solrQuery->addFilterQuery($facetQuery);
                }
            }

            $solrQuery->addField('category')->addField('name')->addField('projectName')->addField('taskTypeName');

            $solrQuery->addFacetField('category')->addFacetField('billable')->addFacetField('taskTypeName');

            $this->getLogger()->debug("Now running Solr query: " . $solrQuery->toString());

            $queryResponse = $this->_client->query($solrQuery);

            $dto = new TDProject_Project_Common_ValueObjects_SearchViewData();

            $response = $queryResponse->getResponse();

            $this->getLogger()->debug(var_export($response, true));

            $tasks = new TechDivision_Collections_ArrayList();

            foreach ($response->response->docs as $solrObject) {
                $tasks->add($solrObject);
            }

            $dto->setTasks($tasks);

            $facets = new TechDivision_Collections_HashMap();

            foreach ($response->facet_counts->facet_fields as $facetName => $solrObject) {
                $facets->add($facetName, $solrObject);
            }

            $dto->setFacets($facets);

            return $dto;
        } catch (SolrClientException $sce) {
            $this->getLogger()->error($sce->__toString());
        }
    }
}