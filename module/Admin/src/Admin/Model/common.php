<?php

namespace Admin\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql;

class common {

    public $adapter;
    public $sql;

    public function __construct() {
        $this->adapter = new Adapter(array(
            'driver' => 'Mysqli',
            'database' => 'ecogypsy',
            'username' => 'root',
            'password' => ''
        ));
        $this->sql = new Sql\Sql($this->adapter);
    }

    public function getUserDetail() {
        $select = $this->sql->select()->from('user_master');
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        return $result;
    }

    public function registration($data) {
        try {
            $newData = array(
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile_no' => $data['number'],
                'password' => md5($data['password'])
            );
            $insert = $this->sql->insert('user_master')
                    ->values($newData);
            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();

            return $result->getAffectedRows();
        } catch (Exception $x) {
            return array();
        }
    }

}
