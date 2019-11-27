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

class Application_Model_Setting extends Zend_Db_Table_Row_Abstract
{
    public function getSettingValue(){
        return trim($this->value);
    }
    
    public function addSettingValue($value = null){
        $this->value = trim($value);
    }
    
    public function removeSettingValue(){
        $this->value = null;
    }
    
    public function getVesselDataFile(){
        if($this->value){
            # Define path to uploads directory
            $fileDirectoryPath = $_SERVER['DOCUMENT_ROOT'];
            if($fileDirectoryPath == '/opt/lampp/htdocs'){
                $fileDirectoryPath .= '/apfk'; // for localhost
            }
            return $fileDirectoryPath .= '/public/vessels/'.trim($this->getSettingValue());
        }else{
            return null;
        }
    }
    
    public function getPortDataFile(){
        if($this->value){
            # Define path to uploads directory
            $fileDirectoryPath = $_SERVER['DOCUMENT_ROOT'];
            if($fileDirectoryPath == '/opt/lampp/htdocs'){
                $fileDirectoryPath .= '/apfk'; // for localhost
            }
            return $fileDirectoryPath .= '/public/seaports/'.trim($this->getSettingValue());
        }else{
            return null;
        }
    }
    
    public function getUnserializeData(){
        if($this->value){
            return unserialize($this->value);
        }else{
            return array();
        }
    }
}
