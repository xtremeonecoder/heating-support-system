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

class Aninda_View_Helper_ChartAllShipWidget extends Zend_View_Helper_Abstract
{
    public function chartAllShipWidget($params = array(), $widgetNumber = null){
        // check for options?
        if(!count($params) || !$widgetNumber){
            return null;
        }

        // call partial
        return $this->view->partial(
            'helper-scripts/_chartAllShipWidget.phtml',
            array(
                'params' => $params, 
                'widgetNumber' => $widgetNumber
            )
        );
    }
}
