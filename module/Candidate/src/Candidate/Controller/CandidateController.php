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

    private function attribute_child($candiate_id, $ID, $disable) {
        $source1 = "";
        if(!empty($ID)){
            $sm = $this->getServiceLocator();
            $core = $sm->get('Candidate\Model\prApiCoreCandidateClass');
            $client = prSession::getSession(prSession::SESSION_USER);
            $Child = $core->get_attribute_child($candiate_id,$ID);
            if(!empty($Child)){

                foreach($Child as $item){
                    $sub = $this->attribute_child($candiate_id, $item['ID'],$disable);
                    $sel1 ="<div class='col-md-2'></div>";
                    $Track1 ='';
                    if(!empty($item['TemplateID'])){
                        $option = "";

                        foreach($item['TemplateID'] as $itemInfo){
                            if($itemInfo['Value'] == $item['Value']){
                                $selected11 = 'selected="selected"';
                            } else{
                                $selected11 ='';
                            }
                            $option .= "<option value='".$itemInfo['Value']."' ".$selected11.">".$itemInfo['Description']."</option>";
                        }
                        $sel1 = " <select class='form-control attr-value'  style='color:#5d629c' ".$disable."> ".$option."</select>";
                    }

                    if($item['TrackYearsOfExperience'] && $item['TrackLevelofInterest']){
                        $selected = '';
                        $option_exp ='';

                        for($i=1; $i<6; $i++){
                            if($i==$item['LevelofInterest']){
                                $selected = 'selected="selected"';
                            } else{
                                $selected ='';
                            }
                            $option_exp .= "<option value='".$i."' ".$selected.">".$i."</option>";
                        }

                        $Track1 = "<div class='col-md-2' style='padding-right:0; color:#706d67;line-height:34px'>Years of Experience </div>
                                                                  <div class='col-md-1' style='padding:0'><input type='text' style='color:#706d67' ".$disable." class='form-control attr-YoE' value='".$item['YearsofExperience']."'></div>
                                                                  <div class='col-md-2' style='text-align:right; color:#706d67;line-height:34px'>Level of Interest</div>
                                                                  <div class='col-md-1' style='padding:0'>
                                                                    <select class='form-control attr-LevelofInterest' ".$disable."  style='color:#706d67'>
                                                                            '.$option_exp.'
                                                                    </select>
                                                                  </div>";
                    }

                    $source1 .="<ul style='margin-top:10px' class='attr-attr'>
                                                            <li>
                                                                <div class='col-md-12' style='padding:0'>
                                                                     <div style='line-height:34px;padding:0;margin-bottom:10px' attr-id=".$item['ID']." class='col-md-4 attrid'>".$item['Attribute']."</div>
                                                                    <div class='col-md-2' style='padding:0; margin-bottom:10px'>
                                                                        ".$sel1."
                                                                    </div>
                                                                    ".$Track1."
                                                                </div>
                                                            </li>

                                                        </ul>";
                    $source1 .=  $sub;
                }

            }
        }
        return $source1;
    }

    public function profileBuilderAction(){
       //$utm_source = $this->params()->fromRoute('utm_source');
        //
        $utm_source = $this->params()->fromQuery('utm_source');
		//echo "testt:".$utm_source;
        $utm_source = trim($utm_source);
        if(!empty($utm_source) && isset($utm_source)){
            $client = prSession::getSession(prSession::SESSION_USER);
            $sm = $this->getServiceLocator();
            $core = $sm->get('Candidate\Model\prApiCoreCandidateClass');
            $getUserArray=$core->getCandidateInfo($client['UserID']);
            $Candidateprofile_ID=$getUserArray["CandidateProfileID"];
            $getCandidates=$core->getCandidateProfile($Candidateprofile_ID);

            switch($utm_source){
                case 'contact':
                    $info = $core->getContactInfo($client['UserID']);
                    $view = new ViewModel(array(
                        'client'=>$getUserArray,
                        'getCandidates'=>$getCandidates,
                        'step'=>$utm_source,
                        'stepCount'=>'1/5 Steps',
                        'info'=>$info
                    ));
                    $view->setTemplate('candidate/candidate/profile-builder/contact.phtml');
                    $this->layout()->setVariables(array('userLogin'=>$client));
                    return $view;
                    break;
                case 'education':
                    $list = $core->getCandidateEducationList($client['UserID']);
                    //echo "tetst:<pre>";print_r($list);echo"</pre>";
                    $view = new ViewModel(array(
                        'client'=>$getUserArray,
                        'getCandidates'=>$getCandidates,
                        'step'=>$utm_source,
                        'stepCount'=>'2/5 Steps',
                        'list'=>$list, 
                        'sm'=>$sm                       
                    ));
                    $view->setTemplate('candidate/candidate/profile-builder/education.phtml');
                    $this->layout()->setVariables(array('userLogin'=>$client));
                    return $view;
                    break;
				case 'employment':                    
                    $list = $core->getCandidateEmployments($client['UserID']);                   
                    if(isset($params["id"])||!empty($params["id"])){
                    $id=$params["id"];
                    $jobfunctions=$core->get_jobfuntion($id);                    
                    $employment=$core->getCandidateEmployment($id);                   
                    $totalPercentage=$core->totalPercentage($id);
                    $totalprcent=round($totalPercentage["totalPercentage"]*100,2);
                    }
                    
                    $view = new ViewModel(array(
                        'client'=>$getUserArray,
                        'getCandidates'=>$getCandidates,
                        'step'=>$utm_source,
                        'stepCount'=>'3/5 Steps',
                        'list'=>$list, 
                        'sm'=>$sm,
                                                                      
                    ));
                    if(!empty($jobfunctions))
                    {
                      $view->jobfunctions=$jobfunctions;  
                    }
                    if(!empty($employment)){
                        $view->employment=$employment;
                    }
                    if(!empty($totalprcent)){
                        $view->totalPercentage=$totalprcent;
                    }
                    $view->setTemplate('candidate/candidate/profile-builder/employment.phtml');
                    $this->layout()->setVariables(array('userLogin'=>$client));
                    return $view;
                    break;
                case 'skills':
                    $tree = '';
                    // -----------------Attribute-----------------------------
                    $html ='';
                    $attr_p0_list = $core->get_attribute_p0($Candidateprofile_ID);
                     if(!empty($attr_p0_list)){

                        foreach($attr_p0_list as $attr_p0Info){
                            if(!empty($attr_p0Info['Candidate_ProfileID'])){
                                $select = 'select';
                                $disable = '';
                            } else {
                                $select = 'deselect';
                                $disable = 'disabled';
                            }
                            $src = (!empty($attr_p0Info['Candidate_ProfileID'])) ?  URL_THEMES.'images/trees/ico_colapse.png' : URL_THEMES.'images/trees/ico_expand.png';

                            $toggle = URL_THEMES .'images/trees/ico_sub_sm.png';

                            $html .= "<div class='col-md-12' style='margin:0;padding:0; color: #5d629c'><div class='tree'><ul>";

                            $html .= "<li>
                                            <img data-id='".$attr_p0Info['ID']."' data-status='".$select."' class='img-parent-attr' src='".$src."'/>
                                            <a href='#' style='color:#5d629c'><strong>" . $attr_p0Info['AttributeCategory'] . "</strong></a>
                                        <span><img class='img-toggle' src='".$toggle."'/></span>";

                            $AttributeID_Parents = $core->get_attribute_parent($Candidateprofile_ID, $attr_p0Info['ParentAttributeID'], $attr_p0Info['AtttributeCategoryID']);
                            if(!empty($AttributeID_Parents)){
                                foreach ($AttributeID_Parents as $attrInfo){
                                    $sel ="<div class='col-md-2'></div>";

                                    if(!empty($attrInfo['TemplateID'])){
                                        $option = "";
                                        foreach($attrInfo['TemplateID'] as $TemplateIDInfo){
                                            if($TemplateIDInfo['Value'] == $attrInfo['Value']){
                                                $selected1 = 'selected="selected"';
                                            } else{
                                                $selected1 ='';
                                            }
                                            $option .= "<option value='".$TemplateIDInfo['Value']."' ".$selected1." >".$TemplateIDInfo['Description']."</option>";
                                        }
                                        $sel = " <select class='form-control attr-value'  style='color:#5d629c' ".$disable."> ".$option."</select>";
                                    }

                                    $Track ='';
                                    if($attrInfo['TrackYearsOfExperience'] && $attrInfo['TrackLevelofInterest']){
                                        $selected = '';
                                        $option_exp ='';

                                        for($i=1; $i<6; $i++){
                                            if($i==$attrInfo['LevelofInterest']){
                                                $selected = 'selected="selected"';
                                            } else{
                                                $selected ='';
                                            }
                                            $option_exp .= "<option value='".$i."' ".$selected.">".$i."</option>";
                                        }

                                        $Track = "<div class='col-md-2' style='padding-right:0; color:#706d67;line-height:34px'>Years of Experience </div>
                                                                  <div class='col-md-1' style='padding:0'><input type='text' style='color:#706d67' ".$disable." class='form-control attr-YoE' value='".$attrInfo['YearsofExperience']."'></div>
                                                                  <div class='col-md-2' style='text-align:right;color:#706d67;line-height:34px'>Level of Interest</div>
                                                                  <div class='col-md-1' style='padding:0'>
                                                                    <select class='form-control attr-LevelofInterest' style='color:#706d67' ".$disable.">
                                                                            '.$option_exp.'
                                                                    </select>
                                                                  </div>";
                                    }



                                    $html .="<ul style='margin-top:10px' class='attr-attr'>
                                                            <li>
                                                                <div class='col-md-12' style='padding:0'>
                                                                    <div style='line-height:34px;padding:0;margin-bottom:10px' attr-id=".$attrInfo['ID']." class='col-md-4 attrid'>".$attrInfo['Attribute']."</div>
                                                                    <div class='col-md-2' style='padding:0; margin-bottom:10px'>
                                                                        ".$sel."
                                                                    </div>
                                                                    ".$Track."
                                                               </div>

                                                            </li>

                                                        </ul>";

                                    $html .= $this->attribute_child($Candidateprofile_ID,$attrInfo['ID'],$disable);

                                }
                            }

                            $html .= "</li></ul></div></div>";
                        }

                    }
                    $view = new ViewModel(array(
                        'client'=>$getUserArray,
                        'getCandidates'=>$getCandidates,
                        'step'=>$utm_source,
                        'stepCount'=>'4/5 Steps',
                        'html'=>$html
                    ));
                    $view->setTemplate('candidate/candidate/profile-builder/skills.phtml');
                    $this->layout()->setVariables(array('userLogin'=>$client));
                    return $view;
                    break;
                case 'portfolio':
                    $list = $core->getListCandidatePortfolio($client['UserID']);

                    $view = new ViewModel(array(
                        'client'=>$getUserArray,
                        'getCandidates'=>$getCandidates,
                        'step'=>$utm_source,
                        'stepCount'=>'5/5 Steps',
                        'list'=>$list
                    ));
                    $view->setTemplate('candidate/candidate/profile-builder/portfolio.phtml');
                    $this->layout()->setVariables(array('userLogin'=>$client));
                    return $view;
                    break;
                default:
                   // $this->render('profile-builder/index');
                    break;
            }
        }else{
            //$this->render('profile-builder/index');
        }

    }

    public function doUpdateSkillsAction(){
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getPost('data');

                if(isset($params)){
                    $attributeIDs = $params;
                } else{
                    $attributeIDs = array();
                }

            $client = prSession::getSession(prSession::SESSION_USER);
            $sm = $this->getServiceLocator();
            $core = $sm->get('Candidate\Model\prApiCoreCandidateClass');
            if($core->updateCandidateAttribute($client['CandidateProfileID'],$attributeIDs)){
                $ajaxRes['success'] = 1;
            }
        }

        //return
        $this->getResponse()->getHeaders()->addHeaders(array('Content-Type'=>'application/json;charset=UTF-8'));
        return $this->getResponse()->setContent(Json::encode($ajaxRes));

    }

    public function stepNextContactAction(){
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params['data'] = $this->getRequest()->getPost('data');
            if(!empty($params['data']) && sizeof($params['data'])){
                $client = $client = prSession::getSession(prSession::SESSION_USER);
                $data = array();
                $errors = array();
                foreach($params['data'] as $item){
                    if($item['name']=='firstname'){
                        if(empty($item['value'])){
                            $errors['firstname'] = 1;
                        }else{
                            $data['firstname'] = $item['value'];
                        }
                    }
                    if($item['name']=='lastname'){
                        if(empty($item['value'])){
                            $errors['lastname'] = 1;
                        }else{
                            $data['lastname'] = $item['value'];
                        }
                    }
                    if($item['name']=='email'){
                        if(empty($item['value'])){
                            $errors['email'] = 1;

                        }else{
                            if (!filter_var($item['value'], FILTER_VALIDATE_EMAIL)) {
                                $errors['email'] = 1;
                            }else{
                                $data['emailaddress'] = $item['value'];
                            }
                        }
                    }

                    if($item['name']=='phone')          $data['PhoneNumber']  = $item['value'];
                    if($item['name']=='url')            $data['URL']  = $item['value'];
                    if($item['name']=='city')           $data['City']  = $item['value'];
                    if($item['name']=='country')        $data['Country']  = $item['value'];
                    if($item['name']=='zipcode')        $data['PostalCode']  = $item['value'];

                }

                if(empty($errors)){

                    $sm = $this->getServiceLocator();
                    $core = $sm->get('Candidate\Model\prApiCoreCandidateClass');
                    $core->saveContactInfo($client['UserID'],$data);
                    $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = $errors;
                }

            }
        }

        $this->getResponse()->getHeaders()->addHeaders(array('Content-Type'=>'application/json;charset=UTF-8'));
        return $this->getResponse()->setContent(Json::encode($ajaxRes));
    }

}
?>