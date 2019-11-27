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

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        // init
    }

    public function indexAction()
    {
        // page title
        $this->_helper->layout()
                ->getView()->headTitle('Home Page');

        // implement meta
        $identity = "{$this->_getParam('module')}_{$this->_getParam('controller')}_{$this->_getParam('action')}";
        $pageInfo = $this->_helper->getHelper('Page')->getPage($identity);
        if($pageInfo && $pageInfo->page_id){
            $this->view->headMeta()->setName('keywords', $pageInfo->getMetaKeys());
            $this->view->headMeta()->setName('description', $pageInfo->getMetaDesc());
        }
        
        // get vessel data from database
        $table = $this->_helper->getHelper('DbTable')->getTable("settings");
        $dataResult = $table->getSetting('vessel_data_result')->getUnserializeData();
        $dataCalculated = $table->getSetting('vessel_data_calculated')->getUnserializeData();
        $decisionCriteria = $table->getSetting('seaport_criteria')->getUnserializeData();
        
        // port used
        $portNameArray = array();
        foreach($dataResult as $portData){            
            foreach($portData as $portName => $portScore){
                $portNameArray[$portName][] = $portScore;
            }
        }

        // count port used
        $portCountArray = array();
        foreach($portNameArray as $portName => $portUsed){            
            $portCountArray[$portName] = count($portUsed);
        }
        
        // get port data
        $itemPerPage = 50;
        $paginator = Zend_Paginator::factory($dataResult);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $paginator->setItemCountPerPage($itemPerPage);
        $this->view->paginator = $paginator;
        $this->view->itemPerPage = $itemPerPage;
        $this->view->dataCalculated = $dataCalculated;        
        $this->view->decisionCriteria = $decisionCriteria; 
        $this->view->portCountArray = $portCountArray;
    }
    
    public function exportAction()
    {
        // disable layout
        set_time_limit(0);
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        // get table and all the records
        $table = $this->_helper->getHelper('DbTable')->getTable("settings");
        $dataResult = $table->getSetting('vessel_data_result')->getUnserializeData();
        $dataCalculated = $table->getSetting('vessel_data_calculated')->getUnserializeData();
        $decisionCriteria = $table->getSetting('seaport_criteria')->getUnserializeData();

        // get result table columns
        $resultTableColumns = array(
            'Vessel Name',
            'Sea-Port Name',
            'Highest Score'
        );        
        
        // print result table header columns
        echo "Selection Result Table\n";
                
        // print result data row by row
        if(count($dataResult)>0){
            echo implode("\t", $resultTableColumns)."\n";
            foreach($dataResult as $vesselName => $vesselData){
                $resultArray = array();
                $resultArray[] = $vesselName;
                foreach($vesselData as $portName => $highScore){
                    $resultArray[] = $portName;
                    $resultArray[] = $highScore;
                }
                echo implode("\t", $resultArray)."\n";
            }
        }
        
        // print detailed result table title
        echo "\n\nSelection Result Table (Details)\n";
        
        // detailed result table header
        if(count($dataCalculated)>0){
            $resultTableColumns = array();
            foreach($dataCalculated as $vesselName => $vesselData){
                $resultTableColumns[] = $vesselName;
            }
            echo "Sea-Port Name\t".implode("\t", $resultTableColumns)."\n";

            // get all the port names
            $portNames = array();
            foreach($dataCalculated as $vesselName => $vesselData){
                foreach($vesselData as $portName => $highScore){
                    $portNames[] = $portName;
                }
                break;
            }

            // print all the data
            foreach($portNames as $portName){
                $detailTableRow = array();
                foreach($resultTableColumns as $shipName){
                    $detailTableRow[] = $dataCalculated[$shipName][$portName];
                }
                echo "{$portName}\t".implode("\t", $detailTableRow)."\n";
            }
        }
        
        // set header for excel
        header("Expires: 0");
        header("Content-Type: application/vnd.ms-excel");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Disposition: attachment;filename=port-selection-result-" . time() . ".xls");
    }

    public function calculateAction()
    {
        // page title
        $this->_helper->layout()
                ->getView()->headTitle('Calculate Sea-Port Manually');
        
        // render form
        $this->view->form = $form 
                = new Application_Form_Calculate();
        
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
        
        // make array
        $counter = 1;
        $vesselCriterias = array();
        foreach($values as $value){
            $vesselCriterias[$counter++] = $value;
        }
                
        // calculate port
        $this->view->shipWeightRandom = $shipTotalWeights 
                = $this->_helper->getHelper('Port')->calculate($vesselCriterias);
        
        // sort reverse
        arsort($shipTotalWeights);
        foreach($shipTotalWeights as $portName => $portScore){
            $properPortName = $portName;
            $properPortScore = $portScore;
            break;
        }
        $this->view->properPortName = $properPortName;
        $this->view->properPortScore = $properPortScore;
        $this->view->shipTotalWeights = $shipTotalWeights;
    }

}
