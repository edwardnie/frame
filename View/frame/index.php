<?php include VIEW_DIR . 'header.php'; ?>
    <input value="<?php echo '000123'; ?>">
    <div class="container">
        <div ng-app="myApp" ng-controller="myCtr">
            <p>在输入框中尝试输入：</p>

            <p>姓名：<input type="text" ng-model="name"></p>

            <p>年龄：<input type="text" ng-model="age"></p>

            <p>过滤：<input type="text" ng-model="number"></p>

            <p><span ng-bind="name"></span>--<span ng-bind="age"></span></p>

            <p>{{name + "--" + age}}</p>

            <p>{{uInfo()}}</p>

            <p>{{name|uppercase}}</p>

            <p>{{name|lowercase}}</p>

            <ul>
                <li ng-repeat="num in list | filter:number | orderBy: 'num' ">
                    {{ num }}
                </li>
            </ul>
            <table class="table table-bordered" style="width: 40%">
                <tr>
                    <th>姓名</th>
                    <th>年龄</th>
                    <th>筹码</th>
                </tr>
                <tr ng-repeat="item in userList | orderBy: 'item.age'">
                    <td>{{item.name}}</td>
                    <td>{{item.age}}</td>
                    <td>{{item.chips}}</td>
                </tr>
            </table>
        </div>
    </div>
<script src="<?php echo Helper_Url::themes('js/angular/demo.js') ?>"></script>
<?php include VIEW_DIR . 'footer.php'; ?>