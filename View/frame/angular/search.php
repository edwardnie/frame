<?php include VIEW_DIR . 'header.php'; ?>
    <div class="container" ng-app="searchApp" ng-controller="searchCtrl" style="margin-top: 20px">
        <p><input type="text" ng-model="txt" ng-keyup="toS()"></p>
        <table class="table table-bordered" style="width: 40%">
            <tr>
                <th>姓名</th>
                <th>年龄</th>
                <th>筹码</th>
            </tr>
            <tr ng-repeat="item in userList">
                <td>{{item.name}}</td>
                <td>{{item.age}}</td>
                <td>{{item.chips}}</td>
            </tr>
        </table>
    </div>
    <script>
        var app = angular.module('searchApp',[]);
        app.controller('searchCtrl',function($scope,$http){
            $scope.toS = function(){
                if(!$scope.txt){$scope.txt='';}
                var params = {
                    txt:$scope.txt,
                    format:'json'
                };
                var url = generateUrl(config_base.base_url,'data.jsonData',params);
                $http.get(url).success(function(response){
                    $scope.userList = response;
                });
            }
        })
    </script>
<?php include VIEW_DIR . 'footer.php'; ?>