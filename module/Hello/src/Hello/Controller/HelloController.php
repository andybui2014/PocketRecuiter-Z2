<?php
namespace Hello\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Hello\Form\NewsForm;
use Hello\Form\Validate\News;
class HelloController extends AbstractActionController
{
    protected $itemTable;
   public function getItemTable()
   {
      if (!$this->itemTable) {
         $sm = $this->getServiceLocator();

         $this->itemTable = $sm->get('Hello\Model\News');
      }
      return $this->itemTable;
   }
   public function indexAction()
   {
      return new ViewModel(array(
            'news' => $this->getItemTable()->listItem(),
      ));
      
   }
   public function addAction()
   {
      $form = new NewsForm();
      $form->get('submit')->setAttribute('value', 'Add');
      $request = $this->getRequest();
      if ($request->isPost()) {
         $validateNews = new News();
         $form->setInputFilter($validateNews->getInputFilter());
         $form->setData($request->getPost());
         if ($form->isValid()) {
            
            $data = $form->getData();
            echo "testt:<pre>";print_r($data);echo("</pre>");die();
            $this->getItemTable()->saveItem($data);
            // Redirect to list of albums
            return $this->redirect()->toRoute('hello');
            
         }
      }
      //Truyen ra ngoai VIEW
      return array('action' => __METHOD__,'form'=>$form);
   }

    public function editAction()
    {
      $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('hello', array(
                'action' => 'add'
            ));
        }
        $item = $this->getItemTable()->getItem($id);

        $form = new NewsForm();
        $form->bind($item);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $validateNews = new FormNews();
         $form->setInputFilter($validateNews->getInputFilter());
         $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getItemTable()->saveItem($item);

                // Redirect to list of albums
                return $this->redirect()->toRoute('hello');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
      }
    public function deleteAction()
    {
      $id = (int) $this->params()->fromRoute('id', 0);
      if (!$id) {
         return $this->redirect()->toRoute('hello');
      }

      $this->getItemTable()->deleteItem($id);

      return $this->redirect()->toRoute('hello');
    }
}