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

class Aninda_View_Helper_MainMenuWidget extends Zend_View_Helper_Abstract
{
    public function mainMenuWidget($params = array()) {
        // get table
        $helper = Zend_Controller_Action_HelperBroker::getStaticHelper('DbTable');
        $table = $helper->getTable("menuitems");


        // get page body identity
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $identity = $request->getModuleName() . '_' .
                $request->getControllerName() . '_' .
                $request->getActionName();

        // get menus
        $navigation = $table->getMenus(array('menu' => 'core_main'));

        // call partial
        return $this->view->partial(
            'helper-scripts/_mainMenuWidget.phtml',
            array('navigation' => $navigation, 'identity' => $identity)
        );
    }
}
