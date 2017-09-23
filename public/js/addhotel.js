function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};

app.controller('hotelController', function ($scope, $http, $sce,$timeout,cityList) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.cityList = cityList;
    $scope.hotel_id = 0;
    $scope.tempfoldername = '';
   $scope.readhotelimage();
    $scope.addhotel = function () {
                $scope.upload_file = $('input[type=file]').val();
                $scope.category = $('#category').find(":selected").val();
                $scope.city_id = $('#city_id').find(":selected").val();
                $scope.type = $('#type').find(":selected").val();
		var error = '';
		if($scope.hotel_name == undefined || $scope.hotel_name == ''){
			error = 'Please enter hotel location' ;
		}
		
		if($scope.city_id == undefined || $scope.city_id == ''){
			error = 'Please select city name' ;
		}
                
                if($scope.category == undefined || $scope.category == ''){
			error = 'Please select category name' ;
		}
                if($scope.type == undefined || $scope.type == ''){
			error = 'Please select type name' ;
		}
		if($scope.upload_file == undefined || $scope.upload_file == ''){
			error = 'Please upload hotel image';
		}
		if(error == ''){
			var dataList = {};
			dataList.hotel_name = $scope.hotel_name;
			dataList.city_id = $scope.city_id;
                        dataList.category = $scope.category;
			dataList.type = $scope.type;
                        dataList.upload_file = $scope.upload_file;
                        dataList.uploadFileInput = $("#uploadfile").files;
                        dataList.hotel_id = $scope.hotel_id;
                        dataList.tempfoldername = $scope.tempfoldername;
                        if($scope.cover_image != undefined) {
                            dataList.cover_image = $scope.cover_image;
                        }
                        if($scope.ext != undefined) {
                            dataList.ext = $scope.ext;
                        }
			$http({
				method: 'POST',
				data : ObjecttoParams(dataList),
				url: serverUrl + 'admin/dashboard/savehotel',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status) {
                                    $scope.hotel_id = response.hotelId
                                    $scope.successShow = true;
                                    $scope.successMsg = response.msg;
                                    $timeout(function(){
                                            $scope.successShow = false;
                                            var path = serverUrl + 'admin/dashboard/hotellist';
                                            window.location.href = path;
                                            $scope.errorShow = false;
                                    },2000);                                    
				}else{
					$scope.errorShow = true;
					$scope.errorMsg = response.msg;
				}
			});
		}else{
			$scope.errorShow = true;
			$scope.errorMsg = error;
			$timeout(function(){
				$scope.errorShow = false;
			},2000)
		}
		
    };
	
	$scope.loginUser = function(){
		var error = ' ';
		if($scope.password == undefined || $scope.password == ''){
			error = 'Please enter password' ;
		}
		
		if($scope.number == undefined || $scope.number == ''){
			error = 'Please enter number' ;
		}
		
		
		if(error == ' '){
			var dataList = {};
			dataList.name = $scope.name;
			dataList.email = $scope.email;
			dataList.number = $scope.number;
			dataList.password = $scope.password;
			
			$http({
				method: 'POST',
				data : ObjecttoParams(dataList),
				url: url + 'index/signup',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status) {
					$scope.successShow = true;
			        $scope.successMsg = response.msg;
				}else{
					$scope.errorShow = true;
					$scope.errorMsg = response.msg;
				}
				$timeout(function(){
					$scope.successShow = false;
					$scope.errorShow = false;
				},2000)
			});
		}else{
			$scope.errorShow = true;
			$scope.errorMsg = error;
			$timeout(function(){
				$scope.errorShow = false;
			},2000)
		}

	}      
        
        $scope.readhotelimage = function() {
            $http({
                    method: 'POST',
                    data : ObjecttoParams(dataList),
                    url: serverUrl + 'admin/dashboard/readhotelimage',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function (response) {
                console.log(response);
            });            
        };
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#profile-img-tag').attr('src', e.target.result);
            var appElement = document.querySelector('[ng-app=hotel]');
            var $scope = angular.element(appElement).scope(); 
            var tempfoldername = $("#tempfoldername").val();
            $.post("upload",{image:e.target.result, hotel_id:$scope.hotel_id, tempfoldername:tempfoldername} ,function (data) {
                var obj = JSON.parse(data);
                console.log(obj.tempfoldername);
                $("#tempfoldername").val(obj.tempfoldername);
                $scope.$apply(function() {
                    $scope.tempfoldername = obj.tempfoldername;
                });                
            });              
        }    
        reader.readAsDataURL(input.files[0]);
    }
}
function uploadImageInTemp(obj){
    readURL(obj);
};

function uploadCoverImage(input) {
if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#hotel-cover-tag').attr('src', e.target.result);
            var appElement = document.querySelector('[ng-app=hotel]');
            var $scope = angular.element(appElement).scope(); 
            $.post("upload",{image:e.target.result, hotel_id:$scope.hotel_id, imageType:'coverimage'} ,function (data) {
                var obj = JSON.parse(data);
                $scope.$apply(function() {
                    $scope.cover_image = obj.coverImageName; 
                    $scope.ext = obj.imageExt;
                });                 
            });              
        }    
        reader.readAsDataURL(input.files[0]);
    }    
}
