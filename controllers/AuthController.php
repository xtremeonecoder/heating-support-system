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

class AuthController extends Zend_Controller_Action
{
    public function init() {
        /* Initialize action controller here */
    }

    public function loginAction()
    {
        // check user loggedin?
        if(Zend_Auth::getInstance()->hasIdentity()){
            // get viewer
            $viewer = $this->_helper->getHelper('User')->getViewer();
            if($viewer->isModerator() 
                    || $viewer->isAdmin() 
                    || $viewer->isSuperAdmin()){
                return $this->_helper->redirector
                        ->gotoRoute(array(), 'admin_dashboard', true);
            }else{
                return $this->_helper->redirector
                        ->gotoRoute(array(), 'user_dashboard', true);
            }
        }

        // page title
        $this->_helper->layout()
                ->getView()->headTitle('Member Login');

        // implement meta
        $identity = "{$this->_getParam('module')}_{$this->_getParam('controller')}_{$this->_getParam('action')}";
        $pageInfo = $this->_helper->getHelper('Page')->getPage($identity);
        if($pageInfo AND $pageInfo->page_id){
            $this->view->headMeta()->setName('keywords', $pageInfo->getMetaKeys());
            $this->view->headMeta()->setName('description', $pageInfo->getMetaDesc());
        }

        // render login form
        $this->view->form = $form
                = new Application_Form_Login();

        // Check form posted
        if(!$this->getRequest()->isPost()){
            return;
        }

        // Check form valid
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }

        // get auth and set credentials
        $db = $this->_getParam('db');
        $auth = Zend_Auth::getInstance();
        $table = $this->_helper
                    ->getHelper('DbTable')->getTable("users");
        $authAdapter = new Zend_Auth_Adapter_DbTable($db);
        $authAdapter->setTableName($table->info('name'))
                ->setIdentityColumn('email')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('MD5(CONCAT(?, salt))');

        //get form values
        $values = $form->getValues();

        // delete captcha image
        $this->deleteCaptchaImages();

        // Set the input credential values
        $authAdapter->setIdentity($values['email']);
        $authAdapter->setCredential($values['password']);

        // Perform the authentication query, saving the result
        $result = $auth->authenticate($authAdapter);
        if($result->isValid()){
            $data = $authAdapter->getResultRowObject(null, 'password');
            $auth->getStorage()->write($data);

            // update last login            
            $db = $table->getAdapter();
            $db->beginTransaction();

            try{
                $user = $table->fetchRow(array('user_id = ?' => (int) $data->user_id));
                $user->lastlogin = @date('Y-m-d H:i:s', time());
                $user->save();

                // Commit
                $db->commit();
            }catch(Exception $e){
                $db->rollBack();
                throw $e;
            }

            // redirect to admin area
            if($user->isModerator() 
                    || $user->isAdmin() 
                    || $user->isSuperAdmin()){
                return $this->_helper->redirector
                        ->gotoRoute(array(), 'admin_dashboard', true);
            }else{
                // redirect to user area
                return $this->_helper->redirector
                        ->gotoRoute(array(), 'user_dashboard', true);
            }
        }else{
            $form->getElement('email')->addError(
                $this->view->translate('Email or password does not matched!')
            );
        }
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        return $this->_helper->redirector
                ->gotoRoute(array(), 'member_login', true);
    }

    public function resetAction(){
        // check key valid or not?
        $this->view->success = false;
        $resetKey = $this->_getParam('key', false);
        if(!$resetKey){
            return $this->_forward('error', 'error', 'default', array());
        }

        // get user by reset key
        $dbTableHelper = $this->_helper->getHelper('DbTable');
        $userTable = $dbTableHelper->getTable("users");
        $user = $userTable->fetchRow(array('resetkey = ?' => $resetKey));

        // check user exists?
        if(!isset($user->user_id) || empty($user->user_id)){
            return $this->_forward('error', 'error', 'default', array());
        }

        // page title
        $this->_helper->layout()
                ->getView()->headTitle('Reset Account Password');

        // implement meta
        $identity = "{$this->_getParam('module')}_{$this->_getParam('controller')}_{$this->_getParam('action')}";
        $pageInfo = $this->_helper->getHelper('Page')->getPage($identity);
        if($pageInfo && $pageInfo->page_id){
            $this->view->headMeta()->setName('keywords', $pageInfo->getMetaKeys());
            $this->view->headMeta()->setName('description', $pageInfo->getMetaDesc());
        }

        // render login form
        $this->view->form = $form
                = new Application_Form_Reset();

        // Check form posted
        if(!$this->getRequest()->isPost()){
            return;
        }

        // Check form valid
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }

        // update new password
        $password = $this->getRequest()->getPost('password');
        $db = $userTable->getAdapter();
        $db->beginTransaction();

        try{
            $salt = trim(mt_rand(100000, 999999));
            $user->active = 1;
            $user->resetkey = '';
            $user->salt = $salt;
            $passwd = "{$password}{$salt}";
            $user->password = md5($passwd);
            $user->modified_date = @date('Y-m-d H:i:s', time());
            $user->save();

            // prepare mail params
            $view = new Zend_View();
            $loginLink = "http://{$_SERVER['HTTP_HOST']}{$view->url(array(), 'member_login', true)}";
            $mailParams = array(
                'to' => $user->getEmail(),
                'from' => 'info@xtremeonecoder.com',
                'reciepient' => $user->getCompany()->getTitle(),
                'sender' => 'Sea-Port Recommendation System',
                'subject' => 'Password reset confirmation mail!',
                'messagebody' => "
                    Dear Member,

                    You have successfully reset your account new password.

                    New Login Details:
                    Email: <strong>{$user->getEmail()}</strong>
                    Password: <strong>{$password}</strong>

                    Now you can login your account using new password, please click on the link below -

                    <a href='{$loginLink}'>{$loginLink}</a>

                    Best Regards,
                    Sea-Port Recommendation System
                ",
                //'messagebody' => nl2br(html_entity_decode(nl2br($this->getMessage()), ENT_QUOTES)),
                'mailtype' => 'html'
            );

            // send mail
            $mail = $this->_helper->getHelper('Mail');
            $mail->send($mailParams);

            // Commit
            $db->commit();
            $this->view->success = true;
        }catch(Exception $e){
            $db->rollBack();
            throw $e;
        }
    }

    public function activateAction(){
        // check key valid or not?
        $activeKey = $this->_getParam('key', false);
        if(!$activeKey){
            return $this->_forward('error', 'error', 'default', array());
        }

        // get user by active key
        $dbTableHelper = $this->_helper->getHelper('DbTable');
        $userTable = $dbTableHelper->getTable("users");
        $user = $userTable->fetchRow(array('activekey = ?' => $activeKey));

        // check user exists?
        if(!isset($user->user_id) || empty($user->user_id)){
            return $this->_forward('error', 'error', 'default', array());
        }

        // page title
        $this->_helper->layout()
                ->getView()->headTitle('Account Activation');

        // implement meta
        $identity = "{$this->_getParam('module')}_{$this->_getParam('controller')}_{$this->_getParam('action')}";
        $pageInfo = $this->_helper->getHelper('Page')->getPage($identity);
        if($pageInfo AND $pageInfo->page_id){
            $this->view->headMeta()->setName('keywords', $pageInfo->getMetaKeys());
            $this->view->headMeta()->setName('description', $pageInfo->getMetaDesc());
        }

        // update active key
        $db = $userTable->getAdapter();
        $db->beginTransaction();

        try{
            $user->active = 1;
            $user->activekey = '';
            $user->modified_date = @date('Y-m-d H:i:s', time());
            $user->save();

            // prepare mail params
            $view = new Zend_View();
            $loginLink = "http://{$_SERVER['HTTP_HOST']}{$view->url(array(), 'member_login', true)}";
            $mailParams = array(
                'to' => $user->getEmail(),
                'from' => 'info@xtremeonecoder.com',
                'reciepient' => $user->getCompany()->getTitle(),
                'sender' => 'Sea-Port Recommendation System',
                'subject' => 'Account activation confirmation mail!',
                'messagebody' => "
                    Dear Member,

                    You have successfully activated your account.

                    Now you can login your account, please click on the link below -

                    <a href='{$loginLink}'>{$loginLink}</a>

                    Best Regards,
                    Sea-Port Recommendation System
                ",
                //'messagebody' => nl2br(html_entity_decode(nl2br($this->getMessage()), ENT_QUOTES)),
                'mailtype' => 'html'
            );

            // send mail
            $mail = $this->_helper->getHelper('Mail');
            $mail->send($mailParams);

            // Commit
            $db->commit();
        }catch(Exception $e){
            $db->rollBack();
            throw $e;
        }
    }

    // Delete all unnecessary captcha images
    public function deleteCaptchaImages()
    {
        // Delete captcha images
        $captchaDir = realpath(APPLICATION_PATH . '/../public/captcha');
        if($handle = opendir($captchaDir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && stripos($file, '.png')) {
                    $filePath = $captchaDir . '/' . $file;
                    if(stripos($file, '.png') AND is_file($filePath)){
                        @chmod($filePath, 0777);
                        @unlink($filePath);
                    }
                }
            }
            closedir($handle);
        }
    }
}
