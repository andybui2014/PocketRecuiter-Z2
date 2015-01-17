<?php
namespace Login\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
//use Application\Model\PR_Api_Error;
//use Login\Model\Sql11;

class userLogin extends AbstractTableGateway
{
    const USER_TYPE_COMPANY = 1;
    const USER_TYPE_CANDIDATE = 2;

    protected $_name = 'user';
    private $UserID;
    private $usertype;
    private $firstname;
    private $middlename;
    private $lastname;
    private $dob;
    private $CompanyID;
    private $CandidateProfileID;
    private $loginname;
    private $password;
    private $emailaddress;
    private $URL;
    private $PhoneNumber;
    private $Address1;
    private $Address2;
    private $City;
    private $State;
    private $PostalCode;
    private $Country;
    private $HeardFrom;
    private $Lastsigned;

    protected $table ='user';
    protected $authenticate = false;
    public $errMsg = "";
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->initialize();
    }

    public function getUserArray($authData) {
        $errors = prApiError::getInstance();
        $eml =$authData['emailaddress'];
        $pass = $authData['password'];
        if($eml=='api@pr.com' && $pass=='api'){
            $errors->addError(3, 'emailaddress or password is not correct');
            $this->ClientID = NULL;
            $this->UserName      = NULL;
            return null;
        }

        $sql = new Sql($this->adapter);
        /*echo "<pre>";
        echo "login= "; print_r($sql);
        echo "</pre>"; die();*/
        $select = $sql->select();
        $select->from($this->table);
        $select->where(array('emailaddress' => $authData['emailaddress']));

        if (isset($authData['password'])) {
            $select->where(array('password' => $authData['password']));
        }
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $user = array();
        foreach ($results as $result) {
            $user[] = $result;
        }

        ///

        if (!empty($user))
        {
            foreach ($user[0] as $key => $value)
            {
                if (property_exists('userLogin', $key)) {
                    $this->{$key} = $value;
                }
            }
            return $user[0];
        } else {
            $errors->addError(3, 'emailaddress or password is not correct');
            $this->ClientID = NULL;
            $this->UserName  = NULL;
            return null;
        }
    }


    public function loadAndCheckAuthentication($authData)
    {
        $errors = prApiError::getInstance();
        $this->authenticate = false;

        if (empty($authData['emailaddress']) || empty($authData['password'])) {
            $errors->addError(2, 'emailaddress or password is empty but required');
            return false;
        }
        $user = $this->getUserArray($authData);

        if (!empty($user)) {
            $this->authenticate = true;
        }
        return $user;

    }

    public function UpdateLastsigned($userID)
    {
        $Lastsigned=date('Y-m-d H:i:s');
        /*echo "<pre>";
        print_r($userID);
        echo "<pre>"; die();*/
        $sql = new Sql($this->adapter);
        $select = $sql->update();
        $select->table($this->table);
        $select->set(array( 'Lastsigned' => $Lastsigned));
        $select->where(array('UserID'=>$userID));
        $statement = $sql->prepareStatementForSqlObject($select);
        $statement->execute();
        return;
    }

    public function getCandidateInfo($userID)
    {
        //user: UserID,usertype,CandidateProfileID
        $userID = trim($userID);
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from('user');
        $select->where(array('UserID'=>$userID));
        $select->where(array("usertype" => 2));
        $statement = $db->prepareStatementForSqlObject($select);
        $results=$statement->execute();
        $records = array();
        foreach ($results as $resultsInfo) {
            $records[] = $resultsInfo;
        }
        if(empty($records) || count($records)==0) {
            return null;
        } else {
            return $records[0];
        }
    }

    public function createCandidateProfileID($userID)
    {
        //--- userInfo
        $userInfo = $this->getCandidateInfo($userID);
        if(empty($userInfo) || count($userInfo)==0) {
            return 0;
        }
        if($userInfo['usertype'] != self::USER_TYPE_CANDIDATE){
            return 0;
        }

        $candidateProfileID = 0;
        if(!empty($userInfo['CandidateProfileID'])) {
            $candidateProfileID = $userInfo['CandidateProfileID'];
        } else {
            //$candidateProfileID = PR_Database::insert('candidate_profile',array('usercol1'=>''),true);
            //Insert
            $sql = new Sql($this->dbAdapter);
            $insert = $sql->insert('candidate_profile');
            $newData = array( 'CandidateProfileID'=> '');
            $insert->values($newData);
            $statement = $sql->prepareStatementForSqlObject($insert);
            $candidateProfileID = $statement->execute()->getGeneratedValue();
            //update
            $sql = new Sql($this->adapter);
            $select = $sql->update();
            $select->table('user');
            $select->set(array( 'CandidateProfileID' => $candidateProfileID));
            $select->where(array('UserID'=>$userID));
            $statement = $sql->prepareStatementForSqlObject($select);
            $statement->execute()->getAffectedRows();
        }
        //print_r($userInfo);
        return $candidateProfileID;
    }

}
?>