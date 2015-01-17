<?php
namespace Notifications\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Login\Model\prApiError;

//use Application\Model\PR_Api_Error;

class prApiCoreNotiClass extends AbstractTableGateway
{
    protected $table ='notification';
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->initialize();
    }


    public function getList($filters=NULL,$limit=0, $offset=0)
    {
        $errors = prApiError::getInstance();
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from(array('n' => 'notification'))
           ->join(array('senderuser'=>'user'),
                'senderuser.UserID = n.sender_iduser',
                array('cbContactNameT'=>'firstname', 'cbContactLNameT'=>'lastname' ),
                $select::JOIN_LEFT);
        $select->join(array('receiveduser'=>'user'),
            'receiveduser.UserID = n.receiver_iduser',
            array('cbContactNameR'=>'firstname' , 'cbContactLNameR'=>'lastname'),
            $select::JOIN_LEFT);

        if(count($filters)>0)
        {
            if(isset($filters)){
                $where = new \Zend\Db\Sql\Where();

                $where
                    ->nest()
                    ->equalTo('sender_iduser', $filters)
                    ->or
                    ->equalTo('receiver_iduser', $filters)
                    ->unnest();
                $select->where($where);
               //  $select->where(array('sender_iduser' => $filters, 'receiver_iduser' =>$filters)); and
            }
        }
        if ( $limit != 0 || $offset != 0){
            $select->limit($limit, $offset);
        }

        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }

        return $records;
    }

    public function geAllUser()
    {
        $errors = prApiError::getInstance();
        $db = new Sql($this->adapter);
        $select = $db->select();
        $select->from(array('u'=>'user'));
        $statement = $db->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $records = array();
        foreach ($results as $result) {
            $records[] = $result;
        }
        return $records;
    }

    public function delete($notiIDArray)
    {
        if(!is_array($notiIDArray) || count($notiIDArray)==0)
        {
            return;
        }

        $db = new Sql($this->adapter);
        $select = $db->delete();
        $select->from('notification');
        $criteria= "".implode(",",$notiIDArray)."";
        $select->where->in("NotificationID", array($criteria));
        $statement = $db->prepareStatementForSqlObject($select);
       // $deleteString = $db->getSqlStringForSqlObject($select);
       // $results = $statement->execute();
        try {
            $affectedRows = $statement->execute()->getAffectedRows();
        } catch (\Exception $e) {
            die('Error: ' . $e->getMessage());
        }
        if (empty($affectedRows)) {
            $affectedRows = 0;
        }
        /*echo "<pre>";
            print_r($affectedRows);
        echo "<pre>"; die(); */
        return $affectedRows;

    }

    public function save($data = null){
        $errors = prApiError::getInstance();
        if( empty($data) ) {
            return;
        }

            //$objDateNow = new Zend_Date();
            $dateSend=date('Y-m-d H:i:s');
            $data['cbDateTime'] = $dateSend;
            $data['lmDateTime'] = $dateSend;
            if(empty($data['NotificationType'])){
                $data['NotificationType'] = 2; //private noti.
            }
            $dataBk = array();
            $dataBk = $data['receiver_iduser'];

            $insertFlag = 0;
            $db = new Sql($this->adapter);
            foreach ($dataBk as $k=>$v){
                $data['receiver_iduser'] = $v;

                $insert = $db->insert('notification');
                $insert->values($data);
                $statement = $db->prepareStatementForSqlObject($insert);
                $affectedRows = $statement->execute()->getAffectedRows();
                if($affectedRows!=0){
                    $insertFlag = 1;
                }

            }

        return $insertFlag;
    }

    public function edit($data = null, $NotificationID){
        $errors = prApiError::getInstance();
        if( empty($data) ) {
            return;
        }
                $today =  $dateSend=date('Y-m-d H:i:s');
                $data['lmDateTime'] = $today;

                $db = new Sql($this->adapter);
                $select = $db->update();
                $select->table('notification');
                $select->set($data);
                $select->where(array('NotificationID'=>$NotificationID));
                //$deleteString = $db->getSqlStringForSqlObject($select);
                $statement = $db->prepareStatementForSqlObject($select);
                $resUpdate = $statement->execute()->getAffectedRows();

                return $resUpdate;
    }

}
?>