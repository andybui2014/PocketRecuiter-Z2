<?php
namespace Register\Form;
use Zend\Form\Form;
class RegisterForm extends Form
{
   public function __construct()
   {
      // we want to ignore the name passed
      parent::__construct();
      $this->setAttribute('method', 'post');
      $this->add(array(
            'name'     => 'emailaddress',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Email',
                'placeholder' => 'Email',
                'class'       => 'form-control',
                'id'       => 'emailaddress'
            )
        ));
        
      $this->add(array(
            'name'     => 'firstname',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Enter First Name',
                'class'       => 'form-control',
                'id'          => 'firstname',
            )
        ));
      $this->add(array(
            'name'     => 'lastname',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Enter Last Name',
                'class'       => 'form-control',
                'id'          => 'lastname',
            )
        ));
     
      $this->add(array(
            'name'     => 'password',
            'type'     => 'Zend\Form\Element\Password',
            'attributes' => array(
                'type' => 'password',
                'placeholder' => '',
                'class'       => 'form-control',
                'id'          => 'password'
            )
        ));
        $this->add(array(
            'name'     => 'RetypePassword',
            'type'     => 'Zend\Form\Element\Password',
            'attributes' => array(
                'type' => 'password',
                'placeholder' => '',
                'class'       => 'form-control',
                'id'          => 'RetypePassword'
            )
        ));
      $this->add(array(
            'name'     => 'PostalCode',
            'attributes' => array(
                'type' => 'text',                
                'class'       => 'form-control',
                'id'          => 'PostalCode',
            )
        ));
      
        $this->add(array(
            'name' => 'usertype',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label_attributes' => array('class' => 'radio-inline')
            ),
            'attributes' => array(
                'options' => array(
                    '2' => 'Candidate',
                    '1' => 'Company'
                ),
                'value' => '2'               
                
            )
        ));
        $this->add(array(
            'name' => 'Companyname',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'id' => 'Companyname'
            )
        ));
        $this->add(array(
            'name'     => 'accept',
            'type'     => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'type' => 'checkbox',
                'class'       => '',
                'id'      =>'accept'
            )
        ));
  
     $mmOptions = array(""=>"Select","Indeed.com"=>"Indeed.com","SimplyHired.com"=>"SimplyHired.com","Search Engine"=>"Search Engine","Contacted by FieldSolutions"=>"Contacted by FieldSolutions","Referral - Client"=>"Referral - Client","Referral - Technician"=>"Referral - Technician","SkillNet"=>"SkillNet","LinkedIn"=>"LinkedIn","Facebook"=>"Facebook","Twitter"=>"Twitter","Internet Forum"=>"Internet Forum","College Job Boards"=>"College Job Boards","Local Employment Agency"=>"Local Employment Agency","Misc Job Search Site"=>"Misc Job Search Site","ISO"=>"ISO","Other"=>"Other");
     
        $this->add(array(
            'name' => 'About_us',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class'       => 'form-control',
                'id'       => 'About_us',
                'options'     => $mmOptions
                
            )
        ));
        
      
      
   }
   public function setData($data) {
        return parent::setData($data);
    }

}
?>