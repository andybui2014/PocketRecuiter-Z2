<?php
/**
 * Description of RegisterController
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Register/src/Register/Controller/RegisterController.php:
namespace Register\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Register\Form\RegisterForm;


class RegisterController extends AbstractActionController
{
    protected $_sourceTable;
    protected $_userTable;
    protected $_companyTable; 
    public function indexAction()
    {
        
        $form =new RegisterForm(); 
        return new ViewModel(array(
                    'source' => $this->getSourceTable()->fetchAll(),
                    'form'   => $form
                ));
        
        // $new_note = array("firstname"=>"Nguyen","lastname"=>"Nguyet","usertype"=>"1","emailaddress"=>"carot@2014","password"=>"Test1234","HeardFrom"=>"tetst","PostalCode"=>"123","CompanyID"=>"1");
        // echo "tetst:<pre>";print_r($form);echo("</pre>");   die();
        // $note_id = $this->getUserTable()->saveUser($new_note);
    }
    public function doRegisterAction(){
        $form = new RegisterForm(); 
        $request = $this->getRequest();
        $response = $this->getResponse();
        //$return = array("success" => 0, "error" => "","usertype"=>"");
        $params= $request->getPost();
        if(!empty($params)){
               
        $firstname = $params['firstname'];
        $lasttname = $params['lastname'];                    
        $Acount_type = $params['usertype'];                    
        $email = $params['emailaddress'];                    
        $pass = $params['password'];                   
        $About_us = $params['About_us'];                     
        $accept = $params['accept'];                    
        $Companyname = $params['Companyname'];
        $PostalCode=$params['PostalCode'];
               
                              
            }
                
       // echo "testt:<pre>";print_r($companyID);echo("</pre>");
         $data=array(
                "firstname" => $firstname,
                "lastname" => $lasttname,
                "usertype" => $Acount_type,
                "emailaddress" => $email,
                "password" => $pass,
                "HeardFrom" => $About_us,
                "PostalCode"=>$PostalCode
                //"CompanyID"=>$companyID

                );
        if(!empty($Companyname)&& isset($accept))
         {
           // $new_note = new \Register\Model\Entity\Company(); 
           $Company=array("Companyname"=>$Companyname);
           $this->getCompanyTable()->saveCompany($Company); 
           $companyID=$this->getCompanyTable()->getCompanyName($Companyname);
           $companyID=$companyID->getCompanyID();
           $data["CompanyID"]=$companyID;
         }
         
         
        if(isset($accept)&& !empty($data) )
        {
           // $new_note = new \Register\Model\Entity\User();
           $emailold=$this->getUserTable()->getEmailUser($email);
           $emailold=$emailold->getEmailaddress();
           if($email==$emailold) {
               //echo "Email nay da ton tai";
              
               $response->setContent(\Zend\Json\Json::encode(array('response' => 'email exists'))); 
           }
           else{
            $this->getUserTable()->saveUser($data);    
            
           }
          // echo "tetst:<pre>";print_r($response);echo("</pre>");
           return $response;
            
        }
        
//$this->viewModel->__set('form', $form);
      // return $this->viewModel;
        
    }
    public function getSourceTable() {
        if (!$this->_sourceTable) {
            
            $sm = $this->getServiceLocator();             
            $this->_sourceTable = $sm->get('Register\Model\SourceTable');     
            
        }
        return $this->_sourceTable;
    }
    public function getUserTable() {
        if (!$this->_userTable) {
            
            $sm = $this->getServiceLocator();             
            $this->_userTable = $sm->get('Register\Model\UserTable');
            
        }
        return $this->_userTable;
    }
    public function getCompanyTable() {
        if (!$this->_companyTable) {
            
            $sm = $this->getServiceLocator();             
            $this->_companyTable = $sm->get('Register\Model\CompanyTable');
            
        }
        return $this->_companyTable;
    }
    
}
?>