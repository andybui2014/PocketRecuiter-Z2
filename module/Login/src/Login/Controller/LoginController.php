<?php
namespace Login\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Login\Form\NewsForm;
use Zend\Json\Json;
use Application\Controller\ApplicationControllerAction;
use Login\Model\prSession;


class LoginController extends ApplicationControllerAction
{
    protected $itemTable;
    public function __construct()
    {
        parent::__construct();
    }

    public function getItemTable()
    {
        if (!$this->itemTable) {
            $sm = $this->getServiceLocator();

            $this->itemTable = $sm->get('Login\Model\userLogin');
        }
        return $this->itemTable;
    }
    public function indexAction()
    {
       
    }

    function doLoginAction()
    {
        $username = $this->getRequest()->getPost('email');
        $password = $this->getRequest()->getPost('password');           

        $return = array("success" => 0, "error" => "usertype",""=>"");

        $authData = array('emailaddress' => $username, 'password' => $password);
        $client = $this->getItemTable()->loadAndCheckAuthentication($authData);
        if(!empty($client) && $client["active"]==1 )
        {
            prSession::setSession($client,prSession::SESSION_USER);
            $user = prSession::getSession(prSession::SESSION_USER);

            $userID = $user['UserID'];

            $this->getItemTable()->UpdateLastsigned($userID);
            $return['success'] = 1;
            $return['usertype'] = $user["usertype"];

            if($user["usertype"]==USER_TYPE_CANDIDATE){
                $can = $this->getItemTable()->createCandidateProfileID($userID);
            }

        } else
        {
            $return['error']="Account not activated. Please check email and active.";
        }
        $this->getResponse()->getHeaders()->addHeaders(array('Content-Type'=>'application/json;charset=UTF-8'));
        return $this->getResponse()->setContent(Json::encode($return));


    }
    public function doLogoutAction()
    {
       // $this->_helper->layout->disableLayout();
       // echo "tetst";die();
        $sestionClient=prSession::clearSessions();
        $this->layout()->setVariables(array('userLogin'=>$sestionClient));
        return $this->redirect()->toRoute('login',
                array('controller'=>'login',
                    'action' => 'index'));
    }  
    
}