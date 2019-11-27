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

class Application_Model_Page extends Zend_Db_Table_Row_Abstract
{
    public function getIdentity(){
        return (int) $this->page_id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getPageName(){
        return $this->name;
    }

    public function isEnabled(){
        return $this->enabled;
    }

    public function getMetaKeys(){
        return $this->keywords;
    }

    public function getMetaDesc(){
        return $this->description;
    }

    public function creationDate(){
        return $this->creation_date;
    }

    public function modifiedDate(){
        return $this->creation_date;
    }
}
