<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Company
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Register/src/Register/Model/Entity/Company.php

namespace Register\Model\Entity;

class Company {  
    
    protected $CompanyID;
    protected $Companyname;
    protected $Industry;
    protected $Address;
    protected $Address2;
    protected $Description;
    protected $images;
    protected $PhoneNumber;
    protected $country;
    protected $emailinfo;
    protected $Zipcode;
    protected $faxnumber;
    protected $city;
    protected $state;
    protected $video;
    

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

    public function getCompanyID() {
        return $this->CompanyID;
    }

    public function setCompanyID($CompanyID) {
        $this->CompanyID = $CompanyID;
        return $this;
    }

    public function getCompanyname() {
        return $this->Companyname;
    }

    public function setCompanyname($Companyname) {
        $this->Companyname = $Companyname;
        return $this;
    }

    public function getIndustry() {
        return $this->Industry;
    }

    public function setIndustry($Industry) {
        $this->Industry = $Industry;
        return $this;
    }
    public function getAddress() {
        return $this->Address;
    }

    public function setAddress($Address) {
        $this->Address = $Address;
        return $this;
    }
    public function getAddress2() {
        return $this->Address2;
    }

    public function setAddress2($Address2) {
        $this->Address2 = $Address2;
        return $this;
    }
    public function getDescription() {
        return $this->Description;
    }

    public function setDescription($Description) {
        $this->Description = $Description;
        return $this;
    }
    public function getImages() {
        return $this->images;
    }

    public function setImages($images) {
        $this->images = $images;
        return $this;
    }
    public function getPhoneNumber() {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber($PhoneNumber) {
        $this->PhoneNumber = $PhoneNumber;
        return $this;
    }
    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->loginname = $country;
        return $this;
    }
    public function getEmailinfo() {
        return $this->emailinfo;
    }

    public function setEmailinfo($emailinfo) {
        $this->emailinfo = $emailinfo;
        return $this;
    }
    public function getZipcode() {
        return $this->Zipcode;
    }

    public function setZipcode($Zipcode) {
        $this->Zipcode = $Zipcode;
        return $this;
    }
    public function getFaxnumber() {
        return $this->faxnumber;
    }

    public function setFaxnumber($faxnumber) {
        $this->faxnumber = $faxnumber;
        return $this;
    }
    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
        return $this;
    }
    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
        return $this;
    }
    public function getVideo() {
        return $this->video;
    }

    public function setVideo($video) {
        $this->video = $video;
        return $this;
    }
    

}

?>
