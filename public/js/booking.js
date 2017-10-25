function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
}
;

app.controller('bookingController', function ($scope, $http, $sce, $timeout) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.changeBookingStatus = function (id, status) {
        var dataList = {};
        dataList.id = id;
        dataList.status = status
        $http({
            method: 'POST',
            data: ObjecttoParams(dataList),
            url: serverUrl + 'admin/dashboard/changeBookingStatus',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            console.log(response);
            $scope.successMsg = response.msg;
            if (response.status) {
                $scope.successShow = true;
                location.reload();
            }else{
                $scope.errorShow = true;
            }
        });
    };
});
