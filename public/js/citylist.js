function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};

app.controller('cityListController', function ($scope, $http, $sce,$timeout,cityList) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.cityList = cityList;
    console.log($scope.cityList);
    $scope.delete = function (city_id) {
        var dataList = {};
        dataList.city_id = city_id;
        $http({
            method: 'POST',
            data: ObjecttoParams(dataList),
            url: serverUrl + 'admin/dashboard/deletecity',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            if (response.status) {
                getcityList();
                $scope.successShow = true;
                $scope.successMsg = response.msg;
            } else {
                $scope.errorShow = true;
                $scope.errorMsg = response.msg;
            }
            $timeout(function () {
                $scope.successShow = false;
                $scope.errorShow = false;
            }, 2000)
        });


    };
    
    function getcityList() {
        $http({
            method: 'POST',
            url: serverUrl + 'admin/dashboard/getcity',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.cityList = response;
            console.log($scope.cityList);
        });

    }
});	