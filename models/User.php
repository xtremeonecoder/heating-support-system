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

class Application_Model_User extends Zend_Db_Table_Row_Abstract
{
    public function getIdentity(){
        return (int) $this->user_id;
    }

    public function getTitle(){
        if($this->isSuperAdmin()){
            return 'Super Admin';
        }elseif($this->isAdmin()){
            return 'Admin';
        }elseif($this->isModerator()){
            return 'Moderator';
        }elseif($this->isUser()){
            $company = $this->getCompany();
            if(!$company->getContactName()){
                return 'Courier User';
            }

            return $company->getContactName();
        }
    }

    public function getUserType(){
        if($this->isSuperAdmin()){
            return 'superadmin';
        }elseif($this->isAdmin()){
            return 'admin';
        }elseif($this->isModerator()){
            return 'moderator';
        }elseif($this->isUser()){
            return 'user';
        }
    }

    public function getHref($params = array()){
        if($this->isModerator() OR
                $this->isAdmin() OR $this->isSuperAdmin()){
            $route = 'admin_dashboard';
        }else{
            $route = 'user_dashboard';
        }

        $params = array_merge(array(
            'route' => $route,
            'reset' => true
            //'id' => $profileAddress,
        ), $params);
        $route = $params['route'];
        $reset = $params['reset'];
        unset($params['route']);
        unset($params['reset']);
        return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
    }

    public function getDashBoardLink($params = array()){
        if($this->isModerator() OR
                $this->isAdmin() OR $this->isSuperAdmin()){
            $route = 'admin_dashboard';
        }else{
            $route = 'user_dashboard';
        }

        $params = array_merge(array(
            'route' => $route,
            'reset' => true
            //'id' => $profileAddress,
        ), $params);
        $route = $params['route'];
        $reset = $params['reset'];
        unset($params['route']);
        unset($params['reset']);
        return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
    }

    public function getCompanyId(){
        return $this->company_id;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getLevel(){
        return $this->level;
    }

    public function isSuperAdmin(){
        if($this->level == 1){
            return true;
        }

        return false;
    }

    public function isAdmin(){
        if($this->level == 2){
            return true;
        }

        return false;
    }

    public function isModerator(){
        if($this->level == 3){
            return true;
        }

        return false;
    }

    public function isUser(){
        if($this->level == 4){
            return true;
        }

        return false;
    }

    public function isActive(){
        return $this->active;
    }

    public function getLastLogin(){
        return $this->lastlogin;
    }

    public function getResetKey(){
        return $this->resetkey;
    }

    public function getCompany(){
        $helper = Zend_Controller_Action_HelperBroker::getStaticHelper('DbTable');
        $table = $helper->getTable("companies");
        return $table->fetchRow(array('company_id = ?' => $this->getCompanyId()));
    }

    public function sendResetPasswordLink(){
        // create and save reset key
        $timestamp = time();
        $key = md5("{$this->salt}{$this->email}{$timestamp}");
        $this->resetkey = $key;
        $this->save();

        // prepare mail params
        $view = new Zend_View();
        $activationLink = "http://{$_SERVER['HTTP_HOST']}{$view->url(array('key' => $key), 'reset_password', true)}";
        $mailParams = array(
            'to' => $this->getEmail(),
            'from' => 'info@xtremeonecoder.com',
            'reciepient' => $this->getCompany()->getTitle(),
            'sender' => 'Sea-Port Recommendation System',
            'subject' => 'Account activation and reset password request',
            'messagebody' => "
                Dear Member,

                You have requested for your account activation and reset password.

                Here is the link to do that, please click on the link below -

                <a href='{$activationLink}'>{$activationLink}</a>

                Best Regards,
                Sea-Port Recommendation System
            ",
            //'messagebody' => nl2br(html_entity_decode(nl2br($this->getMessage()), ENT_QUOTES)),
            'mailtype' => 'html'
        );

        // sent mail
        $mail = Zend_Controller_Action_HelperBroker::getStaticHelper('Mail');
        $mail->send($mailParams);

        // return key
        return $key;
    }

    public function sendActiveAccountLink(){
        // create and save active key
        $timestamp = time();
        $key = md5("{$this->salt}{$this->email}{$timestamp}");
        $this->activekey = $key;
        $this->save();

        // prepare mail params
        $view = new Zend_View();
        $activationLink = "http://{$_SERVER['HTTP_HOST']}{$view->url(array('key' => $key), 'activate_account', true)}";
        $mailParams = array(
            'to' => $this->getEmail(),
            'from' => 'info@xtremeonecoder',
            'reciepient' => $this->getCompany()->getTitle(),
            'sender' => 'Sea-Port Recommendation System',
            'subject' => 'Account activation and email confirmation!',
            'messagebody' => "
                Dear Member,

                You have successfully registered your account with us. Now you have to activate your account by clicking on the below link.

                Here is the link to do that, please click on the link below -

                <a href='{$activationLink}'>{$activationLink}</a>

                Best Regards,
                Sea-Port Recommendation System
            ",
            //'messagebody' => nl2br(html_entity_decode(nl2br($this->getMessage()), ENT_QUOTES)),
            'mailtype' => 'html'
        );

        // sent mail
        $mail = Zend_Controller_Action_HelperBroker::getStaticHelper('Mail');
        $mail->send($mailParams);

        // return key
        return $key;
    }
}
