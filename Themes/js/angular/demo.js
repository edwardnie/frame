var app = angular.module('myApp',[]);
app.controller('myCtr',function($scope,$http){
    $scope.name = 'laonie';
    $scope.age = 1024;
    $scope.list = [1,2,5,3,77,32];
    $scope.uInfo = function(){
        return $scope.name + '--' +$scope.age;
    };
    var params = {
        format:'json'
    };
    var url = generateUrl(config_base.base_url,'data.jsonData',params);
    $http.get(url).success(function(response){
        $scope.userList = response;
    })
});