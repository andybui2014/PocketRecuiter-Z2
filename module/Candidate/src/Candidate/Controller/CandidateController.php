<?php
namespace Candidate\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\ApplicationControllerAction;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Login\Model\prSession;

class CandidateController extends ApplicationControllerAction {
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

    public function startProfileAction()
    {
        $sestionClient = prSession::getSession(prSession::SESSION_USER);
        $view = new ViewModel(array(
            'result'=>''
        ));
       $this->layout()->setVariables(array('userLogin'=>$sestionClient));
        return $view;
    }

}
?>