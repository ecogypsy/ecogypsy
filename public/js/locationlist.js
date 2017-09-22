function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};

app.controller('locationListController', function ($scope, $http, $sce,$timeout,locationList) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.locationList = locationList;
    $scope.delete = function (location_id) {
        var dataList = {};
        dataList.location_id = location_id;
        $http({
            method: 'POST',
            data: ObjecttoParams(dataList),
            url: serverUrl + 'admin/dashboard/deletelocation',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            if (response.status) {
                getlocationList();
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
    
    function getlocationList() {
        $http({
            method: 'POST',
            url: serverUrl + 'admin/dashboard/getlocation',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.locationList = response;
            console.log($scope.locationList);
        });

    }
});	