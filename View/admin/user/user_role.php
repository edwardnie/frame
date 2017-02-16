<?php include_once ROOT_DIR.'View/admin/frame_header.php'?>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th>角色ID</th>
            <th>角色名</th>
            <th>操作&nbsp;<?php if($role[1][2]['add']): ?><a onclick="showBox('addBox', '<?php echo Helper_Url::getAdminUrl( 'user.roleOperate' ) ?>', 450);return"><i class="purple fa fa-plus-circle"></i></a><?php endif; ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $data as $item ): ?>
            <tr>
                <td><?php echo $item['role_id'] ?></td>
                <td><?php echo $item['role_name_'.Common::getLocale()] ?></td>
                <td>
                    <?php if($role[1][2]['update']): ?><a onclick="showBox('addBox', '<?php echo Helper_Url::getAdminUrl( 'user.roleOperate', array( 'role_id' => $item['role_id'] ) ) ?>', 450);return"><i class="blue fa fa-pencil"></i></a><?php endif; ?>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php include_once ROOT_DIR.'View/admin/frame_footer.php'?>
