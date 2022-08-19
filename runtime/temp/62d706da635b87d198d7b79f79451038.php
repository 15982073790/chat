<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"/var/www/html/public/../application/platform/view/app/edit.html";i:1660889134;}*/ ?>
<link href="/assets/css/jquery.datetimepicker.min.css?v=LK_DIY6.0.3" rel="stylesheet"><style>
    form {
        position: relative;
    }

    .form-disable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, .75);
        z-index: 10;
        text-align: center;
        padding: 50px 0;
    }

    .form-disable .alert {
        display: table;
        margin: 0 auto;
    }

    .form-control:disabled, .form-control[readonly] {
        opacity: .5;
    }

    .add-form-body {
        padding: 12px 0;
    }

    .add-form-body .add-label {
        width: 195px;
        padding-left: 85px;
        text-align: left;
        margin: 0;
        float: left;
    }

    .add-label.required::before {
        left: 70px;
    }

    .add-form-body .form-group {
        margin-bottom: 12px;
    }

    .form-input .upload a {
        width: 80px;
        height: 36px;
        background-color: #2E9FFF;
        line-height: 36px;
        padding: 0;
        border-color: #2E9FFF;
        border-top-right-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }

    .preview {
        display: block;
        height: 72px;
        width: 196px;
        position: relative;
        background-color: #f7f7f7;
    }

    .preview .upload-preview-tip {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        margin-top: 30px;
        width: 196px;
        text-align: center;
        color: #bbbbbb;
        font-size: 12px;
        z-index: 2;
    }

    .preview img {
        position: absolute;
        left: 0;
        top: 0;
        height: 72px;
        width: 196px;
        display: block;
        z-index: 3;
    }

    .add-form-body .form-submit .btn-secondary, .add-form-body .form-submit .auto-form-btn {
        display: inline-block;
        height: 32px;
        line-height: 32px;
        width: 66px;
        border-radius: 16px;
        font-size: 13px;
        color: #555555;
        background-color: #f7f7f7;
        padding: 0;
        border: 1px solid #f7f7f7;
        cursor: pointer;
    }

    .add-form-body .form-submit .auto-form-btn {
        color: #fff;
        background-color: #25c16f;
        border: 1px solid #25c16f;
        margin-right: 20px;
    }

    #login-input {
        position: relative;
    }

    #login-input .eyes {
        position: absolute;
        top: 7px;
        right: 15%;
        width: 22px;
        height: 22px;
        cursor: pointer;
    }
</style><form method="post" return="<?php echo url('app/index'); ?>" action="<?php echo $action_url; ?>" class="auto-form"><?php if($account_over_max): ?><div class="form-disable"><div class="alert alert-danger"><div class="mb-2 pl-3 pr-3"><b>子账户创建数量上限！</b></div><div>当前客服系统数量：<?php echo $count; ?></div><div>最大客服系统数量：<?php echo $account_max; ?></div></div></div><?php endif; if(isset($model['id'])): ?><input type="hidden" value="<?php echo $model->admin_id; ?>" name="admin_id"><?php endif; $model['business_name'] = isset($model['business_name'])?$model['business_name']:'';?><div class="add-form-body"><div class="form-group row"><label class="add-label col-form-label required">客服系统名称</label><div class="form-input"><input type="text" class="form-control " name="business_name"
                       value="<?php echo $model['business_name']; ?>"></div></div><div class="form-group row"><label class="add-label col-form-label required">客服数量限制</label><div class="form-input"><?php if(isset($model['id'])): ?><input type="number" class="form-control "
                       value="<?php echo $model->max_count; ?>" name="max_count" class="form-control-plaintext "><div class="fs-sm text-muted">此客服系统站点可以创建客服的数量，填写0则表示不限制用户创建客服的数量</div><?php else: ?><input type="number" class="form-control " name="max_count"
                       value=""><div class="fs-sm text-muted">此客服系统站点可以创建客服的数量，填写0则表示不限制用户创建客服的数量</div><?php endif; ?></div></div><div class="form-group row"><label class="add-label col-form-label required">管理员用户名</label><div class="form-input"><?php if(isset($model->id)): ?><input class="form-control "
                       value="<?php echo $model->service->user_name; ?>" readonly class="form-control-plaintext "><?php else: ?><input type="text" class="form-control " name="user_name"
                       value=""><?php endif; ?></div></div><?php if(!isset($model['id'])): ?><div id="login-input" class="form-group row"><label class="add-label col-form-label required">登录密码</label><div class="form-input"><input type="password" id="password" class="form-control" value="" name="password"></div><img class="eyes" @click="openPassword" src="/assets/images/admin/B/close-eyes.png" v-if="eyes" alt=""><img class="eyes" @click="openPassword" src="/assets/images/admin/B/open-eyes.png" v-if="!eyes" alt=""></div><?php endif; if($is_copyright): ?><div class="form-group row"><label class="add-label col-form-label">LOGO图片URL</label><div class="form-input"><div class="input-group mb-2"><?php if(isset($model['id'])): ?><input class="form-control" value="<?php echo $model['logo']; ?>" name="logo"><?php else: ?><input class="form-control " value="<?php echo $option['logo']; ?>" name="logo"><?php endif; ?><span class="input-group-btn upload"><a class="btn btn-secondary upload-btn" href="javascript:">上传图片</a></span></div><div class="preview"><span class="upload-preview-tip">建议尺寸98&times;36</span><?php if(isset($model['id'])): if(!empty($model['logo'])): ?><img src="<?php echo $model['logo']; ?>" class="logo-preview"><?php endif; else: ?><img src="<?php echo $option['logo']; ?>" class="logo-preview"><?php endif; ?></div></div></div><div class="form-group row"><label class="add-label col-form-label">底部版权信息</label><div class="form-input"><?php if(isset($model['id'])): ?><input class="form-control " value="<?php echo htmlspecialchars($model['copyright']); ?>" name="copyright"><?php else: ?><input class="form-control " value="<?php echo htmlspecialchars($option['copyright']); ?>" name="copyright"><?php endif; ?></div></div><?php endif; ?><div class="form-group row"><label class="add-label col-form-label">备注</label><div class="form-input"><?php if(isset($model['id'])): ?><input class="form-control " value="<?php echo $model['remark']; ?>" name="remark"><?php else: ?><input class="form-control " value="" name="remark"><?php endif; ?></div></div><div class="form-group row"><label class="add-label col-form-label required">帐号有效期</label><div class="form-input"><div class="form-inline"><?php $model['expire_time'] = isset($model['expire_time'])?$model['expire_time']:'';if($model['expire_time'] == null): ?><input id="expire_time" style="width: 200px;" class="form-control" value="<?php echo date('Y-m-d', time()); ?>"
                           name="expire_time" <?php echo !empty($model['expire_time'])?null : 'readonly'; ?>><?php else: ?><input id="expire_time" style="width: 200px;" class="form-control"
                           value="<?php echo date('Y-m-d', $model['expire_time']); ?>"
                           name="expire_time" <?php echo !empty($model['expire_time'])?null : 'readonly'; ?>><?php endif; ?><label class="custom-control custom-checkbox ml-3"><input <?php echo !empty($model['expire_time'])?null: 'checked'; ?>
                        type="checkbox"
                        name="no_expire_time"
                        class="custom-control-input no-expire-time"><span class="custom-control-indicator"></span><span class="custom-control-description">永久</span></label></div></div></div><div class="form-group row form-submit" style="margin-left: 10%"><label class="add-label"></label><a class="btn btn-primary auto-form-btn" href="javascript:">保存</a><button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button></div></div></form><script src="/assets/js/jquery.datetimepicker.full.min.js?v=LK_DIY6.0.3"></script><script>
    new Vue({
        el: '#login-input',
        data() {
            return {
                eyes: true,
            };
        },
        created() {
        },
        methods: {
            openPassword() {
                if($('#password')[0].type == 'password') {
                    $('#password')[0].type = 'text';
                    this.eyes = !this.eyes
                }else if($('#password')[0].type == 'text') {
                    $('#password')[0].type = "password";
                    this.eyes = !this.eyes
                }
            }
        }
    });
</script><script>    $('.upload-btn').plupload({
        url: "<?php echo url('upload/image'); ?>",
        success: function (e, res, status) {
            res = JSON.parse(res);
            if (res.code == 0) {
                $('input[name=logo]').val(res.data.url);
                $('.logo-preview').attr('src', res.data.url);
            } else {
                $.alert({
                    content: res.msg,
                });
            }
        },
    });

    $.datetimepicker.setLocale('zh');

    $('#expire_time').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        minDate: '<?php echo date('Y-m-d'); ?>',
    });

    $(document).on('change', '.no-expire-time', function () {
        if ($(this).prop('checked')) {
            $('#expire_time').prop('readonly', true);
        } else {
            $('#expire_time').prop('readonly', false);
        }
    });

</script>