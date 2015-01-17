<?php
namespace Notifications\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\ApplicationControllerAction;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Login\Model\prSession;

class NotificationsController extends ApplicationControllerAction {
    protected $itemTable;
    public function __construct()
    {
        parent::__construct();
        $user = prSession::getSession(prSession::SESSION_USER);
        if(empty($user))
        {
            return $this->redirect()->toRoute('login',
                array('controller'=>'login',
                    'action' => 'index'));
        }
    }

    public function listAction()
    {
        $sestionClient = prSession::getSession(prSession::SESSION_USER);
        $clientID =  $sestionClient['UserID'];
        $sm = $this->getServiceLocator();
        $notiClass = $sm->get('Notifications\Model\prApiCoreNotiClass');
        $result = $notiClass->getList($clientID);
        $uselist = $notiClass->geAllUser();
        $view = new ViewModel(array(
            'result'=>$result,
            'clientIDLogin'=>$clientID,
            'userName'=>$sestionClient['firstname'],
            'uselist'=>$uselist,
        ));
       $this->layout()->setVariables(array('userLogin'=>$sestionClient));
        return $view;
    }

    public function deleteNotificationsAction()
    {
        //$this->_helper->layout->disableLayout();
       // $this->_helper->viewRenderer->setNoRender();
        $listNotiID = $this->getRequest()->getPost('listNotiID');
        $idCurrentActive = $this->getRequest()->getPost('idCurrentActive');
        $sm = $this->getServiceLocator();
        $notiClass = $sm->get('Notifications\Model\prApiCoreNotiClass');
        $return = $notiClass->delete($listNotiID);
        if($return){
            $return = array("success" => 1, "error" => "");
        }else{
            $return = array("success" => 0, "error" => "");
        }
        //return
        $this->getResponse()->getHeaders()->addHeaders(array('Content-Type'=>'application/json;charset=UTF-8'));
        return $this->getResponse()->setContent(Json::encode($return));
    }

    public function saveNotificationsAction()
    {
        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
        $subjectNotification = $this->getRequest()->getPost('subjectNotification');
        $this->getRequest()->getPost('contentNotification');
        $contentNotification = $this->getRequest()->getPost('contentNotification');
        $receideid = $this->getRequest()->getPost('receiverid');

        $sestionClient = prSession::getSession(prSession::SESSION_USER);
        $clientID =  $sestionClient['UserID'];

        $sm = $this->getServiceLocator();
        $noti = $sm->get('Notifications\Model\prApiCoreNotiClass');
        $updateFields = array('subjecttext'=>$subjectNotification,'sender_iduser'=>$clientID,'sender_iduser'=>$clientID, 'receiver_iduser'=>$receideid, 'content'=>$contentNotification);
        $result = $noti->save($updateFields);

        if($result){
            $return = array("success" => 1, "error" => "");
        } else{
            $return = array("success" => 0, "error" => "1");
        }

        //return
        $this->getResponse()->getHeaders()->addHeaders(array('Content-Type'=>'application/json;charset=UTF-8'));
        return $this->getResponse()->setContent(Json::encode($return));
    }

}
?>