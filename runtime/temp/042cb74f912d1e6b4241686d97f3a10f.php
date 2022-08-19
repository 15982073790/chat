<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"/var/www/html/public/../application/admin/view/custom/index.html";i:1660889134;s:55:"/var/www/html/application/admin/view/public/header.html";i:1660889134;s:56:"/var/www/html/application/admin/view/public/footer2.html";i:1660889134;}*/ ?>
<!DOCTYPE html><html lang="zh-CN"><head><title><?php echo $seo['business_name']; ?></title><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta name="renderer" content="webkit"><meta http-equiv="Content-Language" content="zh-CN"><link rel="shortcut icon" href="/favicon.ico"/><script>
        YMWL_ROOT_URL = "<?php echo $baseroot; ?>";

    </script><link href="/assets/libs/layer/skin/layer.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><link href="/assets/libs/amaze/css/amazeui.min.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><script type="text/javascript" src="/assets/libs/jquery/jquery.min.js?v=LK_DIY6.0.3"></script><script src="/assets/libs/jquery/jquery.form.min.js?v=LK_DIY6.0.3" type="text/javascript"></script><script src="/assets/libs/jquery/jquery.cookie.js?v=LK_DIY6.0.3" type="text/javascript"></script><script src="/assets/libs/layer/layer.js?v=LK_DIY6.0.3" type="text/javascript"></script><script src="/assets/libs/amaze/js/amazeui.min.js?v=LK_DIY6.0.3" type="text/javascript"></script><script src="/assets/libs/layui/layui.js?v=LK_DIY6.0.3" type="text/javascript"></script><link href="/assets/libs/layui/css/layui.css?v=LK_DIY6.0.3" rel="stylesheet"><link href="/assets/libs/bootstrap/bootstrap.min.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><link href="/assets/css/admin/common.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><link href="/assets/css/admin/admin.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><link href="/assets/css/admin/reload.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><script src="/assets/js/admin/functions.js?v=LK_DIY6.0.3" type="text/javascript"></script><link href="/assets/css/admin/index.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><link href="/assets/css/admin/index_me.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><link href="/assets/css/admin/set.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/><script src="/assets/js/admin/common_me.js?v=LK_DIY6.0.3" type="text/javascript"></script><script src="/assets/libs/push/pusher.min.js?v=LK_DIY6.0.3" type="text/javascript"></script><script src="/assets/libs/adapter.js?v=LK_DIY6.0.3"></script><script type="application/javascript">
        var mediaStreamTrack;
        var WEB_SOCKET_SWF_LOCATION = "/assets/libs/web_socket/WebSocketMain.swf";
        var WEB_SOCKET_DEBUG = true;
		var WEB_SOCKET_SUPPRESS_CROSS_DOMAIN_SWF_ERROR = true;
        var chat_data =Array();
        var record;
        var choose_lock = false;
        var myTitle = document.title;
        var msgreminder = <?php echo config('setting.msgreminder'); ?>;
        var config ={
          'app_key':'<?php echo $app_key; ?>',
          'whost':'<?php echo $whost; ?>',
          'value':<?php echo $value; ?>,
          'wport':<?php echo $wport; ?>
        };
        function titleBlink(){
        record++;

        if(record === 3){
             record =1;
        }
         
        if(record === 1){
            document.title='【 】'+myTitle;
        }

        if(record === 2){
            document.title='【消息】'+myTitle;
        }

        if(record > 3){
              getwaitnum();
             return;
        }

          setTimeout("titleBlink()",500);//调节时间，单位毫秒。
        }

        layui.use('element', function () {
           var element = layui.element;
        });
        var wolive_connect =function () {
            pusher = new Pusher('<?php echo $app_key; ?>', {
                encrypted: <?php echo $value; ?>
                , enabledTransports: ['ws']
                , wsHost: '<?php echo $whost; ?>'
                , <?php echo $port; ?>: <?php echo $wport; ?>
                , authEndpoint: YMWL_ROOT_URL + '/admin/login/auth'
                ,disableStats: true
         });

            var web = "<?php echo $arr['business_id']; ?>";
            var value ="<?php echo $arr['service_id']; ?>";
            // 私人频道
            var channelme = pusher.subscribe("ud" + value);
            channelme.bind("on_notice", function (data) {
                if(data.message.type == 'change'){
                    layer.msg(data.message.msg);
                }
                getchat();
                getwait();
            });

            channelme.bind("on_chat", function (data) {
               $.cookie("cu_com",'');
               layer.msg('该访客被删除');
               getchat();
            });
            // 公共平道
            var channelall = pusher.subscribe("all" + web);
            channelall.bind("on_notice", function (data) {
                if(<?php echo $arr['groupid']; ?> == 0 || <?php echo $arr['groupid']; ?> == data.message.groupid){
                    layer.msg(data.message.msg, {offset: "20px"});
                }

                if(<?php echo $arr['groupid']; ?> != data.message.groupid){
                 
                     layer.msg('该用户向其他分组咨询！', {offset: "20px"});
                }

                  getwait();
                  getchat();
                
            });
            
            var channel =pusher.subscribe("kefu" + value);
            // 发送一个推送
            channel.bind("callbackpusher",function(data){
                $.post("<?php echo url('admin/set/callback','',true,true); ?>",data,function(res){
                })
            });

            // 接受视频请求
            channel.bind("video",function (data) {
                getchat();
                var msg = data.message;
                var cha = data.channel;
                var cid = data.cid;
                var avatar =data.avatar;
                var username =data.username;
                layer.open({
                    type: 1,
                    title: '申请框',
                    area: ['260px', '160px'],
                    shade: 0.01,
                    fixed: true,
                    btn: ['接受', '拒绝'],
                    content: "<div style='position: absolute;left:20px;top:15px;'><img src='"+avatar+"' width='40px' height='40px' style='border-radius:40px;position:absolute;left:5px;top:5px;'><span style='width:100px;position:absolute;left:70px;top:5px;font-size:13px;overflow-x: hidden;'>"+username+"</span><div style='width:90px;height:20px;position:absolute;left:70px;top:26px;'>"+msg+"</div></div>",
                    yes: function () {
                        layer.closeAll('page');
                        var str='';
                        str+='<div class="videos">';
                        str+='<video id="localVideo" autoplay></video>';
                        str+='<video id="remoteVideo" autoplay class="hidden"></video></div>';


                        layer.open({
                          type:1
                          ,title: '视频'
                          ,shade:0
                          ,closeBtn:1
                          ,area: ['440px', '378px']
                          ,content:str
                          ,end:function(){

                           
                             mediaStreamTrack.getTracks().forEach(function (track) {
                                track.stop();
                            });
            
                          }
                      });
                        try{
                          connenctVide(cid);
                         }catch(e){
                             console.log(e);
                             return;
                         }
                        
                    },
                    btn2:function(){
                        var sid = $('#channel').text();
                        $.ajax({
                            url:YMWL_ROOT_URL+'/admin/set/refuse',
                            type:'post',
                            data:{channel:cha}
                        });

                        layer.closeAll('page');
                    }
                });
            });

            channel.bind('bind-wechat',function(data){
                layer.open({
                    content: data.message
                    ,btn: ['确定']
                    ,yes: function(index, layero){
                        location.reload();
                    }
                    ,cancel: function(){
                        return false;
                    }
                });
            });


            channel.bind('getswitch',function(data){
                layer.alert(data.message);
                getchat();
            });

            // 接受拒绝视频请求
            channel.bind("video-refuse",function (data) {
                layer.alert(data.message);
                layer.closeAll('page');
            });
            // 接受消息
            channel.bind("cu-event", function (data) {
                if("<?php echo $voice; ?>" == 'open'){
                    audioElementHovertree = document.createElement('audio');
                    audioElementHovertree.setAttribute('src', "<?php echo $voice_address; ?>");
                    audioElementHovertree.setAttribute('autoplay', 'autoplay');
                }
                var debug, portrait,showtime;
                var cdata = $.cookie("cu_com");

                if (cdata) {
                    var json = $.parseJSON(cdata);
                    debug = json.visiter_id;
                    portrait = json.avatar;
                } else {
                    debug = "";

                }

                if($.cookie("time") == ""){
                    time =data.message.timestamp;
                    $.cookie("time",time);
                    var mydate =new Date(time*1000);
                    showtime =mydate.getHours()+":"+mydate.getMinutes();
                }else{
                    time =$.cookie("time");
                    if((data.message.timestamp - time) >60){
                        var mydate =new Date(data.message.timestamp*1000);
                        showtime =mydate.getHours()+":"+mydate.getMinutes();
                    }else{
                        showtime ="";
                    }
                    $.cookie("time",data.message.timestamp);
                }
                var msg = '';
                msg += '<li class="chatmsg"><div class="showtime">' +showtime+ '</div><div style="position: absolute;left:3px;">';
                msg += '<img class="my-circle  se_pic" src="' + portrait + '" width="50px" height="50px"></div>';
                msg += "<div class='outer-left'><div class='customer'>";
                msg += "<pre>" + data.message.content + "</pre>";
                msg += "</div></div>";
                msg += "</li>";
                var str = data.message.content;
                if (data.message.visiter_id == debug) {
                    $(".conversation").append(msg);
                    getwatch(data.message.visiter_id);


                    str.replace(/<img [^>]*src=['"]([^'"]+)[^>]*>/gi, function (match, capture) {

                    var pos = capture.lastIndexOf("/");
                    var value = capture.substring(pos + 1);

                    if (value.indexOf("emo") == 0) {
                        str = data.message.content;
                    } else {
                        str = '[图片]';
                    }
                });

                str = str.replace(/<div><a[^<>]+><i>.+?<\/i>.+?<\/a><\/div>/,'[文件]');
                str = str.replace(/<a[^<>]+>.+?<\/a>/,'[超链接]');
                str =str.replace(/<img src=['"]([^'"]+)[^>]*>/gi,'[图片]');

                $("#msg" + data.message.channel).html(str);
             
                var div = document.getElementById("wrap");

                } 
                getnow(data.message);
                if(div){
                    div.scrollTop = div.scrollHeight;
                }
                $("#notices-icon").removeClass('hide');

                console.log(data);
                notify(data.message.visiter_name || '新访客', {
                    body: str,
                    icon: data.message.avatar
                }, function(notification) {
                    //可直接打开通知notification相关联的tab窗口
                    window.focus();
                    notification.close();
                    console.log('#v'+data.message.channel+' .visit_content');
                    $('#v'+data.message.channel+' .visit_content').trigger('click');
                    // $('#v'+data.message.channel+' .visit_content').trigger('click');
                });
            });


            // 通知 游客离线
            channel.bind("logout", function (data) {

                //表示访客离线
                var cdata = $.cookie("cu_com");
                var chas;
                if (cdata) {
                    var jsondata = $.parseJSON(cdata);
                    chas = jsondata.channel;
                }

                if (chas == data.message.chas) {
                    //头像变灰
                    $("#v_state").text("离线");
                }

                $("#img" + data.message.chas).addClass("icon_gray");
                getchat();

            });

            channel.bind("geton", function (data) {

                //表示访客在线
                var cdata = $.cookie("cu_com");
                var chas;
                if (cdata) {
                    var jsondata = $.parseJSON(cdata);
                    chas = jsondata.channel;
                }
                if (chas == data.message.chas) {
                    //头像变亮
                    $("#img" + data.message.chas).removeClass("icon_gray");
                    $("#v_state").text("在线");
                }

                $("#img" + data.message.chas).removeClass("icon_gray");
                getchat();

            });

            pusher.connection.bind('state_change', function(states) {
              
                if(states.current == 'unavailable' || states.current == "disconnected" || states.current == "failed" ){

                        pusher.unsubscribe("kefu" + value);
                        pusher.unsubscribe("all" + web);
                        pusher.unsubscribe("ud" + value);
            
                    if (typeof pusher.isdisconnect == 'undefined') {
                     pusher.isdisconnect = true;

                     pusher.disconnect();
                     delete pusher;
                     
                      window.setTimeout(function(){
                         wolive_connect();
                      },1000);
                    }

                 
                    $(".profile").text('离线');
                }
            });

            pusher.connection.bind('connected', function() {
                $(".profile").text('在线');
            });
        }


        function showpage(obj){

            var value =$(obj).attr("name");
            var key =$(obj).attr("id");
            layer.tips(value, '#'+key,{tips: [4, '#2F4050']});
        }




    </script><script type="text/javascript" src="/assets/libs/web_socket/swfobject.js?v=LK_DIY6.0.3"></script><script type="text/javascript" src="/assets/libs/web_socket/web_socket.js?v=LK_DIY6.0.3"></script><script type="text/javascript" src="/assets/js/admin/online.js?v=LK_DIY6.0.3"></script><meta name="renderer" content="webkit"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><link href="/assets/css/admin/ymwl_common.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/></head><body><div id="layout-west"><div id="layout-menus"><div id="layout-menus-info"><a href="javascript:;"><span class="info"
                      style="color: #ffffff;font-size: 16px;vertical-align:middle;display:table-cell;position: static;left: 0"><img src="<?php echo !empty($seo['logo'])?$seo['logo'] :'/assets/images/index/workerman_logo.png'; ?>" class="am-circle"
                         style="margin: 0 10px 0 20px;"><?php echo $seo['business_name']; ?></span></a></div><div id="layout-menus-lists"><ul id="group-menus-main" class="group-menus am-list "><?php if($part == "首页"): ?><li class="menu-item" id="title1" onmouseover="showpage(this)" name="系统首页"
                    style="background: #ffffff;color: #353535"><a href="<?php echo url('admin/index/index'); ?>"><i class="layui-icon">&#xe638;</i><span style="color: #353535" class="info">系统首页</span></a></li><?php else: ?><li class="menu-item" id="title1" onmouseover="showpage(this)" name="系统首页"><a href="<?php echo url('admin/index/index'); ?>"><i class="layui-icon">&#xe638;</i><span style="color: #fff" class="info">系统首页</span></a></li><?php endif; if($part == "客户咨询"): ?><li class="menu-item" id="title2" onmouseover="showpage(this)" name="客户咨询"
                    style="background:#ffffff;color: #353535"><span class="notices hide"></span><span class="notices-icon hide"></span><a href="<?php echo url('admin/index/chats'); ?>"><i class="layui-icon">&#xe606;</i><span style="color: #353535" class="info">客户咨询</span></a></li><?php else: ?><li class="menu-item" id="title2" onmouseover="showpage(this)" name="客户咨询"><!-- <i id="notices-icon" class="hide"></i> --><span class="notices hide"></span><span class="notices-icon hide"></span><a href="<?php echo url('admin/index/chats'); ?>"><i class="layui-icon">&#xe606;</i><span style="color: #fff" class="info">客户咨询</span></a></li><?php endif; if($arr['level'] != 'service'): if($part == "历史记录"): ?><li class="menu-item" id="title7" onmouseover="showpage(this)" name="历史记录" style="background: #ffffff;"><a href="<?php echo url('admin/manager/view'); ?>"><i class="layui-icon">&#xe62a;</i><span style="color: #353535" class="info">历史记录</span></a></li><?php else: ?><li class="menu-item" id="title7" onmouseover="showpage(this)" name="历史记录"><a href="<?php echo url('admin/manager/view'); ?>"><i class="layui-icon">&#xe62a;</i><span style="color: #fff" class="info">历史记录</span></a></li><?php endif; endif; if($part == "评价管理"): ?><li class="menu-item" id="title3" onmouseover="showpage(this)" name="评价管理" style="background: #ffffff;"><a href="<?php echo url('admin/evaluate/index'); ?>"><i class="layui-icon">&#xe67b;</i><span style="color: #353535" class="info">评价管理</span></a></li><?php else: ?><li class="menu-item" id="title3" onmouseover="showpage(this)" name="评价管理"><a href="<?php echo url('admin/evaluate/index'); ?>"><i class="layui-icon">&#xe67b;</i><span style="color: #fff" class="info">评价管理</span></a></li><?php endif; if($part == "客户管理"): ?><li class="menu-item" id="title8" onmouseover="showpage(this)" name="客户管理" style="background: #ffffff;"><a href="<?php echo url('admin/custom/index'); ?>"><i class="layui-icon">&#xe770;</i><span style="color: #353535" class="info">客户管理</span></a></li><?php else: ?><li class="menu-item" id="title8" onmouseover="showpage(this)" name="客户管理"><a href="<?php echo url('admin/custom/index'); ?>"><i class="layui-icon">&#xe770;</i><span style="color: #fff" class="info">客户管理</span></a></li><?php endif; if($part == "接入方法"): ?><li class="menu-item" id="title9" onmouseover="showpage(this)" name="接入方法" style="background: #ffffff;"><a href="<?php echo url('admin/index/front'); ?>"><i class="layui-icon">&#xe64d; </i><span style="color: #353535" class="info">接入方法</span></a></li><?php else: ?><li class="menu-item" id="title9" onmouseover="showpage(this)" name="接入方法"><a href="<?php echo url('admin/index/front'); ?>"><i class="layui-icon">&#xe64d;</i><span style="color: #fff" class="info">接入方法</span></a></li><?php endif; if($part == '系统设置'): ?><li class="menu-item" id="title10" onmouseover="showpage(this)" name="系统设置"
                    style="background: #ffffff;"><a href="<?php echo url('admin/index/set'); ?>"><i class="layui-icon">&#xe620;</i><span style="color: #353535" class="info">系统设置</span></a></li><?php else: ?><li class="menu-item" id="title10" onmouseover="showpage(this)" name="系统设置"><a href="<?php echo url('admin/index/set'); ?>"><i class="layui-icon">&#xe620;</i><span style="color: #fff" class="info">系统设置</span></a></li><?php endif; ?><li class="menu-item <?php if($part=='登录日志')echo 'bg-white'; ?>" onmouseover="showpage(this)"><a href="<?php echo url('admin/service/loginlog'); ?>"><i class="layui-icon layui-icon-log"></i><span style="color: #fff" class="info">登录日志</span></a></li><?php echo hook('adminleftmenuhook', ['part'=>$part]); ?></ul></div></div></div><script type="text/javascript">
    var width =document.body.clientWidth;

    var changeall =function(){
        $("#layout-west").css("width","160px");
        $("#layout-center").css({"position":"absolute","left":"160px","width":(width-160)+"px"});
        $(".info").removeClass("hide");
        $(".west_foot2").removeClass("hide");
        $(".west_foot1").addClass("hide");
        if($(".notices")[0].textContent > 0) {
            $('.notices').removeClass("hide");
            $('.notices-icon').addClass("hide");     
        }
    }
    var changesmall =function(){
        if($(".notices")[0].textContent > 0) {
            $('.notices').removeClass("hide");
        }
    }
    setInterval(function(){
        changesmall();
    },1000);
    if (<?php echo $is_bind_wechat; ?>) {
        var index = layer.open({
            //您还未绑定微信，无法接受模板消息
            content: '当前账户未设置OPENID，无法接受模板消息，是否前去绑定'
            ,btn: ['前去绑定','忽略']
            ,yes: function(index, layero){
                $.ajax({
                    url: "/admin/index/qrcode.html",
                    dataType: 'json',
                    success: function (res) {
                        layer.close(index);
                        if(res.code){
                            layer.msg(res.msg);
                        }else{
                            layer.open({
                                title:'扫码关注,绑定公众号',
                                type: 1,
                                content: '<img style="width: 200px;height: 200px;" src="'+res.data+'"/>',
                            });
                        }
                    }
                });
                return false;
            },
            btn2: function(index, layero){
            //按钮【按钮二】的回调

            //return false 开启该代码可禁止点击该按钮关闭
            }
            ,cancel: function(){

            }
        });
    }
</script><div id="layout-center"><div id="layout-north"><a id="layout-logo" style="font-size: 1.2em;"><i class="am-icon-bookmark"></i>&nbsp;<?php echo $part; ?></a><ul id="layout-tools" class="am-nav am-nav-pills"><li><div class="am-dropdown " data-am-dropdown><a class="am-dropdown-toggle" href="javascript:;" data-am-dropdown-toggle><img id="se_avatar" src="<?php echo $arr['avatar']; ?>" alt="..." class="am-circle" width="28px"
                             height="28px"><span class="handle" id="se"><?php echo $arr['nick_name']; ?></span><span id="channel" style="display: none;"></span><span id="customer" style="display: none;"></span><?php if($arr['state'] == 'online'): ?><span class="profile">在线<i class="am-icon-caret-down"></i></span><?php else: ?><span class="profile">离线<i class="am-icon-caret-down"></i></span><?php endif; ?></a><ul class="am-dropdown-content"><li><a href='javascript:showinfo(<?php echo $data; ?>,<?php echo $group; ?>)'><img src="/assets/images/admin/B/person.png"
                                                                                 alt="">个人资料</a></li><li><a href='javascript:modify(<?php echo $arr["service_id"]; ?>)'><img
                                src="/assets/images/admin/B/change-password.png" alt="">修改密码</a></li><?php if($arr['level'] == 'super_manager' && $referer): ?><li><a href="<?php echo url('platform/index/index'); ?>"><img src="/assets/images/admin/B/back-system.png"
                                                                          alt="">返回系统</a></li><?php endif; ?><li><a href="<?php echo url('admin/login/logout',['business_id'=>$arr['business_id']]); ?>"
                               class="safe-exit"><img src="/assets/images/admin/B/quit.png" alt="">安全退出
                            </a></li></ul></div></li></ul></div><script src="/assets/libs/vue/vue.js?v=LK_DIY6.0.3" type="text/javascript"></script><style>
    #container {
        display: flex;
        padding: 10px 0 0 10px;
        background-color: #F7F7F7;
    }

    .left {
        width: 15%;
        min-width: 250px;
        border-right: 10px solid #f7f7f7;
        background-color: #fff;
        border-top-left-radius: 10px;
        position: relative;
    }

    .left-title {
        height: 50px;
        padding-left: 15px;
        line-height: 50px;
    }

    .left p {
        font-size: 14px;
        color: #858684;
    }

    .left ul {
        display: flex;
        flex-direction: column;
        border-top: 1px solid #F7F7F7;
    }

    .left ul li {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        padding: 0 15px;
        font-size: 14px;
        height: 45px;
        line-height: 45px;
        position: relative;
    }

    .left ul li .more-menu {
        top: 40px;
    }

    .left ul li.active {
        background-color: #F5FAFF;
    }

    .left ul li:hover .number {
        display: none;
    }

    .left ul li .more {
        display: none;
        float: right;
        height: 20px;
        width: 20px;
        margin: 12.5px 0;
    }

    .left ul li:hover .more {
        display: block;
    }


    .left ul li .number {
        float: right;
        height: 20px;
        line-height: 20px;
        text-align: center;
        width: 20px;
        margin: 12.5px 0;
        color: #999999;
    }

    .right {
        background-color: #fff;
        width: 85%;
        position: relative;
    }

    .right-title {
        height: 50px;
        padding-left: 15px;
        line-height: 50px;
        border-bottom: 1px solid #f7f7f7;
    }

    .right .wrap {
        display: flex;
        flex-wrap: wrap;
        align-content: flex-start;
        padding: 15px;
        padding-top: 0;
    }

    .right .wrap .item {
        display: flex;
        width: 350px;
        height: 90px;
        padding: 20px 15px;
        background-color: #f7f7f7;
        margin: 5px;
        position: relative;
    }

    .right .wrap .item.selected {
        border: 2px solid #2E9FFF;
    }

    .right .wrap .item.hand {
        cursor: pointer;
    }

    .right .wrap .item .wrap-name {
        font-size: 14px;
        margin: 5px 0;
        height: 15px;
        line-height: 15px;
    }

    .right .wrap .item .more {
        position: absolute;
        bottom: 20px;
        right: 15px;
        height: 20px;
        width: 20px;
        cursor: pointer;
    }

    .right .wrap .item .info {
        position: static;
        padding: 15px 0 0 20px;
        display: flex;
        flex-direction: column;
    }

    .right .wrap .item .control {
        padding: 30px 0 0 85px;
    }

    .right .wrap .wrap-img {
        margin-right: 15px;
        width: 50px;
        height: 50px;
        border-radius: 100%;
    }

    .right .wrap .wrap-status {
        height: 28px;
        width: 28px;
        position: absolute;
        top: 0;
        right: 0;
    }

    .addgroup {
        height: 32px;
        width: 120px;
        line-height: 32px;
        text-align: center;
        font-size: 13px;
        margin-top: 25px;
        color: #25c16f !important;
        border: 1px solid #25c16f;
        border-radius: 16px;
        cursor: pointer;
        margin-bottom: 25px;
    }

    .group .layui-layer-ico {
        height: 24px;
        width: 24px;
        background: url("/assets/images/admin/B/cha.png") no-repeat;
    }

    .recycle {
        width: 20px;
        height: 20px;
        background: url("/assets/images/admin/B/delete.png");
        cursor: pointer;
    }

    .layui-layer label {
        width: 100px;
    }

    .group .layui-input-block {
        padding-top: 5px;
    }

    .search {
        margin: 20px;
        height: 30px;
        width: 200px;
        position: relative;
        display: inline-block;
    }

    .search-input {
        border: 1px solid #E5E3E9;
        height: 30px;
        line-height: 30px;
        padding: 0 35px 0 15px;
        width: 200px;
        border-radius: 15px;
    }

    .search-icon {
        position: absolute;
        display: block;
        height: 15px;
        width: 15px;
        right: 15px;
        top: 7.5px;
        cursor: pointer;
    }

    .choose {
        height: 30px;
        width: 90px;
        display: inline-block;
    }

    .choose-btn {
        height: 30px;
        width: 90px;
        line-height: 30px;
        text-align: center;
        background-color: #fff;
        font-size: 13px;
        color: #25c16f;
        border: 1px solid #25c16f;
        border-radius: 16px;
        cursor: pointer;
    }

    .more-menu {
        position: absolute;
        right: 0;
        width: 105px;
        padding: 3px 0;
        border-radius: 3px;
        background-color: #fff;
        z-index: 5;
    }

    .more-menu.left-menu {
        width: 90px;
        background-color: #F7F7F7;
    }

    .more-menu.left-menu .menu-item {
        width: 90px;
        background-color: #F7F7F7;
    }

    .more-menu .menu-item {
        width: 105px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        cursor: pointer;
        color: #353535;
        background-color: #fff;
    }

    .more-menu .menu-item:hover {
        background-color: #DDDDDD;
    }

    .operate-menu {
        margin: 20px;
        margin-bottom: 0;
        display: flex;
        color: #353535;
    }

    .operate-menu .dropdown {
        background-color: #fff;
        height: 30px;
        border-radius: 15px;
    }

    .operate-menu button {
        padding: 0 15px;
        background-color: #fff;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-radius: 15px;
        border: 1px solid #E9E7EC;
    }

    .operate-btn {
        margin-left: 18px;
    }

    .list {
        display: none;
        position: absolute;
        top: 32px;
        left: 0;
        width: 90px;
        background-color: #fff;
        color: #353535;
        border: 1px solid #e2e2e2;
        z-index: 10;
    }

    .list li {
        height: 30px;
        width: 88px;
        line-height: 30px;
        cursor: pointer;
        text-align: center;
    }

    .list li:hover {
        background-color: #F5FAFF;
    }

    .set-dialog {
        padding-bottom: 20px;
        width: 300px;
        position: fixed;
        top: 250px;
        left: 0;
        right: 0;
        margin: 0 auto;
        background-color: #fff;
    }



    .group-name {
        position: absolute;
        width: 60%;
        overflow: hidden;
        white-space: nowrap;
        left: 80px;
        bottom: 20px;
    }

    .group-name .group-name-item {
        padding: 0 10px;
        display: inline-block;
        margin-right: 3px;
        height: 22px;
        line-height: 22px;
        border-radius: 11px;
        border: 1px solid #DDDDDD;
        background-color: #fff;
        cursor: pointer;
    }

    .triangle_border_up {
        width: 0;
        height: 0;
        border-width: 0 30px 30px;
        border-style: solid;
        border-color: transparent transparent #333; /*透明 透明  灰*/
        position: absolute;
        top: 75px;
        left: 80px;
        z-index: 99;
    }

    .group-name-array {
        position: absolute;
        top: 80px;
        left: 80px;
        background-color: #353535;
        color: #fff;
        font-size: 14px;
        height: 40px;
        line-height: 40px;
        border-radius: 5px;
        padding: 0 10px;
        z-index: 99;
    }

    .addgroup-input {
        margin-top: 5px;
        display: none;
    }

    .addgroup-input .form-control {
        border: 1px solid #E5E3E9;
        border-radius: 10px;
    }

    .addgroup-input .btn {
        border: 1px solid #2E9FFF;
        border-radius: 10px;
    }

    .new-item {
        display: none;
    }

    .page {
        margin-right: 30px;
        height: 130px;
    }

    .pagination {
        float: right;
    }

    .pagination li {
        display: inline-block;
        margin: 0 5px;
        cursor: pointer;
    }

    .tag {
        font-size: 10px;
        height: 20px;
        background-color: #F5FAFF;
        border: 1px solid #25c16f;
        border-radius: 10px;
        color: #25c16f;
        padding: 1px 5px;
        border-bottom-left-radius: 0;
    }

    .tag:hover .msg-info {
        display: block;
    }

    .msg-info {
        padding: 15px;
        padding-right: 25px;
        position: absolute;
        left: 75px;
        bottom: 80px;
        border-radius: 10px;
        background-color: #fff;
        z-index: 20;
        display: none;
        font-size: 13px;
        color: #353535;
        box-shadow: 0 0 10px rgba(0, 0, 0, .3)
    }

    .msg-info .border {
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #fff;
        position: absolute;
        bottom: -8px;
        z-index: 99;
    }
</style><div id="container" style="overflow-y: auto"><div id="group_list" style="display: none;"><div class="checkbox-list"><div class="checkbox-item" v-for="item in group"><input class="checkbox" v-model="item.checked" name="group" :value="item.id" type="checkbox"><span style="margin-left: 15px;">{{item.group_name}}</span></div><p class="addgroup" style="margin-top: 10px;" onclick="createdGroup()">+ 添加分组</p><div class="addgroup-input input-group" style="margin-bottom: 25px"><input type="text" placeholder="请输入分组名，最多12个字" class="form-control"><span class="input-group-btn"><button class="btn btn-primary" style="background-color:#2E9FFF" type="button"
                            onclick="add()">添加</button></span></div></div></div><div class="left"><p class="left-title">客户分组</p><ul style="max-height: 400px;overflow: auto"><li :class="group_id == 0 ? 'active' : ''" @click="ajax(0,1,'全部客户')"><span>全部客户</span><div class="number" style="display: block;"><?php echo $allcount; ?></div></li><li :class="group_id == -2 ? 'active' : ''" @click="ajax(-2,1,'意向客户')"><span>意向客户</span><div class="number" style="display: block;"><?php echo $message_count; ?></div></li><li class="group-item" v-for="item in group" :class="group_id == item.id ? 'active' : ''"
                @click="ajax(item.id,1,item.group_name)"><span>{{item.group_name}}</span><div class="number">{{item.count}}</div><img class="more" @click.stop="show(item.id,item.group_name,item.bgcolor,$event)"
                     src="/assets/images/admin/more.png" alt=""></li><div class="more-menu left-menu" v-if="toggle" :style="{top: top}"><div @click.stop="editgroup" class="menu-item">修改名称</div><div @click.stop="delGroup" class="menu-item">删除分组</div></div></ul><p class="addgroup" style="margin-left: 15px;" onclick="addgroup()">+ 添加分组</p><ul><li @click="ajax(-1,1,'黑名单')"><span>黑名单</span><div class="number" style="display: block;"><?php echo $blackcount; ?></div></li></ul></div><div class="right"><p class="right-title">{{title}}</p><div class="search" v-if="operate == false"><input class="search-input" v-model="keyword" type="text" placeholder="昵称或姓名" @keyup.enter="search"><img class="search-icon" @click.stop="search" src="/assets/images/admin/icon-search.png"></div><div class="choose" @click.stop="toEdit" v-if="!operate"><button class="choose-btn">批量操作</button></div><div class="operate-menu" v-if="operate == true"><button type="operate-btn" @click="setGroup">添加到
                <span class="caret"></span></button><button class="operate-btn" @click.stop="moreblack">加入黑名单</button><button class="operate-btn" @click.stop="toEdit">退出管理</button></div><div id="wrap" class="wrap"><div @click.stop="selecte(item)" class="item" :class="{hand:title != '黑名单',selected:item.choose}"
                 v-for="item in list" :key="item.vid"><div v-if="item.group_name_array.length > 1 && show_id == item.vid" class="triangle_border_up"></div><span v-if="item.group_name_array.length > 1 && show_id == item.vid" class="group-name-array">{{item.group_name}}</span><img v-if="item.choose" class="wrap-status" src="/assets/images/admin/choose.png" alt=""><img :src="item.avatar" alt="" :class="item.state==='offline'?'wrap-img icon_gray':'wrap-img'"><div class="wrap-name"><span v-if="item.name || item.tel" class="tag"><span>已留信息</span><div class="msg-info"><div>{{item.name}}</div><div>{{item.tel}}</div><div style="color: #999">{{item.msg_time}}</div><div class="border"></div></div></span><span>{{item.visiter_name}}</span></div><div class="group-name" v-if="item.group_name && title != '黑名单'"><span :data-id="item.vid"
                          class="group-name-item group-first-name">{{item.group_name_array[0]}}</span><span v-if="item.group_name_array.length > 1">...</span></div><div class="group-name" v-if="title == '黑名单'"><span class="group-name-item" @click.stop="remove(item.visiter_id)">移出黑名单</span></div><img v-if="title != '黑名单'" class="more" @click.stop="showMenu(item)" src="/assets/images/admin/more.png"
                     alt=""><div class="more-menu" style="top: 80px" v-if="vid == item.vid && title != '黑名单'"><div class="menu-item" @click.stop="setGroup">修改分组</div><div class="menu-item" @click.stop="toBlack(item.visiter_id)">加入黑名单</div></div></div></div><div class="page"><ul class="pagination" v-if="list.length > 0"><li @click="lastPage" class="page-item" :class="{disabled:page.current_page == 1}"><a onclick="return false">&laquo;</a></li><li v-if="length > 1" @click="choosePage" class="page-item" :class="{active:page.current_page == 1}"><a :data-id="1" onclick="return false">1</a></li><li v-if="left_more" class="page-item disabled"><a>…</a></li><li @click="choosePage" v-for="item in page_list" class="page-item"
                    :class="{active:page.current_page == item}"><a :data-id="item" onclick="return false">{{item}}</a></li><li v-if="right_more" class="page-item disabled"><a>…</a></li><li @click="choosePage" class="page-item" :class="{active:page.current_page == length}"><a :data-id="length" onclick="return false">{{length}}</a></li><li @click="nextPage" class="page-item" :class="{disabled:page.current_page == length}"><a onclick="return false">&raquo;</a></li></ul></div></div></div></div><div style="display: none;" id="addgroup"><form class="layui-form" style="margin-top:30px;padding: 0 40px;"><div class="layui-form-item"><label class="layui-form-label" style="font-size: 13px;color: #999999;">分组名:</label><div class="layui-input-block"><input type="text" id="classname" required="" placeholder="请输入分组名，最多12个字"
                       autocomplete="off" class="layui-input" style="width:260px;"></div></div><div class="layui-form-item"><label class="layui-form-label">标签背景色</label><div class="layui-input-block"><div class="layui-input-inline"><input type="hidden" name="bgcolor" id="bgcolor" placeholder="请选择颜色" class="layui-input"
                           value="#707070"></div><div class="layui-inline"><div id="test-form"></div></div></div></div></form></div><script>
    var app = new Vue({
        el: '#container',
        data:{
                list: [],
                toggle: false,
                group: [],
                set: false,
                id: null,
                right_more: false,
                left_more: false,
                show_id: null,
                top: '0px',
                choose_list: [],
                page_list: [],
                length: 0,
                page: {},
                vid: null,
                keyword: null,
                group_id: null,
                operate: false,
                title: '全部客户'
        },

        methods: {
            lastPage:function() {
                let that = this;
                if (that.page.current_page == 1) {
                    return
                } else if (+that.page.current_page > 3 && +that.page.current_page == that.page_list[1] && that.length > 8) {
                    for (let i = 0; i < 3; i++) {
                        that.page_list.splice(i, 1, that.page_list[i] -= 1);
                    }
                    if (that.page_list[0] > 2) {
                        that.left_more = true;
                    } else {
                        that.left_more = false;
                    }
                    if (that.page_list[that.page_list.length - 1] == (length - 1)) {
                        that.right_more = false;
                    } else {
                        that.right_more = true;
                    }
                }
                that.ajax(that.group_id, +that.page.current_page - 1, that.title);
            },

            nextPage:function() {
                let that = this;
                if (that.page.current_page == that.length) {
                    return
                } else if (that.length > 8 && +that.page.current_page > 2) {
                    if (that.page.current_page > (that.length - 4)) {
                        that.right_more = false;
                        if (that.page.current_page == (that.length - 3) && that.page_list.indexOf(that.length - 1) == -1) {
                            that.page_list.push(that.length - 1)
                        }
                    } else {
                        that.right_more = true;
                        if (that.page_list[0] > 2) {
                            that.left_more = true;
                        } else {
                            that.left_more = false;
                        }
                        for (let i = 0; i < 3; i++) {
                            that.page_list.splice(i, 1, that.page_list[i] += 1);
                        }
                    }
                }
                that.ajax(that.group_id, +that.page.current_page + 1, that.title);
            },

            choosePage:function(e) {
                let that = this;
                let page = +e.target.dataset.id;
                if (page == that.page.current_page) {
                    return
                } else {
                    that.left_more = false;
                    that.right_more = false;
                    if (that.length > 8) {
                        that.page_list = [];
                    }
                    if (page > 2 && page < +that.length - 1 && that.length > 8) {
                        for (let i = 0; i < 3; i++) {
                            that.page_list.splice(i, 1, page + i - 1);
                        }
                        if (page > 3) {
                            if (page == 4) {
                                that.page_list.unshift(2)
                            } else {
                                that.left_more = true;
                            }
                        }
                        if (page == that.length - 3) {
                            that.page_list.push(that.length - 1)
                        } else {
                            that.right_more = true;
                        }
                    } else {
                        if (that.length > 8) {
                            that.right_more = true;
                            if (page < 3) {
                                for (let i = 2; i < 5; i++) {
                                    that.page_list.splice(i, 1, i);
                                }
                            } else if (page > +that.length - 2) {
                                for (let i = +that.length - 3; i < +that.length; i++) {
                                    that.page_list.splice(i, 1, i);
                                }
                            }
                        }
                    }
                    if (page > +that.length - 3 && that.length > 8) {
                        that.left_more = true;
                        that.right_more = false;
                    }
                    that.ajax(that.group_id, page, that.title);
                }
            },

            // 搜索
            search:function() {
                let that = this;
                that.list = [];
                that.length = 0;
                that.page_list = [];
                layer.msg('加载中...', {
                    icon: 16,
                    shade: 0.01,
                    time: 0
                });
                $.ajax({
                    url: YMWL_ROOT_URL + '/admin/custom/search',
                    type: 'get',
                    data: {
                        group_id: that.group_id,
                        page: 1,
                        nickname: that.keyword
                    },
                    success: function (res) {
                        layer.close(layer.msg());
                        if (res.code == 0) {
                            that.page.current_page = res.data.current_page;
                            that.page.per_page = res.data.per_page;
                            that.page.total = res.data.total;
                            that.list = res.data.data;
                            if (that.length == 0) {
                                that.length = Math.floor((+res.data.total / +res.data.per_page) - 0.001) + 1;
                                if (that.length > 8) {
                                    that.right_more = true;
                                    for (let i = 2; i < 5; i++) {
                                        that.page_list.push(i);
                                    }
                                } else {
                                    if (that.length > 2) {
                                        for (let i = 2; i < that.length; i++) {
                                            that.page_list.push(i);
                                        }
                                    }
                                }
                            }
                            for (let i = 0; i < that.list.length; i++) {
                                that.list[i].choose = false;
                            }
                        }
                    },
                    error: function (res) {
                        layer.close(layer.msg());
                    }
                });
            },

            // 单用户移入黑名单
            toBlack:function(visiter_id) {
                let that = this;
                layer.open({
                    title: '提示',
                    content: '是否移入黑名单',
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {
                        $.ajax({
                            url: YMWL_ROOT_URL + '/admin/set/blacklist',
                            type: 'post',
                            data: {
                                visiter_id: visiter_id
                            },
                            success: function (res) {
                                layer.close(layer.msg());
                                if (res.code == 0) {
                                    location.reload();
                                }
                            },
                            error: function (res) {
                                layer.close(layer.msg());
                            }
                        });
                    }
                })
            },
            // 单用户移出黑名单
            remove:function(visiter_id) {
                let that = this;
                layer.open({
                    title: '提示',
                    content: '是否移出黑名单',
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {
                        $.ajax({
                            url: YMWL_ROOT_URL + '/admin/set/removeblacklist',
                            type: 'post',
                            data: {
                                visiter_id: visiter_id
                            },
                            success: function (res) {
                                layer.close(layer.msg());
                                if (res.code == 0) {
                                    location.reload();
                                }
                            },
                            error: function (res) {
                                layer.close(layer.msg());
                            }
                        });
                    }
                })
            },
            // 选择
            selecte:function(row) {
                let that = this;
                that.id = null;
                that.vid = null;
                if (!app.operate) {
                    if (app.title == '黑名单') {
                        return false
                    }
                    var data = chat_data['visiter' + row.vid];
                    $.cookie("cu_com", JSON.stringify(data), {expires: 7, path: YMWL_ROOT_URL + '/admin/index'});
                    window.location.href = YMWL_ROOT_URL + '/admin/index/chats?id=' + row.visiter_id;
                } else {
                    let index = that.choose_list.indexOf(row);
                    let num = that.list.indexOf(row);
                    if (index == -1) {
                        that.choose_list.push(row);
                        row.choose = true;
                        that.$set(that.list, num, row)
                    } else {
                        that.choose_list.splice(index, 1);
                        row.choose = false;
                        that.$set(that.list, num, row)
                    }
                }
            },
            // 菜单栏切换
            toEdit:function() {
                let that = this;
                that.choose_list = [];
                for (let i = 0; i < that.list.length; i++) {
                    that.list[i].choose = false;
                }
                that.operate = !that.operate;
            },
            // 右下角菜单栏显示
            showMenu:function(row) {
                let that = this;
                that.toggle = false;
                that.id = null;
                that.vid = row.vid;
                that.choose_list[0] = row;
            },
            // 右下角菜单栏显示
            show:function(id,group_name,bgcolor, e) {
                let that = this;
                that.top = (+e.clientY - 35) + 'px';
                that.id = id;
                that.group_name = group_name;
                that.bgcolor = bgcolor;
                that.toggle = true;
                that.vid = null;
            },
            // 请求用户数据
            ajax:function(group_id, page, title) {
                let that = this;
                that.keyword = null;
                that.group_id = group_id;
                that.list = [];
                that.page_list = [];
                that.length = 0;
                that.title = title;
                layer.msg('加载中...', {
                    icon: 16,
                    shade: 0.01,
                    time: 0
                })
                $.ajax({
                    url: YMWL_ROOT_URL + '/admin/custom/visiter',
                    type: 'get',
                    data: {
                        group_id: group_id,
                        page: page
                    },
                    success: function (res) {
                        layer.close(layer.msg());
                        if (res.code == 0) {
                            that.list = res.data;
                            that.page = res.page;
                            if (that.length == 0) {
                                that.length = Math.floor((res.page.total / res.page.per_page) - 0.001) + 1;
                                if (that.length > 8) {
                                    that.right_more = true;
                                    for (let i = 2; i < 5; i++) {
                                        that.page_list.push(i);
                                    }
                                } else {
                                    if (that.length > 2) {
                                        for (let i = 2; i < that.length; i++) {
                                            that.page_list.push(i);
                                        }
                                    }
                                }
                            }
                            for (let i = 0; i < that.list.length; i++) {
                                that.list[i].choose = false;
                            }
                        }
                    },
                    error: function (res) {
                        layer.close(layer.msg());
                    }
                });
            },
            // 获取分组信息
            getGroup:function(page) {
                let that = this;
                $.ajax({
                    url: YMWL_ROOT_URL + '/admin/custom/group',
                    data: {
                        page: page
                    },
                    type: 'get',
                    success: function (res) {
                        if (res.code == 0) {
                            if (res.data.data.length == 10) {
                                that.getGroup(page + 1);
                            }
                            that.group = that.group.concat(res.data.data);
                        }
                    }
                });
            },
            // 删除分组信息
            delGroup:function() {
                let that = this;
                let id = that.id;
                that.toggle = false;
                layer.open({
                    title: '提示',
                    content: '是否删除该分组',
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {
                        $.ajax({
                            url: YMWL_ROOT_URL + '/admin/custom/delGroup',
                            type: 'post',
                            data: {
                                id: id
                            },
                            success: function (res) {
                                if (res.code == 0) {
                                    $('.group-item').remove();
                                    that.group = [];
                                    that.getGroup(1);
                                    that.ajax(0, 1, '全部客户');
                                    layer.msg('删除成功', {icon: 1});
                                }
                            }
                        });
                    }
                })
            },
            // 编辑分组
            editgroup:function() {
                let that = this;
                var id = that.id;
                console.log(that);
                //item.group_name,item.bgcolor
                bgcolorDom.val(that.bgcolor);
                classnameDom.val(that.group_name);
                colorpicker.render({
                    elem: '#test-form'
                    , color: that.bgcolor
                    , done: function (color) {
                        bgcolorDom.val(color);
                    }
                });
                var lock = false;
                layer.open({
                    skin: 'group',
                    type: 1,
                    title: '编辑分组',
                    area: ['501px', '300px'],
                    content: AddgroupDom,
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {

                        var name = classnameDom.val().trim();
                        if (!name) {
                            layer.msg('分组名不能为空', {icon: 2});
                            return false;
                        }

                        if (!lock) {
                            lock = true;
                            $.ajax({
                                url: '<?php echo url("admin/custom/editGroup"); ?>',
                                type: 'post',
                                data: {group_name: name, id: id,bgcolor: bgcolorDom.val().trim()},
                                dataType: 'json',
                                success: function (res) {
                                    if (res.code == 0) {
                                        $('.group-item').remove();
                                        that.group = [];
                                        that.getGroup(1);
                                        layer.msg(res.msg, {
                                            icon: 1, time: 2000, end: function () {
                                                layer.closeAll();
                                            }
                                        });
                                    } else {
                                        layer.msg(res.msg, {icon: 2});
                                    }
                                }
                            });
                        }
                    }
                })
            },
            // 批量加入黑名单
            moreblack:function() {
                let that = this;
                if (that.choose_list.length == 0) {
                    layer.msg('请先选择用户', {icon: 2});
                    return false;
                }
                let vid = [];
                for (let i = 0; i < that.choose_list.length; i++) {
                    vid.push(that.choose_list[i].visiter_id)
                }
                layer.open({
                    title: '提示',
                    content: '是否移入黑名单',
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {
                        $.ajax({
                            url: YMWL_ROOT_URL + '/admin/custom/moreblack',
                            type: 'post',
                            data: {
                                vid: vid
                            },
                            success: function (res) {
                                layer.close(layer.msg());
                                if (res.code == 0) {
                                    location.reload();
                                }
                            },
                            error: function (res) {
                                layer.close(layer.msg());
                            }
                        });
                    }
                })
            },
            // 修改分组用户
            setGroup:function() {
                let that = this;
                let group = that.group;
                for (let i = 0; i < group.length; i++) {
                    group[i].checked = false;
                }
                let vid = [];
                let list = that.choose_list;
                if (list.length == 0) {
                    layer.msg('请先选择用户', {icon: 2});
                    return false;
                }
                for (let i = 0; i < list.length; i++) {
                    vid.push(list[i].vid)
                }
                layer.open({
                    skin: 'group',
                    type: 1,
                    title: '设置分组',
                    area: ['300px', 'auto'],
                    content: $('#group_list').html(),
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {
                        let group_id = [];
                        var obj = document.getElementsByName("group");
                        for (var i = 0; i < obj.length; i++) {
                            if (obj[i].checked)
                                group_id.push(obj[i].value);
                        }
                        if (group_id.length > 0) {
                            $.ajax({
                                url: '<?php echo url("admin/custom/visitergroup"); ?>',
                                type: 'post',
                                data: {
                                    group_id: group_id,
                                    vid: vid,
                                },
                                dataType: 'json',
                                success: function (res) {
                                    if (res.code == 0) {
                                        $('.group-item').remove();
                                        that.group = [];
                                        that.getGroup(1);
                                        that.ajax(0, 1, '全部客户');
                                        that.operate = false;
                                        layer.closeAll();
                                        layer.msg(res.msg, {icon: 1});
                                    } else {
                                        layer.msg(res.msg, {icon: 2});
                                    }
                                }
                            });
                        }
                    }
                })

                // if(list.group_name_array.length > 0) {
                //     for(let i =0;i < list.group_name_array.length;i++) {
                //         for(let y = 0;y < group.length; y++) {
                //             if(list.group_name_array[i] == group[y].group_name) {
                //                 group[y].checked = true;
                //             }
                //         }
                //     }
                // }
                // that.group = group;
            }
        },
        created:function() {
            let that = this;
            that.ajax(0, 1, '全部客户');
            that.getGroup(1);
        }
    })
</script><script type="text/javascript">
    var AddgroupDom=$('#addgroup'),colorpicker,bgcolorDom=$('#bgcolor'),classnameDom=$("#classname");

    layui.use(['form', 'colorpicker'], function () {
        var form = layui.form;
        colorpicker = layui.colorpicker;
        //各种基于事件的操作，下面会有进一步介绍
        colorpicker.render({
            elem: '#test-form'
            , color: '#707070'
            , done: function (color) {
                bgcolorDom.val(color);
            }
        });
    });
    $('body').on('click', function () {
        app.vid = null;
        app.id = null;
    })

    $(document).on("mouseover", ".group-first-name", function (event) {
        let show_id = event.currentTarget.dataset.id;
        app.show_id = show_id;
        $(".triangle_border_up").show();
        $(".group-name-array").show();
    });

    $(document).on("mouseout", ".group-first-name", function (event) {
        $(".triangle_border_up").hide();
        $(".group-name-array").hide();
    });

    function createdGroup() {
        $('.checkbox-list').find('.addgroup').hide();
        $('.addgroup-input').css({
            display: 'table'
        });
    }

    function add() {
        let that = this;
        let name = $('.layui-layer').find('.addgroup-input').find('input').val();
        if (name != '') {
            let para = {};
            para.group_name = name;
            $.ajax({
                url: '<?php echo url("admin/custom/editGroup"); ?>',
                type: 'post',
                data: para,
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        layer.closeAll();
                        $('.group-item').remove();
                        $('.checkbox-list').find('.addgroup').show();
                        $('.addgroup-input').hide();
                        app.group = [];
                        app.getGroup(1);
                        layer.msg(res.msg, {icon: 1});
                        setTimeout(function () {
                            layer.closeAll();
                            app.setGroup();
                        }, 2000)
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                }
            });
        }
    }

    function addgroup() {
        var lock = false;
        layer.open({
            skin: 'group',
            type: 1,
            title: '添加分组',
            area: ['501px', '300px'],
            content: AddgroupDom,
            btn: ['确认', '取消'],
            yes: function (index, layero) {
                var name = $("#classname").val().trim();
                if (!name) {
                    layer.msg('分组名不能为空', {icon: 2});
                    return false;
                }

                if (!lock) {
                    lock = true;
                    $.ajax({
                        url: '<?php echo url("admin/custom/editGroup"); ?>',
                        type: 'post',
                        data: {group_name: name,bgcolor: $("#bgcolor").val().trim()},
                        dataType: 'json',
                        success: function (res) {
                            if (res.code == 0) {
                                $('.group-item').remove();
                                app.group = [];
                                app.getGroup(1);
                                layer.msg(res.msg, {
                                    icon: 1, end: function () {
                                        layer.close(index);
                                    }
                                });
                            } else {
                                layer.msg(res.msg, {icon: 2});
                            }
                        }
                    });
                }
            }
        })
    }
</script><script type="text/javascript" src="/assets/js/video.js?v=LK_DIY6.0.3"></script><script>
    wolive_connect();
</script><script type="text/javascript" src="/assets/js/80zxcom.js?v=LK_DIY6.0.3"></script></body></html>