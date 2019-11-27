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

class Aninda_View_Helper_ChartPerShipWidget extends Zend_View_Helper_Abstract
{
    public function chartPerShipWidget($params = array(), $shipName = null, $widgetNumber = null, $popupView = true){
        // check for options?
        if(!count($params) || !$shipName || !$widgetNumber){
            return null;
        }

        // call partial
        return $this->view->partial(
            'helper-scripts/_chartPerShipWidget.phtml',
            array(
                'params' => $params, 
                'shipName' => $shipName,
                'popupView' => $popupView,
                'widgetNumber' => $widgetNumber
            )
        );
    }
}
