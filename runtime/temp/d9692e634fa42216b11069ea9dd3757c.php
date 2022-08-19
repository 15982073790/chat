<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"/data/wwwroot/dev/chat.profittravel.com/public/../application/platform/view/user/index.html";i:1619138110;s:85:"/data/wwwroot/dev/chat.profittravel.com/application/platform/view/layout/default.html";i:1619138110;s:82:"/data/wwwroot/dev/chat.profittravel.com/application/platform/view/common/meta.html";i:1617183426;s:84:"/data/wwwroot/dev/chat.profittravel.com/application/platform/view/common/script.html";i:1586314438;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="shortcut icon" href="/favicon.ico">
    <title><?php echo !empty($title) ? $title . '-' : null; ?><?php echo !empty($option['title']) ? $option['title'] : '来客PHP在线客服系统'; ?></title>
    <link rel="stylesheet" href="https://at.alicdn.com/t/font_353057_c9nwwwd9rt7.css?v=LK_DIY6.0.3">
    <link href="//at.alicdn.com/t/font_353057_iozwthlolt.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/css/platform/bootstrap.min.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/css/platform/common.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/css/platform/common.v2.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/css/platform/flex.css?v=LK_DIY6.0.3" rel="stylesheet">
    <script>var _upload_url = "<?php echo url('upload/file'); ?>";</script>
    <script type="text/javascript" src="/assets/js/platform/vue.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/libs/jquery/jquery.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/popper.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/bootstrap.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/common.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/common.v2.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/plupload.full.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/libs/layui/layui.js?v=LK_DIY6.0.3"></script>
    <link rel="stylesheet" type="text/css" href="/assets/libs/layui/css/layui.css?v=LK_DIY6.0.3"/>
    <style>            .navbar {
            height: 200px;
            color: #fff;
            font-size: 18px;
            position: relative;
            background-image: url("/assets/images/admin/A/topbg.jpg");
        }

        .nav-top {
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
        }

        .navbar-nav .nav-link {
            color: #fff;
            font-size: 16px;
        }

        .dropdown-menu.show {
            display: inline-block;
        }

        .navbar-nav .dropdown-menu {
            position: absolute;
            background-color: #262760;
            border-radius: 10px;
        }

        .navbar-nav .dropdown-menu a {
            color: #fff;
        }

        .dropdown-item {
            height: 40px;
            line-height: 40px;
            padding: 0 0 0 20px;
            font-size: 12px;
        }

        .dropdown-item:hover {
            background-color: #1D1840;
        }

        .nav-text {
            padding-left: 36.5px;
            font-size: 22px;
        }

        .navbar .nav-menu {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            justify-content: flex-start;
            font-size: 14px;
            height: 35px;
        }

        .menu-item {
            float: left;
            margin-right: 40px;
            height: 35px;
            cursor: pointer;
        }

        .menu-item:first-of-type {
            padding-left: 16.5px;
        }

        .menu-item.active {
            color: #25c16f;
            border-bottom: 2px solid #25c16f;
        }

        .child-menu {
            background-color: #fff;
            border-radius: 8px;
            height: 65px;
            padding: 16.5px 20px;
            margin-bottom: 10px;
            display: none;
        }

        .child-menu a {
            color: #999999;
            display: inline-block;
            height: 32px;
            line-height: 32px;
            padding: 0 12px;
            text-decoration: none;
        }

        .child-menu .active {
            color: #25c16f;
            background-color: #F5FAFF;
            border-radius: 16px;
        }

        .change-password-modal .modal-dialog {
            margin-top: 15%;
            max-width: 600px;
        }

        .change-password-modal .modal-dialog .modal-content {
            width: 600px;
            border-radius: 8px;
        }

        .change-password-modal .modal-header {
            padding: 0 24px;
            height: 56px;
            line-height: 56px;
            border-bottom: 1px solid #f7f7f7;
        }

        .change-password-modal .modal-title {
            font-size: 13px;
            color: #555555;
        }

        .form-input .form-control {
            width: 300px;
            padding-left: 16px;
            border-radius: 8px;
            height: 36px;
            border: 1px solid #e5e3e9;
        }

        .change-password-modal .modal-body {
            padding: 24px 0;
        }

        .col-form-label {
            padding: 0;
            width: 160px;
            height: 36px;
            line-height: 36px;
            text-align: right;
            margin-right: 32px;
            float: left;
            font-size: 13px;
            color: #999999;
        }

        label.required::before {
            content: '';
            background-color: #ff5c5c;
            width: 6px;
            height: 6px;
            border-radius: 6px;
            top: 14px;
            left: 105px;
        }

        label.check::before {
            left: 90px;
        }

        .change-password-modal .form-group {
            margin: 0 0 24px 0;
            height: 36px;
        }

        .change-password-modal .modal-footer {
            border-top: 0;
            padding: 0;
            height: 56px;
            position: relative;
        }

        .change-password-modal .modal-footer .btn-secondary, .modal-footer .alter-password-submit {
            position: absolute;
            height: 32px;
            width: 66px;
            border-radius: 16px;
            font-size: 13px;
            color: #555555;
            top: 0;
            left: 50%;
            background-color: #f7f7f7;
            border: 1px solid #f7f7f7;
        }

        .change-password-modal .modal-footer .alter-password-submit {
            left: 35%;
            color: #fff;
            background-color: #25c16f;
            border: 1px solid #25c16f;
        }

        main.container .main-r {
            max-width: 100%;
        }

        main.container .main-r-content {
            padding: 0;
            border-color: #fff;
            border-radius: 8px;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .disabled span, .pagination li span, .pagination li a {
            margin: 0 1.5px;
            height: 32px;
            width: 32px;
            color: #555555;
            border-radius: 4px;
            text-decoration: none;
            border: 0;
        }

        .pagination li {
            border: 1px solid #f7f7f7;
        }

        .pagination li:first-of-type {
            margin-right: 11px;
        }

        .pagination li:last-of-type {
            margin-left: 11px;
        }

        .pagination .active span {
            background-color: #25c16f;
            border: 1px solid #25c16f;
            color: #fff;
        }

        .nav-menu div a {
            color: #ffffff;
        }

        .nav-menu div a.active {
            color: #25c16f;
            font-weight: 600;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="container nav-top"><a class="navbar-brand"
                                      href="<?php echo url('index/index'); ?>"><?php if (!empty($option['logo'])): ?>
                <img src="<?php echo $option['logo']; ?>" style="height: 30px;display: inline-block"><?php else: ?><img
                    src="/assets/images/platform/logo.png" style="height: 30px;display: inline-block"><?php endif; ?>
        </a>
        <ul class="navbar-nav">
            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                             href="http://example.com"
                                             id="dropdown01"
                                             data-toggle="dropdown"
                                             aria-haspopup="true"
                                             aria-expanded="false"><i class="iconfont icon-person"
                                                                      style="font-size: 1.3rem;line-height: 1;vertical-align: middle"></i><span><?php echo $admin['username']; ?></span></a>
                <div class="dropdown-menu" aria-labelledby="dropdown01"><a class="dropdown-item alter-password"
                                                                           href="javascript:" data-toggle="modal"
                                                                           data-target="#alterPassword">修改密码</a><a
                            class="dropdown-item" href="<?php echo url('passport/logout'); ?>">注销</a></div>
            </li>
        </ul>
    </div>
    <div class="container nav-text">管理中心</div>
    <div class="container nav-menu">
        <div style="margin: 0;padding: 0 10px;"><?php if (is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0;
                $__LIST__ = $menu;
                if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $vo): $mod = ($i % 2);
                    ++$i;
                    if ($vo): ?><a class="menu-item <?php if ($route == $vo['route']): ?>active<?php endif; ?>"
                                   href="<?php echo url($vo['route']); ?>"><?php echo $vo['name']; ?></a><?php endif; endforeach; endif; else: echo "";endif; ?>
        </div>
    </div>
</nav>
<main role="main" class="container">
    <div><?php if (is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0;
            $__LIST__ = $menu;
            if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $vo): $mod = ($i % 2);
                ++$i;
                if ($vo): ?>
                    <div class="child-menu"
                         data-url="<?php echo $vo['route']; ?>"><?php if (is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): $i = 0;
                        $__LIST__ = $vo['children'];
                        if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $i): $mod = ($i % 2);
                            ++$i; ?><a class="<?php if ($route == $i['route']): ?>active<?php endif; ?>"
                                       href="<?php echo url($i['route']); ?>"><span><?php echo $i['name']; ?></span>
                            </a><?php endforeach; endif; else: echo "";endif; ?>
                    </div><?php endif; endforeach; endif; else: echo "";endif; ?></div>
    <div class="main-r">
        <div class="main-r-content">
            <style>    .account-search {
                    width: 196px;
                    height: 54px;
                    padding-left: 16px;
                    position: relative;
                }

                .account-search input {
                    margin-top: 12px;
                    width: 180px !important;
                    height: 30px;
                    border-radius: 15px;
                    padding-left: 12px;
                }

                .account-search .btn {
                    position: absolute;
                    height: 14px;
                    width: 14px;
                    padding: 0;
                    line-height: normal;
                    top: 20px;
                    border: 0;
                    right: 12px;
                }

                .account-search .btn img {
                    height: 14px;
                    width: 14px;
                }

                .account-list {
                    border: 0;
                    border-top: 1px solid #f7f7f7;
                }

                .account-list thead, .account-list thead tr {
                    border: 0;
                }

                .account-list thead th {
                    background-color: #fff;
                    height: 40px;
                    border: 0;
                }

                .account-list tbody td {
                    height: 56px;
                    line-height: 56px;
                    padding-top: 0;
                    padding-bottom: 0;
                    border-top: 1px solid #f7f7f7;
                }

                .operate a {
                    margin-top: 12px;
                    display: inline-block;
                    height: 32px;
                    width: 32px;
                    border-radius: 16px;
                    background-color: #f5f5f5;
                    margin-right: 10px;
                }

                .operate a img {
                    display: block;
                    margin: 6px;
                    height: 20px;
                    width: 20px;
                }

                .account-list .remark {
                    line-height: normal;
                    margin-top: -18px;
                }
            </style>
            <div class="account-search">
                <form method="get" class="form-inline"><input type="hidden" name="r" value="admin/app/other-app"><input
                            value="<?php echo $keyword; ?>" placeholder="手机号/账户" type="text" name="keyword"
                            class="form-control form-control-sm">
                    <button style="cursor: pointer" class="btn btn-link btn-sm"><img
                                src="/assets/images/admin/A/search.png" alt=""></button>
                </form>
            </div>
            <table class="account-list table bg-white">
                <thead>
                <tr style="font-size: 13px;color: #555555">
                    <th></th>
                    <th>ID</th>
                    <th>账户</th>
                    <th style="text-align: center;">可创建客服系统数量</th>
                    <th style="text-align: center;">已创建客服系统数量</th>
                    <th>有效期</th>
                    <th>操作</th>
                </tr>
                </thead>
                <col style="width: 32px">
                <col style="width: 80px">
                <col style="width: 175px">
                <col style="width: 150px">
                <col style="width: 150px">
                <col style="width: 124px">
                <col><?php if (is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $k = 0;
                    $__LIST__ = $data;
                    if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $vo): $mod = ($k % 2);
                        ++$k; ?>
                        <tr style="font-size: 13px;color: #555555">
                        <td style="width: 32px"></td>
                        <td><?php echo $vo['id']; ?></td>
                        <td>
                            <div><?php echo $vo['username']; ?></div>
                            <div class="text-muted remark fs-sm"><?php echo $vo['remark']; ?></div>
                        </td>
                        <td style="text-align: center;"><?php if ($vo['app_max_count'] == '0'): ?>                无限制
                            <?php else: ?><?php echo $vo['app_max_count']; endif; ?></td>
                        <td style="text-align: center;color: #25c16f"><?php echo $counts[$k - 1]['business_count']; ?></td>
                        <td><?php if ($vo['expire_time'] == '0'): ?>                    永久
                            <?php else: ?><?php echo date("Y-m-d", $vo['expire_time']); endif; ?></td>
                        <td class="operate"><a href="<?php echo url('user/edit', ['id' => $vo['id']]); ?>"><img
                                        src="/assets/images/admin/A/edit.png" alt=""></a><?php if ($vo['id'] != 1): ?><a
                                class="modify-password"
                                href="<?php echo url('user/modifypassword', ['id' => $vo['id']]); ?>"
                                data-name="<?php echo $vo['username']; ?>"><img src="/assets/images/admin/A/change.png"
                                                                                alt=""></a><a class="delete"
                                                                                              href="<?php echo url('user/delete', ['id' => $vo['id']]); ?>">
                                    <img src="/assets/images/admin/A/delete.png" alt=""></a><?php endif; ?></td>
                        </tr><?php endforeach; endif; else: echo "";endif; ?></table><?php echo $page; ?>
            <script>    $(document).on("click", ".modify-password", function () {
        var href = $(this).attr("href");
        var username = $(this).attr("data-name");
        var id = $.randomString();
        var html = '';
        html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
        html += '<div class="modal-dialog modal-sm" role="document">';
        html += '<div class="modal-content" style="box-shadow: 0 1px 5px rgba(0,0,0,.25)">';
        html += '<div class="modal-header">';
        html += '<h6 class="modal-title">修改用户名或密码</h6>';
        html += '</div>';
        html += '<div class="modal-body">';
        html += '<div>请输入用户名：</div>';
        html += '<div class="mt-3"><input name="username" value="'+ username +'" class="form-control form-control-sm"></div>';
        html += '<div style="margin-top: 10px">请输入密码（必填）：</div>';
        html += '<div class="mt-3"><input name="password" class="form-control form-control-sm"></div>';
        html += '</div>';
        html += '<div class="modal-footer">';
        html += '<button type="button" class="btn btn-sm btn-secondary alert-cancel-btn">取消</button>';
        html += '<button type="button" class="btn btn-sm btn-primary alert-confirm-btn">确认</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $("body").append(html);
        $("#" + id).modal("show");
        $(document).on("click", "#" + id + " .alert-confirm-btn", function () {
            $("#" + id).modal("hide");
            var username = $("#" + id).find('[name="username"]').val();
            var val = $("#" + id).find('[name="password"]').val();
            if (!val) {
                $.myToast({
                    content: "密码不能为空",
                });
                return;
            }
            $.myLoading({
                title: "正在提交",
            });
            $.ajax({
                url: href,
                type: "post",
                dataType: "json",
                data: {
                    username: username,
                    password: val,
                },
                success: function (res) {
                    $.myLoadingHide();
                    $.myToast({
                        content: res.msg,
                    });
                    if (res.code == 0) {
                        location.reload();
                    }
                }
            });
        });
        $(document).on("click", "#" + id + " .alert-cancel-btn", function () {
            $("#" + id).modal("hide");
        });
        return false;
    });
    $(document).on("click", ".delete", function () {
        var href = $(this).attr("href");
        $.myConfirm({
            content: "确认删除此用户？此操作将不可恢复！",
            confirm: function () {
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: href,
                    type: "post",
                    dataType: "json",
                    success: function (res) {
                        $.myLoadingHide();
                        $.myToast({
                            content: res.msg,
                        });
                        location.reload();
                    }
                });

            }
        });
        return false;
    });

            </script>
        </div>
    </div>
</main><!-- /.container -->
<footer><?php if (!empty($option['copyright']) || $option['copyright'] != ''): ?>
        <div class="text-center copyright p-4"><?php echo $option['copyright']; ?></div><?php else: ?>
        <div class="text-center copyright p-4" style="color: #999;font-size: 16px">Powered by 来客PHP在线客服系统
        </div><?php endif; ?></footer><!-- Modal -->
<div class="modal fade change-password-modal" id="alterPassword" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="exampleModalLabel">修改密码</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" class="auto-submit-form alter-password-form">
                    <div class="form-group"><label class="col-form-label required">原密码</label>
                        <div class="form-input"><input type="password" name="old_password" class="form-control"
                                                       placeholder="您当前的密码"></div>
                    </div>
                    <div class="form-group"><label class="col-form-label required">新密码</label>
                        <div class="form-input"><input type="password" name="new_password"
                                                       class="form-control new-password-1"
                                                       placeholder="要设置的新密码"></div>
                    </div>
                    <div class="form-group"><label class="col-form-label required check">确认密码</label>
                        <div class="form-input"><input type="password" class="form-control new-password-2"
                                                       placeholder="再次输入新密码"></div>
                    </div>
                    <div class="form-error alert alert-danger" style="display: none">aaaaaa</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary alter-password-submit">提交</button>
            </div>
        </div>
    </div>
</div><!--无用、防止报错 start-->
<div id="file" hidden></div>
<div id="district_pick_modal" hidden></div><!--end-->
<script>            $(function(){
                $(".child-menu").find('.active').parent().show();
                let url = $(".child-menu").find('.active').parent()[0].dataset.url;     
                let first_url = $(".menu-item")[0].dataset.url;
                let sec_url = $(".menu-item")[1].dataset.url;
                let thrid_url = $(".menu-item")[2].dataset.url;
                if(url == first_url) {
                    $(".menu-item").first().addClass('active');
                }else if (url == thrid_url) {
                    $(".menu-item").last().addClass('active');
                }else if (url == sec_url) {
                    $(".menu-item:first").next().addClass('active');
                }
            });

            $(document).on("click", ".alter-password-submit", function () {
                var new_password_1 = $(".alter-password-form .new-password-1").val();
                var new_password_2 = $(".alter-password-form .new-password-2").val();
                var error = $(".alter-password-form .form-error");
                var btn = $(this);
                if (new_password_1 !== new_password_2) {
                    error.html("新密码与确认密码不一致，请重新输入").show();
                    return false;
                }
                error.hide();
                btn.btnLoading();
                $.ajax({
                    url: "<?php echo url('passport/modifyPassword'); ?>",
                    type: "post",
                    dataType: "json",
                    data: $(".alter-password-form").serialize(),
                    success: function (res) {
                        if (res.code == 0) {
                            $("#alterPassword").modal("hide");
                            $.myAlert({
                                content: res.msg,
                                confirm: function () {
                                    location.reload();
                                }
                            });
                        } else {
                            error.html(res.msg).show();
                            btn.btnReset();
                        }
                    }
                });
            });

</script>
</body>
</html>