<?php
$user = Helper_Login::getUserInfo();
$role = User_Role::getInstance()->getRoleById($user['admin_role_id']);
$role = $role['data'];
//echo "<pre>";
//var_dump($role);
//echo "</pre>";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo Common::getLang( 'admin_title' ); ?></title>
    <link rel="stylesheet" href="<?php echo Helper_Url::themes('plugin/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo Helper_Url::themes('plugin/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo Helper_Url::themes('css/common.css'); ?>">
    <script src="<?php echo Helper_Url::themes('js/lib/jQuery-2.2.0.min.js') ?>"></script>
    <script src="<?php echo Helper_Url::themes('plugin/bootstrap/js/bootstrap.min.js') ?>"></script>
</head>
<body>
<div class="mask"></div>
<div id="ajax-loader"><img src="<?php echo Helper_Url::themes('admin/img/ajax-loader.gif'); ?>"/></div>

<div id="addBox">

</div>

<i class="fa fa-close fa-2x delete" id="close-box"></i>



<div class="container-fluid">