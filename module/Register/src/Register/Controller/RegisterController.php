<?php
/**
 * Description of RegisterController
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Register/src/Register/Controller/RegisterController.php:
namespace Register\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\ApplicationControllerAction;  
use Zend\View\Model\ViewModel;
use Register\Form\RegisterForm;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions; 


class RegisterController extends ApplicationControllerAction
{
    protected $_mail;
    protected $_userTable;
    protected $_companyTable; 
    public function indexAction()
    {
        
        $form =new RegisterForm(); 
        return new ViewModel(array(
                    //'source' => $this->getSourceTable()->fetchAll(),
                    'form'   => $form
                ));
        
        
    }
    public function doRegisterAction(){
        $form = new RegisterForm(); 
        $request = $this->getRequest();
        $response = $this->getResponse();
        $return = array("success" => 0, "error" => "","usertype"=>"");
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
           if(!empty($emailold))  {
            $_emailold=$emailold->getEmailaddress();   
           }
           
           if(!empty($_emailold) && $email==$_emailold) {                     
           $return["error"]='email exists';               
           }
           else{
            $this->getUserTable()->saveUser($data);  
            $return['success'] = 1;  
            $authData = array('emailaddress' => $data["emailaddress"], 'password' => $data["password"]);
           if($User = $this->getUserTable()->loadAndCheckAuthentication($authData));
           {
              
                $toEmail = $email; //$techEmail;base64_encode($User["password"])
                $fromName = "Pocket Recruiter";
                $fromEmail = "info@vienetllc.com";
                $userid=base64_encode($User->getUserID());
                $mail=base64_encode($User->getEmailaddress());        
                $pass=base64_encode($User->getPassword());
               
                $basePath = $this->getRequest()->getBasePath();
                $uri = new \Zend\Uri\Uri($this->getRequest()->getUri());
                $uri->setPath($basePath);
                $uri->setQuery(array());
                $uri->setFragment('');
                $baseUrl = $uri->getScheme() . '://' . $uri->getHost() . '/' . $uri->getPath();
               
                $link=$baseUrl."/confirm?UserID=".$userid."&&emailaddress=".$mail."&&password=".$pass;                  
                $subject = "Welcome to Pocket Recruiter!";
                $body = "Thank you for signing up for a Company account with Pocket Recruiter. Please click on the following link to validate your email address:      
                                                
".$link." 
            
Thank you,                    
Your Pocket Recruiter Team
               ";    
               //$mail=$this->getMail();
               // $mail= new SendMail();                
               // $mail->setBodyText($body);
               // $mail->setFromName($fromName);
               // $mail->setFromEmail($fromEmail);
               // $mail->setToEmail($toEmail);
               // $mail->setSubject($subject);
               // $mail->send();
              //  $mail = new Mail\Message(); 
             //   $mail->setBody('This is the text of the email.');
              //  $mail->setFrom('Freeaqingme@example.org');
             //   $mail->addTo('nhunguyet.ntn@gmail.com', 'Name of recipient');
              //  $mail->setSubject('TestSubject');

             //   $transport = new Mail\Transport\Sendmail('-freturn_to_me@example.com');
              /*  $transport = new SmtpTransport();
                $options   = new SmtpOptions(array(
                    'name'              => 'localhost',
                    'host'              => 'smtp.sendgrid.net',
                    'connection_class'  => 'login',
                    'connection_config' => array(   
                        'username' => 'andybuiAT',
                        'password' => '1234$Abcd',
                    ), 
                ));
                $transport->setOptions($options);*/
               // $transport->send($mail);    
                
            /*    $message = new Message();
$message->addTo('nhunguyet.ntn@gmail.com')
        ->addFrom('nhunguyet.ntn@gmail.com')
        ->setSubject('Greetings and Salutations!')
        ->setBody("Sorry, I'm going to be late today!");

// Setup SMTP transport using LOGIN authentication
$transport = new SmtpTransport();
$options   = new SmtpOptions(array(
    'name'              => 'smtp.sendgrid.net',
    'host'              => 'smtp.sendgrid.net',
    'connection_class'  => 'login',
    'connection_config' => array(
        'username' => 'andybuiAT',
        'password' => '1234$Abcd',
    ),
));
 $transport->setOptions($options); 
try{

$transport->send($message);    
}catch(Exception $ex){
     
    // print_r($ex->);
} */

 
 
                
              
                
                   
           }
            
            
           } 
             
            $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
            $response->setContent(json_encode($return));
          
           return $response;
            
        }
        

        
    }
    public function getMail() {
        if (!$this->_mail) {
            
            $sm = $this->getServiceLocator();             
            $this->_mail = $sm->get('Register\Model\Mail');     
            
        }
        return $this->_mail;
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