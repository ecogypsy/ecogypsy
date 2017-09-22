function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};

app.controller('hotelListController', function ($scope, $http, $sce,$timeout,hotelList) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.hotelList = hotelList;
    
    $scope.delete = function (id) {
        var dataList = {};
        dataList.id = id;
        $http({
            method: 'POST',
            data: ObjecttoParams(dataList),
            url: serverUrl + 'admin/dashboard/deletehotel',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            if (response.status) {
                gethotelList();
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
    
    function gethotelList() {
        $http({
            method: 'POST',
            url: serverUrl + 'admin/dashboard/hotelData',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.hotelList = response;
        });

    }
});	