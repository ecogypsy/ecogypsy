function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};

app.controller('packageListController', function ($scope, $http, $sce,$timeout,packageList) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.packageList = packageList;
    getpackageList();
    console.log($scope.packageList);
    $scope.delete = function (package_id) {
        var dataList = {};
        dataList.package_id = package_id;
        console.log(package_id);
        $http({
            method: 'POST',
            data: ObjecttoParams(dataList),
            url: serverUrl + 'admin/dashboard/deletepackage',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            if (response.status) {
                getpackageList();
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
    
    function getpackageList() {
        $http({
            method: 'POST',
            url: serverUrl + 'admin/dashboard/getpackage',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.packageList = response;
        });

    }
});	