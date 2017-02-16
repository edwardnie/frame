<?php include_once ROOT_DIR . 'View/admin/box_header.php' ?>
<link rel="stylesheet" href="<?php echo Helper_Url::themes('plugin/formvalidation/css/bootstrapValidator.min.css'); ?>">
<script src="<?php echo Helper_Url::themes('plugin/formvalidation/js/bootstrapValidator.min.js') ?>"></script>
<style>
    .form-horizontal .form-group{
        margin: 0;
    }
</style>
<h4 class="text-center"><?php echo $title ?></h4>
<form role="form" class="form-horizontal" id="defaultForm" method="post" action="<?php echo Helper_Url::getAdminUrl('user.roleOperate') ?>">
<div class="table-responsive">
    <table class="table table-bordered table-hover table-condensed">
        <tbody>
            <tr>
                <td>角色名-中文</td>
                <td>
                    <div class="form-group">
                        <input type="hidden" name="role_id" value="<?php echo $roleData['role_id'] ?>"/>
                        <input class="input-sm form-control" name="role_name_zh" value="<?php echo $roleData['role_name_zh'] ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td>角色名-英文</td>
                <td>
                    <div class="form-group">
                    <input class="input-sm form-control" name="role_name_en" value="<?php echo $roleData['role_name_en'] ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td>角色权限</td>
                <td>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                        <tr><td>功能名称</td><td><input type="checkbox" class="selectAll"> 功能操作</td></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($menuList as $list): ?>
                            <tr>
                                <td colspan="2"><i class="fa <?php echo $list['icon'] ?> "></i>&nbsp;&nbsp;<span><?php echo $list['name']; ?></span></td>
                            </tr>
                            <?php foreach ($list['child'] as $item): ?>
                            <tr>
                                <td style="text-align: left;padding-left:10% "><i class="fa <?php echo $item['icon'] ?> "></i>&nbsp;<input type="checkbox" class="menu_check">&nbsp;&nbsp;&nbsp;<?php echo $item['name'] ?></td>
                                <td>
                                    <ul class="list-inline list-inline">
                                        <li><i class="fa fa-search-plus gray"></i> <input type="checkbox" class="item-check" name="data[<?php echo $list['menu_id'] ?>][<?php echo $item['func_id'] ?>][view]" value="1" <?php if($roleData['data'][$list['menu_id']][$item['func_id']]['view']) echo 'checked'; ?> > </li>
                                        <li><i class="fa fa-plus-circle purple"></i> <input type="checkbox" class="item-check" name="data[<?php echo $list['menu_id'] ?>][<?php echo $item['func_id'] ?>][add]" value="1" <?php if($roleData['data'][$list['menu_id']][$item['func_id']]['add']) echo 'checked'; ?>> </li>
                                        <li><i class="fa fa-pencil blue"></i> <input type="checkbox" class="item-check" name="data[<?php echo $list['menu_id'] ?>][<?php echo $item['func_id'] ?>][update]" value="1" <?php if($roleData['data'][$list['menu_id']][$item['func_id']]['update']) echo 'checked'; ?>> </li>
                                        <li><i class="trash fa fa-trash delete"></i> <input type="checkbox" class="item-check" name="data[<?php echo $list['menu_id'] ?>][<?php echo $item['func_id'] ?>][delete]" value="1" <?php if($roleData['data'][$list['menu_id']][$item['func_id']]['delete']) echo 'checked'; ?>> </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" class="btn btn-primary" name="sub" value="sub">提 交</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default cancel">取 消</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</form>

<script>
    $(function () {
        //表单验证
        $('#defaultForm').bootstrapValidator({
            container: 'tooltip',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                'role_name_zh': {
                    validators: {
                        notEmpty: {
                            message: '用户名不能为空'
                        }
                    }
                },
                'role_name_en': {
                    validators: {
                        notEmpty: {
                            message: '用户名不能为空'
                        }
                    }
                }

            }
        });
        checkSelect();
        //全选
        $('.selectAll').change(function (  ) {
            $("input[type='checkbox']").prop("checked",$(this).prop('checked')?true:false);
            return false;
        });
        //单选
        $(".menu_check").change(function (  ) {
            $(this).parent('td').siblings('td').find("input[type='checkbox']").prop("checked",$(this).prop('checked')?true:false);
            return false;
        });
        //栏目选择
        $(".item-check").change(function (  ) {
            var parent = $(this).parent().parent();
            var len = parent.find("input[type='checkbox']:checked").length;
            parent.parent().parent('tr').find("td").eq(0).find("input[type='checkbox']").prop("checked",len>0?true:false);
            return false;
        });
        //检查父级元素是够应该选中
        function checkSelect(  ) {
            $(".item-check").each(function ( e ) {
                var parent = $(this).parent().parent();
                var len = parent.find("input[type='checkbox']:checked").length;
                parent.parent().parent('tr').find("td").eq(0).find("input[type='checkbox']").prop("checked",len>0?true:false);
            });
            return false;
        }
    })
</script>

<?php include_once ROOT_DIR . 'View/admin/box_footer.php' ?>
