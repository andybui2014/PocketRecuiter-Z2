<?php

/**
 * Description of Mail
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Register/src/Register/Model/Mail.php

namespace Register\Model;

use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class SendMail extends AbstractTableGateway {
     protected $bodyText;
    protected $fromEmail;
    protected $fromName;
    protected $toEmail;
    protected $toName;
    protected $subject;
    
    public function setBodyText($value)
    {
        $this->bodyText = $value;
    }
    public function setFromEmail($value)
    {
        $this->fromEmail = $value;
    }

    public function setFromName($value)
    {
        $this->fromName = $value;
    }

    public function setToEmail($value)
    {
        $this->toEmail = $value;
    }

    public function setToName($value)
    {
        $this->toName = $value;
    }
    public function setSubject($value)
    {
        $this->subject = $value;
    }
     public function send()
    {
        $mail = new Mail\Message();
        $mail->setBody('This is the text of the email.');
        $mail->setFrom('Freeaqingme@example.org', 'Sender\'s name');
        $mail->addTo('Matthew@example.com', 'Name of recipient');
        $mail->setSubject('TestSubject');

        //$transport = new Mail\Transport\Sendmail();
        $transport = new SmtpTransport();
        $options   = new SmtpOptions(array(
            //'name'              => 'localhost.localdomain',
            'host'              => 'smtp.sendgrid.net',
            'connection_class'  => 'login',
            'connection_config' => array(
                'username' => 'andybuiAT',
                'password' => '1234$Abcd',
            ),
        ));
        $transport->setOptions($options);
        $transport->send($mail);
       
    }
    

   
}
?>