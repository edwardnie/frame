<?php include_once ROOT_DIR.'View/admin/frame_header.php'?>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th>uid</th>
        <th>昵称</th>
        <th>用户名</th>
        <th>电话</th>
        <th>邮箱</th>
        <th>角色</th>
        <th>创建时间</th>
        <th>最近登录</th>
        <th>操作&nbsp;<?php if($role[1][1]['add']): ?><a onclick="showBox('addBox', '<?php echo Helper_Url::getAdminUrl( 'user.userOperate' ) ?>', 450);return"><i class="purple fa fa-plus-circle"></i></a> <?php endif; ?> </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ( $data as $item ): ?>
        <tr>
            <td colspan="9" class="blue" style="text-align: left"><strong><?php echo $item['role_name'] ?></strong></td>
        </tr>
        <? foreach ( $item['child'] as $list ): ?>
            <tr>
                <td><?php echo $list['uid'] ?></td>
                <td><?php echo $list['nickname'] ?></td>
                <td><?php echo $list['username'] ?></td>
                <td><?php echo $list['tel'] ?></td>
                <td><?php echo $list['email'] ?></td>
                <td><?php echo $list['role_name'] ?></td>
                <td><?php echo date('Y-m-d H:i:s',$list['created']) ?></td>
                <td><?php echo date('Y-m-d H:i:s',$list['last_active']) ?></td>
                <td>
            <?php if($role[1][1]['update']): ?><a onclick="showBox('addBox', '<?php echo Helper_Url::getAdminUrl( 'user.userOperate', array( 'uid' => $list['uid'] ) ) ?>', 450);return"><i class="blue fa fa-pencil"></i></a><?php endif; ?>
            <?php if($role[1][1]['delete']): ?><a onclick="deleteUser(<?php echo $list['uid'] ?>);return;"><i class="trash fa fa-trash delete"></i></a><?php endif; ?>
                </td>
            </tr>
        <?php endforeach;; ?>
    <?php endforeach; ?>

    </tbody>
    </table>
</div>
<script>
    function deleteUser(uid) {
        if(!confirm("确定要删除吗?")){
            return false;
        }
        $.ajax({
            url:'<?php echo Helper_Url::getApiUrl('admin.deleteUser',array('format' => 'json')) ?>',
            data:{uid:uid,sign:md5},
            dataType:'json',
            success:function ( data ) {
                if(data.ret == 0){
                    alert("删除成功");
                    window.location.reload();
                }
            }
        });
        return false;
    }
</script>
<?php include_once ROOT_DIR.'View/admin/frame_footer.php'?>