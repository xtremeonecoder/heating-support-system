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

class Application_Model_DbTable_Users extends Aninda_Db_Table_Abstract
{
    protected $_name = 'users';
    protected $_rowClass = 'Application_Model_User';
}
