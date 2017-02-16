<!DOCTYPE html>
<html>
<head>
    <title>后台管理中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="description" content="">
    <link rel="shortcut icon" href="<?php echo Helper_Url::themes('images/favicon_admin.png'); ?>">
    <link href="<?php echo Helper_Url::themes('plugin/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" media="screen">
    <link href="<?php echo Helper_Url::themes( 'admin/css/signin.css' ); ?>" rel="stylesheet" media="screen">
    <script src="<?php echo Helper_Url::themes('js/lib/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?php echo Helper_Url::themes('plugin/bootstrap/js/bootstrap.min.js') ?>"></script>
</head>
<body>
<div class="container">
    <form class="form-signin" role="form" autocomplete="off" method="post">
        <h2 class="form-signin-heading">管理后台</h2>
        <input type="text" name="admin" class="form-control" placeholder="用户名" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="密码" required>
        <ul class="list-unstyled list-inline">
            <li><input type="text" style="width: 80px" name="code" class="form-control" placeholder="验证码" required></li>
            <li>
                <a style="margin-left:20%;margin-top:0">
                    <img style="cursor:pointer" src="<?php echo Helper_Url::getUrl('tools.code'); ?>&t=<?php echo time(); ?>" onclick="javascript:this.src='<?php echo Helper_Url::getUrl('tools.code'); ?>&'+Math.random()" >
                </a>
            </li>
        </ul>
        <button class="btn btn-lg btn-primary btn-block sub" type="submit">登&nbsp;录</button>
    </form>
</div>
</body>
</html>
