<?php

/**
 * Description of UserTable
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Register/src/Register/Model/UserTable.php

namespace Register\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class UserTable extends AbstractTableGateway {

    protected $table = 'user';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                    $select->order('UserID ASC');
                });
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Entity\User();
            $entity->setUserID($row->UserID)
                    ->setUsertype($row->usertype)
                    ->setFirstname($row->firstname)
					->setMiddlename($row->middlename)
					->setLastname($row->lastname)
					->setDob($row->dob)
					->setCompanyID($row->CompanyID)
					->setCandidateProfileID($row->CandidateProfileID)
					->setLoginname($row->loginname)
					->setPassword($row->password)
					->setEmailaddress($row->emailaddress)
					->setPhoneNumber($row->PhoneNumber)
					->setAddress1($row->Address2)
					->setCity($row->City)
					->setState($row->State)
					->setPostalCode($row->PostalCode)
					->setCountry($row->Country)
					->setRole($row->Role)
					->setActive($row->Active)
					->setLastsigned($row->Lastsigned)
					->setFaxnumber($row->Faxnumber)
					->setHeardFrom($row->HeardFrom);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function getUser($UserID) {
        $row = $this->select(array('UserID' => (int) $UserID))->current();
        if (!$row)
            return false;

        $users = new Entity\User(array(
                    'UserID' => $row->UserID,
                    'usertype' => $row->usertype,
                    'firstname' => $row->firstname,
					'middlename' => $row->middlename,
					'lastname' => $row->lastname,
					'dob' => $row->dob,
					'CompanyID' => $row->CompanyID,
					'CandidateProfileID' => $row->CandidateProfileID,
					'loginname' => $row->loginname,
					'password' => $row->password,
					'emailaddress' => $row->emailaddress,
					'PhoneNumber' => $row->PhoneNumber,
					'Address2' => $row->Address2,
					'City' => $row->City,
					'State' => $row->State,
					'PostalCode' => $row->PostalCode,
					'Country' => $row->Country,
					'Role' => $row->Role,
					'Active' => $row->Active,
					'Lastsigned' => $row->Lastsigned,
					'Faxnumber' => $row->Faxnumber,
					'HeardFrom' => $row->HeardFrom,
                ));
        return $users;
    }

   //public function saveUser(Entity\User $user) {
    public function saveUser($user) {
        
            $data=array(
            "firstname"=>$user["firstname"],
            "lastname"=>$user["lastname"],
            "usertype"=>$user["usertype"],
            "emailaddress"=>$user["emailaddress"],
            "password"=>$user["password"],
            "HeardFrom"=>$user["HeardFrom"],
            "PostalCode"=>$user["PostalCode"],
            "CompanyID"=>$user["CompanyID"]
            );
       
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue(); 
       
    }
     public function getEmailUser($emailaddress) {
        $row = $this->select(array('emailaddress' => $emailaddress))->current();
        if (!$row)
            return false;

        $email = new Entity\User(array(
                    
                    'emailaddress' => $row->emailaddress,
                    
                ));
        return $email;
    }

    public function removeStickyNote($id) {
        return $this->delete(array('id' => (int) $id));
    }

}
?>