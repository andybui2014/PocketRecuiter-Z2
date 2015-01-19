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

    public function profileBuilderAction(){
       // $utm_source = $this->getRequest()->getPost();
        $utm_source = $this->params()->fromRoute('utm_source');
        $utm_source = trim($utm_source);
        if(!empty($utm_source) && isset($utm_source)){
            $client = prSession::getSession(prSession::SESSION_USER);
            $sm = $this->getServiceLocator();
            $core = $sm->get('Candidate\Model\prApiCoreCandidateClass');
            $getUserArray=$core->getCandidateInfo($client['UserID']);
            $Candidateprofile_ID=$getUserArray["CandidateProfileID"];
            $getCandidates=$core->getCandidateProfile($Candidateprofile_ID);

            //$this->view->client = $getUserArray;
            //$this->view->getCandidates=$getCandidates;
            //$this->view->step = $params['utm_source'];

            switch($utm_source){
                case 'contact':
                    $info = $core->getContactInfo($client['UserID']);
                    $view = new ViewModel(array(
                        'client'=>$getUserArray,
                        'getCandidates'=>$getCandidates,
                        'step'=>$utm_source,
                        'stepCount'=>'1/5 Steps',
                        'info'=>$info,
                        'utm_source'=>$utm_source
                    ));
                    $this->layout()->setVariables(array('userLogin'=>$client));
                    return $view;
                    break;
                case 'skills':


                    $tree = '';
                    // -----------------Attribute-----------------------------

                    $html ='';
                    $attr_p0_list = $core->get_attribute_p0($Candidateprofile_ID);
                    /* if(!empty($attr_p0_list)){

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
                 */
                    $view = new ViewModel(array(
                        'getCandidates'=>'',
                        'step'=>'',
                        'stepCount'=>'',
                        'html'=>'',
                        'utm_source'=>$utm_source
                    ));
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

}
?>