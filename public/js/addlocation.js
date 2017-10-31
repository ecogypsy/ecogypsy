function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
}
;

app.controller('locationController', function ($scope, $http, $sce, $timeout, $location, cityList, hotelList, locationData) {
    $scope.successShow = false;
    $scope.errorShow = false;
    $scope.cityList = cityList;
    $scope.locationData = locationData;
    $scope.tempfoldername = '';
    $scope.id = '';
    $scope.location_id = 0;
    console.log(locationData);
    if (locationData.length == undefined) {
        $scope.hotel_id = locationData['1']['hotel_id'];
        $scope.city_id = locationData['1']['city'];
        $scope.location_name = locationData['1']['location_name'];
        $scope.id = $scope.location_id = locationData['1']['id'];
        $('#description').val(locationData['1']['description']);
        var temp = {};
        angular.forEach(hotelList, function (value, key) {
            if ($scope.city_id == value.city) {
                temp[key] = value;
            }
        });
        $scope.hotelList = temp;
    }

    $(function () {
        $("#city_id").on("change", function () {
            var city_id = this.value;
            var temp = {};
            angular.forEach(hotelList, function (value, key) {
                if (city_id == value.city) {
                    temp[key] = value;
                }
            });
            var select = document.getElementById('hotel');
            $scope.hotelList = temp;
//                angular.forEach(temp, function(value, key) {
//                    var opt = document.createElement('option');
//                    opt.value = value.id;
//                    opt.innerHTML = value.name;
//                    select.appendChild(opt);
//                 });
        });
    });
    $scope.addlocation = function () {
        $scope.upload_file = $('input[type=file]').val();
        $scope.city_id = $('#city_id').find(":selected").val();
        $scope.hotel_id = $('#hotel').find(":selected").val();
        var error = ' ';
        if ($scope.location_name == undefined || $scope.location_name == '') {
            error = 'Please enter hotel name';
        }

        if ($scope.city_id == undefined || $scope.city_id == '') {
            error = 'Please select city name';
        }

        if ($scope.hotel_id == undefined || $scope.hotel_id == '') {
            error = 'Please select hotel name';
        }

        if (error == ' ') {
            var dataList = {};
            dataList.location_name = $scope.location_name;
            dataList.city_id = $scope.city_id;
            dataList.hotel_id = $scope.hotel_id;
            dataList.description = $('#description').val();
            dataList.upload_file = $scope.upload_file;
            dataList.id = $scope.id;
            dataList.tempfoldername = $scope.tempfoldername;
            if ($scope.cover_image != undefined) {
                dataList.cover_image = $scope.cover_image;
            }
            if ($scope.ext != undefined) {
                dataList.ext = $scope.ext;
            }
            if ($scope.id == '') {
                delete(dataList.id);
            }
            $http({
                method: 'POST',
                data: ObjecttoParams(dataList),
                url: serverUrl + 'admin/dashboard/savelocation',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function (response) {
                if (response.status) {
                    $scope.successShow = true;
                    $scope.successMsg = response.msg;
                } else {
                    $scope.errorShow = true;
                    $scope.errorMsg = response.msg;
                }
                $timeout(function () {
                    $scope.successShow = false;
                    var path = serverUrl + 'admin/dashboard/locationlist';
                    window.location.href = path;
                    $scope.errorShow = false;
                }, 2000)
            });
        } else {
            $scope.errorShow = true;
            $scope.errorMsg = error;
            $timeout(function () {
                $scope.errorShow = false;
            }, 2000)
        }

    };

    $scope.loginUser = function () {
        var error = ' ';
        if ($scope.password == undefined || $scope.password == '') {
            error = 'Please enter password';
        }

        if ($scope.number == undefined || $scope.number == '') {
            error = 'Please enter number';
        }


        if (error == ' ') {
            var dataList = {};
            dataList.name = $scope.name;
            dataList.email = $scope.email;
            dataList.number = $scope.number;
            dataList.password = $scope.password;

            $http({
                method: 'POST',
                data: ObjecttoParams(dataList),
                url: url + 'index/signup',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function (response) {
                if (response.status) {
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
        } else {
            $scope.errorShow = true;
            $scope.errorMsg = error;
            $timeout(function () {
                $scope.errorShow = false;
            }, 2000)
        }

    }
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#profile-img-tag').attr('src', e.target.result);
            var appElement = document.querySelector('[ng-app=location]');
            var $scope = angular.element(appElement).scope(); 
            var tempfoldername = $("#tempfoldername").val();
            $.post("uploadlocation",{image:e.target.result, location_id:$scope.location_id, tempfoldername:tempfoldername} ,function (data) {
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
            var appElement = document.querySelector('[ng-app=location]');
            var $scope = angular.element(appElement).scope(); 
            $.post("uploadlocation",{image:e.target.result, location_id:$scope.location_id, imageType:'coverimage'} ,function (data) {
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