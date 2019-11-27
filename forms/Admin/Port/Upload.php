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

class Application_Form_Admin_Port_Upload extends Zend_Form
{
  public function init()
  {
    // Init form
    $tabindex = 1;
    $this->setAttrib('id', 'upload_port')
      ->setAttrib('class', 'global_form')
      ->setMethod("POST")
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    // to show the errors above the form
    $this->setDecorators(array(
        'FormElements',
        'Form',
        array('FormErrors', array('placement' => 'prepend'))
    ));

    // Sea-Port Data Upload
    $this->addElement('File', 'portfile', array(
      'label' => 'Select Data File',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',
    ));
    //$this->portfile->setMultiFile(4);
    $this->portfile->removeDecorator('Errors');
    $this->portfile->addValidator('Extension', false, 'xls, xlsx')
         ->getValidator('Extension')->setMessage('This file type is not supportted!');
    
    # Define path to uploads directory
    $fileDirectoryPath = $_SERVER['DOCUMENT_ROOT'];
    if($fileDirectoryPath == '/opt/lampp/htdocs'){
        $fileDirectoryPath .= '/apfk'; // for localhost
    }
    $fileDirectoryPath .= '/public/seaports';
    $this->portfile->setDestination($fileDirectoryPath);

    // adding dummy html div
    $this->addElement(
        'hidden',
        'dummy',
        array(
            'required' => false,
            'ignore' => true,
            'autoInsertNotEmptyValidator' => false,
            'decorators' => array(
                array(
                    'HtmlTag', array(
                        'tag'  => 'div',
                        'id'   => 'dummy-button-bar',
                        'class' => 'dummy-panel',
                        'style' => 'padding: 0px 0px;'
                    )
                )
            )
        )
    );
    $this->dummy->clearValidators();   
    
    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Upload',
      'type' => 'submit',
      'ignore' => true,
      'tabindex' => $tabindex++,
    ));
  }
}
