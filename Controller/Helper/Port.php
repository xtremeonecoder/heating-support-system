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

class Helper_Port extends Zend_Controller_Action_Helper_Abstract
{
    public function calculate($vesselCriterias = array()){
        // check array empty or not?
        if(!count($vesselCriterias)){return null;}
        
        // get port data from database
        $helper = Zend_Controller_Action_HelperBroker::getStaticHelper('DbTable');
        $table = $helper->getTable("settings");
        $portData = $table->getSetting('seaport_port_data')->getUnserializeData();
        $decisionCriteria = $table->getSetting('seaport_criteria')->getUnserializeData();
        
        // Calculates all scored weights per ports for ships one by one
        // Like for ship-1, X = {PORT1=869, PORT2=985, ....., PORTn=995}
        $weightPerPort = array();
        foreach($portData as $portName => $portCriterias){
            $totalPortWeight = 0;
            for($i=1; $i<=count($vesselCriterias); $i++){
                // Here port criteria and ship criteria weight cross multiplication 
                // is going on criteria by criterai for each ports
                // Like Wspc1 = MUL(Wpc1 x Wvc1)
                $totalPortWeight += $vesselCriterias[$i]*$portCriterias[$i];
            }
            $weightPerPort[$portName] = $totalPortWeight;
        }
        
        // return value
        return $weightPerPort;
    }
}
