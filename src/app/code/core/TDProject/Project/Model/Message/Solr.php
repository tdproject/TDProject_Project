<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Solr
 *
 * @author wagnert
 */
class TDProject_Project_Model_Message_Solr extends PHP_Fork implements TechDivision_Model_Interfaces_Message {

    protected $_messageId = null;
    protected $_container = null;
    protected $_conunter = 0;


    public function __construct() {
        PHP_Fork::PHP_Fork(__CLASS__);
    }

    public function connect(TechDivision_Model_Interfaces_Container $container) {
        $this->_container = $container;
        return $this;
    }

    public function load($messageId) {
        // set the message ID
        return $this->_messageid = $messageId;
        // fork the message
        $this->start();
        
        return $this;
    }

    public function onMessage($message) {
        // log the message itself
        error_log("Message is: $message");
        
        return $this;
    }

    public function run() {
        while (true) {
            error_log("Now in loop iteration: " . $this->_conunter++);
            sleep(1);
        }
    }

    /**
     * Returns the cache key 
     *
     * @return TechDivision_Lang_Integer
     * 		Holds the value of the primary key field
     */
    public function getCacheKey() {
        return;
    }

    /**
     * Returns the cache tags
     *
     * @return array Array with the entities cache tags
     */
    public function getCacheTags() {
        return;
    }

    /**
     * Returns the value of the primary key field
     *
     * @return TechDivision_Lang_Integer
     * 		Holds the value of the primary key field
     */
    public function getPrimaryKey() {
        return $this->_messageId;
    }

    /**
     * This method returns the container that
     * handles the entity.
     *
     * @return TechDivision_Model_Interfaces_Container
     * 		Returns a reference to the container used to handle the storable
     */
    public function getContainer() {
        return $this->_container;
    }

    /**
     * This method returns the classname of the
     * actual object.
     *
     * @return string Holds the classname of the actual object
     */
    public function getClass() {
        return __CLASS__;
    }

    /**
     * Destroys the internal references that are not serializable, e. g.
     * reference to the container instance.
     *
     * @return TechDivision_Model_Interfaces_Entity
     * 		The instance itself
     */
    public function disconnect() {
        return;
    }

}
