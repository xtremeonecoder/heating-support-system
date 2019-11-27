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

class Aninda_View_Helper_PortPopularityWidget extends Zend_View_Helper_Abstract
{
    public function portPopularityWidget($params = array(), $widgetNumber = null){
        // check for options?
        if(!count($params) || !$widgetNumber){
            return null;
        }

        // call partial
        return $this->view->partial(
            'helper-scripts/_portPopularityWidget.phtml',
            array(
                'params' => $params, 
                'widgetNumber' => $widgetNumber
            )
        );
    }
}
