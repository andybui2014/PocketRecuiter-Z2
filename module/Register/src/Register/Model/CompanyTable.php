<?php

/**
 * Description of CompanyTable
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Register/src/Register/Model/CompanyTable.php

namespace Register\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class CompanyTable extends AbstractTableGateway {

    protected $table = 'company';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                    $select->order('CompanyID ASC');
                });
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Entity\Company();
            $entity->setCompanyID($row->CompanyID)
                    ->setCompanynamee($row->Companyname)
                    ->setIndustry($row->Industry)
                    ->setAddress($row->Address)
                    ->setAddress2($row->Address2)
                    ->setDescription($row->Description)
                    ->setImages($row->images)
                    ->setPhoneNumber($row->PhoneNumber)
                    ->setCountry($row->country)
                    ->setEmailinfo($row->emailinfo)
                    ->setZipcode($row->Zipcode)
                    ->setFaxnumber($row->faxnumber)
                    ->setCity($row->city)
                    ->setState($row->state)
                    ->setVideo($row->video);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function getCompany($CompanyID) {
        $row = $this->select(array('CompanyID' => (int) $CompanyID))->current();
        if (!$row)
            return false;

        $company = new Entity\Company(array(
                    'CompanyID' => $row->CompanyID,
                    'Companyname' => $row->Companyname,
                    'Industry' => $row->Industry,
                    'Address' => $row->Address,
                    'Address2' => $row->Address2,
                    'Description' => $row->Description,
                    'images' => $row->images,
                    'PhoneNumber' => $row->PhoneNumber,
                    'country' => $row->country,
                    'emailinfo' => $row->emailinfo,
                    'Zipcode' => $row->Zipcode,
                    'faxnumber' => $row->faxnumber,
                    'city' => $row->city,
                    'state' => $row->state,
                    'video' => $row->video
                ));
        return $company;
    }
     public function getCompanyName($Companyname) {
        $row = $this->select(array('Companyname' => $Companyname))->current();
        if (!$row)
            return false;

        $company = new Entity\Company(array(
                    'CompanyID' => $row->CompanyID
                   // 'Companyname' => $row->Companyname,
                    //'Industry' => $row->Industry,
                  //  'Address' => $row->Address,
                   // 'Address2' => $row->Address2,
                   // 'Description' => $row->Description,
                   // 'images' => $row->images,
                   // 'PhoneNumber' => $row->PhoneNumber,
                   // 'country' => $row->country,
                   // 'emailinfo' => $row->emailinfo,
                  //  'Zipcode' => $row->Zipcode,
                    //'faxnumber' => $row->faxnumber,
                   // 'city' => $row->city,
                  //  'state' => $row->state,
                 //   'video' => $row->video
                ));
        return $company;
    }

   //public function saveUser(Entity\Company $user) {
    public function saveCompany( $company) {
            $data=array("Companyname"=>$company["Companyname"]);
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue(); 
    }

   
}
?>