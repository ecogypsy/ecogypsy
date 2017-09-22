function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};

app.controller('addpackageController', function ($scope, $http, $sce,$timeout,locationList) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.locationList = locationList;
    
   
    $scope.addpackage = function () {
                $scope.location = $('#location').find(":selected").val();
		var error = ' ';
		if($scope.package_name == undefined || $scope.package_name == ''){
			error = 'Please enter package name' ;
		}
		
		if($scope.location == undefined || $scope.location == ''){
			error = 'Please select location name' ;
		}
                
                if($scope.total_seat == undefined || $scope.total_seat == ''){
			error = 'Please enter total seat' ;
		}
                
                if($scope.price == undefined || $scope.price == ''){
			error = 'Please enter price ' ;
		}
                
		if(error == ' '){
			var dataList = {};
			dataList.package_name = $scope.package_name;
			dataList.total_seat = $scope.total_seat;
                        dataList.location = $scope.location;
                        dataList.price = $scope.price;
			dataList.description = $scope.description;
                        dataList.start_date =  $("#start_date").val();
                        dataList.end_date = $("#end_date").val();
			$http({
				method: 'POST',
				data : ObjecttoParams(dataList),
				url: serverUrl + 'admin/dashboard/savepackage',
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