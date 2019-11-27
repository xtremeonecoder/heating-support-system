<?php
/**
 * Sea-Port Recommendation System
 *
 * @category   Application_Core
 * @package    seaport-recommender
 * @author     Suman Barua
 * @developer  Suman Barua <sumanbarua576@gmail.com>
 */

/**
 * @category   Application_Core
 * @package    seaport-recommender
 */

class Helper_DbTable extends Zend_Controller_Action_Helper_Abstract
{
    public function getTable($tableName = false) {
        if(!$tableName){
            return null;
        }

        $tableName = str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName)));
        $className = "Application_Model_DbTable_{$tableName}";
        return new $className();
    }
}
