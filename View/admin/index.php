<!DOCTYPE html>
<html>
<head>
    <title><?php echo Common::getLang('admin_title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo Helper_Url::themes('plugin/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo Helper_Url::themes('plugin/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo Helper_Url::themes('admin/css/index.css'); ?>">
    <link rel="stylesheet" href="<?php echo Helper_Url::themes('admin/css/_all-skins.css'); ?>">
</head>
<body class="hold-transition skin-blue sidebar-mini" style="overflow:hidden;">
<div id="ajax-loader" style="cursor: progress; position: fixed; top: -50%; left: -50%; width: 200%; height: 200%; background: #fff; z-index: 10000; overflow: hidden;">
    <img src="<?php echo Helper_Url::themes('admin/img/ajax-loader.gif'); ?>" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; margin: auto;"/>
</div>

<div class="wrapper">
    <!--头部信息-->
    <header class="main-header">
        <a href="<?php echo Helper_Url::getAdminUrl() ?>"  class="logo">
            <span class="logo-mini"><?php echo Common::getLang('admin_title_short'); ?></span>
            <span class="logo-lg"><strong><?php echo Common::getLang('admin_title'); ?></strong></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a class="sidebar-toggle">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o "></i>
                            <span class="label label-success">4</span>
                        </a>
                    </li>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                    </li>
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                        </a>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo BASE_URL . '/Themes/admin/' ?>img/user2-160x160.jpg" class="user-image"
                                 alt="User Image">
                            <span class="hidden-xs">administrator</span>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a class="menuItem" data-id="userInfo" href="/SystemManage/User/Info"><i class="fa fa-user"></i>个人信息</a></li>
                            <li><a href="javascript:void();"><i class="fa fa-trash-o"></i>清空缓存</a></li>
                            <li><a href="javascript:void();"><i class="fa fa-paint-brush"></i>皮肤设置</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo Helper_Url::getAdminUrl('admin.loginOut') ?>"><i class="ace-icon fa fa-power-off"></i>安全退出</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!--左边导航-->
    <div class="main-sidebar">
        <div class="sidebar">
            <div class="sidebar-top"><a target="_blank" href="<?php echo Helper_Url::getUrl(); ?>"><i class="fa fa-home">&nbsp;&nbsp;</i><?php echo Common::getLang('menu_title'); ?></a></div>
            <ul class="sidebar-menu" id="sidebar-menu">
                <?php foreach ($menuList as $list): ?>
                    <li class="treeview">
                        <a href="#"><i class="fa <?php echo $list['icon'] ?> "></i>&nbsp;&nbsp;<span><?php echo $list['name']; ?></span><i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <?php foreach ($list['child'] as $item): ?>
                                <li><a class="menuItem" data-id="<?php echo Helper_Url::getAdminUrl($item['url']) ?>" href="<?php echo Helper_Url::getAdminUrl($item['url']) ?>"><i class="fa <?php echo $item['icon'] ?> "></i>&nbsp;&nbsp;<?php echo $item['name'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach;; ?>
            </ul>
        </div>
    </div>
    <!--中间内容-->
    <div id="content-wrapper" class="content-wrapper">
        <div class="content-tabs">
<!--            <button class="roll-nav roll-left tabLeft">-->
<!--                <i class="fa fa-backward"></i>-->
<!--            </button>-->
            <nav class="page-tabs menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="menuTab active" data-id="<?php echo Helper_Url::getAdminUrl('admin.show') ?>">欢迎首页</a>
                </div>
            </nav>
<!--            <button class="roll-nav roll-right tabRight">-->
<!--                <i class="fa fa-forward" style="margin-left: 3px;"></i>-->
<!--            </button>-->
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown tabClose" data-toggle="dropdown">
                    页签操作<i class="fa fa-caret-down" style="padding-left: 3px;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a class="tabReload" href="javascript:void();">刷新当前</a></li>
                    <li><a class="tabCloseCurrent" href="javascript:void();">关闭当前</a></li>
                    <li><a class="tabCloseAll" href="javascript:void();">全部关闭</a></li>
                    <li><a class="tabCloseOther" href="javascript:void();">除此之外全部关闭</a></li>
                </ul>
            </div>
            <button class="roll-nav roll-right fullscreen"><i class="fa fa-arrows-alt"></i></button>
        </div>
        <div class="content-iframe" style="overflow: hidden;">
            <div class="mainContent" id="content-main" style="margin: 0px; padding: 0;">
                <iframe class="LRADMS_iframe" width="100%" height="100%" src="<?php echo Helper_Url::getAdminUrl('admin.show') ?>" frameborder="0" data-id="<?php echo Helper_Url::getAdminUrl('admin.default') ?>"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Helper_Url::themes('js/lib/jQuery-2.2.0.min.js') ?>"></script>
<script src="<?php echo Helper_Url::themes('plugin/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo Helper_Url::themes('admin/js/index.js') ?>"></script>
</body>
</html>
