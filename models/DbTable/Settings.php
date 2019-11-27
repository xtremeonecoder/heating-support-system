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

class Application_Model_DbTable_Settings extends Aninda_Db_Table_Abstract
{
    protected $_name = 'settings';
    protected $_rowClass = 'Application_Model_Setting';


    public function getSettings($param){
        $select = $this->select();

        // enabled
        if(isset($param)){
            $select->where('name LIKE ?', "{$param}%");
        }

        return $this->fetchAll($select);
    }

    public function getSetting($param){
        $select = $this->select();

        // enabled
        if(isset($param)){
            $select->where('name = ?', $param);
        }

        return $this->fetchRow($select);
    }
}
