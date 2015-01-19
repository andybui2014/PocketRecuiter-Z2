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
}
?>