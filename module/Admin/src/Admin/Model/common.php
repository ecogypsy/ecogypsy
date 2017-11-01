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

            return $result->getGeneratedValue();
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

            if (isset($data['id'])) {
                $update = $this->sql->update('city_master')
                        ->set($newData)
                        ->where(array('id'=>$data['id']));
                $statement = $this->sql->prepareStatementForSqlObject($update);
            } else {
                $insert = $this->sql->insert('city_master')
                        ->values($newData);
                $statement = $this->sql->prepareStatementForSqlObject($insert);
            }

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
    
    public function getCityList($city_id='') {
        try {
            $select = $this->sql->select()->from('city_master');
            if($city_id != ''){
              $select =  $select->where(array('id'=>$city_id));
            }
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
                'description' => $data['description'],
            );      
            if(!empty($data['ext'])) {
                $newData['cover_image'] = $data['ext'];
            }
            if(!empty($data['hotel_id'])) {
                $query = $this->sql->update()->table('hotel_master')
                    ->set($newData)
                    ->where(array('id' => $data['hotel_id']));
            }else{
                $query = $this->sql->insert('hotel_master')
                    ->values($newData);                
            }
            $statement = $this->sql->prepareStatementForSqlObject($query);
            $result = $statement->execute();
            if(!empty($data['hotel_id'])){
                return $data['hotel_id']; 
            }
            return $this->adapter->getDriver()->getLastGeneratedValue();
        } catch (Exception $ex) {
            echo $ex->getMessage();die;
            return array();
        }
    }
    
    public function gethotelList($optional=array()) {
        try {
            $select = $this->sql->select()->from('hotel_master');
            if(!empty($optional['id'])) {
                $select = $select->where(array('id'=>$optional['id']));
            }
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            if(!empty($optional['id'])) {
                $result = $result->current();
            }
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
            if (isset($data['id'])) {
                $update = $this->sql->update('location_master')
                        ->set($newData)
                        ->where(array('id'=>$data['id']));
                $statement = $this->sql->prepareStatementForSqlObject($update);
            } else {
                $insert = $this->sql->insert('location_master')
                    ->values($newData);
            $statement = $this->sql->prepareStatementForSqlObject($insert);
            }
            
            $result = $statement->execute();

            if(!empty($data['id'])){
                return $data['id']; 
            }
            return $this->adapter->getDriver()->getLastGeneratedValue();
        } catch (Exception $x) {
            return array();
        }
    }
    
    public function getLocationList($id = '') {
        try {
            $select = $this->sql->select()->from('location_master');
            if($id != ''){
              $select =  $select->where(array('id'=>$id));
            }
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();

            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function savePackage($data) {
        try {
            $newData = array(
                'package_name' => $data['package_name'],
                'location_id' => $data['location'],
                'total_seat' => $data['total_seat'],
                'description' => $data['description'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'price' => $data['price']
            );
            if (isset($data['id'])) {
                $update = $this->sql->update('package_master')
                        ->set($newData)
                        ->where(array('id'=>$data['id']));
                $statement = $this->sql->prepareStatementForSqlObject($update);
            } else {
                $insert = $this->sql->insert('package_master')
                    ->values($newData);
            $statement = $this->sql->prepareStatementForSqlObject($insert);
            }
           
            $result = $statement->execute();

            return $result->getAffectedRows();
        } catch (Exception $x) {
            return array();
        }
    }
    
    public function deleteCity($data) {
        try {
            $select = $this->sql->delete('city_master')->where(array('id'=>$data['city_id']));
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute()->getAffectedRows();
            
            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function deleteLocation($data) {
        try {
            $select = $this->sql->delete('location_master')->where(array('id'=>$data['location_id']));
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute()->getAffectedRows();
            
            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function deleteHotel($data) {
        try {
            $select = $this->sql->delete('hotel_master')->where(array('id'=>$data['id']));
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute()->getAffectedRows();
            
            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function getPackageList($id ='') {
        try {
            $select = $this->sql->select()->from('package_master')->order('start_date');
            $select = $select->join('location_master', 'location_master.id = package_master.location_id', array('location_name', 'location_description'=>'description'), 'LEFT');
            $select = $select->join('hotel_master', 'hotel_master.id = location_master.hotel_id', array('hotel_id'=>'id', 'hotel_name'=>'name', 'category', 'type', 'city', 'cover_image', 'hotel_description'=>'description'), 'LEFT');
            if($id != ''){
              $select =  $select->where(array('package_master.id'=>$id));
            }	
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
	
	public function createBooking($data) {
        try {
            
            $insert = $this->sql->insert('booking_master')
                    ->values($data);
            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();

            return $result->getGeneratedValue();
        } catch (Exception $x) {
            return array();
        }
    }
    
    public function checkUserExist($data) {
        try {
            $select = $this->sql->select()->from('user_master');
            $select =  $select->where($data);
			
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();

            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
    public function readFileFromFolder($path, $optional=array()) {
        $fileList = array();
        if(file_exists($path) && $dir = opendir($path)) {
            while($file= readdir($dir)) {
                if(!($file =='.' || $file=='..')) {
                   $fileList[] = '/hotel/'.$optional['hotel_id'].'/'.$file; 
                }
            }
        }
        
        return $fileList;
    } 
    
    public function readFileFromFolderForLocation($path, $optional=array()) {
        $fileList = array();
        if(file_exists($path) && $dir = opendir($path)) {
            while($file= readdir($dir)) {
                if(!($file =='.' || $file=='..')) {
                   $fileList[] = '/location/'.$optional['location_id'].'/'.$file; 
                }
            }
        }
        
        return $fileList;
    } 
    
    public function getbookingList($params) {
        try {
            $select = $this->sql->select()->from('booking_master');
            $select = $select->join('package_master', 'package_master.id = booking_master.package_id', array('package_name', 'price'), 'LEFT');
            $select = $select->join('location_master', 'location_master.id = booking_master.location_id', array('location_name', 'location_description'=>'description'), 'LEFT');
            $select = $select->join('hotel_master', 'hotel_master.id = location_master.hotel_id', array('hotel_name'=>'name', 'category', 'type', 'city', 'cover_image'), 'LEFT');
            $select = $select->join('user_master', 'user_master.id = booking_master.user_id', array('traveler_name'=>'name', 'traveler_email'=>'email', 'traveler_mobile_no'=>'mobile_no'), 'LEFT');
            if(!empty($params['id'])){
                $select =  $select->where(array('booking_master.id'=>$params['id']));
            }
            if(!empty($params['status'])){
                $select =  $select->where(array('booking_master.status'=>$params['status']));
            }
            if(!empty($params['creation_from_date'])){
                $select =  $select->where("booking_master.creation_date>=$params[creation_from_date]");
            }
            if(!empty($params['creation_till_date'])){
                $select =  $select->where("booking_master.creation_date<=$params[creation_till_date]");
            }
            $select = $select->order('booking_master.id DESC');
            //echo $select->getSqlString();die;
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();

            return $result;
        } catch (Exception $e) {
            return array();
        }        
    }
    
    public function changeBookingStatus($params) {
        try {
            if (isset($params['id'])) {
                $newData = array(
                    'status' => $params['status']
                );            
                $update = $this->sql->update('booking_master')
                        ->set($newData)
                        ->where(array('id'=>$params['id']));
                $statement = $this->sql->prepareStatementForSqlObject($update);
                $result = $statement->execute();
                return true;
            }else {
                return false;
            }
        }  catch (Exception $e) {
            return false;
        }
    }
    
    
    
    public function deletePackage($data) {
        try {
            $select = $this->sql->delete('package_master')->where(array('id'=>$data['package_id']));
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute()->getAffectedRows();
            
            return $result;
        } catch (Exception $e) {
            return array();
        }
    }
}        
