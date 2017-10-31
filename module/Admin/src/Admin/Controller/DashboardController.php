<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Admin\Model\common;

class DashboardController extends AbstractActionController {
    public function __construct() {
        $this->view =  new ViewModel();
        $this->session = new Container('User');
        $this->commonObj = new common();     
    }

    public function countrylistAction() {
        $countryListResponse = $this->commonObj->curlhit('', 'getcountrylist');
        $countryList = json_decode($countryListResponse, true);
        if($countryList['status']){
            $this->view->countryList = $countryList['data'];
        }
        return $this->view;
    }

    public function statelistAction() {
        $stateListResponse = $this->commonObj->curlhit('', 'getstatelist');
        $stateList = json_decode($stateListResponse, true);
        if($stateList['status']){
            $this->view->stateList = $stateList['data'];
        }
        return $this->view;
    } 

    public function indexAction() {      
        return $this->view;
    }

    public function addcityAction() {
        $countryList = array();
        $cityList = array();
        $city_id = $this->params()->fromQuery('data');
        if (!empty($city_id)) {
            $getCityList = $this->commonObj->getCityList($city_id);
            if (!empty($getCityList)) {
                foreach ($getCityList as $key => $value) {
                    $cityList[$key] = $value;
                }
                
            }
        }
        
        $getCountryList = $this->commonObj->getCountryList();
        if(!empty($getCountryList)){
            foreach ($getCountryList as $key => $value) {
                $countryList[$key] = $value;
            }
        }
        $this->view->cityData = $cityList;
        $this->view->countryList = $countryList;
        return $this->view;
    }
    
    public function savecityAction(){
        $return = array('status' => false, 'msg' => 'error');
        $request = (array) $this->getRequest()->getPost(); 
        $registrationResponse = $this->commonObj->savecity($request);
        if (!empty($registrationResponse)) {
            $return = array('status' => true, 'msg' => 'Succesfully created');
            if(isset($request['id'])){
                $return = array('status' => true, 'msg' => 'Succesfully updated');
            }
             
        }
        echo json_encode($return);
        exit;
    }
    
    public function hotelAction() {
        $request = (array) $this->getRequest()->getQuery();
        $cityList = array();
        $getCityList = $this->commonObj->getCityList();
        $hotelDetail = array();
        if(!empty($request['data'])){
            $params = array();
            $params['id'] = $request['data'];
            $commonObj = new common();  
            $hotelDetail = $commonObj->gethotelList($params);
        }
        if(!empty($getCityList)){
            foreach ($getCityList as $key => $value) {
                $cityList[$key] = $value;
            }
        }
        $this->view->hotelDetail = $hotelDetail;
        $this->view->cityList = $cityList;
        return $this->view;
    }
    
    public function savehotelAction(){
        $return = array('status' => false, 'msg' => 'error');
        $request = (array) $this->getRequest()->getPost();
        $hotelId = $this->commonObj->saveHotel($request);
        if (!empty($hotelId)) {
            if(!empty($request['tempfoldername'])){
                @rename($GLOBALS['HOTELIMAGEPATH'].'/'.$request['tempfoldername'], $GLOBALS['HOTELIMAGEPATH'].'/'.$hotelId);
            }
            if(!empty($request['cover_image'])) {
                @rename($GLOBALS['HOTELIMAGEPATH'].'/coverimage/'.$request['cover_image'].'.'.$request['ext'], $GLOBALS['HOTELIMAGEPATH'].'/'.$hotelId.'.'.$request['ext']);
            }
            $return = array('status' => true, 'msg' => 'Succesfully created', 'hotelId'=>$hotelId);
        }
        echo json_encode($return);
        exit;
    }
    
    public function addlocationAction() {
        $cityList = array();
        $locationList = array();
        $id = $this->params()->fromQuery('data');
        if (!empty($id)) {
            $getLocationList = $this->commonObj->getLocationList($id);
            if (!empty($getLocationList)) {
                foreach ($getLocationList as $key => $value) {
                    $locationList[$key] = $value;
                }
                
            }
        }
        
        $getCityList = $this->commonObj->getCityList();
        if(!empty($getCityList)){
            foreach ($getCityList as $key => $value) {
                $cityList[$key] = $value;
            }
        }
        
        $hotelList = array();
        $getHotelList = $this->commonObj->gethotelList();
        if(!empty($getHotelList)){
            foreach ($getHotelList as $key => $value) {
                $hotelList[$key] = $value;
            }
        }
        
        $this->view->cityList = $cityList;
        $this->view->hotelList = $hotelList;
        $this->view->locationData = $locationList;
        return $this->view;
    }
    public function savelocationAction() {
        $return = array('status' => false, 'msg' => 'error');
        $request = (array) $this->getRequest()->getPost();
        $files = $this->params()->fromFiles($request['upload_file']);
        $locationId = $this->commonObj->saveLocation($request);
        if (!empty($locationId)) {
            if(!empty($request['tempfoldername'])){
                @rename($GLOBALS['LOCATIONIMAGEPATH'].'/'.$request['tempfoldername'], $GLOBALS['LOCATIONIMAGEPATH'].'/'.$locationId);
            }
            if(!empty($request['cover_image'])) {
                @rename($GLOBALS['LOCATIONIMAGEPATH'].'/coverimage/'.$request['cover_image'].'.'.$request['ext'], $GLOBALS['LOCATIONIMAGEPATH'].'/'.$locationId.'.'.$request['ext']);
            }
            $return = array('status' => true, 'msg' => 'Succesfully created', 'hotelId'=>$locationId);
        }
        echo json_encode($return);
        exit;
        
    }

    public function addpackageAction() {
        $locationList = array();
        $packageList = array();
        $package_id = $this->params()->fromQuery('data');
        if (!empty($package_id)) {
            $getPackageList = $this->commonObj->getpackageList($package_id);
            if (!empty($getPackageList)) {
                foreach ($getPackageList as $key => $value) {
                    $packageList = $value;
                }
                
            }
        }
        $this->view->packageData = $packageList;
        $getLocationList = $this->commonObj->getLocationList();
        if(!empty($getLocationList)){
            foreach ($getLocationList as $key => $value) {
                $locationList[$key] = $value;
            }
        }
        
        $this->view->locationList = $locationList;
        return $this->view;
     }    
        
     public function savepackageAction(){
            $return = array('status' => false, 'msg' => 'error');
            $request = (array) $this->getRequest()->getPost();
            $packageResponse = $this->commonObj->savePackage($request);
            if (!empty($packageResponse)) {
                $return = array('status' => true, 'msg' => 'Succesfully created');
            }
            echo json_encode($return);
            exit;
        }
    
    public function citylistAction() {
        $cityList = array();
        $getCityList = $this->commonObj->getCityList();
        if(!empty($getCityList)){
            foreach ($getCityList as $key => $value) {
                $cityList[$key] = $value;
            }
        }
        
        $this->view->cityList = $cityList;
        return $this->view;
     }
     
     public function hotellistAction() {
        $hotelList = array();
        $getHotelList = $this->commonObj->gethotelList();
        if (!empty($getHotelList)) {
            foreach ($getHotelList as $key => $value) {
                $hotelList[$key] = $value;
            }
        }
        $this->view->hotelList = $hotelList;
        return $this->view;
    }
    
     public function locationlistAction() {
        $locationList = array();
        $getLocationList = $this->commonObj->getLocationList();
        if (!empty($getLocationList)) {
            foreach ($getLocationList as $key => $value) {
                $locationList[$key] = $value;
            }
        }
        $this->view->locationList = $locationList;
        return $this->view;
    }

    public function hotelDataAction() {
        $hotelList = array();
        $getHotelList = $this->commonObj->gethotelList();
        if (!empty($getHotelList)) {
            foreach ($getHotelList as $key => $value) {
                $hotelList[$key] = $value;
            }
        }
        echo json_encode($hotelList);die;
    }

    public function deletehotelAction() {
        $return = array('status' => false, 'msg' => 'error');
        $request = (array) $this->getRequest()->getPost();
        $response = $this->commonObj->deleteHotel($request);
        if (!empty($response)) {
            $return = array('status' => true, 'msg' => 'Succesfully deleted');
        }
        echo json_encode($return);
        exit;
    }
    
    public function deletelocationAction() {
        $return = array('status' => false, 'msg' => 'error');
        $request = (array) $this->getRequest()->getPost();
        $response = $this->commonObj->deleteLocation($request);
        if (!empty($response)) {
            $return = array('status' => true, 'msg' => 'Succesfully deleted');
        }
        echo json_encode($return);
        exit;
    }
    
    
    public function deletecityAction() {
        $return = array('status' => false, 'msg' => 'error');
        $request = (array) $this->getRequest()->getPost();
        $response = $this->commonObj->deleteCity($request);
        if (!empty($response)) {
            $return = array('status' => true, 'msg' => 'Succesfully deleted');
        }
        echo json_encode($return);
        exit;
    }
    
    public function getcityAction() {
        $cityList = array();
        $getCityList = $this->commonObj->getCityList();
        if (!empty($getCityList)) {
            foreach ($getCityList as $key => $value) {
                $cityList[$key] = $value;
            }
        }
        echo json_encode($cityList);exit;
    }
    
    public function getlocationAction() {
        $locationList = array();
        $getLocationList = $this->commonObj->getLocationList();
        if (!empty($getLocationList)) {
            foreach ($getLocationList as $key => $value) {
                $locationList[$key] = $value;
            }
        }
        echo json_encode($locationList);exit;
    }

    public function pricesaveAction() {
        $request = $this->getRequest()->getPost();
        $params = array();
        $params["monthly_service"] = $request["monthly_service"];
        $params["phone_number_charge"] = $request["phone_number"];
        $params["sms_pack_price"] =$request["sms_pack_price"];
        $params["nbr_of_sms_in_pack"] = $request["nbr_of_sms_in_pack"];
        $params["free_sms"] = $request["free_sms"];
        $inputParams['parameters'] = json_encode($params);
        //print_r($inputParams);die;
        $savePrice = $this->commonObj->curlhit($inputParams, 'pricesave');
        $savePrice = json_decode($savePrice);
        print_r($savePrice);die;
        if($savePrice['status']){
            $this->view->priceList = $priceList['data'];
        }
        print_r($SavePrice);
        return $this->view;
      
    }
    public function newcompanylistAction(){
        return $this->view;
    }

    public function companylistAction(){
        $request = $this->getRequest()->getQuery();
        $params = array();
        $params["status"] = isset($request["status"])?$request["status"]:'';  
        $newcompanylist = $this->commonObj->curlhit($params, 'getcompanylist');
        echo $newcompanylist;        
        exit();
    }
    public function activateordeactivatecompanyAction(){
        $request = $this->getRequest()->getPost();
        $params = array();
        $params['company_id'] = $request["company_id"]; 
        $params['status'] = $request["status"];
        $params['activate_by'] = $this->session['user']->data[0]->id;
        $response = $this->commonObj->curlhit($params, 'activateordeactivatecompany');
        echo $response;
        exit();
    }    


    public function emailsetupAction() {
        return $this->view;
    }
    public function emailsetuplistAction() {
        return $this->view;
    }
    public function saveemaildataAction(){
        $request = (array)$this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'saveemailtemplate');
        exit;
    }
    public function gettemplatelistAction(){
        echo $saveEmail = $this->commonObj->curlhit('', 'gettemplatelist');
        exit;
    }
    
    public function deleteEmailTemplateAction(){
        $request = (array)$this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'deleteEmailTemplate');
        exit;
    }
    public function editEmailTemplateAction(){
        $request = (array)$this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'editEmailTemplate');
        exit;
    }

    public function addUserAction() {
        return $this->view;
    }
      
    public function packagelistAction() {
        $packageList = array();
        $getPackageList = $this->commonObj->getpackageList();
        
        if (!empty($getPackageList)) {
            foreach ($getPackageList as $key => $value) {
                $packageList[$key] = $value;
            }
        }
        $this->view->packagelList = $packageList;
        return $this->view;
    }
    
     public function deletepackageAction() {
        $return = array('status' => false, 'msg' => 'error');
        $request = (array) $this->getRequest()->getPost();
        $response = $this->commonObj->deletePackage($request);
        if (!empty($response)) {
            $return = array('status' => true, 'msg' => 'Succesfully deleted');
        }
        echo json_encode($return);
        exit;
    }
    
    public function getpackageAction() {
        $packageList = array();
        $getPackageList = $this->commonObj->getpackageList();
        if (!empty($getPackageList)) {
            foreach ($getPackageList as $key => $value) {
                $packageList[$key] = $value;
            }
        }
        echo json_encode($packageList);exit;
    }





      public function uploadAction() {
        $request = (array) $this->getRequest()->getPost();
        $return = array('tempfoldername'=>'', 'msg'=>'');
        if(!empty($request['image'])) {
            $data = explode(',', $request['image']);
            $imagData = base64_decode($data[1]);
            if(!empty($request['imageType']) && $request['imageType']=='coverimage') {
                $coverimageName = time();
                if(!empty($request['hotel_id'])) {
                    $coverimageName = $request['hotel_id'];
                }else{
                    $return['coverImageName'] = $coverimageName;
                }
                $imagePath = $GLOBALS['HOTELIMAGEPATH'].'/coverimage/';                
            }else {
                if(!empty($request['hotel_id'])) {
                    //$request['tempfolderanme'] = $request['hotel_id'];
                    $imagePath = $GLOBALS['HOTELIMAGEPATH'].'/'.$request['hotel_id'].'/';
                }else{
                    if(!empty($request['tempfoldername'])) {
                        $return['tempfoldername'] = $tempFolderName = $request['tempfoldername'];
                    }else {
                        $return['tempfoldername'] = $tempFolderName = 'temp_'.time();
                    }

                    $imagePath = $GLOBALS['HOTELIMAGEPATH'].'/'.$tempFolderName.'/';
                }
            }
            @mkdir($imagePath, '0777', true);
            if(!empty($request['imageType']) && $request['imageType']=='coverimage') {
                $imagePath = $imagePath.$coverimageName;
            }else{
                $imagePath = $imagePath.time();
            }
            
            $im = imagecreatefromstring($imagData);
            //print_r($data);die;
            if ($im !== false) {
                if($data[0] == 'data:image/jpeg;base64'){
                    header('Content-Type: image/jpeg');
                    imagejpeg($im, $imagePath.'.jpg');
                    $return['imageExt'] = 'jpg';
                }else {
                    header('Content-Type: image/png');
                    imagepng($im, $imagePath.'.png');
                    $return['imageExt'] = 'png';
                }
                imagedestroy($im);
            } else {
                $return['msg'] = 'An error occurred.';
            }        
        }
        echo json_encode($return);
        die;
    }
    
    function readhotelimageAction() {
        $request = (array) $this->getRequest()->getPost();
        $fileList = array();
        if($request['hotel_id']) {
            $path = $GLOBALS['HOTELIMAGEPATH'].'/'.$request['hotel_id'];
            $fileList = $this->commonObj->readFileFromFolder($path, $request);
        }
        echo json_encode($fileList);
        die;
    }
    public function bookingAction() {
        $commonObj = new common(); 
        $params = (array) $this->getRequest()->getPost();
        $bookingList = $commonObj->getbookingList($params);
        $this->view->bookingList = $bookingList;
        return $this->view;
    }
    
    public function changeBookingStatusAction() {
        $return = array('status'=>false, 'msg'=>'Booking can not updated');
        $commonObj = new common(); 
        $params = (array) $this->getRequest()->getPost();
        $bookingUpdated = $commonObj->changeBookingStatus($params);
        if($bookingUpdated) {
            $return = array('status'=>true, 'msg'=>'Booking updated');
        }
        echo json_encode($return);
        exit();
    }

    public function uploadlocationAction() {
        $request = (array) $this->getRequest()->getPost();
        $return = array('tempfoldername'=>'', 'msg'=>'');
        if(!empty($request['image'])) {
            $data = explode(',', $request['image']);
            $imagData = base64_decode($data[1]);
            if(!empty($request['imageType']) && $request['imageType']=='coverimage') {
                $coverimageName = time();
                if(!empty($request['location_id'])) {
                    $coverimageName = $request['location_id'];
                }else{
                    $return['coverImageName'] = $coverimageName;
                }
                $imagePath = $GLOBALS['LOCATIONIMAGEPATH'].'/coverimage/';                
            }else {
                if(!empty($request['location_id'])) {
                    //$request['tempfolderanme'] = $request['hotel_id'];
                    $imagePath = $GLOBALS['LOCATIONIMAGEPATH'].'/'.$request['location_id'].'/';
                }else{
                    if(!empty($request['tempfoldername'])) {
                        $return['tempfoldername'] = $tempFolderName = $request['tempfoldername'];
                    }else {
                        $return['tempfoldername'] = $tempFolderName = 'temp_'.time();
                    }

                    $imagePath = $GLOBALS['LOCATIONIMAGEPATH'].'/'.$tempFolderName.'/';
                }
            }
            @mkdir($imagePath, '0777', true);
            if(!empty($request['imageType']) && $request['imageType']=='coverimage') {
                $imagePath = $imagePath.$coverimageName;
            }else{
                $imagePath = $imagePath.time();
            }
            
            $im = imagecreatefromstring($imagData);
            //print_r($data);die;
            if ($im !== false) {
                if($data[0] == 'data:image/jpeg;base64'){
                    header('Content-Type: image/jpeg');
                    imagejpeg($im, $imagePath.'.jpg');
                    $return['imageExt'] = 'jpg';
                }else {
                    header('Content-Type: image/png');
                    imagepng($im, $imagePath.'.png');
                    $return['imageExt'] = 'png';
                }
                imagedestroy($im);
            } else {
                $return['msg'] = 'An error occurred.';
            }        
        }
        echo json_encode($return);
        die;
    }    
}
