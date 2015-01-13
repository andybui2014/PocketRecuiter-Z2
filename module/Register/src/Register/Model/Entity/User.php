<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Users
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Register/src/Register/Model/Entity/User.php

namespace Register\Model\Entity;

class User {  
    
    protected $UserID;
    protected $usertype;
    protected $firstname;
    protected $middlename;
    protected $lastname;
    protected $dob;
    protected $CompanyID;
    protected $CandidateProfileID;
    protected $loginname;
    protected $password;
    protected $emailaddress;
    protected $URL;
    protected $PhoneNumber;
    protected $Address1;
    protected $Address2;
    protected $City;
    protected $State;
    protected $PostalCode;
    protected $Country;
    protected $HeardFrom;
    protected $Role;
    protected $active;
    protected $Lastsigned;
    protected $faxnumber;
    protected $URlnetwork;
    protected $instanmessaing;

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        return $this->$method();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function getUserID() {
        return $this->UserID;
    }

    public function setUserID($UserID) {
        $this->UserID = $UserID;
        return $this;
    }

    public function getUsertype() {
        return $this->usertype;
    }

    public function setUsertype($usertype) {
        $this->usertype = $usertype;
        return $this;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }
    public function getMiddlename() {
        return $this->middlename;
    }

    public function setMiddlename($middlename) {
        $this->middlename = $middlename;
        return $this;
    }
    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $middlename;
        return $this;
    }
    public function getDob() {
        return $this->dob;
    }

    public function setDob($dob) {
        $this->dob = $dob;
        return $this;
    }
    public function getCompanyID() {
        return $this->CompanyID;
    }

    public function setCompanyID($CompanyID) {
        $this->CompanyID = $CompanyID;
        return $this;
    }
    public function getCandidateProfileID() {
        return $this->CandidateProfileID;
    }

    public function setCandidateProfileID($CandidateProfileID) {
        $this->CandidateProfileID = $CandidateProfileID;
        return $this;
    }
    public function getLoginname() {
        return $this->loginname;
    }

    public function setLoginname($loginname) {
        $this->loginname = $loginname;
        return $this;
    }
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    public function getEmailaddress() {
        return $this->emailaddress;
    }

    public function setEmailaddress($emailaddress) {
        $this->emailaddress = $emailaddress;
        return $this;
    }
    public function getPhoneNumber() {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber($PhoneNumber) {
        $this->PhoneNumber = $PhoneNumber;
        return $this;
    }
    public function getAddress1() {
        return $this->Address1;
    }

    public function setAddress1($Address1) {
        $this->Address2 = $Address1;
        return $this;
    }
    public function getAddress2() {
        return $this->Address2;
    }

    public function setAddress2($Address2) {
        $this->Address2 = $Address2;
        return $this;
    }
    public function getCity() {
        return $this->City;
    }

    public function setCity($City) {
        $this->City = $City;
        return $this;
    }
    public function getState() {
        return $this->State;
    }

    public function setState($State) {
        $this->State = $State;
        return $this;
    }
    public function getPostalCode() {
        return $this->PostalCode;
    }

    public function setPostalCode($PostalCode) {
        $this->PostalCode = $PostalCode;
        return $this;
    }
    public function getCountry() {
        return $this->Country;
    }

    public function setCountry($Country) {
        $this->Country = $Country;
        return $this;
    }
    public function getRole() {
        return $this->Role;
    }

    public function setRole($Role) {
        $this->Role = $Role;
        return $this;
    }
    public function getActive() {
        return $this->Active;
    }

    public function setActive($Active) {
        $this->Active = $Active;
        return $this;
    }
    public function getLastsigned() {
        return $this->Lastsigned;
    }

    public function setLastsigned($Lastsigned) {
        $this->Lastsigned = $Lastsigned;
        return $this;
    }
    public function getFaxnumber() {
        return $this->Faxnumber;
    }

    public function setFaxnumber($Faxnumber) {
        $this->Faxnumber = $Faxnumber;
        return $this;
    }
	public function getHeardFrom() {
        return $this->HeardFrom;
    }

    public function setHeardFrom($HeardFrom) {
        $this->HeardFrom = $HeardFrom;
        return $this;
    }
    //                 

}

?>
