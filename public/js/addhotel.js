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
    
   
    $scope.addhotel = function () {
                $scope.upload_file = $('input[type=file]').val();
                $scope.category = $('#category').find(":selected").val();
                $scope.city_id = $('#city_id').find(":selected").val();
                $scope.type = $('#type').find(":selected").val();
		var error = ' ';
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
		if(error == ' '){
			var dataList = {};
			dataList.hotel_name = $scope.hotel_name;
			dataList.city_id = $scope.city_id;
                        dataList.category = $scope.category;
			dataList.type = $scope.type;
                        dataList.upload_file = $scope.upload_file;
			$http({
				method: 'POST',
				data : ObjecttoParams(dataList),
				url: serverUrl + 'admin/dashboard/savehotel',
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
                                        var path = serverUrl + 'admin/dashboard/hotellist';
                                        window.location.href = path;
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
});	