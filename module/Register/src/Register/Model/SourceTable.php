<?php

/**
 * Description of SourceTable
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/Register/src/Register/Model/SourceTable.php

namespace Register\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class SourceTable extends AbstractTableGateway {

    protected $table = 'source';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                    $select->order('name ASC');
                });
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Entity\Source();
            $entity->setId($row->id)
                    ->setName($row->name);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function getSource() {
        //echo "tetst:";die();
        $row = $this->select()->current();
        
        if (!$row)
            return false;

        $Source = new Entity\Source(array(
                    'id' => $row->id,
                    'name' => $row->name
                    
                ));
        return $Source;
    }

    
}
?>