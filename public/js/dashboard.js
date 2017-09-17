function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};



app.controller('dashboardController', function ($scope, $http, $sce,$timeout,countryList) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.countryList = countryList;
    $scope.setCountry = function(id){        
        $scope.country_id = id;
    }
    $scope.addcity = function (data) {
		var error = ' ';
		if($scope.city_name == undefined || $scope.city_name == ''){
			error = 'Please enter city name' ;
		}
		
		if($scope.country_id == undefined || $scope.country_id == ''){
			error = 'Please select country name' ;
		}
		
		if(error == ' '){
			var dataList = {};
			dataList.city_name = $scope.city_name;
			dataList.country_id = $scope.country_id;
			$http({
				method: 'POST',
				data : ObjecttoParams(dataList),
				url: serverUrl + 'admin/dashboard/savecity',
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