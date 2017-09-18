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
    public function savecity($data) {
        try {
            $newData = array(
                'city_name' => $data['city_name'],
                'country_id' => $data['country_id']
            );
            
            $insert = $this->sql->insert('city_master')
                    ->values($newData);
            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();

            return $result->getAffectedRows();
        } catch (Exception $x) {
            return array();
        }
    }

    public function getCountryList() {
        try {
            $select = $this->sql->select()->from('country_master');
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();

            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function getCityList() {
        try {
            $select = $this->sql->select()->from('city_master');
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();

            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function saveHotel($data) {
        try {
            $newData = array(
                'city' => $data['city_id'],
                'name' => $data['hotel_name'],
                'category' => $data['category'],
                'type' => $data['type'],
                'cover_image' => $data['upload_file'],
            );
            
            $insert = $this->sql->insert('hotel_master')
                    ->values($newData);
            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();

            return $result->getAffectedRows();
        } catch (Exception $x) {
            return array();
        }
    }
    
    public function gethotelList() {
        try {
            $select = $this->sql->select()->from('hotel_master');
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();

            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
    public function saveLocation($data) {
        try {
            $newData = array(
                'city' => $data['city_id'],
                'location_name' => $data['location_name'],
                'hotel_id' => $data['hotel_id'],
                'description' => $data['description'],
                'image' => $data['upload_file'],
            );
            
            $insert = $this->sql->insert('location_master')
                    ->values($newData);
            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();

            return $result->getAffectedRows();
        } catch (Exception $x) {
            return array();
        }
    }

}