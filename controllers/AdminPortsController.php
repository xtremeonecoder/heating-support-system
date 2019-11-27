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

class AdminPortsController extends Zend_Controller_Action
{
    public function init()
    {
        // check user loggedin?
        ini_set('memory_limit', '512M');
        if(!Zend_Auth::getInstance()->hasIdentity()){
            return $this->_helper->redirector
                    ->gotoRoute(array(), 'member_login', true);
        }

        // get viewer
        $viewer = $this->_helper->getHelper('User')->getViewer();
        if(!$viewer->isModerator() 
                && !$viewer->isAdmin() 
                && !$viewer->isSuperAdmin()){
            return $this->_helper
                    ->redirector->gotoRoute(array(), 'user_dashboard', true);
        }
        
        // unlimited
        set_time_limit(0);
    }

    public function indexAction()
    {
        // check user loggedin?
        if(!Zend_Auth::getInstance()->hasIdentity()){
            return $this->_helper->redirector
                    ->gotoRoute(array(), 'member_login', true);
        }

        // redirect to browse
        return $this->_helper->redirector
                ->gotoRoute(array('action' => 'browse'), 'admin_port_general', true);
    }

    public function browseAction()
    {
        // check user loggedin?
        if(!Zend_Auth::getInstance()->hasIdentity()){
            return $this->_helper->redirector
                    ->gotoRoute(array(), 'member_login', true);
        }

        // page title
        $this->_helper->layout()->getView()
                ->headTitle('Admin - Sea-Port Information List');

        // Get DbTable
        $helper = $this->_helper->getHelper('DbTable');
        $table = $helper->getTable("settings");
        $fileName = $table->getSetting('seaport_data_file')->getPortDataFile();
        
        // Check file exists or not
        $portData = array();
        $decisionCriteria = array();
        if($fileName && file_exists($fileName)){
            // Read Excel Data
            require_once APPLICATION_PATH.'/plugins/simplexlsx-master/SimpleXLSX.php';
            $xlsx = SimpleXLSX::parse($fileName);

            if($xlsx){
                $m = null; $n = null;
                $xlsxData = $xlsx->rows();
                for($i=0; $i<count($xlsxData[0]); $i++){
                    for($j=0; $j<count($xlsxData); $j++){
                        if(!isset($xlsxData[$j][$i]) || empty($xlsxData[$j][$i])){continue;}
                        if($n === null){$m = $i; $n = $j;}
                        if($i == $m && $j != $n){ // storing decisition criteria
                            $decisionCriteria[($j-$n)] = strtolower(trim(str_replace(" ", "_", trim($xlsxData[$j][$i]))));
                        }elseif($j != $n){ // storing port decision data                               
                            $portData[trim($xlsxData[$n][$i])][($j-$n)] = trim($xlsxData[$j][$i]);
                        }
                    }
                }
            }else{
                echo SimpleXLSX::parseError();
            }
            unset($xlsx);
            @unlink($fileName) or die('Could not delete file!');            
            
            // get settings
            $db = $table->getAdapter();
            $db->beginTransaction();

            try{
                // Update settings value
                $setting1 = $table->getSetting('seaport_criteria');
                $setting2 = $table->getSetting('seaport_port_data');
                $setting1->addSettingValue(serialize($decisionCriteria));
                $setting2->addSettingValue(serialize($portData));
                $setting1->save();
                $setting2->save();

                // Remove existing file record
                $setting = $table->getSetting('seaport_data_file');
                $setting->removeSettingValue();
                $setting->save();
                
                // Commit
                $db->commit();
            }catch(Exception $e){
                $db->rollBack();
                throw $e;
            }
            
        }
        
        // get port data
        $decisionCriteria = $table->getSetting('seaport_criteria')->getUnserializeData();
        $portData = $table->getSetting('seaport_port_data')->getUnserializeData();

        // get port data
        $itemPerPage = 50;
        $paginator = Zend_Paginator::factory($portData);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $paginator->setItemCountPerPage($itemPerPage);
        $this->view->paginator = $paginator;
        $this->view->itemPerPage = $itemPerPage;
        $this->view->decisionCriteria = $decisionCriteria;
    }

    public function uploadAction()
    {
        // check user loggedin?
        if(!Zend_Auth::getInstance()->hasIdentity()){
            return $this->_helper->redirector
                    ->gotoRoute(array(), 'member_login', true);
        }

        // page title
        $this->_helper->layout()
                ->getView()->headTitle('Admin - Upload Sea-Port Data');

        // render form
        $this->view->form = $form
                = new Application_Form_Admin_Port_Upload();

        // Check form posted
        if(!$this->getRequest()->isPost()){
            return;
        }

        // Check form valid
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }

        //get form values
        $values = $form->getValues();
        
        // get settings
        $table = $this->_helper->getHelper('DbTable')->getTable("settings");
        $db = $table->getAdapter();
        $db->beginTransaction();
        
        try{
            // Upload, rename and delete existing file
            $fileDestinationPath = $form->portfile->getDestination();
            $originalFileName = pathinfo($form->portfile->getFileName());
            $newFileName = 'file-' . uniqid() . '.' . $originalFileName['extension'];
            $filterObj = new Zend_Filter_File_Rename(array(
                'source' => $fileDestinationPath . DIRECTORY_SEPARATOR . $originalFileName['basename'],
                'target' => $fileDestinationPath . DIRECTORY_SEPARATOR . $newFileName,
                'overwrite' => true // delete the existing file
            ));
            $filterObj->filter($fileDestinationPath . DIRECTORY_SEPARATOR . $originalFileName['basename']);

            // Move file from tmp to destination
            $form->portfile->receive();
            
            // Update settings value
            $setting = $table->getSetting('seaport_data_file');
            $setting->addSettingValue($newFileName);
            $setting->save();

            // change permission
            @chmod($fileDestinationPath . DIRECTORY_SEPARATOR . $originalFileName['basename'], 0777);
            
            // Commit
            $db->commit();

            // redirect to sea-port list
            return $this->_helper->redirector
                    ->gotoRoute(array('action' => 'browse'), 'admin_port_general', true);
        }catch(Exception $e){
            $db->rollBack();
            $form->addError($e->getMessage());
            throw $e;
        }
    }

    public function deleteAction()
    {
        // check user loggedin?
        if(!Zend_Auth::getInstance()->hasIdentity()){
            return $this->_helper->redirector
                    ->gotoRoute(array(), 'member_login', true);
        }

        // start transaction
        $table = $this->_helper->getHelper('DbTable')->getTable("settings");
        $db = $table->getAdapter();
        $db->beginTransaction();

        try{
            // Remove settings values
            $setting1 = $table->getSetting('seaport_criteria');
            $setting2 = $table->getSetting('seaport_port_data');
            $setting1->removeSettingValue();
            $setting2->removeSettingValue();
            $setting1->save();
            $setting2->save();

            // Remove the file if exists and also database record
            $setting = $table->getSetting('seaport_data_file');
            $fileName = $setting->getPortDataFile();
            $setting->removeSettingValue();
            $setting->save();
            if($fileName && file_exists($fileName)){
                @unlink($fileName) or die('Could not delete file!');
            }
            
            // Commit
            $db->commit();
        }catch(Exception $e){
            $db->rollBack();
            throw $e;
        }

        // redirect to port list page
        return $this->_helper->redirector
                ->gotoRoute(array('action' => 'browse'), 'admin_port_general', true);
    }

    public function calculateAction()
    {
        // check user loggedin?
        if(!Zend_Auth::getInstance()->hasIdentity()){
            return $this->_helper->redirector
                    ->gotoRoute(array(), 'member_login', true);
        }

        // page title
        $this->_helper->layout()
                ->getView()->headTitle('Admin - Calculate Sea-Port For Vessels');
        
        // get vessel data from database
        $table = $this->_helper->getHelper('DbTable')->getTable("settings");
        $this->view->portData = $portData = $table->getSetting('seaport_port_data')->getUnserializeData();
        $this->view->vesselData = $vesselData = $table->getSetting('vessel_decision_data')->getUnserializeData();
        $decisionCriteria = $table->getSetting('vessel_criteria')->getUnserializeData();
        
        // check port and/or vessel data exists or not?
        if(!$portData || !$vesselData || !count($portData) || !count($vesselData)){
            return;
        }
        
        // Calculates all scored weights per ports for all the available ships
        $shipTotalWeight = array();
        foreach($vesselData as $vesselName => $vesselCriterias){           
            // Calculates all scored weights per ports for ships one by one
            // Like for ship-1, X = {PORT1=869, PORT2=985, ....., PORTn=995}
            $shipTotalWeight[$vesselName] = $this->_helper
                    ->getHelper('Port')->calculate($vesselCriterias);
        }
        
        // Calculates the highest score of port per ship
        // Actually the maximum score finding operation is going here
        // MAX(Wvp1, Wvp2, Wvp3,.....,Wvpn)
        $shipHighScoredWeight = array();
        foreach($shipTotalWeight as $shipName => $shipWeights){
            arsort($shipWeights);
            foreach($shipWeights as $portName => $highScore){
                $shipHighScoredWeight[$shipName][$portName] = $highScore;
                break;
            }
        }

        // get settings
        $db = $table->getAdapter();
        $db->beginTransaction();

        try{
            // Update calculated result
            $setting1 = $table->getSetting('vessel_data_result');
            $setting2 = $table->getSetting('vessel_data_calculated');
            $setting1->addSettingValue(serialize($shipHighScoredWeight));
            $setting2->addSettingValue(serialize($shipTotalWeight));
            $setting1->save();
            $setting2->save();

            // Commit
            $db->commit();
            
            // redirect to admin page
            return $this->_helper->redirector->gotoRoute(array(), 'admin_dashboard', true);
        }catch(Exception $e){
            $db->rollBack();
            throw $e;
        }
    }    
    
}
