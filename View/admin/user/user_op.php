<?php include_once ROOT_DIR . 'View/admin/box_header.php' ?>
<link rel="stylesheet" href="<?php echo Helper_Url::themes('plugin/formvalidation/css/bootstrapValidator.min.css'); ?>">
<script src="<?php echo Helper_Url::themes('plugin/formvalidation/js/bootstrapValidator.min.js') ?>"></script>
<style>.form-group{margin-bottom: 5px}</style>
<div class="row">
    <section>
        <div class="col-lg-8 col-lg-offset-2">
            <h4 class="text-center"><?php echo $title ?></h4>
            <form role="form" class="form-horizontal" id="defaultForm" method="post" action="<?php echo Helper_Url::getAdminUrl('user.userOperate') ?>">
                <div class="form-group">
                    <label class="col-lg-3 control-label ">用户名</label>
                    <div class="col-lg-4">
                        <input class="form-control input-sm" name="data[username]" value="<?php echo $userData['username'] ?>"/>
                        <input type="hidden" name="uid" value="<?php echo $userData['uid'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">密码</label>
                    <div class="col-lg-4">
                        <?php if($userData['uid']){ ?>
                            <input class="form-control input-sm" placeholder="输入为修改密码" name="data[password]" type="password" />
                        <?php }else{ ?>
                            <input class="form-control input-sm" placeholder="输入密码" required data-bv-notempty-message="请输入密码"  type="password"   name="data[password]" />
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">昵称</label>
                    <div class="col-lg-4">
                        <input class="form-control input-sm" name="data[nickname]" value="<?php echo $userData['nickname'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">邮箱</label>
                    <div class="col-lg-4">
                        <input class="form-control input-sm" name="data[email]" value="<?php echo $userData['email'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">手机</label>
                    <div class="col-lg-4">
                        <input class="form-control input-sm" name="data[tel]" value="<?php echo $userData['tel'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">角色</label>
                    <div class="col-lg-4">
                        <select class="form-control input-sm" name="data[role_id]">
                            <?php foreach ($userRole as $list): ?>
                                <option <?php if($list['role_id'] == $userData['role_id']) echo "selected"; ?> value="<?php echo $list['role_id'] ?>"><?php echo $list['role_name_'.Common::getLocale()] ?></option>
                            <?php endforeach;; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-9 col-lg-offset-3 text-center">
                        <button type="submit" class="btn btn-primary" name="sub" value="sub">提 交</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-default cancel">取 消</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<script>
    $(function () {
        $('#defaultForm').bootstrapValidator({
            container: 'tooltip',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                'data[username]': {
                    validators: {
                        notEmpty: {
                            message: '用户名不能为空'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: '用户名的长度在5-30个字符'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_\.]+$/,
                            message: '用户名由字母数字和下划线组成'
                        }
                    }
                },
                'data[email]': {
                    validators: {
                        emailAddress: {
                            message: '无效的邮箱地址'
                        },notEmpty: {
                            message: '无效的邮箱地址'
                        }
                    }
                },
                'data[nickname]': {
                    validators: {
                        notEmpty: {
                            message: '请输入昵称'
                        }
                    }
                },
                'data[tel]': {
                    validators: {
                        notEmpty: {message:'请输入有效的手机号码'},
                        digits: {message:'请输入有效的手机号码'},
                        phone: {
                            country: 'CN',
                            message:'请输入有效的手机号码'
                        }
                    }
                }
            }
        });
    })
</script>

<?php include_once ROOT_DIR . 'View/admin/box_footer.php' ?>
