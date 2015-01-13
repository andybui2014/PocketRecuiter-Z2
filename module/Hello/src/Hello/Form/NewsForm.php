<?php
namespace Hello\Form;
use Zend\Form\Form;
class NewsForm extends Form
{
   public function __construct($name = null)
   {
      // we want to ignore the name passed
      parent::__construct('news');
      $this->setAttribute('method', 'post');
      $this->add(array(
            'name' => 'id',
            'attributes' => array(
                  'type' => 'hidden',
            ),
      ));
      $this->add(array(
            'name' => 'name',
            'attributes' => array(
                  'type' => 'text',
            ),
            'options' => array(
                  'label' => 'Name',
            ),
      ));
      $this->add(array(
            'name' => 'content',
            'attributes' => array(
                  'type'       => 'textarea',
                  'maxlength'   =>1000,
                  'class'      =>"editor"
            ),
            'options' => array(
                  'label' => 'Content',
            ),
      ));
      $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                  'type' => 'submit',
                  'value' => 'Go',
                  'id' => 'submitbutton',
            ),
      ));
   }
}
?>