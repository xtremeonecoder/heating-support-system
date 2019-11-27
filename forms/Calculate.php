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

class Application_Form_Calculate extends Zend_Form
{
  public function init()
  {
    // Init form
    $tabindex = 1;
    $this->setAttrib('id', 'calculate_form')
      ->setAttrib('class', 'global_form')     
      ->setMethod("POST")
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    // to show the errors above the form
    $this->setDecorators(array(
        'FormElements',
        'Form',
        array('FormErrors', array('placement' => 'prepend'))
    ));        

    // option array
    $optionArray = array(
        '' => 'Make your choice',
        '10' => 'Extremely important',
        '9' => 'Very important',
        '8' => 'Yes, important',
        '7' => 'Moderately important',
        '6' => 'Less important',
        '5' => 'Maybe important, not sure',
        '4' => 'I do not want to say',
        '3' => 'Not important',
        '2' => 'It is irrelevant',
        '1' => 'Completely irrelevant'        
    );
        
    // Init Port Draft
    $this->addElement('Select', 'port_draft', array(
      'label' => 'What extent "Port Draft" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->port_draft->removeDecorator('Errors');

    // Init Number of Berth
    $this->addElement('Select', 'number_of_berth', array(
      'label' => 'What extent "Number of Berth" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->number_of_berth->removeDecorator('Errors');

    // Init Capacity of Port Facilities
    $this->addElement('Select', 'capacity_of_port_facilities', array(
      'label' => 'What extent "Capacity of Port Facilities" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->capacity_of_port_facilities->removeDecorator('Errors');

    // Init Port Location
    $this->addElement('Select', 'port_location', array(
      'label' => 'What extent "Port Location" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->port_location->removeDecorator('Errors');

    // Init Port Technology
    $this->addElement('Select', 'port_technology', array(
      'label' => 'What extent "Port Technology" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->port_technology->removeDecorator('Errors');

    // Init Operating Time
    $this->addElement('Select', 'operating_time', array(
      'label' => 'What extent "Operating Time" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->operating_time->removeDecorator('Errors');

    // Init Port Safety
    $this->addElement('Select', 'port_safety', array(
      'label' => 'What extent "Port Safety" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->port_safety->removeDecorator('Errors');

    // Init Operating Cost
    $this->addElement('Select', 'operating_cost', array(
      'label' => 'What extent "Operating Cost" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->operating_cost->removeDecorator('Errors');

    // Init International Policies
    $this->addElement('Select', 'international_policies', array(
      'label' => 'What extent "International Policies" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->international_policies->removeDecorator('Errors');

    // Init Night Navigation
    $this->addElement('Select', 'night_navigation', array(
      'label' => 'What extent "Night Navigation" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->night_navigation->removeDecorator('Errors');

    // Init Port Management
    $this->addElement('Select', 'port_management', array(
      'label' => 'What extent "Port Management" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->port_management->removeDecorator('Errors');

    // Init Port Labour
    $this->addElement('Select', 'port_labour', array(
      'label' => 'What extent "Port Labour" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->port_labour->removeDecorator('Errors');
    
    // Init Custom Formalities
    $this->addElement('Select', 'custom_formalities', array(
      'label' => 'What extent "Custom Formalities" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->custom_formalities->removeDecorator('Errors');

    // Init Vessel Maintenance
    $this->addElement('Select', 'vessel_maintenance', array(
      'label' => 'What extent "Vessel Maintenance" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->vessel_maintenance->removeDecorator('Errors');

    // Init Port Services
    $this->addElement('Select', 'port_services', array(
      'label' => 'What extent "Port Services" is important for you?',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'class' => 'span5',        
      'multiOptions' => $optionArray,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        array('NotEmpty', true)
      )
    ));
    $this->port_services->removeDecorator('Errors');
        
    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Calculate',
      'type' => 'submit',
      'ignore' => true,
      'tabindex' => $tabindex++,
    ));
  }
}