<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:100:"/data/wwwroot/dev/chat.profittravel.com/public/../application/admin/view/vgroup/user_group_list.html";i:1613730848;}*/ ?>
<div class="checkbox-list"><?php if (is_array($group) || $group instanceof \think\Collection || $group instanceof \think\Paginator): $i = 0;
        $__LIST__ = $group;
        if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $vo): $mod = ($i % 2);
            ++$i; ?>
            <div class="checkbox-item"><input class="checkbox" name="group" value="<?php echo $vo['id']; ?>"
                                              type="checkbox" <?php if (in_array($vo['id'], $user_groups)) echo 'checked="checked"'; ?>><span
                    style="margin-left: 15px;"><?php echo $vo['group_name']; ?></span>
            </div><?php endforeach; endif; else: echo "";endif; ?></div>