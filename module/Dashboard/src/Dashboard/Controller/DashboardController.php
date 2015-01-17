<?php
namespace Dashboard\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Login\Model\prSession;
use Application\Controller\ApplicationControllerAction;

class DashboardController extends ApplicationControllerAction {
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

    public function getItemTable()
    {
        if (!$this->itemTable) {
            $sm = $this->getServiceLocator();

           // $this->itemTable = $sm->get('Login\Model\userLogin');
        }
       // return $this->itemTable;
    }

    public function indexAction()
    {
        $userLogin = prSession::getSession(prSession::SESSION_USER);
        $view = new ViewModel(array(
            'uselist'=>'',
            'getListOpp'=>'',
            'messageList'=>'',
            'listCandidate'=>'',
            'resultTestList'=>'',
        ));
        $this->layout()->setVariables(array('userLogin'=>$userLogin));
        //$view->setTemplate('layout/layout');
        return $view;
    }

}
?>