<?php
namespace Candidate\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Login\Model\prApiError;


class prApiCoreCandidateClass extends AbstractTableGateway
{
    const USER_TYPE_COMPANY = 1;
    const USER_TYPE_CANDIDATE = 2;
    protected $table ='user';
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->initialize();
    }

    public function getContactInfo($userID)
    {
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from('user');
        $select->where(array('UserID' => $userID));
        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }
        if(!empty($records) && count($records)>0){
            return $records[0];
        } else{
            return $records;
        }

    }

    public function getCandidateInfo($userID)
    {
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from(array('u'=>'user'));
        $select->where(array("UserID" =>$userID));
        $select->where(array("usertype" => 2));
        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }

        if(!empty($records) && count($records)>0){
            return $records[0];
        } else{
            return $records;
        }
    }

    public function getCandidate_Employments($CandidateProfileID)
    {

        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->columns(array('CandidateEmploymentID','CandidateProfileID','CompanyName','PostionHeld','StartDate','EndDate','Description','LastUpdated','LastUpdatedByUserID'));
        $select->from('candidate_employments');
        $select->where(array("CandidateProfileID" =>$CandidateProfileID));
        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }

        return $records;
    }

    public function getCandidateProfile($Candidateprofile_ID)
    {
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from('candidate_profile');
        $select->where(array("CandidateProfileID" =>$Candidateprofile_ID));
        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }

        if(empty($records) || count($records)==0){
            return array();
        } else {
            $retArray = array();
            foreach($records[0] as $key=>$val){
                $retArray[$key] = $val;
            }
            $retArray['CandidateEmploymentID'] = $this->getCandidate_Employments($Candidateprofile_ID);
            return $retArray;
        }

    }

    public function get_attribute_p0($ID)
    {
        $errors = prApiError::getInstance();
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from(array('ct' => 'attribute_category'))
            ->join(array('jft'=>'jobfunctionattributecategory'),
                'jft.AttributeCategoryID = ct.AttributeCatetoryID',
                array('*'),
                $select::JOIN_INNER
            );
        $select->join(array('attr'=>'attribute'),
            'attr.AtttributeCategoryID = ct.AttributeCatetoryID',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->join(array('cav'=>'candidate_attribute_value'),
            new Expression('attr.ID = cav.AttributeID AND cav.Candidate_ProfileID = "'.$ID.'"'),
            array('Candidate_ProfileID'),
            $select::JOIN_LEFT
        );

        $select->group('ct.AttributeCatetoryID');

        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }

        return $records;
    }

    public function get_attribute_rubrics($TemplateID)
    {
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from(array('att'=>'attribute_rubrics'));
        $select->where(array("att.TemplateID" => $TemplateID));
        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }
        if(empty($records) && count($records)==0){
            return array();
        } else {
            return $records;
        }
    }

    public function get_attribute_child($cadidate_profileID, $ID)
    {
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from(array('attr'=>'attribute'));
        $select->join(array('ct'=>'attribute_category'),
            'attr.AtttributeCategoryID = ct.AttributeCatetoryID',
            array('AttributeCategory'),
            $select::JOIN_LEFT
        );
        $select->join(array('cav'=>'candidate_attribute_value'),
            new Expression('attr.ID = cav.AttributeID AND cav.Candidate_ProfileID ="'.$cadidate_profileID.'"'),
            array('YearsofExperience','LevelofInterest','Candidate_ProfileID','Value'),
            $select::JOIN_LEFT
        );
        $select->where(array("attr.ParentAttributeID" =>$ID));

        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }

        if(empty($records) && count($records)==0){
            return array();
        } else {
            $list = array();
            foreach($records as $rec){
                $rec['TemplateID'] = $this->get_attribute_rubrics($rec['AttributeRubricTemplateID']);

                $list[] = $rec;
            }
            return $list;
        }
    }

    public function get_attribute_parent($ID,$ParentAttributeID,$AtttributeCategoryID)
    {
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->columns(array('*'));
        $select->from(array('attr'=>'attribute'));
        $select->join(array('ct'=>'attribute_category'),
            'attr.AtttributeCategoryID = ct.AttributeCatetoryID',
            array('AttributeCategory'),
            $select::JOIN_LEFT

        );

        $select->join(array('cav'=>'candidate_attribute_value'),
            new Expression('attr.ID = cav.AttributeID AND cav.Candidate_ProfileID ="'.$ID.'"'),
            array('YearsofExperience','LevelofInterest','Candidate_ProfileID','Value'),
            $select::JOIN_LEFT
        );

        $select->where("attr.ParentAttributeID = '".$ParentAttributeID."' && attr.AtttributeCategoryID = '".$AtttributeCategoryID."' " );
        $select->where(array("attr.ParentAttributeID" =>$ParentAttributeID, "attr.AtttributeCategoryID" => $AtttributeCategoryID));

        $select->order(array('attr.AtttributeCategoryID ASC','attr.ID ASC' ));

        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }

        if(empty($records) && count($records)==0){
            return array();
        } else {
            $list = array();
            foreach($records as $rec){
                $rec['TemplateID'] = $this->get_attribute_rubrics($rec['AttributeRubricTemplateID']);

                $list[] = $rec;
            }
            return $list;
        }
    }

    public function updateCandidateAttribute($CandidateProfileID,$attrs){
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from(array('cav'=>'candidate_attribute_value') );

        $select->where(array("cav.Candidate_ProfileID" =>$CandidateProfileID));
        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }

        if(count($records)>0){
            if(empty($attrs) || count($attrs)==0){
                $select = $db->delete();
                $select->from('candidate_attribute_value');
                $select->where(array("Candidate_ProfileID" =>$CandidateProfileID));
                $statement = $db->prepareStatementForSqlObject($select);
                $results = $statement->execute();

            } else{

                foreach($records as $rec){
                    $database_Exist=$rec['AttributeID'];
                    $flag_exsist = false;
                    foreach($attrs as $attrInfo){
                        if($database_Exist == $attrInfo['attr_id']){
                            $flag_exsist = true;
                            break;
                        }
                    }

                    if(!$flag_exsist){
                        $select = $db->delete();
                        $select->from('candidate_attribute_value');
                        $select->where(array("Candidate_ProfileID" =>$CandidateProfileID, "AttributeID" => $database_Exist));
                        $statement = $db->prepareStatementForSqlObject($select);
                        $results = $statement->execute();
                    }
                }

                // if $attr not exist in database then insert, else update
                foreach($attrs as $attributeInfo){
                    $attr_Exist=$attributeInfo['attr_id'];
                    $flag_exsist = false;
                    foreach($records as $recordsInfo){
                        if($attr_Exist == $recordsInfo['AttributeID']){
                            $data = array('Candidate_ProfileID'=>$CandidateProfileID,'AttributeID'=>$attributeInfo['attr_id'],'Value'=>$attributeInfo['attr_value'],'YearsofExperience'=>$attributeInfo['attr_YoE'],'LevelofInterest'=>$attributeInfo['attr_LevelofInterest']);
                            $select = $db->update();
                            $select->table('candidate_attribute_value');
                            $select->set($data);
                            $select->where(array("Candidate_ProfileID" => $CandidateProfileID, "AttributeID" => $attr_Exist));
                            $statement = $db->prepareStatementForSqlObject($select);
                            $results = $statement->execute();
                            $flag_exsist = true;
                            break;
                        }
                    }

                    if(!$flag_exsist){
                        $data=array('Candidate_ProfileID'=>$CandidateProfileID,'AttributeID'=>$attributeInfo['attr_id'],'Value'=>$attributeInfo['attr_value'],'YearsofExperience'=>$attributeInfo['attr_YoE'],'LevelofInterest'=>$attributeInfo['attr_LevelofInterest']);
                        $insert = $db->insert('candidate_attribute_value');
                        $insert->values($data);
                        $statement = $db->prepareStatementForSqlObject($insert);
                        $affectedRows = $statement->execute()->getAffectedRows();
                    }
                }

            }
        } else {
            if(!empty($attrs) && count($attrs)>0){
                foreach($attrs as $id){
                    $data=array('Candidate_ProfileID'=>$CandidateProfileID,'AttributeID'=>$id['attr_id'],'Value'=>$id['attr_value'],'YearsofExperience'=>$id['attr_YoE'],'LevelofInterest'=>$id['attr_LevelofInterest']);
                    $insert = $db->insert('candidate_attribute_value');
                    $insert->values($data);
                    $statement = $db->prepareStatementForSqlObject($insert);
                    $affectedRows = $statement->execute()->getAffectedRows();
                }
            }
        }
        return true;
    }

    public function saveContactInfo($userID, $data)
    {
        $avaiFields = array('firstname','middlename','lastname','dob','emailaddress',
            'URL','PhoneNumber','Address1','Address2','City','State','PostalCode','Country','faxnumber','URlnetwork','instanmessaing');
        $contactInfo = $this->getContactInfo($userID);

        $updateFields = array();
        foreach($data as $k=>$v){
            if(in_array($k,$avaiFields)){
                if($v != $contactInfo[$k]){
                    $updateFields[$k] = $v;
                }
            }
        }

        if(count($updateFields)==0){
            return 0;
        } else {
            $db = new Sql($this->adapter);
            $select = $db->update();
            $select->table('user');
            $select->set($updateFields);
            $select->where(array("UserID"=> $userID));
            $statement = $db->prepareStatementForSqlObject($select);
            $results = $statement->execute()->getAffectedRows();

        }
    }

    public function getListCandidatePortfolio($userID)
    {

        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from(array('capfl'=>'candidate_portfolio'),
            array('CandidatePortfolioID','CandidateProfileID','Title','URL','Description','IconURL')
        );
        $select->join(array('u'=>'user'),
            'u.CandidateProfileID = capfl.CandidateProfileID',
            array('UserID'),
            $select::JOIN_INNER
        );
        $select->where(array("u.UserID" =>$userID));
        $select->where(array("u.usertype" => 2));
        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }
        return $records;
    }

}
?>