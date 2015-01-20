<?php
namespace Home\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Home\Form\NewsForm;
use Application\Controller\ApplicationControllerAction; 

class HomeController extends ApplicationControllerAction
{
    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction()
    {
       
    }
    
}